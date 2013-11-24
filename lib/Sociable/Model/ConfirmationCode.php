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

use Sociable\Utility\Crypto;

class ConfirmationCode {

    protected $confirmationCode = null;
    protected $isConfirmed = null;

    public function __construct() {
        $this->renewConfirmationCode();
    }
    public function setConfirmed($status) {
        if (is_bool($status)) {
            $this->isConfirmed = $status;
        } else {
            $this->isConfirmed = false;
        }
        return $this->isConfirmed;
    }

    protected function matchesConfirmationCode($code) {
        return $code === $this->confirmationCode;
    }
    
    public function confirmCode($code) {
        if ($this->matchesConfirmationCode($code)) {
            $this->setConfirmed(true);
            return true;
        }
        return false;
    }

    public function renewConfirmationCode() {
        $this->confirmationCode = Crypto::getRandom64HexString();
        $this->setConfirmed(false);
        return $this->confirmationCode;
    }

    public function getConfirmationCode() {
        return $this->confirmationCode;
    }
    
    public function isConfirmed() {
        return $this->isConfirmed;
    }
}


