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

use Sociable\Model\MultiLanguageString,
    Sociable\Model\Language,
    Sociable\Utility\StringValidator;

class MultiLanguageStringTest extends \PHPUnit_Framework_TestCase {

    protected $multiLanguageString;

    const DEFAULT_LANGUAGE_CODE = 'fr';
    const LANGUAGE_STRING1_STRING = 'chaîne de caractères';
    const LANGUAGE_STRING1_LANGUAGE_CODE = 'fr';
    const LANGUAGE_STRING2_STRING = 'string';
    const LANGUAGE_STRING2_LANGUAGE_CODE = 'en';
    
    const DEFAULT_LANGUAGE_CODE_MISMATCH = 'es';
    const LENGTH_CONSTRAINT = 2;
    
    public function setUp() {
        $this->multiLanguageString = new MultiLanguageString();
    }

    public function test__construct() {
        $this->assertInstanceOf('Sociable\Model\MultiLanguageString', $this->multiLanguageString);
    
        $this->multiLanguageString = 
                new MultiLanguageString(self::LANGUAGE_STRING1_STRING, 
                        self::LANGUAGE_STRING1_LANGUAGE_CODE);
        $this->assertEquals(self::LANGUAGE_STRING1_LANGUAGE_CODE, 
                $this->multiLanguageString->getDefaultLanguageCode());
        $this->assertEquals(self::LANGUAGE_STRING1_STRING, 
                $this->multiLanguageString->getStringByLanguageCode(self::LANGUAGE_STRING1_LANGUAGE_CODE));
    }

