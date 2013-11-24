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

// background: http://www.langtag.net/registries.html
// more: http://www.w3.org/International/articles/language-tags/Overview.en.php

class Language {
    protected $id = null;
    
    protected $code = null;
    const CODE_MAX_LENGTH = 32;
    
    protected $displayName = null;
    const DISPLAY_NAME_MAX_LENGTH = 128;
    
    const EXCEPTION_NOT_IN_CATALOGUE = 'not in catalogue';

    public function getId() {
        return $this->id;
    }

    public function setCode($code) {
        try {
            self::validateCode($code);
        }
        catch (Exception $e) {
            $this->code = null;
            throw $e;
        }
        $this->code = $code;
        return $this->code;
    }
    
    public static function validateCode($code) {
        StringValidator::validate($code, 
            array(
                'not_empty' => true,
                'max_length' => self::CODE_MAX_LENGTH)
            );
    }

    public function getCode() {
        return $this->code;
    }

    public function setDisplayName($displayName) {
        try {
            $this->validateDisplayName($displayName);
        }
        catch (Exception $e) {
            $this->displayName = null;
            throw $e;
        }
        $this->displayName = $displayName;
        return $this->displayName;
    }
    
    public function validateDisplayName($displayName) {
        StringValidator::validate($displayName,
                array('max_length' => self::DISPLAY_NAME_MAX_LENGTH)
                );
    }
    
    public function getDisplayName() {
        return $this->displayName;
    }
    
    public function validate() {
        self::validateCode($this->code);
        $this->validateDisplayName($this->displayName);
    }
}


