<?php

/*
 * This file is part of the Sociable package.
 *
 * Copyright 2013 by Sébastien Pujadas
 *
 * For the full copyright and licence information, please view the LICENCE
 * file that was distributed with this source code.
 */

namespace Sociable\Utility;

abstract class PhoneValidator {
    const PHONE_MAX_LENGTH = 32;
    const PHONE_MIN_DIGITS = 6;
    
    public static function validateNumber($number) {
        StringValidator::validate($number,
            array('max_length' => self::PHONE_MAX_LENGTH)
        );
        StringValidator::validate(preg_replace('/[^\d]/', '', $number),
            array('min_length' => self::PHONE_MIN_DIGITS)
        );
    }
}


