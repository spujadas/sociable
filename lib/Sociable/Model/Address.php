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

class Address {
    protected $address1 = null;
    const ADDRESS1_MAX_LENGTH = 100;

    protected $address2 = null;
    const ADDRESS2_MAX_LENGTH = 100;

    protected $cityAreaOrDistrict = null; // null en .fr
    const CITYAREAORDISTRICT_MAX_LENGTH = 50;

    protected $postCode = null;
    const POSTCODE_MIN_LENGTH = 1;
    const POSTCODE_MAX_LENGTH = 30;

    protected $cityOrTownOrVillage = null;
    const CITYORTOWNORVILLAGE_MAX_LENGTH = 50;

    protected $county = null; // null en .fr
    const COUNTY_MAX_LENGTH = 50;

    protected $country = null;
    const EXCEPTION_MISSING_COUNTRY = 'missing country';

    public function setAddress1($address = null) {
        if (!is_null($address)) {
            try {
                $this->validateAddress1($address);
            } catch (Exception $e) {
                $this->address1 = null;
                throw $e;
            }
        }

        $this->address1 = $address;
        return $this->address1;
    }

    protected function validateAddress1($address) {
        StringValidator::validate($address, array(
            'max_length' => self::ADDRESS1_MAX_LENGTH,
        ));
    }

    public function getAddress1() {
        return $this->address1;
    }

    public function setAddress2($address = null) {
        if (!is_null($address)) {
            try {
                $this->validateAddress2($address);
            } catch (Exception $e) {
                $this->address2 = null;
                throw $e;
            }
        }

        $this->address2 = $address;
        return $this->address2;
    }

    protected function validateAddress2($address) {
        StringValidator::validate($address, array(
            'max_length' => self::ADDRESS2_MAX_LENGTH,
        ));
    }

    public function getAddress2() {
        return $this->address2;
    }

    public function setCityAreaOrDistrict($cad = null) {
        if (!is_null($cad)) {
            try {
                $this->validateCityAreaOrDistrict($cad);
            } catch (Exception $e) {
                $this->cityAreaOrDistrict = null;
                throw $e;
            }
        }

        $this->cityAreaOrDistrict = $cad;
        return $this->cityAreaOrDistrict;
    }

    protected function validateCityAreaOrDistrict($cad) {
        StringValidator::validate($cad, array(
            'max_length' => self::CITYAREAORDISTRICT_MAX_LENGTH,
        ));
    }

    public function getCityAreaOrDistrict() {
        return $this->cityAreaOrDistrict;
    }

    public function setPostCode($postCode) {
        try {
            $this->validatePostCode($postCode);
        } catch (Exception $e) {
            $this->postCode = null;
            throw $e;
        }

        $this->postCode = $postCode;
        return $this->postCode;
    }
    
    protected function validatePostCode($postCode) {
        StringValidator::validate($postCode, array(
            'min_length' => self::POSTCODE_MIN_LENGTH,
            'max_length' => self::POSTCODE_MAX_LENGTH,
        ));
    }

    public function getPostCode() {
        return $this->postCode;
    }

    public function setCityOrTownOrVillage($ctv) {
        try {
            $this->validateCityOrTownOrVillage($ctv);
        } catch (Exception $e) {
            $this->cityOrTownOrVillage = null;
            throw $e;
        }

        $this->cityOrTownOrVillage = $ctv;
        return $this->cityOrTownOrVillage;
    }

    protected function validateCityOrTownOrVillage($ctv) {
        StringValidator::validate($ctv, array(
            'not_empty' => true,
            'max_length' => self::CITYORTOWNORVILLAGE_MAX_LENGTH,
        ));
    }
    
    public function getCityOrTownOrVillage() {
        return $this->cityOrTownOrVillage;
    }

    public function setCounty($county = null) {
        if (!is_null($county)) {
            try {
                $this->validateCounty($county);
            } catch (Exception $e) {
                $this->county = null;
                throw $e;
            }
        }

        $this->county = $county;
        return $this->county;
    }

    protected function validateCounty($county) {
        StringValidator::validate($county, array(
            'max_length' => self::COUNTY_MAX_LENGTH,
        ));
    }
    
    public function getCounty() {
        return $this->county;
    }

    public function setCountry(Country $country) {
        $this->country = $country;
        return $this->country;
    }
    
    public function getCountry() {
        return $this->country;
    }
    
    public function validate() {
        if (!is_null($this->address1)) {
            $this->validateAddress1($this->address1);
        }
        if (!is_null($this->address2)) {
            $this->validateAddress2($this->address2);
        }
        $this->validateCityOrTownOrVillage($this->cityOrTownOrVillage);
        $this->validatePostCode($this->postCode);
        if (!is_null($this->cityAreaOrDistrict)) {
            $this->validateCityAreaOrDistrict($this->cityAreaOrDistrict);
        }
        if (!is_null($this->county)) {
            $this->validateCounty($this->county);
        }
        if (is_null($this->country)) {
            throw new AddressException(self::EXCEPTION_MISSING_COUNTRY);
        }
    }
}


