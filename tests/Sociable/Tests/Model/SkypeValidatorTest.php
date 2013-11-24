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

use Sociable\Utility\SkypeValidator,
    Sociable\Utility\StringValidator;

class SkypeValidatorTest extends \PHPUnit_Framework_TestCase {

    const NAME = 'foo.bar';

    public function testValidateNotAString() {
        $this->setExpectedException('Sociable\Utility\StringException', 
            StringValidator::EXCEPTION_NOT_A_STRING);
        SkypeValidator::validateName(null);
    }

    public function testValidateTooLong() {
        $this->setExpectedException('Sociable\Utility\StringException', 
            StringValidator::EXCEPTION_TOO_LONG);
        SkypeValidator::validateName(str_repeat('a', SkypeValidator::SKYPE_MAX_LENGTH + 1));
    }
    
    public function testValidateTooShort() {
        $this->setExpectedException('Sociable\Utility\StringException', 
            StringValidator::EXCEPTION_TOO_SHORT);
        SkypeValidator::validateName(str_repeat('a', SkypeValidator::SKYPE_MIN_LENGTH - 1));
    }
    
    public function testValidateInvalidName() {
        $this->setExpectedException('Sociable\Utility\SkypeException', 
            SkypeValidator::EXCEPTION_INVALID_SKYPE_NAME);
        SkypeValidator::validateName(str_repeat('$', SkypeValidator::SKYPE_MIN_LENGTH));
    }
    
    public function testValidate() {
        SkypeValidator::validateName(self::NAME);
    }
    
}