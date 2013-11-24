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

use Sociable\Model\URL,
    Sociable\Model\URLException,
    Sociable\Model\MultiLanguageString,
    Sociable\Utility\StringValidator;

class URLTest extends \PHPUnit_Framework_TestCase {

    protected $url;
    protected $description;
    protected $descriptionTooLong;
    
    const URL = 'https://www.facebook.com/Sociable';
    const URL_NOSCHEME = 'www.facebook.com/Sociable';
    const URL_NOHOSTNAME = 'facebook';
    
    public function setUp() {
        $this->url = new URL();
        $this->description = new MultiLanguageString('chaîne de caractères', 'fr');
        $this->descriptionTooLong = new MultiLanguageString(
                str_repeat('a', URL::DESCRIPTION_MAX_LENGTH + 1), 'fr');
    }

    public function test__construct() {
        $this->assertInstanceOf('Sociable\Model\URL', $this->url);
        $this->url = new URL(self::URL);
        $this->assertEquals(self::URL, $this->url->getUrl());
    }

    public function testSetUrlNotAString() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_NOT_A_STRING);
        $this->url->setUrl(null);
    }
    
    public function testGetUrlNotAString() {
        try {
            $this->url->setUrl(null);
        }
        catch (\Exception $e) {}
        $this->assertNull($this->url->getUrl());
    }
    
    public function testSetUrlTooLong() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_TOO_LONG);
        $this->url->setUrl(self::URL . str_repeat('a', URL::URL_MAX_LENGTH - strlen(self::URL) + 1));
    }
    
    public function testGetUrlTooLong() {
        try {
            $this->url->setUrl(self::URL . str_repeat('a', URL::URL_MAX_LENGTH - strlen(self::URL) + 1));
        }
        catch (\Exception $e) {}
        $this->assertNull($this->url->getUrl());
    }
    
    public function testSetUrlNoHostname() {
        $this->setExpectedException('Sociable\Model\URLException', 
                URL::EXCEPTION_INVALID_URL);
        $this->url->setUrl(self::URL_NOHOSTNAME);
    }
    
    public function testGetUrlNoHostname() {
        try {
            $this->url->setUrl(self::URL_NOHOSTNAME);
        }
        catch (\Exception $e) {}
        $this->assertNull($this->url->getUrl());
    }
    
    public function testSetGetUrl() {
        $this->assertEquals(self::URL, 
                $this->url->setUrl(self::URL));
        $this->assertEquals(self::URL, $this->url->getUrl());
        $this->assertEquals(self::URL_NOSCHEME, 
                $this->url->setUrl(self::URL_NOSCHEME));
        $this->assertEquals(self::URL_NOSCHEME, $this->url->getUrl());
    }

    public function testSetDescriptionTooLong() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_TOO_LONG);
        $this->url->setDescription($this->descriptionTooLong);
    }
    
    public function testGetDescriptionTooLong() {
        try {
            $this->url->setDescription($this->descriptionTooLong);
        }
        catch (\Exception $e) {}
        $this->assertNull($this->url->getDescription());
    }
    
    public function testSetGetDescription() {
        $this->assertEquals($this->description, 
                $this->url->setDescription($this->description));
        $this->assertEquals($this->description, $this->url->getDescription());
    }
    
    public function testValidateUninitialised() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_NOT_A_STRING);        
        $this->url->validate();
    }
    
    public function testValidate() {
        $this->url->setUrl(self::URL);
        $this->url->validate();
    }

    public function tearDown() {
        unset($this->url);
    }

}


