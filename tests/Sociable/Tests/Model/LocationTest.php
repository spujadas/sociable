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

use Sociable\Model\Location,
    Sociable\Model\Country,
    Sociable\Model\MultiLanguageString,
    Sociable\Utility\StringValidator;

class LocationTest extends \PHPUnit_Framework_TestCase {

    protected $location;
    
    const NAME = 'Hauts-de-Seine';
    const LABEL = 'hds';
    const SUBLOCATIONS_NAME = 'communes';
    
    protected $parentCountry;
    protected $parentLocation;
    const PARENT_LOCATION_NAME = 'Ile-de-France';
    const PARENT_LOCATION_LABEL = 'idf';
    
    public function setUp() {
        $this->location = new Location();

        $countryName = new MultiLanguageString('ZZZZZ', 'fr');
        
        $this->parentCountry = new Country();
        $this->parentCountry->setCode('FR');
        $this->parentCountry->setName($countryName);
        $this->parentCountry->setLocationsName('département');
        $this->parentCountry->validate();
        
        $this->parentLocation = new Location();
        $this->parentLocation->setName(self::PARENT_LOCATION_NAME);
        $this->parentLocation->setLabel(self::PARENT_LOCATION_LABEL);
        $this->parentLocation->setParentCountry($this->parentCountry);
    }

    public function test__construct() {
        $this->assertInstanceOf('Sociable\Model\Location', $this->location);
    }
    
    public function testSetNameNotAString() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_NOT_A_STRING);
        $this->location->setName(array());
    }
    
    public function testGetNameNotAString() {
        try {
            $this->location->setName(array());
        }
        catch (\Exception $e) {}
        $this->assertNull($this->location->getName());
    }
    
    public function testSetNameEmpty() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_EMPTY);
        $this->location->setName('');
    }
    
    public function testGetNameEmpty() {
        try {
            $this->location->setName('');
        }
        catch (\Exception $e) {}
        $this->assertNull($this->location->getName());
    }
    
    public function testSetNameTooLong() {
        $this->setExpectedException('Sociable\Utility\StringException', StringValidator::EXCEPTION_TOO_LONG);
        $this->location->setName(str_repeat('a', Location::NAME_MAX_LENGTH + 1));
    }
    
    public function testGetNameTooLong() {
        try {
            $this->location->setName(str_repeat('a', Location::NAME_MAX_LENGTH + 1));
        }
        catch (\Exception $e) {}
        $this->assertNull($this->location->getName());
    }
    
    public function testSetGetName() {
        $this->assertEquals(self::NAME, 
                $this->location->setName(self::NAME));
        $this->assertEquals(self::NAME, $this->location->getName());
    }
    
    public function testSetLabelNotAString() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_NOT_A_STRING);
        $this->location->setLabel(array());
    }
    
    public function testGetLabelNotAString() {
        try {
            $this->location->setLabel(array());
        }
        catch (\Exception $e) {}
        $this->assertNull($this->location->getLabel());
    }
    
    public function testSetLabelEmpty() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_EMPTY);
        $this->location->setLabel('');
    }
    
    public function testGetLabelEmpty() {
        try {
            $this->location->setLabel('');
        }
        catch (\Exception $e) {}
        $this->assertNull($this->location->getLabel());
    }
    
    public function testSetLabelTooLong() {
        $this->setExpectedException('Sociable\Utility\StringException', StringValidator::EXCEPTION_TOO_LONG);
        $this->location->setLabel(str_repeat('a', Location::LABEL_MAX_LENGTH + 1));
    }
    
    public function testGetLabelTooLong() {
        try {
            $this->location->setLabel(str_repeat('a', Location::LABEL_MAX_LENGTH + 1));
        }
        catch (\Exception $e) {}
        $this->assertNull($this->location->getLabel());
    }
    
    public function testSetGetLabel() {
        $this->assertEquals(self::LABEL, 
                $this->location->setLabel(self::LABEL));
        $this->assertEquals(self::LABEL, $this->location->getLabel());
    }
    
    public function testSetSublocationsNameNotAString() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_NOT_A_STRING);
        $this->location->setSublocationsName(array());
    }
    
    public function testGetSublocationsNameNotAString() {
        try {
            $this->location->setSublocationsName(array());
        }
        catch (\Exception $e) {}
        $this->assertNull($this->location->getSublocationsName());
    }
    
    public function testSetSublocationsNameEmpty() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_EMPTY);
        $this->location->setSublocationsName('');
    }
    
    public function testGetSublocationsNameEmpty() {
        try {
            $this->location->setSublocationsName('');
        }
        catch (\Exception $e) {}
        $this->assertNull($this->location->getSublocationsName());
    }
    
    public function testSetSublocationsNameTooLong() {
        $this->setExpectedException('Sociable\Utility\StringException', StringValidator::EXCEPTION_TOO_LONG);
        $this->location->setSublocationsName(str_repeat('a', Location::NAME_MAX_LENGTH + 1));
    }
    
    public function testGetSublocationsNameTooLong() {
        try {
            $this->location->setSublocationsName(str_repeat('a', Location::NAME_MAX_LENGTH + 1));
        }
        catch (\Exception $e) {}
        $this->assertNull($this->location->getSublocationsName());
    }
    
    public function testSetGetSublocationsName() {
        $this->assertNull($this->location->getSublocationsName());
        $this->assertEquals(self::NAME, 
                $this->location->setSublocationsName(self::NAME));
        $this->assertEquals(self::NAME, $this->location->getSublocationsName());
    }
    
    public function testSetGetParentCountry() {
        $this->assertEquals($this->parentCountry,
                $this->location->setParentCountry($this->parentCountry));
        $this->assertEquals($this->parentCountry, 
                $this->location->getParentCountry());
        $this->assertEquals(Location::PARENT_TYPE_COUNTRY, 
                $this->location->getParentType());
    }
    
    public function testSetGetParentLocation() {
        $this->assertEquals($this->parentLocation, 
                $this->location->setParentLocation($this->parentLocation));
        $this->assertEquals($this->parentLocation, 
                $this->location->getParentLocation());
        $this->assertEquals(Location::PARENT_TYPE_LOCATION, 
                $this->location->getParentType());
    }
    
    public function testValidateUninitialised() {
        $this->setExpectedException('Sociable\Utility\StringException', 
            StringValidator::EXCEPTION_NOT_A_STRING);
        $this->location->validate();
    }

    public function testValidateMissingParentCountry() {
        $this->location->setName(self::NAME);
        $this->location->setLabel(self::LABEL);
        $this->setExpectedException('Sociable\Model\LocationException', 
            Location::EXCEPTION_INVALID_TYPE);
        $this->location->validate();
    }

    public function testValidateMissingParentLocation() {
        $this->location->setName(self::NAME);
        $this->location->setLabel(self::LABEL);
        $this->setExpectedException('Sociable\Model\LocationException', 
            Location::EXCEPTION_INVALID_TYPE);
        $this->location->validate();
    }

    public function testValidate() {
        $this->location->setName(self::NAME);
        $this->location->setLabel(self::LABEL);
        $this->location->setParentCountry($this->parentCountry);
        $this->location->validate();
        $this->location->setParentLocation($this->parentLocation);
        $this->location->validate();
    }

    public function tearDown() {
        unset($this->location);
    }

}


