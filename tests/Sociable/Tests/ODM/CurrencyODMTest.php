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

use Sociable\Model\Currency,
    Sociable\ODM\ObjectDocumentMapper;

class CurrencyODMTest extends \PHPUnit_Framework_TestCase {
    protected static $dm ;
    
    protected $currency;

    const CODE = 'ZZZ';

    public static function setUpBeforeClass() {
        include SOCIABLE_ROOT . '/config-test.inc.php'; // initialises $config
        self::$dm = $config->getDocumentManager();
    }
    
    public function setUp() {
        self::cleanUp();

        $this->currency = new Currency();
        $this->currency->setCode(self::CODE);
        $this->currency->validate();

        self::$dm->persist($this->currency);
        self::$dm->flush();

        self::$dm->clear();
    }

    public function testFound() {
        $this->currency = ObjectDocumentMapper::getByCode(self::$dm, 
            'Sociable\Model\Currency', self::CODE);
        $this->assertNotNull($this->currency);
    }
    
    public function testIsValid() {
        $this->currency = ObjectDocumentMapper::getByCode(self::$dm, 
            'Sociable\Model\Currency', self::CODE);
        $this->currency->validate();
    }
   
    public function testIsEqual() {
        $currency = ObjectDocumentMapper::getByCode(self::$dm, 
            'Sociable\Model\Currency', self::CODE);
        $this->assertEquals($this->currency, $currency);
    }
   
    public function testRemove() {
        $this->currency = ObjectDocumentMapper::getByCode(self::$dm, 
            'Sociable\Model\Currency', self::CODE);
        self::$dm->remove($this->currency);
        self::$dm->flush();

        $this->assertNull(ObjectDocumentMapper::getByCode(self::$dm, 
            'Sociable\Model\Currency', self::CODE));
    }
    
    public function testDuplicate() {
        $this->currency = new Currency();
        $this->currency->setCode(self::CODE);
        $this->currency->validate();

        self::$dm->persist($this->currency);
        
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
        $currency = ObjectDocumentMapper::getByCode(self::$dm, 
            'Sociable\Model\Currency', self::CODE);
        if(!is_null($currency)) {
            self::$dm->remove($currency);
            self::$dm->flush();
        }
    }
}


