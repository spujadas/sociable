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

class Organisation {
    protected $id = null;
    
    const NPO_ORGANISATION = 'npo';
    const BUSINESS_ORGANISATION = 'business';
    protected $type = null;
    
    protected $name = null;
    const NAME_MAX_LENGTH = 64;
    
    /** @var BusinessSector */
    protected $businessSector = null;
    const EXCEPTION_MISSING_BUSINESS_SECTOR = 'missing business sector';
    
    const EXCEPTION_INVALID_TYPE = 'invalid type';
    
    public function getId() {
        return $this->id;
    }
    
    public function setType($type) {
        try {
            $this->validateType($type);
        }
        catch (Exception $e) {
            $this->type = null;
            throw $e;
        }
        $this->type = $type;
        return $this->type;
    }
    
    protected function validateType($type) {
        if (($type != self::NPO_ORGANISATION)
                && ($type != self::BUSINESS_ORGANISATION)) {
            throw new OrganisationException(self::EXCEPTION_INVALID_TYPE);
        }
    }
    
    public function getType() {
        return $this->type;
    }
    
    public function setName($name) {
        try {
            self::validateName($name);
        }
        catch (Exception $e) {
            $this->name = null;
            throw $e;
        }
        $this->name = $name;
        return $this->name;
    }
    
    protected static function validateName($name) {
        StringValidator::validate($name, 
                array(
                    'not_empty' => true,
                    'max_length' => self::NAME_MAX_LENGTH)
                );
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function setBusinessSector(BusinessSector $businessSector) {
        $this->businessSector = $businessSector;
        return $this->businessSector;
    }
    
    public function getBusinessSector() {
        return $this->businessSector;
    }
    
    public function validate() {
        self::validateName($this->name);
        if (is_null($this->businessSector)) {
            throw new OrganisationException(self::EXCEPTION_MISSING_BUSINESS_SECTOR);
        }
        $this->validateType($this->type);
    }
}


