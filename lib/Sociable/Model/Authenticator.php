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

abstract class Authenticator {
    const PASSWORD_AUTHENTICATOR = 'password';
    const OPAUTH_AUTHENTICATOR = 'opauth';
    protected $type;
    
    const EXCEPTION_MISSING_PARAMETERS = 'missing parameters';
    const EXCEPTION_INVALID_TYPE = 'invalid type';
    const EXCEPTION_TYPE_MISMATCH = 'type mismatch';
    
    const EXCEPTION_INVALID_PARAM_ARRAY = 'invalid parameter array';
    
    public function authenticate($requestArray) {
        if (!is_array($requestArray)) {
            throw new AuthenticatorException(self::EXCEPTION_INVALID_PARAM_ARRAY);
        }
    }
    
    abstract public function setParams($paramsArray);
    
    protected function validateParams($paramsArray) {
        if (!is_array($paramsArray)) {
            throw new AuthenticatorException(self::EXCEPTION_INVALID_PARAM_ARRAY);
        }
    }
    
    public function validate() {
        $this->validateType($this->type);
    }
    
    protected function setType($type) {
        try {
            $this->validateType($type);
        } catch (Exception $e) {
            $this->type = null;
            throw $e;
        }
        $this->type = $type;
        return $this->type;
    }
    
    protected function validateType($type) {
        if (($type != self::PASSWORD_AUTHENTICATOR) 
                && ($type != self::OPAUTH_AUTHENTICATOR)) {
            throw new AuthenticatorException(self::EXCEPTION_INVALID_TYPE);
        }
    }
    
    public function getType() {
        return $this->type;
    }
}


