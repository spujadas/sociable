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

use Sociable\Model\Organisation,
    Sociable\Model\BusinessSector,
    Sociable\Utility\StringValidator;

class OrganisationTest extends \PHPUnit_Framework_TestCase {

    protected $organisation;
    protected $businessSector;

    const NAME = 'Foo Bar Ltd';
    const TYPE = Organisation::BUSINESS_ORGANISATION;
    const BUSINESS_SECTOR_CODE = 'code';
    
    public function setUp() {
        $this->organisation = new Organisation();
        $this->businessSector = new BusinessSector;
    }

    public function test__construct() {
        $this->assertInstanceOf('Sociable\Model\Organisation', $this->organisation);
    }

    public function testSetNameNotAString() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_NOT_A_STRING);
        $this->organisation->setName(array());
    }
    
    public function testGetNameNotAString() {
        try {
            $this->organisation->setName(array());
        }
        catch (\Exception $e) {}
        $this->assertNull($this->organisation->getName());
    }
    
    public function testSetNameEmpty() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_EMPTY);
        $this->organisation->setName('');
    }
    
    public function testGetNameEmpty() {
        try {
            $this->organisation->setName('');
        }
        catch (\Exception $e) {}
        $this->assertNull($this->organisation->getName());
    }
    
    public function testSetNameTooLong() {
        $this->setExpectedException('Sociable\Utility\StringException', StringValidator::EXCEPTION_TOO_LONG);
        $this->organisation->setName(str_repeat('a', Organisation::NAME_MAX_LENGTH + 1));
    }
    
    public function testGetNameTooLong() {
        try {
            $this->organisation->setName(str_repeat('a', Organisation::NAME_MAX_LENGTH + 1));
        }
        catch (\Exception $e) {}
        $this->assertNull($this->organisation->getName());
    }
    
    public function testSetGetName() {
        $this->assertEquals(self::NAME, 
                $this->organisation->setName(self::NAME));
        $this->assertEquals(self::NAME, $this->organisation->getName());
    }

    public function testSetTypeInvalidType() {
        $this->setExpectedException('Sociable\Model\OrganisationException', 
            Organisation::EXCEPTION_INVALID_TYPE);
        $this->organisation->setType(null);
    }
    
    public function testGetTypeInvalidType() {
        try {
            $this->organisation->setType(null);
        }
        catch (\Exception $e) {}
        $this->assertNull($this->organisation->getType());
    }
    
    public function testSetGetType() {
        $this->assertEquals(self::TYPE, $this->organisation->setType(self::TYPE));
        $this->assertEquals(self::TYPE, $this->organisation->getType());
    }
    
    public function testSetGetBusinessSector() {
        $this->assertEquals($this->businessSector, 
                $this->organisation->setBusinessSector($this->businessSector));
        $this->assertEquals($this->businessSector, 
                $this->organisation->getBusinessSector());
    }
    
    public function testValidateUninitialised() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_NOT_A_STRING);
        $this->organisation->validate();
    }    

    public function testValidate() {
        $this->organisation->setName(self::NAME);
        $this->organisation->setType(self::TYPE);
        $this->organisation->setBusinessSector($this->businessSector);
        $this->organisation->validate();
    }

    public function tearDown() {
        unset($this->organisation);
    }

}


