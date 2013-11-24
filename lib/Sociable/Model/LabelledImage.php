<?php

/*
 * This file is part of the Sociable package.
 *
 * Copyright 2013 by Sébastien Pujadas
 *
 * For the full copyright and licence information, please view the LICENCE
 * file that was distributed with this source code.
 */

namespace Sociable\Model;

use Sociable\Utility\StringValidator;

class LabelledImage {
    protected $id;

    protected $imageFile = null;

    const IMAGE_FILE_MAX_LENGTH = 255;

    protected $mime = null;

    const MIME_JPEG = 'image/jpeg';
    const MIME_PNG = 'image/png';
    const MIME_GIF = 'image/gif';

    /** @var MultiLanguageString */
    protected $description = null;

    const DESCRIPTION_MAX_LENGTH = 140;
    
    const EXCEPTION_INVALID_MIME_TYPE = 'invalid MIME type';

    public function getId() { 
        return $this->id; 
    }

    public function setImageFile($imageFile) {
        try {
            $this->validateImageFile($imageFile);
        } catch (Exception $e) {
            $this->imageFile = null;
            throw $e;
        }
        $this->imageFile = $imageFile;
        return $this->imageFile;
    }

    protected function validateImageFile($imageFile) {
        if (!is_a($imageFile, 'Doctrine\MongoDB\GridFSFile')) {
            StringValidator::validate($imageFile, 
                array('max_length' => self::IMAGE_FILE_MAX_LENGTH)
                );
        }
    }
    
    public function getImageFile() {
        return $this->imageFile;
    }
    
    public function setMime($mime) {
        try {
            $this->validateMime($mime);
        } catch (Exception $e) {
            $this->mime = null;
            throw $e;
        }
        $this->mime = $mime;
        return $this->mime;
        
    }
    
    protected function validateMime($mime) {
        if (($mime!=self::MIME_GIF) && ($mime!=self::MIME_JPEG) 
                && ($mime!=self::MIME_PNG)){
            throw new LabelledImageException(self::EXCEPTION_INVALID_MIME_TYPE);
        }
    }
    
    public function getMime() {
        return $this->mime;
    }
    
    public function setDescription(MultiLanguageString $description) {
        try {
            $this->validateDescription($description);
        } catch (Exception $e) {
            $this->description = null;
            throw $e;
        }
        $this->description = $description;
        return $this->description;
        
    }
    
    protected function validateDescription(MultiLanguageString $description) {
        $description->validate(array(
            'max_length' => self::DESCRIPTION_MAX_LENGTH));
    }

    public function getDescription() {
        return $this->description;
    }
    
    public function validate() {
        $this->validateImageFile($this->imageFile);
        $this->validateDescription($this->description);
        $this->validateMime($this->mime);
    }
}


