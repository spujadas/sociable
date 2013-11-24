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

use Sociable\Model\Organisation,
    Sociable\Model\BusinessSector,
    Sociable\ODM\ObjectDocumentMapper;

class OrganisationODMTest extends \PHPUnit_Framework_TestCase {
    protected static $dm ;
    
    protected $organisation;

    const NAME = 'Foo Bar Ltd';
    const TYPE = Organisation::BUSINESS_ORGANISATION;
    protected $businessSector;
    const BUSINESS_SECTOR_CODE = 'code';
    
    public static function setUpBeforeClass() {
        include SOCIABLE_ROOT . '/config-test.inc.php'; // initialises $config
        self::$dm = $config->getDocumentManager();
    }
    
    public function setUp() {
        self::cleanUp();
        
        $this->businessSector = new BusinessSector;
        $this->businessSector->setCode(self::BUSINESS_SECTOR_CODE);
        self::$dm->persist($this->businessSector);

        $this->organisation = new Organisation;
        $this->organisation->setName(self::NAME);
        $this->organisation->setType(self::TYPE);
        $this->organisation->setBusinessSector($this->businessSector);
        $this->organisation->validate();

        self::$dm->persist($this->organisation);
        self::$dm->flush();
        
        self::$dm->clear();
    }

    public function testFound() {
        $this->organisation = ObjectDocumentMapper::getByName(self::$dm, 
            'Sociable\Model\Organisation', self::NAME);
        $this->assertNotNull($this->organisation);
    }
    
    public function testIsValid() {
        $this->organisation = ObjectDocumentMapper::getByName(self::$dm, 
            'Sociable\Model\Organisation', self::NAME);
        $this->organisation->validate();
    }
   
    public function testIsEqual() {
        $organisation = ObjectDocumentMapper::getByName(self::$dm, 
            'Sociable\Model\Organisation', self::NAME);
        $this->assertEquals($this->organisation->getName(), $organisation->getName());
        $this->assertEquals($this->organisation->getType(), $organisation->getType());
        $this->assertEquals($this->organisation->getBusinessSector()->getCode(), 
            $organisation->getBusinessSector()->getCode());
    }
   
    public function testRemove() {
        $this->organisation = ObjectDocumentMapper::getByName(self::$dm, 
            'Sociable\Model\Organisation', self::NAME);
        self::$dm->remove($this->organisation);
        self::$dm->flush();

        $this->assertNull(ObjectDocumentMapper::getByName(self::$dm, 
            'Sociable\Model\Organisation', self::NAME));
    }
    
    public function testDuplicate() {
        $this->organisation = new Organisation();
        $this->organisation->setName(self::NAME);
        $this->organisation->setType(self::TYPE);
        $this->organisation->setBusinessSector($this->businessSector);
        $this->organisation->validate();

        self::$dm->persist($this->organisation);
        $this->setExpectedException('MongoCursorException');
        self::$dm->flush();
    }

    public function tearDown() {
        self::cleanUp();
    }
    
    public static function tearDownAfterClass() {
        self::cleanUp();
    }
    
    public static function cleanUp() {
        self::$dm->clear();
        $organisation = ObjectDocumentMapper::getByName(self::$dm, 
            'Sociable\Model\Organisation', self::NAME);
        if(!is_null($organisation)) {
            self::$dm->remove($organisation);
            self::$dm->flush();
        }
        $businessSector = ObjectDocumentMapper::getByCode(self::$dm, 
            'Sociable\Model\BusinessSector', self::BUSINESS_SECTOR_CODE);
        if(!is_null($businessSector)) {
            self::$dm->remove($businessSector);
            self::$dm->flush();
        }
    }

}


