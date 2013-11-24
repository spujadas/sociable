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

use Sociable\Model\User,
    Sociable\Model\PasswordAuthenticator,
    Sociable\ODM\ObjectDocumentMapper;

class UserODMTest extends \PHPUnit_Framework_TestCase {
    protected static $dm ;
    
    protected $user;

    protected $authenticator;

    const NAME = 'Foo';
    const SURNAME = 'Bar';
    const EMAIL = 'zzzzzz@zzzzzz.com';
    
    public static function setUpBeforeClass() {
        include SOCIABLE_ROOT . '/config-test.inc.php'; // initialises $config
        self::$dm = $config->getDocumentManager();
    }
    
    public function setUp() {
        self::cleanUp();
        
        $this->authenticator = new PasswordAuthenticator();
        $this->authenticator->setParams(array('password' => '1234'));

        $this->user = new User();
        $this->user->setName(self::NAME);
        $this->user->setSurname(self::SURNAME);
        $this->user->setEmail(self::EMAIL);
        $this->user->setAuthenticator($this->authenticator);
        $this->user->validate();

        self::$dm->persist($this->user);
        self::$dm->flush();

        self::$dm->clear();
    }

    public function testFound() {
        $this->user = ObjectDocumentMapper::getByEmail(self::$dm, 
            'Sociable\Model\User', self::EMAIL);
        $this->assertNotNull(ObjectDocumentMapper::getByEmail(self::$dm, 
            'Sociable\Model\User', self::EMAIL));
    }
    
    public function testIsValid() {
        $this->user = ObjectDocumentMapper::getByEmail(self::$dm, 
            'Sociable\Model\User', self::EMAIL);
        $this->user->validate();
    }
   
    public function testIsEqual() {
        $user = ObjectDocumentMapper::getByEmail(self::$dm, 
            'Sociable\Model\User', self::EMAIL);
        $this->assertEquals($this->user, $user);
    }
   
    public function testRemove() {
        $this->user = ObjectDocumentMapper::getByEmail(self::$dm, 
            'Sociable\Model\User', self::EMAIL);
        self::$dm->remove($this->user);
        self::$dm->flush();

        $this->assertNull(ObjectDocumentMapper::getByEmail(self::$dm, 
            'Sociable\Model\User', self::EMAIL));
    }
    
    public function testDuplicate() {
        $this->authenticator = new PasswordAuthenticator();
        $this->authenticator->setParams(array('password' => '1234'));

        $this->user = new User();
        $this->user->setName(self::NAME);
        $this->user->setSurname(self::SURNAME);
        $this->user->setEmail(self::EMAIL);
        $this->user->setAuthenticator($this->authenticator);
        $this->user->validate();

        self::$dm->persist($this->user);

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
        $user = ObjectDocumentMapper::getByEmail(self::$dm, 
            'Sociable\Model\User', self::EMAIL);
        if(!is_null($user)) {
            self::$dm->remove($user);
            self::$dm->flush();
        }
    }
}


