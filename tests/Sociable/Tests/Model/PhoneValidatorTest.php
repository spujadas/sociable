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

use Sociable\Utility\PhoneValidator,
    Sociable\Utility\StringValidator;

class PhoneValidatorTest extends \PHPUnit_Framework_TestCase {

    const NUMBER = '0123456789';

    public function testValidateNotAString() {
        $this->setExpectedException('Sociable\Utility\StringException', 
            StringValidator::EXCEPTION_NOT_A_STRING);
        PhoneValidator::validateNumber(null);
    }

    public function testValidateTooLong() {
        $this->setExpectedException('Sociable\Utility\StringException', 
            StringValidator::EXCEPTION_TOO_LONG);
        PhoneValidator::validateNumber(str_repeat('0', PhoneValidator::PHONE_MAX_LENGTH + 1));
    }
    
    public function testValidateNotEnoughDigits() {
        $this->setExpectedException('Sociable\Utility\StringException', 
            StringValidator::EXCEPTION_TOO_SHORT);
        PhoneValidator::validateNumber('+' . str_repeat('0', PhoneValidator::PHONE_MIN_DIGITS - 1));
    }
    
    public function testValidate() {
        PhoneValidator::validateNumber(self::NUMBER);
    }
    
}