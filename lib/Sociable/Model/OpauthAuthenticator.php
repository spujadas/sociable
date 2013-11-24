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

class OpauthAuthenticator extends Authenticator {
    protected $strategy = null;
    const STRATEGY_GOOGLE = 'Google';
    const STRATEGY_FACEBOOK = 'Facebook';
    const STRATEGY_TWITTER = 'Twitter';
    
    protected $uid = null;
    const UID_MAX_LENGTH = 1024; // should be enough...
    
    const EXCEPTION_INVALID_STRATEGY = 'invalid strategy';
    
    public function __construct() {
        $this->setType(parent::OPAUTH_AUTHENTICATOR);
    }
    

    public function setParams($paramsArray) {
        try {
            $this->validateParams($paramsArray);
        }
        catch (Exception $e) {
            $this->strategy = null;
            $this->uid = null;
            throw $e;
        }

        $this->strategy = $paramsArray['strategy'];
        $this->uid = $paramsArray['uid'];
    }
    
    protected function validateParams($paramsArray) {
        parent::validateParams($paramsArray);
        
        if (!array_key_exists('strategy', $paramsArray) 
                || !array_key_exists('uid', $paramsArray)) {
            throw new AuthenticatorException(parent::EXCEPTION_MISSING_PARAMETERS);
        }

        $this->validateStrategy($paramsArray['strategy']);
        $this->validateUid($paramsArray['uid']);        
    }
    
    public function authenticate($requestArray) {
        parent::authenticate($requestArray);

        if (!array_key_exists('strategy', $requestArray) 
                || !array_key_exists('uid', $requestArray)) {
            throw new AuthenticatorException(parent::EXCEPTION_MISSING_PARAMETERS);
        }

        $this->validateStrategy($requestArray['strategy']);
        $this->validateUid($requestArray['uid']);
        
        return (($this->strategy == $requestArray['strategy']) 
                && ($this->uid == $requestArray['uid']));
    }
    
    protected function validateUid($uid) {
        StringValidator::validate($uid, 
                array('max_length' => self::UID_MAX_LENGTH));
    }
    
    protected function validateStrategy($strategy) {
        if (($strategy != self::STRATEGY_FACEBOOK) 
                && ($strategy != self::STRATEGY_GOOGLE) 
                && ($strategy != self::STRATEGY_TWITTER)) {
            throw new AuthenticatorException(self::EXCEPTION_INVALID_STRATEGY);
        }
    }
    
    public function validate() {
        parent::validate();
        if ($this->type != parent::OPAUTH_AUTHENTICATOR) {
            throw new AuthenticatorException(parent::EXCEPTION_TYPE_MISMATCH);
        }
        $this->validateStrategy($this->strategy);
        $this->validateUid($this->uid);
    }
}


