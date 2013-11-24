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

use Sociable\Model\Country,
    Sociable\Model\MultiLanguageString,
    Sociable\Utility\StringValidator;

class CountryTest extends \PHPUnit_Framework_TestCase {

    protected $country;

    const CODE = 'FR';
    protected $name;
    protected $nameTooLong;
    
    const LOCATIONS_NAME = 'département';
    const LOCATION_NAME = 'Hauts-de-Seine';
    
    public function setUp() {
        $this->country = new Country();

        $this->name = new MultiLanguageString('France', 'fr');
        $this->nameTooLong = new MultiLanguageString(
                str_repeat('a', Country::NAME_MAX_LENGTH + 1),
                'fr');
    }

    public function test__construct() {
        $this->assertInstanceOf('Sociable\Model\Country', $this->country);
    }

    public function testSetCodeNotAString() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_NOT_A_STRING);
        $this->country->setCode(null);
    }
    
    public function testGetCodeNotAString() {
        try {
            $this->country->setCode(null);
        }
        catch (\Exception $e) {}
        $this->assertNull($this->country->getCode());
    }
    
    public function testSetCodeIncorrectLength() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_INCORRECT_LENGTH);
        $this->country->setCode(str_repeat('a', Country::CODE_LENGTH + 1));
    }
    
    public function testGetCodeIncorrectLength() {
        try {
            $this->country->setCode(str_repeat('a', Country::CODE_LENGTH + 1));
        }
        catch (\Exception $e) {}
        $this->assertNull($this->country->getCode());
    }

    public function testSetGetCode() {
        $this->assertEquals(self::CODE, $this->country->setCode(self::CODE));
        $this->assertEquals(self::CODE, $this->country->getCode());
    }
    
    public function testSetNameTooLong() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_TOO_LONG);
        $this->country->setName($this->nameTooLong);
    }
    
    public function testGetNameTooLong() {
        try {
            $this->country->setName($this->nameTooLong);
        }
        catch (\Exception $e) {}
        $this->assertNull($this->country->getName());
    }
    
    public function testSetGetName() {
        $this->assertEquals($this->name, 
                $this->country->setName($this->name));
        $this->assertEquals($this->name, $this->country->getName());
    }
    
    public function testSetLocationsNameNotAString() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_NOT_A_STRING);
        $this->country->setLocationsName(array());
    }
    
    public function testGetLocationsNameNotAString() {
        try {
            $this->country->setLocationsName(array());
        }
        catch (\Exception $e) {}
        $this->assertNull($this->country->getLocationsName());
    }
    
    public function testSetLocationsNameTooLong() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_TOO_LONG);
        $this->country->setLocationsName(str_repeat('a', Country::LOCATIONS_NAME_MAX_LENGTH + 1));
    }
    
    public function testGetLocationsNameTooLong() {
        try {
            $this->country->setLocationsName(str_repeat('a', Country::LOCATIONS_NAME_MAX_LENGTH + 1));
        }
        catch (\Exception $e) {}
        $this->assertNull($this->country->getLocationsName());
    }

    public function testSetGetLocationsName() {
        $this->assertNull($this->country->setLocationsName(null));
        $this->assertNull($this->country->getLocationsName());
        $this->assertEquals(self::LOCATIONS_NAME, $this->country->setLocationsName(self::LOCATIONS_NAME));
        $this->assertEquals(self::LOCATIONS_NAME, $this->country->getLocationsName());
    }
    
    public function testValidateUninitialised() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_NOT_A_STRING);
        $this->country->validate();
    }
    
    public function testValidate() {
        $this->assertEquals(self::CODE, $this->country->setCode(self::CODE));
        $this->assertEquals($this->name, 
                $this->country->setName($this->name));
        $this->assertEquals(self::LOCATIONS_NAME, 
                $this->country->setLocationsName(self::LOCATIONS_NAME));
        $this->country->validate();
    }

    public function tearDown() {
        unset($this->country);
    }

}


