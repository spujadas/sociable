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

use Sociable\Model\WebPresence,
    Sociable\Model\URL;

class WebPresenceTest extends \PHPUnit_Framework_TestCase {

    protected $webPresence;
    protected $url;
    
    const TYPE = WebPresence::TYPE_FACEBOOK;

    const URL_FACEBOOK = 'https://www.facebook.com/foo';
    const URL_TWITTER = 'twitter.com/foo';
    const URL_WEBSITE = 'foo.com';
    
    public function setUp() {
        $this->url = new URL(self::URL_FACEBOOK);
        $this->webPresence = new WebPresence();
    }

    public function test__construct() {
        $this->assertInstanceOf('Sociable\Model\WebPresence', $this->webPresence);
    }
    
    public function testSetGetURL() {
        $this->assertEquals($this->url, $this->webPresence->setUrl($this->url));
        $this->assertEquals($this->url, $this->webPresence->getUrl());
    }
    
    public function testSetTypeInvalidType() {
        $this->setExpectedException('Sociable\Model\WebPresenceException', 
            WebPresence::EXCEPTION_INVALID_TYPE);
        $this->webPresence->setType(null);
    }
    
    public function testGetTypeInvalidType() {
        try {
            $this->webPresence->setType(null);
        }
        catch (\Exception $e) {}
        $this->assertNull($this->webPresence->getType());
    }
    
    public function testSetGetType() {
        $this->assertEquals(self::TYPE, $this->webPresence->setType(self::TYPE));
        $this->assertEquals(self::TYPE, $this->webPresence->getType());
    }

    public function testSetUrlInferType() {
        $this->webPresence->setUrlInferType(new URL(self::URL_FACEBOOK));
        $this->assertEquals(WebPresence::TYPE_FACEBOOK, $this->webPresence->getType());
        $this->webPresence->setUrlInferType(new URL(self::URL_TWITTER));
        $this->assertEquals(WebPresence::TYPE_TWITTER, $this->webPresence->getType());
        $this->webPresence->setUrlInferType(new URL(self::URL_WEBSITE));
        $this->assertEquals(WebPresence::TYPE_WEBSITE, $this->webPresence->getType());
    }
        
    public function testValidateUninitialised() {
        $this->setExpectedException('Sociable\Model\WebPresenceException', 
            WebPresence::EXCEPTION_INVALID_TYPE);
        $this->webPresence->validate();
    }

    public function testValidateMissingUrl() {
        $this->webPresence->setType(self::TYPE);
        $this->setExpectedException('Sociable\Model\WebPresenceException', 
            WebPresence::EXCEPTION_MISSING_URL);
        $this->webPresence->validate();
    }

    public function testValidate() {
        $this->webPresence->setType(self::TYPE);
        $this->webPresence->setUrl($this->url);
        $this->webPresence->validate();
    }

    public function tearDown() {
        unset($this->webPresence);
    }

}


