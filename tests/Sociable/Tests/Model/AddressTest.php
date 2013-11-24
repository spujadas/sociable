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

use Sociable\Model\Address,
    Sociable\Model\Country,
    Sociable\Utility\StringValidator;

class AddressTest extends \PHPUnit_Framework_TestCase {

    protected $address;

    const ADDRESS1 = '1 rue du Château';
    const ADDRESS2 = 'Appartement A';
    const CITYAREAORDISTRICT = 'Ile-de-France';
    const POSTCODE = '92290';
    const CITYORTOWNORVILLAGE = 'Châtenay-Malabry';
    const COUNTY = 'Le Comté';
    protected $country;
    
    public function setUp() {
        $this->address = new Address();
        $this->country = new Country();
    }

    public function test__construct() {
        $this->assertInstanceOf('Sociable\Model\Address', $this->address);
    }

    public function testSetAddress1NotAString() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_NOT_A_STRING);
        $this->address->setAddress1(array());
    }
    
    public function testGetAddress1NotAString() {
        try {
            $this->address->setAddress1(array());
        }
        catch (\Exception $e) {}
        $this->assertNull($this->address->getAddress1());
    }
    
    public function testSetAddress1TooLong() {
        $this->setExpectedException('Sociable\Utility\StringException', StringValidator::EXCEPTION_TOO_LONG);
        $this->address->setAddress1(str_repeat('a', Address::ADDRESS1_MAX_LENGTH + 1));
    }
    
    public function testGetAddress1TooLong() {
        try {
            $this->address->setAddress1(str_repeat('a', Address::ADDRESS1_MAX_LENGTH + 1));
        }
        catch (\Exception $e) {}
        $this->assertNull($this->address->getAddress1());
    }
    
    public function testSetGetAddress1() {
        $this->assertEquals('', $this->address->setAddress1(''));
        $this->assertEquals('', $this->address->getAddress1());
        $this->assertEquals(self::ADDRESS1, 
                $this->address->setAddress1(self::ADDRESS1));
        $this->assertEquals(self::ADDRESS1, $this->address->getAddress1());
    }

    public function testSetAddress2NotAString() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_NOT_A_STRING);
        $this->address->setAddress2(array());
    }
    
    public function testGetAddress2NotAString() {
        try {
            $this->address->setAddress2(array());
        }
        catch (\Exception $e) {}
        $this->assertNull($this->address->getAddress2());
    }
    
    public function testSetAddress2TooLong() {
        $this->setExpectedException('Sociable\Utility\StringException', StringValidator::EXCEPTION_TOO_LONG);
        $this->address->setAddress2(str_repeat('a', Address::ADDRESS2_MAX_LENGTH + 1));
    }
    
    public function testGetAddress2TooLong() {
        try {
            $this->address->setAddress2(str_repeat('a', Address::ADDRESS2_MAX_LENGTH + 1));
        }
        catch (\Exception $e) {}
        $this->assertNull($this->address->getAddress2());
    }
    
    public function testSetGetAddress2() {
        $this->assertEquals(null, $this->address->setAddress2(null));
        $this->assertEquals(null, $this->address->getAddress2());
        $this->assertEquals('', $this->address->setAddress2(''));
        $this->assertEquals('', $this->address->getAddress2());
        $this->assertEquals(self::ADDRESS2, 
                $this->address->setAddress2(self::ADDRESS2));
        $this->assertEquals(self::ADDRESS2, $this->address->getAddress2());
    }
    
    public function testSetCityAreaOrDistrictNotAString() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_NOT_A_STRING);
        $this->address->setCityAreaOrDistrict(array());
    }
    
    public function testGetCityAreaOrDistrictNotAString() {
        try {
            $this->address->setCityAreaOrDistrict(array());
        }
        catch (\Exception $e) {}
        $this->assertNull($this->address->getCityAreaOrDistrict());
    }

    public function testSetCityAreaOrDistrictTooLong() {
        $this->setExpectedException('Sociable\Utility\StringException', StringValidator::EXCEPTION_TOO_LONG);
        $this->address->setCityAreaOrDistrict(str_repeat('a', Address::CITYAREAORDISTRICT_MAX_LENGTH + 1));
    }
    
    public function testGetCityAreaOrDistrictTooLong() {
        try {
            $this->address->setCityAreaOrDistrict(str_repeat('a', Address::CITYAREAORDISTRICT_MAX_LENGTH + 1));
        }
        catch (\Exception $e) {}
        $this->assertNull($this->address->getCityAreaOrDistrict());
    }

    public function testSetGetCityAreaOrDistrict() {
        $this->assertEquals(null, $this->address->setCityAreaOrDistrict(null));
        $this->assertEquals(null, $this->address->getCityAreaOrDistrict());
        $this->assertEquals('', $this->address->setCityAreaOrDistrict(''));
        $this->assertEquals('', $this->address->getCityAreaOrDistrict());
        $this->assertEquals(self::CITYAREAORDISTRICT, 
                $this->address->setCityAreaOrDistrict(self::CITYAREAORDISTRICT));
        $this->assertEquals(self::CITYAREAORDISTRICT, 
                $this->address->getCityAreaOrDistrict());
    }

    public function testSetPostCodeNotAString() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_NOT_A_STRING);
        $this->address->setPostCode(null);
    }
    
    public function testGetPostCodeNotAString() {
        try {
            $this->address->setPostCode(null);
        }
        catch (\Exception $e) {}
        $this->assertNull($this->address->getPostCode());
    }


    public function testSetPostCodeTooShort() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_TOO_SHORT);
        $this->address->setPostCode(str_repeat('a', Address::POSTCODE_MIN_LENGTH - 1));
    }
    
    public function testGetPostCodeTooShort() {
        try {
            $this->address->setPostCode(str_repeat('a', Address::POSTCODE_MIN_LENGTH - 1));
        }
        catch (\Exception $e) {}
        $this->assertNull($this->address->getPostCode());
    }

    public function testSetPostCodeTooLong() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_TOO_LONG);
        $this->address->setPostCode(str_repeat('a', Address::POSTCODE_MAX_LENGTH + 1));
    }
    
    public function testGetPostCodeTooLong() {
        try {
            $this->address->setPostCode(str_repeat('a', Address::POSTCODE_MAX_LENGTH + 1));
        }
        catch (\Exception $e) {}
        $this->assertNull($this->address->getPostCode());
    }

    public function testSetGetPostCode() {
        $this->assertEquals(self::POSTCODE, 
                $this->address->setPostCode(self::POSTCODE));
        $this->assertEquals(self::POSTCODE, $this->address->getPostCode());
    }
    
    public function testSetCityOrTownOrVillageNotAString() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_NOT_A_STRING);
        $this->address->setCityOrTownOrVillage(null);
    }
    
    public function testGetCityOrTownOrVillageNotAString() {
        try {
            $this->address->setCityOrTownOrVillage(null);
        }
        catch (\Exception $e) {}
        $this->assertNull($this->address->getCityOrTownOrVillage());
    }

    public function testSetCityOrTownOrVillageEmpty() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_EMPTY);
        $this->address->setCityOrTownOrVillage('');
    }
    
    public function testGetCityOrTownOrVillageEmpty() {
        try {
            $this->address->setCityOrTownOrVillage('');
        }
        catch (\Exception $e) {}
        $this->assertNull($this->address->getCityOrTownOrVillage());
    }

    public function testSetCityOrTownOrVillageTooLong() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_TOO_LONG);
        $this->address->setCityOrTownOrVillage(str_repeat('a', Address::CITYORTOWNORVILLAGE_MAX_LENGTH + 1));
    }
    
    public function testGetCityOrTownOrVillageTooLong() {
        try {
            $this->address->setCityOrTownOrVillage(str_repeat('a', Address::CITYORTOWNORVILLAGE_MAX_LENGTH + 1));
        }
        catch (\Exception $e) {}
        $this->assertNull($this->address->getCityOrTownOrVillage());
    }

    public function testSetGetCityOrTownOrVillage() {
        $this->assertEquals(self::CITYORTOWNORVILLAGE, 
                $this->address->setCityOrTownOrVillage(
                        self::CITYORTOWNORVILLAGE)
                );
        $this->assertEquals(self::CITYORTOWNORVILLAGE, 
                $this->address->getCityOrTownOrVillage());
    }
    
    public function testSetCountyNotAString() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_NOT_A_STRING);
        $this->address->setCounty(array());
    }
    
    public function testGetCountyNotAString() {
        try {
            $this->address->setCounty(array());
        }
        catch (\Exception $e) {}
        $this->assertNull($this->address->getCounty());
    }

    public function testSetCountyTooLong() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_TOO_LONG);
        $this->address->setCounty(str_repeat('a', Address::COUNTY_MAX_LENGTH + 1));
    }
    
    public function testGetCountyTooLong() {
        try {
            $this->address->setCounty(str_repeat('a', Address::COUNTY_MAX_LENGTH + 1));
        }
        catch (\Exception $e) {}
        $this->assertNull($this->address->getCounty());
    }

    public function testSetGetCounty() {
        $this->assertEquals(null, $this->address->setCounty(null));
        $this->assertEquals(null, $this->address->getCounty());
        $this->assertEquals('', $this->address->setCounty(''));
        $this->assertEquals('', $this->address->getCounty());
        $this->assertEquals(self::COUNTY, 
                $this->address->setCounty(
                        self::COUNTY)
                );
        $this->assertEquals(self::COUNTY, 
                $this->address->getCounty());
    }

    public function testSetGetCountry() {
        $this->assertEquals($this->country, 
            $this->address->setCountry($this->country));
        $this->assertEquals($this->country, 
                $this->address->getCountry());
    }
    
    public function testValidateUninitialised() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_NOT_A_STRING);
        $this->address->validate();
    }
    
    public function testValidateMissingCountry() {
        $this->address->setAddress1(self::ADDRESS1);
        $this->address->setPostCode(self::POSTCODE);
        $this->address->setCityOrTownOrVillage(self::CITYORTOWNORVILLAGE);
        $this->setExpectedException('Sociable\Model\AddressException', 
                Address::EXCEPTION_MISSING_COUNTRY);
        $this->address->validate();
    }

    public function testValidate() {
        $this->address->setAddress1(self::ADDRESS1);
        $this->address->setPostCode(self::POSTCODE);
        $this->address->setCityOrTownOrVillage(self::CITYORTOWNORVILLAGE);
        $this->address->setCountry($this->country);
        $this->address->validate();
        $this->address->setAddress2(self::ADDRESS2);
        $this->address->setCityAreaOrDistrict(self::CITYAREAORDISTRICT);
        $this->address->setCounty(self::COUNTY);
        $this->address->validate();
    }

    public function tearDown() {
        unset($this->address);
    }

}


