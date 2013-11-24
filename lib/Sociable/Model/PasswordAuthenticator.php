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
    Sociable\Utility\Crypto;

class PasswordAuthenticator extends Authenticator {

    const SALT_LENGTH = 7;
    const HASH_ALGORITHM = 'sha256';
    const SALTED_HASH_LENGTH = 71;
    const MIN_LENGTH = 4;
    const MAX_LENGTH = 256;

    protected $saltedHashedPassword = null;

    const EXCEPTION_INVALID_SHP_STRING = 'invalid salted hashed password string';
    const EXCEPTION_PASSWORD_SHP_MISMATCH = 'password and salted hashed password mismatch';

    public function __construct() {
        $this->setType(parent::PASSWORD_AUTHENTICATOR);
    }
    
    public function validate() {
        parent::validate();
        if ($this->type != parent::PASSWORD_AUTHENTICATOR) {
            throw new AuthenticatorException(parent::EXCEPTION_TYPE_MISMATCH);
        }
        $this->validateSaltedHashedPasswordString($this->saltedHashedPassword);
    }
    
    public function authenticate($requestArray) {
        parent::authenticate($requestArray);
        
        if (!array_key_exists('password', $requestArray)) {
            throw new AuthenticatorException(parent::EXCEPTION_MISSING_PARAMETERS);
        }

        StringValidator::validate($requestArray['password'], array(
            'min_length' => self::MIN_LENGTH,
            'max_length' => self::MAX_LENGTH
        ));

        return $this->matchesPasswordSaltedHash($requestArray['password'], $this->saltedHashedPassword);
    }

    public function setParams($paramsArray) {
        try {
            $this->validateParams($paramsArray);
        }
        catch (Exception $e) {
            $this->saltedHashedPassword = null;
            throw $e;
        }
        
        if (array_key_exists('shpassword', $paramsArray)) {
            $this->saltedHashedPassword = $paramsArray['shpassword'];
        }
        else {
            $this->saltedHashedPassword = $this->getSaltedHash($paramsArray['password']);
        }
    }
    
    protected function validateParams($paramsArray) {
        parent::validateParams($paramsArray);
        
        if (!array_key_exists('password', $paramsArray) 
                && !array_key_exists('shpassword', $paramsArray)) {
            throw new AuthenticatorException(parent::EXCEPTION_MISSING_PARAMETERS);
        }

        if (array_key_exists('password', $paramsArray)) {
            StringValidator::validate($paramsArray['password'], array(
                'min_length' => self::MIN_LENGTH,
                'max_length' => self::MAX_LENGTH
            ));
        }

        if (array_key_exists('shpassword', $paramsArray)) {
            $this->validateSaltedHashedPasswordString($paramsArray['shpassword']);
        }
        
        if (array_key_exists('password', $paramsArray)
                && array_key_exists('shpassword', $paramsArray)) {
            if (!$this->matchesPasswordSaltedHash($paramsArray['password'], $paramsArray['shpassword'])) {
                throw new AuthenticatorException(self::EXCEPTION_PASSWORD_SHP_MISMATCH);
            }
        }
    }

    protected function validateSaltedHashedPasswordString($shp) {
        StringValidator::validate($shp, array(
            'length' => self::SALTED_HASH_LENGTH
        ));
        if (!preg_match('/^[0-9a-f]+$/', $shp)) {
            throw new AuthenticatorException(self::EXCEPTION_INVALID_SHP_STRING);
        }
    }
    
    protected function matchesPasswordSaltedHash($password, $shpassword) {
        StringValidator::validate($password);
        $this->validateSaltedHashedPasswordString($shpassword);
        return $this->getSaltedHash($password, $shpassword) == $shpassword;
    }

    /**
     * Generates a salted hash of a supplied password
     *
     * @param string $password to be hashed
     * @param string $salt extract the hash from here
     * @return string the salted hash
     */
    protected function getSaltedHash($password, $salt = null) {
        StringValidator::validate($password, array(
            'min_length' => self::MIN_LENGTH,
            'max_length' => self::MAX_LENGTH
        ));
        
        if (is_null($salt)) {
            $salt = substr(Crypto::getRandom64HexString(), 0, self::SALT_LENGTH);
        } else {
            StringValidator::validate($salt, array(
                'min_length' => self::SALT_LENGTH
            ));
            $salt = substr($salt, 0, self::SALT_LENGTH);
        }

        return $salt . hash(self::HASH_ALGORITHM, $salt . $password);
    }

}


