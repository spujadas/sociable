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

use Sociable\Utility\EmailValidator,
    Sociable\Utility\StringValidator;

class EmailValidatorTest extends \PHPUnit_Framework_TestCase {

    protected $email = null;
    
    const MAX_LOCAL_PART_LENGTH = 64;
    
    public function testValidateAddressArray() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_NOT_A_STRING);
        EmailValidator::validateAddress(array());
    }
    
    public function testValidateAddressNull() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_NOT_A_STRING);
        EmailValidator::validateAddress(null);
    }
    
    public function testValidateAddressNoAt() {
        $this->setExpectedException('Sociable\Utility\EmailException',
                EmailValidator::EXCEPTION_INVALID_ADDRESS);
        EmailValidator::validateAddress('foo');
    }
    
    public function testValidateAddressNoDomain() {
        $this->setExpectedException('Sociable\Utility\EmailException', 
                EmailValidator::EXCEPTION_INVALID_ADDRESS);
        EmailValidator::validateAddress('foo@');
    }
    
    public function testValidateAddressNoTld() {
        $this->setExpectedException('Sociable\Utility\EmailException', 
                EmailValidator::EXCEPTION_INVALID_ADDRESS);
        EmailValidator::validateAddress('foo@bar');
    }
    
    public function testValidateAddressNoLocalPart() {
        $this->setExpectedException('Sociable\Utility\EmailException', 
                EmailValidator::EXCEPTION_INVALID_ADDRESS);
        EmailValidator::validateAddress('@bar.com');
    }

    public function testValidateAddressLocalPartTooLong() {
        $this->setExpectedException('Sociable\Utility\EmailException', 
                EmailValidator::EXCEPTION_INVALID_ADDRESS);
        EmailValidator::validateAddress(
                str_repeat('a', self::MAX_LOCAL_PART_LENGTH + 1) . '@bar.com'
        );
    }
    
    public function testValidateAndNormaliseEmail() {
        $this->assertEquals('foo@bar.com', EmailValidator::validateAndNormaliseAddress('foo@bar.com'));
        $this->assertEquals('foo@bar.com', EmailValidator::validateAndNormaliseAddress(' foo@bar.com'));
        $this->assertEquals('foo@bar.com', EmailValidator::validateAndNormaliseAddress(' foo@BAR.COM '));
        $this->assertEquals('FOO@bar.com', EmailValidator::validateAndNormaliseAddress(' FOO@BAR.COM '));
    }
    

}


