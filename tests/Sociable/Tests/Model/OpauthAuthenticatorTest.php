<?php

/*
 * This file is part of the Sociable package.
 *
 * Copyright 2013 by Sébastien Pujadas
 *
 * For the full copyright and licence information, please view the LICENCE
 * file that was distributed with this source code.
 */

namespace Sociable\Tests\Model;

use Sociable\Model\Authenticator,
    Sociable\Model\OpauthAuthenticator,
    Sociable\Utility\StringValidator;

class OpauthAuthenticatorTest extends \PHPUnit_Framework_TestCase {

    protected $opauthAuthenticator = null;
    protected $params = null;
    protected $request = null;

    const STRATEGY = 'Facebook';
    const STRATEGY_INCORRECT = 'Google';

    const UID = '1234';
    const UID_INCORRECT = '4321';
    
    public function setUp() {
        $this->opauthAuthenticator = new OpauthAuthenticator();
    }
    
    public function test__construct() {
        $this->assertInstanceOf('Sociable\Model\OpauthAuthenticator',
                $this->opauthAuthenticator);
    }
    
    public function testValidateUninitialised() {
        $this->setExpectedException('Sociable\Model\AuthenticatorException', 
            OpauthAuthenticator::EXCEPTION_INVALID_STRATEGY);
        $this->opauthAuthenticator->validate();
    }
    
    public function testSetParamsNotAnArray() {
        $this->setExpectedException('Sociable\Model\AuthenticatorException', 
            Authenticator::EXCEPTION_INVALID_PARAM_ARRAY);
        $this->opauthAuthenticator->setParams($this->params);
    }

    public function testSetParamsInvalidStrategy() {
        $this->params = array('strategy' => null, 'uid' => self::UID);
        $this->setExpectedException('Sociable\Model\AuthenticatorException', 
            OpauthAuthenticator::EXCEPTION_INVALID_STRATEGY);
        $this->opauthAuthenticator->setParams($this->params);
    }

    public function testSetParamsUidNotAString() {
        $this->params = array('strategy' => self::STRATEGY, 'uid' => null);
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_NOT_A_STRING);
        $this->opauthAuthenticator->setParams($this->params);
    }

    public function testSetParamsUidTooLong() {
        $this->params = array(
            'strategy' => self::STRATEGY, 
            'uid' => str_repeat('-', OpauthAuthenticator::UID_MAX_LENGTH + 1)
        );
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_TOO_LONG);
        $this->opauthAuthenticator->setParams($this->params);
        
    }

    public function testAuthenticateNotAnArray() {
        $this->params = array('strategy' => self::STRATEGY, 'uid' => self::UID);
        $this->opauthAuthenticator->setParams($this->params);
        $this->setExpectedException('Sociable\Model\AuthenticatorException', 
            Authenticator::EXCEPTION_INVALID_PARAM_ARRAY);
        $this->opauthAuthenticator->authenticate($this->request);
    }

    public function testAuthenticateMissingParameters() {
        $this->params = array('strategy' => self::STRATEGY, 'uid' => self::UID);
        $this->opauthAuthenticator->setParams($this->params);
        $this->request = array();
        $this->setExpectedException('Sociable\Model\AuthenticatorException', 
            Authenticator::EXCEPTION_MISSING_PARAMETERS);
        $this->opauthAuthenticator->authenticate($this->request);
    }

    public function testAuthenticateIncorrectParameters() {
        $this->params = array('strategy' => self::STRATEGY, 'uid' => self::UID);
        $this->opauthAuthenticator->setParams($this->params);
        $this->request = array('strategy' => self::STRATEGY_INCORRECT, 'uid' => self::UID);
        $this->assertFalse($this->opauthAuthenticator->authenticate($this->request));
        $this->request = array('strategy' => self::STRATEGY, 'uid' => self::UID_INCORRECT);
        $this->assertFalse($this->opauthAuthenticator->authenticate($this->request));
    }

    public function testAuthenticate() {
        $this->params = array('strategy' => self::STRATEGY, 'uid' => self::UID);
        $this->opauthAuthenticator->setParams($this->params);
        $this->request = array('strategy' => self::STRATEGY, 'uid' => self::UID);
        $this->assertTrue($this->opauthAuthenticator->authenticate($this->request));
        
    }

}


