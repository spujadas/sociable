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

use Sociable\Model\LabelledImage,
    Sociable\Model\MultiLanguageString,
    Sociable\ODM\ObjectDocumentMapper;

class LabelledImageODMTest extends \PHPUnit_Framework_TestCase {
    protected static $dm ;
    
    protected $labelledImage;

    protected static $id;
    const IMAGE_FILE_NAME = 'image.png';
    const MIME = LabelledImage::MIME_PNG;
    
    protected $description;
    
    public static function setUpBeforeClass() {
        include SOCIABLE_ROOT . '/config-test.inc.php'; // initialises $config
        self::$dm = $config->getDocumentManager();
    }
    
    public function setUp() {
        self::cleanUp();
        $this->labelledImage = new LabelledImage;
        $this->labelledImage->setImageFile(__DIR__ . '/' . self::IMAGE_FILE_NAME);
        $this->labelledImage->setMime(self::MIME);

        $this->description = new MultiLanguageString('ZZZZZ', 'fr');

        $this->labelledImage->setDescription($this->description);
        $this->labelledImage->validate();

        self::$dm->persist($this->labelledImage);
        self::$dm->flush();
        
        self::$id = $this->labelledImage->getId();
        self::$dm->clear();
    }

    public function testFound() {
        $this->labelledImage = ObjectDocumentMapper::getById(self::$dm, 
            'Sociable\Model\LabelledImage', self::$id);
        $this->assertNotNull($this->labelledImage);
    }
    
    public function testIsValid() {
        $this->labelledImage = ObjectDocumentMapper::getById(self::$dm, 
            'Sociable\Model\LabelledImage', self::$id);
        $this->labelledImage->validate();
    }
   
    public function testIsEqual() {
        $labelledImage = ObjectDocumentMapper::getById(self::$dm, 
            'Sociable\Model\LabelledImage', self::$id);
        $this->assertEquals(sha1_file(__DIR__ . '/' . self::IMAGE_FILE_NAME), 
            sha1($labelledImage->getImageFile()->getBytes()));
        $this->assertEquals($this->labelledImage->getMime(), $labelledImage->getMime());
        $this->assertEquals($this->labelledImage->getDescription(), $labelledImage->getDescription());
    }
   
    public function testRemove() {
        $this->labelledImage = ObjectDocumentMapper::getById(self::$dm, 
            'Sociable\Model\LabelledImage', self::$id);
        self::$dm->remove($this->labelledImage);
        self::$dm->flush();

        $this->assertNull(ObjectDocumentMapper::getById(self::$dm, 
            'Sociable\Model\LabelledImage', self::$id));
    }
    
    public function tearDown() {
        self::cleanUp();
    }
    
    public static function tearDownAfterClass() {
        self::cleanUp();
    }
    
    public static function cleanUp() {
        self::$dm->clear();
        if (!is_null(self::$id)) {
            $labelledImage = ObjectDocumentMapper::getById(self::$dm, 
            'Sociable\Model\LabelledImage', self::$id);
            if(!is_null($labelledImage)) {
                self::$dm->remove($labelledImage);
                self::$dm->flush();
            }
        }
    }

}


