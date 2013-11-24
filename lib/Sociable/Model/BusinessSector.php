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

class BusinessSector {
    protected $id = null;
    
    protected $code = null;
    const CODE_MAX_LENGTH = 32;
    
    const EXCEPTION_NOT_IN_CATALOGUE = 'not in catalogue';
    
    /** @var MultiLanguageString */
    protected $name = null;
    const NAME_MAX_LENGTH = 128;

    public function getId() {
        return $this->id;
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
                array(
                    'not_empty' => true,
                    'max_length' => self::CODE_MAX_LENGTH));
    }

    public function getCode() {
        return $this->code;
    }

    
    public function setName(MultiLanguageString $name) {
        try {
            $this->validateName($name);
        }
        catch (Exception $e) {
            $this->name = null;
            throw $e;
        }
        $this->name = $name;
        return $this->name;
    }
    
    public function getName() {
        return $this->name;
    }
    
    protected function validateName(MultiLanguageString $name) {
        $name->validate(array(
            'not_empty' => true,
            'max_length' => self::NAME_MAX_LENGTH
        ));
    }
    
    public function validate() {
        $this->validateCode($this->code);
        $this->validateName($this->name);
    }
}


