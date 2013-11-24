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

use Sociable\Model\Country,
    Sociable\Model\MultiLanguageString,
    Sociable\ODM\ObjectDocumentMapper;

class CountryODMTest extends \PHPUnit_Framework_TestCase {
    protected static $dm ;
    
    protected $country;

    const CODE = 'ZZ';
    protected $name;
    const LOCATIONS_NAME = 'locations';
    protected $location;

    public static function setUpBeforeClass() {
        include SOCIABLE_ROOT . '/config-test.inc.php'; // initialises $config
        self::$dm = $config->getDocumentManager();
    }
    
    public function setUp() {
        self::cleanUp();
        
        $this->name = new MultiLanguageString('ZZZZZ', 'fr');
        
        $this->country = new Country;
        $this->country->setCode(self::CODE);
        $this->country->setName($this->name);
        $this->country->setLocationsName(self::LOCATIONS_NAME);
        $this->country->validate();

        self::$dm->persist($this->country);
        self::$dm->flush();

        self::$dm->clear();
    }

    public function testFound() {
        $this->country = ObjectDocumentMapper::getByCode(self::$dm, 
            'Sociable\Model\Country', self::CODE);
        $this->assertNotNull($this->country);
    }
    
    public function testIsValid() {
        $this->country = ObjectDocumentMapper::getByCode(self::$dm, 
            'Sociable\Model\Country', self::CODE);
        $this->country->validate();
    }
   
    public function testIsEqual() {
        $country = ObjectDocumentMapper::getByCode(self::$dm, 
            'Sociable\Model\Country', self::CODE);
        $this->assertEquals($this->country->getCode(), $country->getCode());
        $this->assertEquals($this->country->getName(), $country->getName());
        $this->assertEquals($this->country->getLocationsName(), $country->getLocationsName());
    }
   
    public function testDuplicate() {
        $this->country = new Country();
        $this->country->setCode(self::CODE);
        $this->country->setName($this->name);
        $this->country->validate();

        self::$dm->persist($this->country);
        
        $this->setExpectedException('MongoCursorException');
        self::$dm->flush();
    }
    
    public function testRemove() {
        $this->country = ObjectDocumentMapper::getByCode(self::$dm, 
            'Sociable\Model\Country', self::CODE);
        self::$dm->remove($this->country);
        self::$dm->flush();

        $this->assertNull(ObjectDocumentMapper::getByCode(self::$dm, 
            'Sociable\Model\Country', self::CODE));
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
            'Sociable\Model\Country', self::CODE);
        if(!is_null($country)) {
            self::$dm->remove($country);
            self::$dm->flush();
        }
        self::$dm->clear();
    }
}


