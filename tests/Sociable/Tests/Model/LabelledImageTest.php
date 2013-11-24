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

use Sociable\Model\LabelledImage,
    Sociable\Model\MultiLanguageString,
    Sociable\Utility\StringValidator;

class LabelledImageTest extends \PHPUnit_Framework_TestCase {

    protected $labelledImage;
    protected $description;
    protected $descriptionTooLong;
    protected $descriptionEmpty;
    
    const IMAGE_FILE = 'file.png';
    const MIME = LabelledImage::MIME_PNG;
    
    public function setUp() {
        $this->labelledImage = new LabelledImage();
        $this->description = new MultiLanguageString('foo', 'fr');
        $this->descriptionTooLong = new MultiLanguageString(
                str_repeat('a', LabelledImage::DESCRIPTION_MAX_LENGTH + 1),
                'fr');
        $this->descriptionEmpty = new MultiLanguageString('', 'fr');
    }

    public function test__construct() {
        $this->assertInstanceOf('Sociable\Model\LabelledImage', $this->labelledImage);
    }

    public function testSetImageFileNotAString() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_NOT_A_STRING);
        $this->labelledImage->setImageFile(null);
    }
    
    public function testGetImageFileNotAString() {
        try {
            $this->labelledImage->setImageFile(null);
        }
        catch (\Exception $e) {}
        $this->assertNull($this->labelledImage->getImageFile());
    }
    
    public function testSetImageFileTooLong() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_TOO_LONG);
        $this->labelledImage->setImageFile(str_repeat('a', LabelledImage::IMAGE_FILE_MAX_LENGTH + 1));
    }
    
    public function testGetImageFileTooLong() {
        try {
            $this->labelledImage->setImageFile(null);
        }
        catch (\Exception $e) {}
        $this->assertNull($this->labelledImage->getImageFile());
    }
    
    public function testSetGetImageFile() {
        $this->assertEquals(self::IMAGE_FILE, 
                $this->labelledImage->setImageFile(self::IMAGE_FILE));
        $this->assertEquals(self::IMAGE_FILE, $this->labelledImage->getImageFile());
    }
    
    public function testSetMimeInvalidMime() {
        $this->setExpectedException('Sociable\Model\LabelledImageException', 
            LabelledImage::EXCEPTION_INVALID_MIME_TYPE);
        $this->labelledImage->setMime(null);
    }
    
    public function testGetMimeInvalidMime() {
        try {
            $this->labelledImage->setMime(null);
        }
        catch (\Exception $e) {}
        $this->assertNull($this->labelledImage->getMime());
    }
    
    public function testSetGetMime() {
        $this->assertEquals(self::MIME, $this->labelledImage->setMime(self::MIME));
        $this->assertEquals(self::MIME, $this->labelledImage->getMime());
    }

    public function testSetDescriptionTooLong() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_TOO_LONG);
        $this->labelledImage->setDescription($this->descriptionTooLong);
    }
    
    public function testGetDescriptionTooLong() {
        try {
            $this->labelledImage->setDescription($this->descriptionTooLong);
        }
        catch (\Exception $e) {}
        $this->assertNull($this->labelledImage->getDescription());
    }
    
    public function testSetGetDescription() {
        $this->assertEquals($this->description, 
                $this->labelledImage->setDescription($this->description));
        $this->assertEquals($this->description, $this->labelledImage->getDescription());
        $this->assertEquals($this->descriptionEmpty, 
                $this->labelledImage->setDescription($this->descriptionEmpty));
        $this->assertEquals($this->descriptionEmpty, $this->labelledImage->getDescription());
    }
    
    public function testValidateUninitialised() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                        StringValidator::EXCEPTION_NOT_A_STRING);
        $this->labelledImage->validate();
    }

    public function testValidate() {
        $this->labelledImage->setImageFile(self::IMAGE_FILE);
        $this->labelledImage->setMime(self::MIME);
        $this->labelledImage->setDescription($this->description);
        $this->labelledImage->validate();
    }

    public function tearDown() {
        unset($this->labelledImage);
    }

}


