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

use Sociable\Utility\EmailValidator,
    Sociable\Utility\PhoneValidator,
    Sociable\Utility\SkypeValidator;

class ContactDetails {
    protected $email = null;
    protected $phoneNumber = null;
    protected $mobileNumber = null;
    protected $faxNumber = null;
    protected $skypeName = null;
    
    /** @var MultiLanguageString */
    protected $notes = null;
    const NOTES_MAX_LENGTH = 140;

    public function setEmail($email = null) {
        if (!is_null($email)) {
            try {
                $this->email = EmailValidator::validateAndNormaliseAddress($email);
            } catch (Exception $e) {
                $this->email = null;
                throw $e;
            }
        }
        return $this->email;
    }

    protected function validateEmail($email) {
        EmailValidator::validateAddress($email);
    }
    
    public function getEmail() {
        return $this->email;
    }
    
    public function setPhoneNumber($phoneNumber = null) {
        if (!is_null($phoneNumber)) {
            try {
                $this->validatePhoneNumber($phoneNumber);
            } catch (Exception $e) {
                $this->phoneNumber = null;
                throw $e;
            }
        }
        $this->phoneNumber = $phoneNumber;
        return $this->phoneNumber;
    }

    protected function validatePhoneNumber($phoneNumber) {
        PhoneValidator::validateNumber($phoneNumber);
    }
    
    public function getPhoneNumber() {
        return $this->phoneNumber;
    }
    
    public function setMobileNumber($mobileNumber = null) {
        if (!is_null($mobileNumber)) {
            try {
                $this->validateMobileNumber($mobileNumber);
            } catch (Exception $e) {
                $this->mobileNumber = null;
                throw $e;
            }
        }
        $this->mobileNumber = $mobileNumber;
        return $this->mobileNumber;
    }

    protected function validateMobileNumber($mobileNumber) {
        PhoneValidator::validateNumber($mobileNumber);
    }

    public function getMobileNumber() {
        return $this->mobileNumber;
    }
    
    public function setFaxNumber($faxNumber = null) {
        if (!is_null($faxNumber)) {
            try {
                $this->validateFaxNumber($faxNumber);
            } catch (Exception $e) {
                $this->faxNumber = null;
                throw $e;
            }
        }
        $this->faxNumber = $faxNumber;
        return $this->faxNumber;
    }

    protected function validateFaxNumber($faxNumber) {
        PhoneValidator::validateNumber($faxNumber);
    }

    public function getFaxNumber() {
        return $this->faxNumber;
    }

    public function setSkypeName($name = null) {
        if (!is_null($name)) {
            try {
                $this->validateSkypeName($name);
            } catch (Exception $e) {
                $this->skypeName = null;
                throw $e;
            }
        }
        $this->skypeName = $name;
        return $this->skypeName;
    }

    public function getSkypeName() {
        return $this->skypeName;
    }

    protected function validateSkypeName($name) {
        SkypeValidator::validateName($name);
    }

    public function setNotes(MultiLanguageString $notes = null) {
        if (!is_null($notes)) {
            try {
                $this->validateNotes($notes);
            }
            catch (Exception $e) {
                $this->notes = null;
                throw $e;
            }
        }
        $this->notes = $notes;
        return $this->notes;
    }
    
    public function getNotes() {
        return $this->notes;
    }
    
    protected function validateNotes(MultiLanguageString $notes) {
        $notes->validate(array('max_length' => self::NOTES_MAX_LENGTH));
    }
    
    public function validate() {
        if (!is_null($this->email)) {
            $this->validateEmail($this->email);
        }
        if (!is_null($this->faxNumber)) {
            $this->validatePhoneNumber($this->faxNumber);
        }
        if (!is_null($this->mobileNumber)) {
            $this->validateMobileNumber($this->mobileNumber);
        }
        if (!is_null($this->phoneNumber)) {
            $this->validateFaxNumber($this->phoneNumber);
        }
        if (!is_null($this->skypeName)) {
            $this->validateSkypeName($this->skypeName);
        }
        if (!is_null($this->notes)) {
            $this->validateNotes($this->notes);
        }
    }
}


