<?php

/*
 * This file is part of the Sociable package.
 *
 * Copyright 2013 by Sébastien Pujadas
 *
 * For the full copyright and licence information, please view the LICENCE
 * file that was distributed with this source code.
 */

namespace Sociable\Tests\ODM;

use Sociable\Model\Location,
    Sociable\Model\Country,
    Sociable\Model\MultiLanguageString,
    Sociable\ODM\ObjectDocumentMapper;

class LocationODMTest extends \PHPUnit_Framework_TestCase {
    protected static $dm ;
    
    protected $country;

    const COUNTRY_CODE = 'ZZ';
    protected $name;
    const LOCATIONS_NAME = 'région';
    
    protected $location;
    const LOCATION_NAME = 'Île-de-France';
    const LOCATION_LABEL = 'idf';
    const LOCATION_SUBLOCATIONS_NAME = 'département';
    
    protected $sublocation;
    const SUBLOCATION_NAME = 'Hauts-de-Seine';
    const SUBLOCATION_LABEL = 'hds';

    public static function setUpBeforeClass() {
        include SOCIABLE_ROOT . '/config-test.inc.php'; // initialises $config
        self::$dm = $config->getDocumentManager();
    }
    
    public function setUp() {
        self::cleanUp();
        
        $this->name = new MultiLanguageString('ZZZZZ', 'fr');
        
        $this->country = new Country();
        $this->country->setCode(self::COUNTRY_CODE);
        $this->country->setName($this->name);
        $this->country->setLocationsName(self::LOCATIONS_NAME);
        $this->country->validate();

        $this->location = new Location();
        $this->location->setName(self::LOCATION_NAME);
        $this->location->setLabel(self::LOCATION_LABEL);
        $this->location->setSublocationsName(self::LOCATION_SUBLOCATIONS_NAME);
        $this->location->setParentCountry($this->country);
        $this->location->validate();

        $this->sublocation = new Location();
        $this->sublocation->setName(self::SUBLOCATION_NAME);
        $this->sublocation->setLabel(self::SUBLOCATION_LABEL);
        $this->sublocation->setParentLocation($this->location);
        $this->sublocation->validate();

        self::$dm->persist($this->country);
        self::$dm->persist($this->location);
        self::$dm->persist($this->sublocation);
        self::$dm->flush();

        self::$dm->clear();
    }

    public function testOK() {
        $country = ObjectDocumentMapper::getByCode(self::$dm, 
            'Sociable\Model\Country', self::COUNTRY_CODE);
        $this->assertNotNull($country);
        $this->assertInstanceOf('Sociable\Model\Country', $country);
        $country->validate();
        
        $locations = $country->getLocations();
        $this->assertNotNull($locations);
        $this->assertEquals(1, $locations->count());
	$location = $locations[0];
        $this->assertInstanceOf('Sociable\Model\Location', $location);
        $location->validate();
        $this->assertEquals($this->location->getName(), $location->getName());
        $this->assertEquals($this->location->getLabel(), $location->getLabel());
        $this->assertEquals($this->location->getParentType(), $location->getParentType());
        $this->assertEquals($country, $location->getParentCountry());

        $sublocations = $location->getSublocations();
        $this->assertNotNull($sublocations);
        $this->assertEquals(1, $sublocations->count());
        $sublocation = $sublocations[0];
        $this->assertInstanceOf('Sociable\Model\Location', $sublocation);
        $sublocation->validate();
        $this->assertEquals($this->sublocation->getName(), $sublocation->getName());
        $this->assertEquals($this->sublocation->getLabel(), $sublocation->getLabel());
        $this->assertEquals($this->sublocation->getParentType(), $sublocation->getParentType());
        $this->assertEquals($location, $sublocation->getParentLocation());
    }
    
    public function testDuplicateLabel() {
        $country = ObjectDocumentMapper::getByCode(self::$dm, 
            'Sociable\Model\Country', self::COUNTRY_CODE);
        $this->assertNotNull($country);
        $this->assertInstanceOf('Sociable\Model\Country', $country);
        $country->validate();
        
        $this->location = new Location();
        $this->location->setName(self::LOCATION_NAME);
        $this->location->setLabel(self::LOCATION_LABEL);
        $this->location->setParentCountry($country);
        $this->location->validate();

        self::$dm->persist($this->location);
        
        $this->setExpectedException('MongoCursorException');
        self::$dm->flush();
    }
    
    public function testGetFamilyName() {
        $location = ObjectDocumentMapper::getByLabel(self::$dm, 
            'Sociable\Model\Location', self::LOCATION_LABEL);
        $this->assertNotNull($location);
        $this->assertEquals(self::LOCATIONS_NAME, $location->getFamilyName());
        $sublocation = ObjectDocumentMapper::getByLabel(self::$dm, 
            'Sociable\Model\Location', self::SUBLOCATION_LABEL);
        $this->assertNotNull($sublocation);
        $this->assertEquals(self::LOCATION_SUBLOCATIONS_NAME, $sublocation->getFamilyName());
    }

    public function tearDown() {
        self::cleanUp();
    }
    
    public static function tearDownAfterClass() {
        self::cleanUp();
    }

    public static function cleanUp() {
        self::$dm->clear();
        $country = ObjectDocumentMapper::getByCode(self::$dm, 
            'Sociable\Model\Country', self::COUNTRY_CODE);
        if(!is_null($country)) {
            self::$dm->remove($country);
        }

        $location = ObjectDocumentMapper::getByLabel(self::$dm,
            'Sociable\Model\Location', self::LOCATION_LABEL);
        if(!is_null($location)) {
            self::$dm->remove($location);
        }
        
        $sublocation = ObjectDocumentMapper::getByLabel(self::$dm,
            'Sociable\Model\Location', self::SUBLOCATION_LABEL);
        if(!is_null($sublocation)) {
            self::$dm->remove($sublocation);
        }
        
        self::$dm->flush();
        self::$dm->clear();
    }
}


