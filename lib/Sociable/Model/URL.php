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

class URL {
    protected $url = null;
    const URL_MAX_LENGTH = 2000; // http://stackoverflow.com/a/417184

    /** @var MultiLanguageString */
    protected $description = null;
    const DESCRIPTION_MAX_LENGTH = 140;
    
    const EXCEPTION_INVALID_URL = 'invalid URL';

    // https://github.com/symfony/Validator/blob/master/Constraints/UrlValidator.php
    const PATTERN = 
        '~^
            https?:// # protocol
            (
                ([\pL\pN\pS-]+\.)+[\pL]+ # a domain name
                   | # or
                \d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3} # a IP address
                    | # or
                \[
                    (?:(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){6})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:::(?:(?:(?:[0-9a-f]{1,4})):){5})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:[0-9a-f]{1,4})))?::(?:(?:(?:[0-9a-f]{1,4})):){4})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,1}(?:(?:[0-9a-f]{1,4})))?::(?:(?:(?:[0-9a-f]{1,4})):){3})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,2}(?:(?:[0-9a-f]{1,4})))?::(?:(?:(?:[0-9a-f]{1,4})):){2})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,3}(?:(?:[0-9a-f]{1,4})))?::(?:(?:[0-9a-f]{1,4})):)(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,4}(?:(?:[0-9a-f]{1,4})))?::)(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,5}(?:(?:[0-9a-f]{1,4})))?::)(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,6}(?:(?:[0-9a-f]{1,4})))?::))))
                \] # a IPv6 address
            )
            (:[0-9]+)? # a port (optional)
            (/?|/\S+) # a /, nothing or a / with something
        $~ixu';
    
    public function __construct($url = null) {
        if (!is_null($url)) {
            $this->setUrl($url);
        }
    }

    public function setUrl($url) {
        try {
            $this->validateUrl($url);
        }
        catch (Exception $e) {
            $this->url = null;
            throw $e;
        }
        $this->url = $url;
        return $this->url;
    }

    protected function validateUrl($url) {
        StringValidator::validate($url, array(
            'not_empty' => true,
            'max_length' => self::URL_MAX_LENGTH,
        ));
        
        // prepend http:// before checking if no scheme set
        // http://stackoverflow.com/a/9800493
        if ( $parts = parse_url($url) ) {
           if ( !isset($parts['scheme']) )
           {
               $url = "http://$url";
           }
        }
        else {
            throw new URLException(self::EXCEPTION_INVALID_URL);
        }

        // http://www.d-mueller.de/blog/why-url-validation-with-filter_var-might-not-be-a-good-idea/
        if (!preg_match(self::PATTERN, $url)) {
            throw new URLException(self::EXCEPTION_INVALID_URL);
        }
    }
    
    public function getUrl() {
        return $this->url;
    }
    
    public function setDescription(MultiLanguageString $description = null) {
        if (!is_null($description)) {
            try {
                $this->validateDescription($description);
            }
            catch (Exception $e) {
                $this->description = null;
                throw $e;
            }
        }
        $this->description = $description;
        return $this->description;
    }
    
    public function getDescription() {
        return $this->description;
    }
    
    protected function validateDescription(MultiLanguageString $description) {
        $description->validate(array('max_length' => self::DESCRIPTION_MAX_LENGTH));
    }


    public function validate() {
        $this->validateUrl($this->url);
        if (!is_null($this->description)) {
            $this->validateDescription($this->description);
        }
    }

}


