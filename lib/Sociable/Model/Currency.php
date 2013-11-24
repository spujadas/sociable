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

class Currency {
    protected $id = null;
    
    protected $code = null;
    const CODE_LENGTH = 3;
    
    const EXCEPTION_NOT_IN_CATALOGUE = 'not in catalogue';
    
    public function getId() {
        return $this->id;
    }
    
    public function setCode($code) {
        try {
            self::validateCode($code);
        } catch (StringException $e) {
            $this->code = null;
            throw $e;
        }

        $this->code = $code;
        return $this->code;
    }

    public static function validateCode($code) {
        StringValidator::validate($code, 
                array('length' => self::CODE_LENGTH));
    }

    public function getCode() {
        return $this->code;
    }


    public function validate() {
        self::validateCode($this->code);
    }
}


