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

use Sociable\Utility\NumberValidator;

class NumberValidatorTest extends \PHPUnit_Framework_TestCase {

    public function testNumberValidatorNotANumber() {
        $this->setExpectedException('Sociable\Utility\NumberException', 
                NumberValidator::EXCEPTION_NOT_A_NUMBER);
        NumberValidator::validate(null);
    }
    
    public function testNumberValidatorCurrency() {
        NumberValidator::validate(1.23, array('is_currency' => true));
    }

    public function testNumberValidatorNotACurrency() {
        $this->setExpectedException('Sociable\Utility\NumberException', 
                NumberValidator::EXCEPTION_NOT_A_CURRENCY);
        NumberValidator::validate(1.234, array('is_currency' => true));
    }
    
    public function testNumberValidatorPositive() {
        NumberValidator::validate(1.234, array('positive' => true));
    }
    
    public function testNumberValidatorNotPositive() {
        $this->setExpectedException('Sociable\Utility\NumberException', 
                NumberValidator::EXCEPTION_NOT_POSITIVE);
        NumberValidator::validate(-1.234, array('positive' => true));
    }
    
    public function testNumberValidatorMin() {
        NumberValidator::validate(-1.234, array('min' => -1.3));
    }
    
    public function testNumberValidatorTooSmall() {
        $this->setExpectedException('Sociable\Utility\NumberException', 
                NumberValidator::EXCEPTION_TOO_SMALL);
        NumberValidator::validate(-1.234, array('min' => -1.2));
    }
    
    public function testNumberValidatorMax() {
        NumberValidator::validate(-1.234, array('max' => -1));
    }
    
    public function testNumberValidatorTooLarge() {
        $this->setExpectedException('Sociable\Utility\NumberException', 
                NumberValidator::EXCEPTION_TOO_LARGE);
        NumberValidator::validate(-1.234, array('max' => -2));
    }

    public function testNumberValidatorInteger() {
        NumberValidator::validate(-1, array('int' => true));
    }

    public function testNumberValidatorNotAnIntegerString() {
        $this->setExpectedException('Sociable\Utility\NumberException', 
                NumberValidator::EXCEPTION_NOT_AN_INTEGER);
        NumberValidator::validate('-1', array('int' => true));
    }
    
    public function testNumberValidatorNotAnIntegerFloat() {
        $this->setExpectedException('Sociable\Utility\NumberException', 
                NumberValidator::EXCEPTION_NOT_AN_INTEGER);
        NumberValidator::validate(-1., array('int' => true));
    }
    
    public function testNumberValidator() {
        NumberValidator::validate(-1.234);
    }

}


