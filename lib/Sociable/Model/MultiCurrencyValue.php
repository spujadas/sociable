<?php

/*
 * This file is part of the Sociable package.
 *
 * Copyright 2013 by Sébastien Pujadas
 *
 * For the full copyright and licence information, please view the LICENCE
 * file that was distributed with this source code.
 */

namespace Sociable\Model;

use Sociable\Utility\NumberValidator;

class MultiCurrencyValue {
    protected $defaultCurrencyCode = null;
    protected $currencyValues = array();

    const EXCEPTION_UNMATCHED_DEFAULT = 'unmatched default';
    const EXCEPTION_DUPLICATE_CURRENCY = 'duplicate currency';
    
    const COMPARE_EQUALS = 'equals';
    const COMPARE_DIFFERENT_NUMBER_OF_CURRENCIES = 'different number of currencies';
    const COMPARE_DIFFERENT_LIST_OF_CURRENCIES = 'different list of currencies';
    const COMPARE_DIFFERENT_VALUE = 'different value';
    
    public function __construct($value = null, $currencyCode = null) {
        if (!is_null($value) && !is_null($currencyCode)) {
            $this->validateValue($value);
            $this->currencyValues[$currencyCode] = $value;
            $this->setDefaultCurrencyCode($currencyCode);
        }
    }
 
    public function getDefaultCurrencyCode() {
        return $this->defaultCurrencyCode;
    }

    public function setDefaultCurrencyCode($default) {
        try {
            $this->validateDefaultCurrencyCode($default);
        } catch (ValueException $e) {
            $this->defaultCurrencyCode = null;
            throw $e;
        }
        $this->defaultCurrencyCode = $default;
        return $this->defaultCurrencyCode;
    }
    
    public function validateDefaultCurrencyCode($defaultCurrencyCode) {
        Currency::validateCode($defaultCurrencyCode);
    }
    
    public function getCurrencyValues() {
        return $this->currencyValues;
    }
    
    public function getValueByCurrencyCode($code, $orDefault = false) {
        if (array_key_exists($code, $this->currencyValues)) {
            return $this->currencyValues[$code];
        }
        return ($orDefault && array_key_exists($this->defaultCurrencyCode, 
                $this->currencyValues))
            ?$this->currencyValues[$this->defaultCurrencyCode]
            :null;
    }

    /* returns currency code or default currency code
       - designed for use in conjunction with getValueByCurrencyCode(…, true) */
    public function getCurrencyCode($code) {
        if (array_key_exists($code, $this->currencyValues)) {
            return $code;
        }
        return array_key_exists($this->defaultCurrencyCode, $this->currencyValues)
            ?$this->defaultCurrencyCode
            :null;
    }

    public function validate() {
        $this->validateDefaultCurrencyCode($this->defaultCurrencyCode);
        foreach ($this->currencyValues as $code => $value) {
            $this->validateValue($value);
            Currency::validateCode($code);
        }
        
        if (!array_key_exists($this->defaultCurrencyCode, $this->currencyValues)) {
            throw new MultiCurrencyValueException(self::EXCEPTION_UNMATCHED_DEFAULT);
        }
    }

    private function validateValue($value) {
        NumberValidator::validate($value,
            array('is_currency' => true, 'positive' => true)
        );
    }
    
    public function addValueByCurrencyCode($value, $currencyCode, $upsert = false) {    
        $this->validateValue($value);
        Currency::validateCode($currencyCode);
        if (!$upsert && array_key_exists($currencyCode, $this->currencyValues)) {
            throw new MultiCurrencyValueException(self::EXCEPTION_DUPLICATE_CURRENCY);
        }
        $this->currencyValues[$currencyCode] = $value;
    }

    public function removeValueByCurrencyCode($code) {
        if (array_key_exists($code, $this->currencyValues) && 
                ($this->defaultCurrencyCode != $code)) {
            unset($this->currencyValues[$code]);
            return true;
        }
        return false;
    }
    
    public function getDefaultValue() {
        if (array_key_exists($this->defaultCurrencyCode, $this->currencyValues)) {
            return ($this->currencyValues[$this->defaultCurrencyCode]);
        }
        return null;
    }

    public function sum(MultiCurrencyValue $multiCurrencyValue) {
        $currencyValues = $multiCurrencyValue->getCurrencyValues();
        foreach ($currencyValues as $currencyCode => $value) {
            $this->sumCurrencyValue($value, $currencyCode);
        }
    }
    
    public function sumCurrencyValue($value, $code) {
        $this->validateValue($value);
        Currency::validateCode($code);

        $thisCurrencyValue = $this->getValueByCurrencyCode($code);
        if (is_null($thisCurrencyValue)) {
            $this->addValueByCurrencyCode($value, $code);
        }
        else {
            $this->currencyValues[$code] = $thisCurrencyValue + $value;
        }        
    }
    
    public function compareValues (MultiCurrencyValue $multiCurrencyValue) {
        if (count($this->currencyValues) != count($multiCurrencyValue->getCurrencyValues())) {
            return self::COMPARE_DIFFERENT_NUMBER_OF_CURRENCIES;
        }
        foreach ($this->currencyValues as $code => $value) {
            if (!array_key_exists($code, $multiCurrencyValue->currencyValues)) {
                return self::COMPARE_DIFFERENT_LIST_OF_CURRENCIES;
            }
            if ($multiCurrencyValue->getValueByCurrencyCode($code)
                    != $value) {
                return self::COMPARE_DIFFERENT_VALUE;
            }
        }
        return self::COMPARE_EQUALS;
    }

    public static function getSymbol($code) {
        return \Symfony\Component\Intl\Intl::getCurrencyBundle()->getCurrencySymbol($code) ;
    }
}


