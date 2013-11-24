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

use Sociable\Model\Language,
    Sociable\ODM\ObjectDocumentMapper;

class LanguageODMTest extends \PHPUnit_Framework_TestCase {
    protected static $dm ;
    
    protected $language;

    const CODE = 'ZZZZZ';
    const DISPLAY_NAME = 'display name';
    
    public static function setUpBeforeClass() {
        include SOCIABLE_ROOT . '/config-test.inc.php'; // initialises $config
        self::$dm = $config->getDocumentManager();
    }
    
    public function setUp() {
        self::cleanUp();
        
        $this->language = new Language();
        $this->language->setCode(self::CODE);
        $this->language->setDisplayName(self::DISPLAY_NAME);
        $this->language->validate();

        self::$dm->persist($this->language);
        self::$dm->flush();
        self::$dm->clear();
    }

    public function testFound() {
        $this->language = ObjectDocumentMapper::getByCode(self::$dm, 
            'Sociable\Model\Language', self::CODE);
        $this->assertNotNull($this->language);
    }
    
    public function testIsValid() {
        $this->language = ObjectDocumentMapper::getByCode(self::$dm, 
            'Sociable\Model\Language', self::CODE);
        $this->language->validate();
    }
   
    public function testIsEqual() {
        $language = ObjectDocumentMapper::getByCode(self::$dm, 
            'Sociable\Model\Language', self::CODE);
        $this->assertEquals($this->language, $language);
    }
   
    public function testRemove() {
        $this->language = ObjectDocumentMapper::getByCode(self::$dm, 
            'Sociable\Model\Language', self::CODE);
        self::$dm->remove($this->language);
        self::$dm->flush();

        $this->assertNull(ObjectDocumentMapper::getByCode(self::$dm, 
            'Sociable\Model\Language', self::CODE));
    }
    
    public function testDuplicate() {
        $this->language = new Language();
        $this->language->setCode(self::CODE);
        $this->language->setDisplayName(self::DISPLAY_NAME);
        $this->language->validate();

        self::$dm->persist($this->language);
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
        $language = ObjectDocumentMapper::getByCode(self::$dm, 
            'Sociable\Model\Language', self::CODE);
        if(!is_null($language)) {
            self::$dm->remove($language);
            self::$dm->flush();
        }
    }

}