    public function testSetDefaultLanguageCodeNotAString() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_NOT_A_STRING);
        $this->multiLanguageString->setDefaultLanguageCode(null);
    }
    
    public function testGetDefaultLanguageCodeNotAString() {
        try {
            $this->multiLanguageString->setDefaultLanguageCode(null);
        }
        catch (\Exception $e) {}
        $this->assertNull($this->multiLanguageString->getDefaultLanguageCode());
    }
    
    public function testSetDefaultLanguageCodeTooLong() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_TOO_LONG);
        $this->multiLanguageString->setDefaultLanguageCode(str_repeat('a', Language::CODE_MAX_LENGTH + 1));
    }
    
    public function testGetDefaultLanguageCodeTooLong() {
        try {
            $this->multiLanguageString->setDefaultLanguageCode(str_repeat('a', Language::CODE_MAX_LENGTH + 1));
        }
        catch (\Exception $e) {}
        $this->assertNull($this->multiLanguageString->getDefaultLanguageCode());
    }

    public function testSetGetDefaultLanguageCode() {
        $this->assertEquals(self::DEFAULT_LANGUAGE_CODE, 
                $this->multiLanguageString->setDefaultLanguageCode(self::DEFAULT_LANGUAGE_CODE));
        $this->assertEquals(self::DEFAULT_LANGUAGE_CODE, 
                $this->multiLanguageString->getDefaultLanguageCode());
    }
    
    public function testAddStringByLanguageCodeConstraints() {
        $constraints = array('length' => self::LENGTH_CONSTRAINT);
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_INCORRECT_LENGTH);
        $this->multiLanguageString->addStringByLanguageCode(
                str_repeat('a', self::LENGTH_CONSTRAINT + 1), 
                self::LANGUAGE_STRING1_LANGUAGE_CODE,
                false, $constraints);
    }
    
    public function testAddStringByLanguageCodeDuplicateLanguage() {
        $this->multiLanguageString->setDefaultLanguageCode(self::DEFAULT_LANGUAGE_CODE);
        $this->multiLanguageString->addStringByLanguageCode(
                self::LANGUAGE_STRING1_STRING, 
                self::LANGUAGE_STRING1_LANGUAGE_CODE);
        $this->setExpectedException('Sociable\Model\MultiLanguageStringException', 
            MultiLanguageString::EXCEPTION_DUPLICATE_LANGUAGE);
        $this->multiLanguageString->addStringByLanguageCode(
                self::LANGUAGE_STRING2_STRING, 
                self::LANGUAGE_STRING1_LANGUAGE_CODE);
    }

    public function testGetLanguageStringNonExistingLanguageCode() {
        $this->multiLanguageString->addStringByLanguageCode(
                self::LANGUAGE_STRING1_STRING, 
                self::LANGUAGE_STRING1_LANGUAGE_CODE);
        $this->assertNull($this->multiLanguageString
                ->getStringByLanguageCode(self::LANGUAGE_STRING2_LANGUAGE_CODE));
    }
    
    public function testAddStringByLanguageCode() {
        $this->multiLanguageString->setDefaultLanguageCode(self::DEFAULT_LANGUAGE_CODE);
        $this->multiLanguageString->addStringByLanguageCode(
                self::LANGUAGE_STRING1_STRING, 
                self::LANGUAGE_STRING1_LANGUAGE_CODE);
        $this->multiLanguageString->addStringByLanguageCode(
                self::LANGUAGE_STRING2_STRING, 
                self::LANGUAGE_STRING2_LANGUAGE_CODE);
        $this->assertEquals(self::LANGUAGE_STRING1_STRING, 
                $this->multiLanguageString->getStringByLanguageCode(
                        self::LANGUAGE_STRING1_LANGUAGE_CODE));
        $this->assertEquals(self::LANGUAGE_STRING1_STRING, 
                $this->multiLanguageString->getStringByLanguageCode(
                        self::LANGUAGE_STRING1_LANGUAGE_CODE));
        $this->assertEquals(self::LANGUAGE_STRING2_STRING, 
                $this->multiLanguageString->getStringByLanguageCode(
                        self::LANGUAGE_STRING2_LANGUAGE_CODE));
        $this->assertEquals(self::LANGUAGE_STRING2_STRING, 
                $this->multiLanguageString->getStringByLanguageCode(
                        self::LANGUAGE_STRING2_LANGUAGE_CODE));
        $this->multiLanguageString->addStringByLanguageCode(
                self::LANGUAGE_STRING2_STRING, 
                self::LANGUAGE_STRING1_LANGUAGE_CODE, true);
        $this->assertEquals(self::LANGUAGE_STRING2_STRING, 
                $this->multiLanguageString->getStringByLanguageCode(
                        self::LANGUAGE_STRING1_LANGUAGE_CODE));
        $this->assertNull($this->multiLanguageString->getStringByLanguageCode(
                        self::DEFAULT_LANGUAGE_CODE_MISMATCH, false));
    }
    
    public function testGetDefaultString() {
        $this->multiLanguageString->setDefaultLanguageCode(self::DEFAULT_LANGUAGE_CODE);
        $this->multiLanguageString->addStringByLanguageCode(
                self::LANGUAGE_STRING1_STRING, 
                self::LANGUAGE_STRING1_LANGUAGE_CODE);
        $this->multiLanguageString->addStringByLanguageCode(
                self::LANGUAGE_STRING2_STRING, 
                self::LANGUAGE_STRING2_LANGUAGE_CODE);
        $this->assertEquals(self::LANGUAGE_STRING1_STRING, 
                $this->multiLanguageString->getDefaultString());
    }
    
    public function testRemoveLanguageStringByLanguageCode() {
        $this->multiLanguageString->setDefaultLanguageCode(self::DEFAULT_LANGUAGE_CODE);
        $this->multiLanguageString->addStringByLanguageCode(
                self::LANGUAGE_STRING1_STRING, 
                self::LANGUAGE_STRING1_LANGUAGE_CODE);
        $this->multiLanguageString->addStringByLanguageCode(
                self::LANGUAGE_STRING2_STRING, 
                self::LANGUAGE_STRING2_LANGUAGE_CODE);
        $this->multiLanguageString->validate();
        $this->assertEquals(2, count($this->multiLanguageString->getLanguageStrings()));
        $this->assertEquals(self::LANGUAGE_STRING1_STRING, 
                $this->multiLanguageString->getStringByLanguageCode(
                        self::LANGUAGE_STRING1_LANGUAGE_CODE));
        $this->assertFalse($this->multiLanguageString->removeStringByLanguageCode(
                self::LANGUAGE_STRING1_LANGUAGE_CODE));
        $this->assertTrue($this->multiLanguageString->removeStringByLanguageCode(
                self::LANGUAGE_STRING2_LANGUAGE_CODE));
        $this->assertFalse($this->multiLanguageString->removeStringByLanguageCode(
                self::LANGUAGE_STRING2_LANGUAGE_CODE));
        $this->assertFalse($this->multiLanguageString->removeStringByLanguageCode(
                self::DEFAULT_LANGUAGE_CODE_MISMATCH));
        $this->assertEquals(1, count($this->multiLanguageString->getLanguageStrings()));
        $this->assertNull($this->multiLanguageString->getStringByLanguageCode(
                        self::LANGUAGE_STRING2_LANGUAGE_CODE, false));
    }
    

    
    public function testValidateUninitialised() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_NOT_A_STRING);
        $this->multiLanguageString->validate();
    }

    public function testValidateUnmatchedDefault() {
        $this->multiLanguageString->setDefaultLanguageCode(self::DEFAULT_LANGUAGE_CODE_MISMATCH);
        $this->multiLanguageString->addStringByLanguageCode(
                self::LANGUAGE_STRING1_STRING, 
                self::LANGUAGE_STRING1_LANGUAGE_CODE);
        $this->setExpectedException('Sociable\Model\MultiLanguageStringException', 
            MultiLanguageString::EXCEPTION_UNMATCHED_DEFAULT);
        $this->multiLanguageString->validate();
    }

    public function testValidate() {
        $this->multiLanguageString->setDefaultLanguageCode(self::DEFAULT_LANGUAGE_CODE);
        $this->multiLanguageString->addStringByLanguageCode(
                self::LANGUAGE_STRING1_STRING, 
                self::LANGUAGE_STRING1_LANGUAGE_CODE);
        $this->multiLanguageString->addStringByLanguageCode(
                self::LANGUAGE_STRING2_STRING, 
                self::LANGUAGE_STRING2_LANGUAGE_CODE);
        $this->multiLanguageString->validate();
        $this->multiLanguageString = 
                new MultiLanguageString(self::LANGUAGE_STRING1_STRING, 
                        self::LANGUAGE_STRING1_LANGUAGE_CODE);
        $this->multiLanguageString->validate();
    }

    public function tearDown() {
        unset($this->multiLanguageString);
    }

}


