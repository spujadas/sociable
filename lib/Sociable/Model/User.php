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

use Sociable\Utility\StringValidator,
    Sociable\Utility\EmailValidator;

class User {
    protected $id = null;
    
    protected $name = null;
    const NAME_MAX_LENGTH = 32;
    
    protected $surname = null;
    const SURNAME_MAX_LENGTH = 32;
    
    protected $email = null;
    
    /** @var Authenticator */
    protected $authenticator = null;

    const EXCEPTION_MISSING_AUTHENTICATOR = 'missing authenticator';
    
    public function getId() {
        return $this->id;
    }    

    public function setName($name = null) {
        if (!is_null($name)) {
            try {
                $this->validateName($name);
            } catch (Exception $e) {
                $this->name = null;
                throw $e;
            }
        }
        $this->name = $name;
        return $this->name;
    }

    protected function validateName($name) {
        StringValidator::validate($name, 
                array(
                    'max_length' => self::NAME_MAX_LENGTH, 
                    'not_empty' => true)
        );
    }

    public function getName() {
        return $this->name;
    }
    
    public function setSurname($surname = null) {
        if (!is_null($surname)) {
            try {
                $this->validateSurname($surname);
            } catch (Exception $e) {
                $this->surname = null;
                throw $e;
            }
        }
        $this->surname = $surname;
        return $this->surname;
    }

    protected function validateSurname($surname) {
        StringValidator::validate($surname, 
                array(
                    'max_length' => self::SURNAME_MAX_LENGTH,
                    'not_empty' => true)
        );
    }

    public function getSurname() {
        return $this->surname;
    }

    public function setEmail($email) {
        try {
            $this->email = EmailValidator::validateAndNormaliseAddress($email);
        } catch (Exception $e) {
            $this->email = null;
            throw $e;
        }
        return $this->email;
    }

    protected static function validateEmail($email) {
        EmailValidator::validateAddress($email);
    }
    
    public function getEmail() {
        return $this->email;
    }

    public function setAuthenticator(Authenticator $authenticator) {
        try {
            $this->validateAuthenticator($authenticator);
        } catch (Exception $e) {
            $this->authenticator = null;
            throw $e;
        }
        $this->authenticator = $authenticator;
        return $this->authenticator;        
    }
    
    protected function validateAuthenticator(Authenticator $authenticator) {
        $authenticator->validate();
    }
    
    public function getAuthenticator() {
        return $this->authenticator;
    }
    
    public function validate() {
        if (!is_null($this->name)) {
            $this->validateName($this->name);
        }
        if (!is_null($this->surname)) {
            $this->validateSurname($this->surname);
        }
        $this->validateEmail($this->email);
        if (is_null($this->authenticator)) {
            throw new UserException(self::EXCEPTION_MISSING_AUTHENTICATOR);
        }
        else {
            $this->validateAuthenticator($this->authenticator);        
        }
    }
}


