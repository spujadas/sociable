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

class WebPresence {

    protected $type = null;

    const TYPE_WEBSITE = 'website';
    const TYPE_FACEBOOK = 'facebook';
    const TYPE_TWITTER = 'twitter';

    /** @var URL */
    protected $url = null;
    
    const EXCEPTION_INVALID_TYPE = 'invalid type';
    const EXCEPTION_MISSING_URL = 'missing URL';

    public function setType($type) {
        try {
            $this->validateType($type);
        }
        catch (Exception $e) {
            $this->type = null;
            throw $e;
        }
        $this->type = $type;
        return $this->type;

    }

    public function getType() {
        return $this->type;
    }
    
    protected function validateType($type) {
        if (($type != self::TYPE_WEBSITE) 
                && ($type != self::TYPE_FACEBOOK)
                && ($type != self::TYPE_TWITTER)) {
            throw new WebPresenceException(self::EXCEPTION_INVALID_TYPE);
        }
    }

    public function getUrl() {
        return $this->url;
    }

    public function setUrl(URL $url) {
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
    
    public function setUrlInferType(URL $url) {
        $this->setUrl($url);

        // get URL string
        $urlString = $url->getUrl();
        $parts = parse_url($urlString);

        // add http:// if omitted and reparse
        if (!isset($parts['scheme'])) { 
            $urlString = "http://$urlString"; 
            $parts = parse_url($urlString);
        }

        if (preg_match('/facebook.com$/', $parts['host'])) {
            $this->type = self::TYPE_FACEBOOK;
            return;
        }
        if (preg_match('/twitter.com$/', $parts['host'])) {
            $this->type = self::TYPE_TWITTER;
            return;
        }
        $this->type = self::TYPE_WEBSITE;
    }

    protected function validateUrl(URL $url) {
        $url->validate();
    }
    
    public function validate() {
        $this->validateType($this->type);
        if (is_null($this->url)) {
            throw new WebPresenceException(self::EXCEPTION_MISSING_URL);
        }
        $this->validateUrl($this->url);
    }
}


