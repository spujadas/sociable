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

use Sociable\Utility\StringValidator;

class StringValidatorTest extends \PHPUnit_Framework_TestCase {

    protected $string = 'test';

    public function testValidateNotAString() {
        $this->setExpectedException('Sociable\Utility\StringException', 
            StringValidator::EXCEPTION_NOT_A_STRING);
        StringValidator::validate(null);
    }

    public function testValidateEmpty() {
        $this->setExpectedException('Sociable\Utility\StringException', 
            StringValidator::EXCEPTION_EMPTY);
        StringValidator::validate('',
                array('not_empty' => true));
    }

    public function testValidateTooShort() {
        $this->setExpectedException('Sociable\Utility\StringException', 
            StringValidator::EXCEPTION_TOO_SHORT);
        StringValidator::validate(str_repeat('-', strlen($this->string) - 1),
                array('min_length' => strlen($this->string)));
    }
    
    public function testValidateTooLong() {
        $this->setExpectedException('Sociable\Utility\StringException', 
            StringValidator::EXCEPTION_TOO_LONG);
        StringValidator::validate(str_repeat('-', strlen($this->string) + 1),
                array('max_length' => strlen($this->string)));
    }
    
    public function testValidateIncorrectLength() {
        $this->setExpectedException('Sociable\Utility\StringException', 
            StringValidator::EXCEPTION_INCORRECT_LENGTH);
        StringValidator::validate(str_repeat('-', strlen($this->string) + 1),
                array('length' => strlen($this->string)));
    }
    
    public function testValidate() {
        StringValidator::validate($this->string);
    }
    
}