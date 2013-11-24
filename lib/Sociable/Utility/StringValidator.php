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

abstract class StringValidator {
    const EXCEPTION_NOT_A_STRING = 'not a string';
    const EXCEPTION_EMPTY = 'empty';
    const EXCEPTION_TOO_SHORT = 'too short';
    const EXCEPTION_TOO_LONG = 'too long';
    const EXCEPTION_INCORRECT_LENGTH = 'incorrect length';
    
    public static function validate($string, $constraints = array()) {
        if (!is_string($string)) {
            throw new StringException(self::EXCEPTION_NOT_A_STRING);
        }
        
        if (!array_key_exists('no_pretrim', $constraints) ||
                (array_key_exists('no_pretrim', $constraints)&& !$constraints['no_pretrim'])) {
            $string = trim($string);
        }
        
        if (array_key_exists('not_empty', $constraints)) {
            if (empty($string)) {
                throw new StringException(self::EXCEPTION_EMPTY);
            }
        }
        
        if (array_key_exists('min_length', $constraints)) {
            if (strlen($string) < $constraints['min_length']) {
                throw new StringException(self::EXCEPTION_TOO_SHORT);
            }
        }
        
        if (array_key_exists('max_length', $constraints)) {
            if (strlen($string) > $constraints['max_length']) {
                throw new StringException(self::EXCEPTION_TOO_LONG);
            }
        }
        
        if (array_key_exists('length', $constraints)) {
            if (strlen($string) != $constraints['length']) {
                throw new StringException(self::EXCEPTION_INCORRECT_LENGTH);
            }
        }
    }
}


