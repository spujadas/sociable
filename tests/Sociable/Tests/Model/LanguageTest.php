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

use Sociable\Model\Language,
    Sociable\Utility\StringValidator;

class LanguageTest extends \PHPUnit_Framework_TestCase {

    protected $language;

    const CODE = 'fr';
    const DISPLAY_NAME = 'français';
    
    public function setUp() {
        $this->language = new Language();
    }

    public function test__construct() {
        $this->assertInstanceOf('Sociable\Model\Language', $this->language);
    }

    public function testSetCodeNotAString() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_NOT_A_STRING);
        $this->language->setCode(null);
    }
    
    public function testGetCodeNotAString() {
        try {
            $this->language->setCode(null);
        }
        catch (\Exception $e) {}
        $this->assertNull($this->language->getCode());
    }
    
    public function testSetCodeEmpty() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_EMPTY);
        $this->language->setCode('');
    }
    
    public function testGetCodeEmpty() {
        try {
            $this->language->setCode('');
        }
        catch (\Exception $e) {}
        $this->assertNull($this->language->getCode());
    }
    
    public function testSetCodeTooLong() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_TOO_LONG);
        $this->language->setCode(str_repeat('a', Language::CODE_MAX_LENGTH + 1));
    }
    
    public function testGetCodeTooLong() {
        try {
            $this->language->setCode(str_repeat('a', Language::CODE_MAX_LENGTH + 1));
        }
        catch (\Exception $e) {}
        $this->assertNull($this->language->getCode());
    }

    public function testSetGetCode() {
        $this->assertEquals(self::CODE, $this->language->setCode(self::CODE));
        $this->assertEquals(self::CODE, $this->language->getCode());
    }
    
    public function testSetDisplayNameNotAString() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_NOT_A_STRING);
        $this->language->setDisplayName(null);
    }
    
    public function testGetDisplayNameNotAString() {
        try {
            $this->language->setDisplayName(null);
        }
        catch (\Exception $e) {}
        $this->assertNull($this->language->getDisplayName());
    }
    
    public function testSetDisplayNameTooLong() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_TOO_LONG);
        $this->language->setDisplayName(str_repeat('a', Language::DISPLAY_NAME_MAX_LENGTH + 1));
    }
    
    public function testGetDisplayNameTooLong() {
        try {
            $this->language->setDisplayName(str_repeat('a', Language::DISPLAY_NAME_MAX_LENGTH + 1));
        }
        catch (\Exception $e) {}
        $this->assertNull($this->language->getDisplayName());
    }

    public function testSetGetDisplayName() {
        $this->assertEquals(self::DISPLAY_NAME, $this->language->setDisplayName(self::DISPLAY_NAME));
        $this->assertEquals(self::DISPLAY_NAME, $this->language->getDisplayName());
    }
    
    public function testValidateUninitialised() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_NOT_A_STRING);
        $this->language->validate();
    }

    public function testValidate() {
        $this->language->setCode(self::CODE);
        $this->language->setDisplayName(self::DISPLAY_NAME);
        $this->language->validate();
    }

    public function tearDown() {
        unset($this->language);
    }

}


