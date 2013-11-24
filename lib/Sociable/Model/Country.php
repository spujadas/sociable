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

use Doctrine\Common\Collections\ArrayCollection;

class Country {
    protected $id = null;
    
    protected $code = null; // ISO 3166-1 alpha-2 code
    const CODE_LENGTH = 2;
    
    /** @var MultiLanguageString */
    protected $name = null;
    
    const NAME_MAX_LENGTH = 64;
    
    protected $locationsName = null;
    const LOCATIONS_NAME_MAX_LENGTH = 32;
    
    /** @var ArrayCollection of Location */
    protected $locations;
    
    const EXCEPTION_NOT_IN_CATALOGUE = 'not in catalogue';
    const EXCEPTION_INVALID_COUNTRY = 'invalid country';

    public function getId() {
        return $this->id;
    }
    
    public function __construct() {
        $this->locations = new ArrayCollection;
    }
    
    public function setCode($code) {
        try {
            self::validateCode($code);
        } catch (Exception $e) {
            $this->code = null;
            throw $e;
        }

        $this->code = $code;
        return $this->code;
    }

    public static function validateCode($code) {
        StringValidator::validate($code, 
            array('length' => self::CODE_LENGTH));
    }

    public function getCode() {
        return $this->code;
    }

    public function setName(MultiLanguageString $name) {
        try {
            $this->validateName($name);
        } catch (Exception $e) {
            $this->name = null;
            throw $e;
        }

        $this->name = $name;
        return $this->name;
    }

    protected function validateName(MultiLanguageString $name) {
        $name->validate(array('max_length' => self::NAME_MAX_LENGTH));
    }
    
    public function setLocationsName($locationsName = null) {
        if (!is_null($locationsName)) {
            try {
                self::validateLocationsName($locationsName);
            } catch (Exception $e) {
                $this->locationsName = null;
                throw $e;
            }
        }

        $this->locationsName = $locationsName;
        return $this->locationsName;
    }

    protected static function validateLocationsName($locationsName) {
        StringValidator::validate($locationsName, array(
            'max_length' => self::LOCATIONS_NAME_MAX_LENGTH,
            'not_empty' => true)
        );
    }

    public function getLocationsName() {
        return $this->locationsName;
    }

    public function getLocations() {
        return $this->locations;
    }
    
    public function getName() {
        return $this->name;
    }

    public function validate() {
        self::validateCode($this->code);
        $this->validateName($this->name);
        if (!is_null($this->locationsName)) {
            $this->validateLocationsName($this->locationsName);
        }
    }
}


