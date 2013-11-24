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

use Sociable\Utility\StringValidator;

class MultiLanguageString {
    protected $defaultLanguageCode = null;
    protected $languageStrings = array();
    
    const EXCEPTION_UNMATCHED_DEFAULT = 'unmatched default';
    const EXCEPTION_DUPLICATE_LANGUAGE = 'duplicate language';

    public function __construct($string = null, $languageCode = null, 
            $constraints = array()) {
        if (!is_null($string) && !is_null($languageCode)) {
            StringValidator::validate($string, $constraints);
            $this->languageStrings[$languageCode] = $string;
            $this->setDefaultLanguageCode($languageCode);
        }
    }
 
    public function isAvailableInLanguageCode($languageCode) {
        return array_key_exists($languageCode, $this->languageStrings);
    }

    public function getDefaultLanguageCode() {
        return $this->defaultLanguageCode;
    }

    public function setDefaultLanguageCode($default) {
        try {
            $this->validateDefaultLanguageCode($default);
        } catch (StringException $e) {
            $this->defaultLanguageCode = null;
            throw $e;
        }
        $this->defaultLanguageCode = $default;
        return $this->defaultLanguageCode;
    }
    
    public function validateDefaultLanguageCode($defaultLanguageCode) {
        Language::validateCode($defaultLanguageCode);
    }
    
    public function getLanguageStrings() {
        return $this->languageStrings;
    }
    
    public function getStringByLanguageCode($code, $orDefault = true) {
        if (array_key_exists($code, $this->languageStrings)) {
            return $this->languageStrings[$code];
        }
        return $orDefault?$this->getDefaultString():null;
    }

    public function validate($constraints = array()) {
        $this->validateDefaultLanguageCode($this->defaultLanguageCode);
        foreach ($this->languageStrings as $code => $string) {
            StringValidator::validate($string, $constraints);
            Language::validateCode($code);
        }
        
        if (!array_key_exists($this->defaultLanguageCode, $this->languageStrings)) {
            throw new MultiLanguageStringException(self::EXCEPTION_UNMATCHED_DEFAULT);
        }
    }
    
    public function addStringByLanguageCode($string, $languageCode, $upsert = false,
            $constraints = array()) {
        StringValidator::validate($string, $constraints);
        Language::validateCode($languageCode);
        if (!$upsert && array_key_exists($languageCode, $this->languageStrings)) {
            throw new MultiLanguageStringException(self::EXCEPTION_DUPLICATE_LANGUAGE);
        }
        $this->languageStrings[$languageCode] = $string;
    }

    public function removeStringByLanguageCode($code) {
        if (array_key_exists($code, $this->languageStrings) && 
                ($this->defaultLanguageCode != $code)) {
            unset($this->languageStrings[$code]);
            return true;
        }
        return false;
    }
    
    public function getDefaultString() {
        if (array_key_exists($this->defaultLanguageCode, $this->languageStrings)) {
            return ($this->languageStrings[$this->defaultLanguageCode]);
        }
        return null;
    }
}


