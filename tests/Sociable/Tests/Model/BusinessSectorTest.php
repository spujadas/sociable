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

use Sociable\Model\BusinessSector,
    Sociable\Model\MultiLanguageString,
    Sociable\Utility\StringValidator;

class BusinessSectorTest extends \PHPUnit_Framework_TestCase {

    protected $businessSector;
    const CODE = 's';
    
    protected $name;
    protected $nameTooLong;
    
    public function setUp() {
        $this->businessSector = new BusinessSector();

        $this->name = new MultiLanguageString('secteur', 'fr');
        $this->nameTooLong = new MultiLanguageString(
                str_repeat('a', BusinessSector::NAME_MAX_LENGTH + 1),
                'fr');
        $this->nameEmpty = new MultiLanguageString('', 'fr');
    }

    public function test__construct() {
        $this->assertInstanceOf('Sociable\Model\BusinessSector', $this->businessSector);
    }

    public function testSetCodeNotAString() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_NOT_A_STRING);
        $this->businessSector->setCode(array());
    }
    
    public function testGetCodeNotAString() {
        try {
            $this->businessSector->setCode(array());
        }
        catch (\Exception $e) {}
        $this->assertNull($this->businessSector->getCode());
    }
    
    public function testSetCodeEmpty() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_EMPTY);
        $this->businessSector->setCode('');
    }
    
    public function testGetCodeEmpty() {
        try {
            $this->businessSector->setCode('');
        }
        catch (\Exception $e) {}
        $this->assertNull($this->businessSector->getCode());
    }
    
    public function testSetCodeTooLong() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_TOO_LONG);
        $this->businessSector->setCode(str_repeat('a', BusinessSector::CODE_MAX_LENGTH + 1));
    }
    
    public function testGetCodeTooLong() {
        try {
            $this->businessSector->setCode(str_repeat('a', BusinessSector::CODE_MAX_LENGTH + 1));
        }
        catch (\Exception $e) {}
        $this->assertNull($this->businessSector->getCode());
    }

    public function testSetGetCode() {
        $this->assertEquals(self::CODE, $this->businessSector->setCode(self::CODE));
        $this->assertEquals(self::CODE, $this->businessSector->getCode());
    }
    
    public function testSetNameEmpty() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_EMPTY);
        $this->businessSector->setName($this->nameEmpty);
    }
    
    public function testGetNameEmpty() {
        try {
            $this->businessSector->setName($this->nameEmpty);
        }
        catch (\Exception $e) {}
        $this->assertNull($this->businessSector->getName());
    }
    
    public function testSetNameTooLong() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_TOO_LONG);
        $this->businessSector->setName($this->nameTooLong);
    }
    
    public function testGetNameTooLong() {
        try {
            $this->businessSector->setName($this->nameTooLong);
        }
        catch (\Exception $e) {}
        $this->assertNull($this->businessSector->getName());
    }
    
    public function testSetGetName() {
        $this->assertEquals($this->name, 
                $this->businessSector->setName($this->name));
        $this->assertEquals($this->name, $this->businessSector->getName());
    }
    
    public function testValidateUninitialised() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_NOT_A_STRING);
        $this->businessSector->validate();
    }
    
    public function testValidate() {
        $this->assertEquals(self::CODE, $this->businessSector->setCode(self::CODE));
        $this->assertEquals($this->name, 
                $this->businessSector->setName($this->name));
        $this->businessSector->validate();
    }

    public function tearDown() {
        unset($this->businessSector);
    }

}


