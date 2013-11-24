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

use Sociable\Model\User,
    Sociable\Utility\EmailValidator,
    Sociable\Model\PasswordAuthenticator,
    Sociable\Utility\StringValidator;

class UserTest extends \PHPUnit_Framework_TestCase {

    protected $user;
    protected $authenticator;

    const NAME = 'Foo';
    const SURNAME = 'Bar';
    const EMAIL = 'foo@bar.com';
    
    public function setUp() {
        $this->user = new User;
        $this->authenticator = new PasswordAuthenticator();
        $this->authenticator->setParams(array('password' => '1234'));
    }

    public function test__construct() {
        $this->assertInstanceOf('Sociable\Model\User', $this->user);
    }

    public function testSetNameNotAString() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_NOT_A_STRING);
        $this->user->setName(array());
    }
    
    public function testGetNameNotAString() {
        try {
            $this->user->setName(array());
        }
        catch (\Exception $e) {}
        $this->assertNull($this->user->getName());
    }
    
    public function testSetNameEmpty() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_EMPTY);
        $this->user->setName('');
    }
    
    public function testGetNameEmpty() {
        try {
            $this->user->setName('');
        }
        catch (\Exception $e) {}
        $this->assertNull($this->user->getName());
    }
    
    public function testSetNameTooLong() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_TOO_LONG);
        $this->user->setName(str_repeat('a', User::NAME_MAX_LENGTH + 1));
    }
    
    public function testGetNameTooLong() {
        try {
            $this->user->setName(str_repeat('a', User::NAME_MAX_LENGTH + 1));
        }
        catch (\Exception $e) {}
        $this->assertNull($this->user->getName());
    }
    
    public function testSetGetName() {
        $this->assertEquals(null, $this->user->setName());
        $this->assertEquals(null, $this->user->getName());
        $this->assertEquals(self::NAME, 
                $this->user->setName(self::NAME));
        $this->assertEquals(self::NAME, $this->user->getName());
    }
    
    public function testSetSurnameNotAString() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_NOT_A_STRING);
        $this->user->setSurname(array());
    }
    
    public function testGetSurnameNotAString() {
        try {
            $this->user->setSurname(array());
        }
        catch (\Exception $e) {}
        $this->assertNull($this->user->getSurname());
    }
    
    public function testSetSurnameEmpty() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_EMPTY);
        $this->user->setSurname('');
    }
    
    public function testGetSurnameEmpty() {
        try {
            $this->user->setSurname('');
        }
        catch (\Exception $e) {}
        $this->assertNull($this->user->getSurname());
    }
    
    public function testSetSurnameTooLong() {
        $this->setExpectedException('Sociable\Utility\StringException', StringValidator::EXCEPTION_TOO_LONG);
        $this->user->setSurname(str_repeat('a', User::SURNAME_MAX_LENGTH + 1));
    }
    
    public function testGetSurnameTooLong() {
        try {
            $this->user->setSurname(str_repeat('a', User::SURNAME_MAX_LENGTH + 1));
        }
        catch (\Exception $e) {}
        $this->assertNull($this->user->getSurname());
    }
    
    public function testSetGetSurname() {
        $this->assertEquals(null, $this->user->setSurname());
        $this->assertEquals(null, $this->user->getSurname());
        $this->assertEquals(self::SURNAME, 
                $this->user->setSurname(self::SURNAME));
        $this->assertEquals(self::SURNAME, $this->user->getSurname());
    }
    
    public function testSetEmailInvalidEmail() {
        $this->setExpectedException('Sociable\Utility\EmailException', 
                EmailValidator::EXCEPTION_INVALID_ADDRESS);
        $this->user->setEmail('');
    }
    
    public function testGetEmailInvalidEmail() {
        try {
            $this->user->setEmail('');
        }
        catch (\Exception $e) {}
        $this->assertNull($this->user->getEmail());
    }
    
    public function testSetGetEmail() {
        $this->assertEquals(self::EMAIL, $this->user->setEmail(self::EMAIL));
        $this->assertEquals(self::EMAIL, $this->user->getEmail());
    }

    public function testSetAuthenticatorInvalidAuthenticator() {
        $this->setExpectedException('Sociable\Utility\StringException', 
            StringValidator::EXCEPTION_NOT_A_STRING);
        $this->user->setAuthenticator(new PasswordAuthenticator());
    }
    
    public function testGetAuthenticatorInvalidAuthenticator() {
        try {
            $this->user->setAuthenticator(new PasswordAuthenticator());
        }
        catch (\Exception $e) {}
        $this->assertNull($this->user->getAuthenticator());
    }
    
    public function testSetGetAuthenticator() {
        $this->assertEquals($this->authenticator, $this->user->setAuthenticator($this->authenticator));
        $this->assertEquals($this->authenticator, $this->user->getAuthenticator());
    }

    public function testValidateUninitialised() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_NOT_A_STRING);
        $this->user->validate();
    }

    public function testValidateMissingAuthenticator() {
        $this->user->setName(self::NAME);
        $this->user->setSurname(self::SURNAME);
        $this->user->setEmail(self::EMAIL);
        $this->setExpectedException('Sociable\Model\UserException', 
                User::EXCEPTION_MISSING_AUTHENTICATOR);
        $this->user->validate();
    }

    public function testValidate() {
        $this->user->setEmail(self::EMAIL);
        $this->user->setAuthenticator($this->authenticator);
        $this->user->validate();
        $this->user->setName(self::NAME);
        $this->user->setSurname(self::SURNAME);
        $this->user->validate();
    }

    public function tearDown() {
        unset($this->user);
    }

}


