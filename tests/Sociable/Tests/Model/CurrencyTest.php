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

use Sociable\Model\Currency,
    Sociable\Utility\StringValidator;

class CurrencyTest extends \PHPUnit_Framework_TestCase {

    protected $currency;

    const CODE = 'EUR';
    
    public function setUp() {
        $this->currency = new Currency();
    }

    public function test__construct() {
        $this->assertInstanceOf('Sociable\Model\Currency', $this->currency);
    }

    public function testSetCodeNotAString() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_NOT_A_STRING);
        $this->currency->setCode(null);
    }
    
    public function testGetCodeNotAString() {
        try {
            $this->currency->setCode(null);
        }
        catch (\Exception $e) {}
        $this->assertNull($this->currency->getCode());
    }
    
    public function testSetCodeIncorrectLength() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_INCORRECT_LENGTH);
        $this->currency->setCode(str_repeat('a', Currency::CODE_LENGTH + 1));
    }
    
    public function testGetCodeIncorrectLength() {
        try {
            $this->currency->setCode(str_repeat('a', Currency::CODE_LENGTH + 1));
        }
        catch (\Exception $e) {}
        $this->assertNull($this->currency->getCode());
    }

    public function testSetGetCode() {
        $this->assertEquals(self::CODE, $this->currency->setCode(self::CODE));
        $this->assertEquals(self::CODE, $this->currency->getCode());
    }
    
    public function testValidateUninitialised() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_NOT_A_STRING);
        $this->currency->validate();
    }

    public function testValidate() {
        $this->currency->setCode(self::CODE);
        $this->currency->validate();
    }

    public function tearDown() {
        unset($this->currency);
    }

}


