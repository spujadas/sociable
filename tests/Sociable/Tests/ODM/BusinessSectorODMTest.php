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

use Sociable\Model\BusinessSector,
    Sociable\Model\MultiLanguageString,
    Sociable\ODM\ObjectDocumentMapper;

class BusinessSectorODMTest extends \PHPUnit_Framework_TestCase {
    protected static $dm ;
    
    protected $businessSector;

    const CODE = 'ZZZZZ';
    protected $name;

    public static function setUpBeforeClass() {
        include SOCIABLE_ROOT . '/config-test.inc.php'; // initialises $config
        self::$dm = $config->getDocumentManager();
    }
    
    public function setUp() {
        self::cleanUp();
        
        $this->name = new MultiLanguageString('ZZZZZ', 'fr');
        $this->businessSector = new BusinessSector();
        $this->businessSector->setCode(self::CODE);
        $this->businessSector->setName($this->name);
        $this->businessSector->validate();
        
        self::$dm->persist($this->businessSector);
        self::$dm->flush();

        self::$dm->clear();
    }

    public function testFound() {
        $this->businessSector = ObjectDocumentMapper::getByCode(self::$dm, 
            'Sociable\Model\BusinessSector', self::CODE);
        $this->assertNotNull($this->businessSector);
    }
    
    public function testIsValid() {
        $this->businessSector = ObjectDocumentMapper::getByCode(self::$dm, 
            'Sociable\Model\BusinessSector', self::CODE);
        $this->businessSector->validate();
    }
   
    public function testIsEqual() {
        $businessSector = ObjectDocumentMapper::getByCode(self::$dm, 
            'Sociable\Model\BusinessSector', self::CODE);
        $this->assertEquals($this->businessSector, $businessSector);
    }
   
    public function testRemove() {
        $this->businessSector = ObjectDocumentMapper::getByCode(self::$dm, 
            'Sociable\Model\BusinessSector', self::CODE);
        self::$dm->remove($this->businessSector);
        self::$dm->flush();

        $this->assertNull(ObjectDocumentMapper::getByCode(self::$dm, 
            'Sociable\Model\BusinessSector', self::CODE));
    }
    
    public function testDuplicate() {
        $this->name = new MultiLanguageString('ZZZZZ', 'fr');
        $this->businessSector = new BusinessSector();
        $this->businessSector->setCode(self::CODE);
        $this->businessSector->setName($this->name);
        $this->businessSector->validate();

        self::$dm->persist($this->businessSector);
        
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
        $businessSector = ObjectDocumentMapper::getByCode(self::$dm, 
            'Sociable\Model\BusinessSector', self::CODE);
        if(!is_null($businessSector)) {
            self::$dm->remove($businessSector);
            self::$dm->flush();
        }
    }

}


