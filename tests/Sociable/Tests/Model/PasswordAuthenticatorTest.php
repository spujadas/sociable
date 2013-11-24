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
    Sociable\Model\PasswordAuthenticator,
    Sociable\Utility\StringValidator;

class PasswordAuthenticatorTest extends \PHPUnit_Framework_TestCase {

    protected $passwordAuthenticator = null;
    protected $params = null;
    protected $request = null;

    const PASSWORD = '1234';
    const WRONGPASSWORD = '4321';
    const SALTEDHASHEDPASSWORD
            = '6915bfd748a1f256dfcd476f818b0d9d063ab8c0a59b7a6559a20f39225acd4a0f08ea9';

    public function setUp() {
        $this->passwordAuthenticator = new PasswordAuthenticator();
    }
    
    public function test__construct() {
        $this->assertInstanceOf('Sociable\Model\PasswordAuthenticator',
                $this->passwordAuthenticator);
    }
    
    public function testValidateUninitialised() {
        $this->setExpectedException('Sociable\Utility\StringException', 
            StringValidator::EXCEPTION_NOT_A_STRING);
        $this->passwordAuthenticator->validate();
    }
    
    public function testSetParamsNotAnArray() {
        $this->setExpectedException('Sociable\Model\AuthenticatorException', 
            Authenticator::EXCEPTION_INVALID_PARAM_ARRAY);
        $this->passwordAuthenticator->setParams($this->params);
    }

    public function testSetParamsPasswordNotAString() {
        $this->params = array('password' => null, 'shpassword' => self::SALTEDHASHEDPASSWORD);
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_NOT_A_STRING);
        $this->passwordAuthenticator->setParams($this->params);
    }

    public function testSetParamsPasswordTooShort() {
        $this->params = array(
            'password' => str_repeat('-', PasswordAuthenticator::MIN_LENGTH - 1), 
            'shpassword' => self::SALTEDHASHEDPASSWORD);
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_TOO_SHORT);
        $this->passwordAuthenticator->setParams($this->params);    
    }

    public function testSetParamsPasswordTooLong() {
        $this->params = array(
            'password' => str_repeat('-', PasswordAuthenticator::MAX_LENGTH + 1), 
            'shpassword' => self::SALTEDHASHEDPASSWORD);
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_TOO_LONG);
        $this->passwordAuthenticator->setParams($this->params);    
    }

    public function testSetParamsSHPasswordNotAString() {
        $this->params = array('password' => self::PASSWORD, 'shpassword' => null);
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_NOT_A_STRING);
        $this->passwordAuthenticator->setParams($this->params);
    }

    public function testSetParamsSHPasswordIncorrectLength() {
        $this->params = array('password' => self::PASSWORD, 'shpassword' => '');
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_INCORRECT_LENGTH);
        $this->passwordAuthenticator->setParams($this->params);
        
    }

    public function testSetParamsPasswordSHPasswordMismatch() {
        $this->params = array(
            'password' => self::WRONGPASSWORD, 
            'shpassword' => self::SALTEDHASHEDPASSWORD);
        $this->setExpectedException('Sociable\Model\AuthenticatorException', 
            PasswordAuthenticator::EXCEPTION_PASSWORD_SHP_MISMATCH);
        $this->passwordAuthenticator->setParams($this->params);
    }

    public function testAuthenticateNotAnArray() {
        $this->params = array('password' => self::PASSWORD, 'shpassword' => self::SALTEDHASHEDPASSWORD);
        $this->passwordAuthenticator->setParams($this->params);
        $this->setExpectedException('Sociable\Model\AuthenticatorException', 
            Authenticator::EXCEPTION_INVALID_PARAM_ARRAY);
        $this->passwordAuthenticator->authenticate($this->request);
    }

    public function testAuthenticateMissingPassword() {
        $this->params = array('password' => self::PASSWORD, 'shpassword' => self::SALTEDHASHEDPASSWORD);
        $this->passwordAuthenticator->setParams($this->params);
        $this->request = array();
        $this->setExpectedException('Sociable\Model\AuthenticatorException', 
            Authenticator::EXCEPTION_MISSING_PARAMETERS);
        $this->passwordAuthenticator->authenticate($this->request);
    }

    public function testAuthenticateIncorrectPassword() {
        $this->params = array('password' => self::PASSWORD, 'shpassword' => self::SALTEDHASHEDPASSWORD);
        $this->passwordAuthenticator->setParams($this->params);
        $this->request = array('password' => self::WRONGPASSWORD);
        $this->assertFalse($this->passwordAuthenticator->authenticate($this->request));
        
    }

    public function testAuthenticate() {
        $this->params = array('password' => self::PASSWORD, 'shpassword' => self::SALTEDHASHEDPASSWORD);
        $this->passwordAuthenticator->setParams($this->params);
        $this->request = array('password' => self::PASSWORD);
        $this->assertTrue($this->passwordAuthenticator->authenticate($this->request));
        
    }

}


