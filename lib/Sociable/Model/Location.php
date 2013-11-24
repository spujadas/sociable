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

class Location {
    protected $id = null;
    
    protected $label;
    const LABEL_MAX_LENGTH = 32;

    protected $name;
    const NAME_MAX_LENGTH = 64;
    
    protected $sublocationsName = null;
    const SUBLOCATIONS_MAX_LENGTH = 32;
    
    /** @var ArrayCollection of Location */
    protected $sublocations;
    
    protected $parentType = null;
    const PARENT_TYPE_COUNTRY = 'country';
    const PARENT_TYPE_LOCATION = 'location';

    protected $parentCountry = null;
    
    /** @var Location */
    protected $parentLocation = null;
    
    const EXCEPTION_INVALID_TYPE = 'invalid type';
    const EXCEPTION_MISSING_PARENT_COUNTRY = 'missing parent country';
    const EXCEPTION_MISSING_PARENT_LOCATION = 'missing parent location';
    
    public function getId() {
        return $this->id;
    }
    
    public function __construct() {
        $this->sublocations = new ArrayCollection;
    }
    
    public function setLabel($label) {
        try {
            self::validateLabel($label);
        } catch (Exception $e) {
            $this->label = null;
            throw $e;
        }

        $this->label = $label;
        return $this->label;
    }

    protected static function validateLabel($label) {
        StringValidator::validate($label, array(
            'max_length' => self::LABEL_MAX_LENGTH,
            'not_empty' => true)
        );
    }

    public function getLabel() {
        return $this->label;
    }
    
    public function setName($name) {
        try {
            self::validateName($name);
        } catch (Exception $e) {
            $this->name = null;
            throw $e;
        }

        $this->name = $name;
        return $this->name;
    }

    protected static function validateName($name) {
        StringValidator::validate($name, array(
            'max_length' => self::NAME_MAX_LENGTH,
            'not_empty' => true)
        );
    }

    public function getName() {
        return $this->name;
    }
    
    public function setSublocationsName($sublocationsName = null) {
        if (!is_null($sublocationsName)) {
            try {
                self::validateSublocationsName($sublocationsName);
            } catch (Exception $e) {
                $this->sublocationsName = null;
                throw $e;
            }
        }

        $this->sublocationsName = $sublocationsName;
        return $this->sublocationsName;
    }

    protected static function validateSublocationsName($sublocationsName) {
        StringValidator::validate($sublocationsName, array(
            'max_length' => self::SUBLOCATIONS_MAX_LENGTH,
            'not_empty' => true)
        );
    }

    public function getSublocationsName() {
        return $this->sublocationsName;
    }
    
    protected function setParentType($parentType) {
        try {
            $this->validateParentType($parentType);
        }
        catch (Exception $e) {
            $this->parentType = null;
            throw $e;
        }
        $this->parentType = $parentType;
        return $this->parentType;
    }
    
    public function getSublocations() {
        return $this->sublocations;
    }

    public function getParentType() {
        return $this->parentType;
    }
    
    protected function validateParentType($parentType) {
        if (($parentType != self::PARENT_TYPE_COUNTRY) 
                && ($parentType != self::PARENT_TYPE_LOCATION)) {
            throw new LocationException(self::EXCEPTION_INVALID_TYPE);
        }

    }
    
    public function setParentCountry(Country $parentCountry) {
        $this->parentLocation = null;
        $this->parentCountry = $parentCountry;
        $this->parentType = self::PARENT_TYPE_COUNTRY;
        return $this->parentCountry;
    }
    
    public function getParentCountry() {
        return $this->parentCountry;
    }
    
    public function getCountry() {
        if (is_null($this->parentType)) {
            return null;
        }
        if ($this->parentType ==  self::PARENT_TYPE_COUNTRY) {
            return $this->parentCountry;
        }
        return $this->parentLocation->getCountry();
    }

    public function setParentLocation(Location $parentLocation) {
        $this->parentCountry = null;
        $this->parentLocation = $parentLocation;
        $this->parentType = self::PARENT_TYPE_LOCATION;
        return $this->parentLocation;
    }
    
    public function getParentLocation() {
        return $this->parentLocation;
    }
    
    public function getSiblings() {
        if ($this->parentType == self::PARENT_TYPE_COUNTRY) {
            return $this->getParentCountry()->getLocations();
        }
        if ($this->parentType == self::PARENT_TYPE_LOCATION) {
            return $this->getParentLocation()->getSublocations();
        }
        return new ArrayCollection;
    }
    
    public function getFamilyName() {
        if ($this->parentType == self::PARENT_TYPE_COUNTRY) {
            return $this->getParentCountry()->getLocationsName();
        }
        if ($this->parentType == self::PARENT_TYPE_LOCATION) {
            return $this->getParentLocation()->getSublocationsName();
        }
        return null;
    }
    
    public function validate() {
        $this->validateLabel($this->label);
        $this->validateName($this->name);
        $this->validateParentType($this->parentType);
        if ($this->parentType == self::PARENT_TYPE_COUNTRY) {
            if (is_null($this->parentCountry)) {
                throw new LocationException(self::EXCEPTION_MISSING_PARENT_COUNTRY);
            }
        }
        if ($this->parentType == self::PARENT_TYPE_LOCATION) {
            if (is_null($this->parentLocation)) {
                throw new LocationException(self::EXCEPTION_MISSING_PARENT_LOCATION);
            }
        }
        if (!is_null($this->sublocationsName)) {
            $this->validateSublocationsName($this->sublocationsName);
        }
    }
}

