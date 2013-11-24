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

abstract class NumberValidator {
    const EXCEPTION_NOT_A_NUMBER = 'not a number';
    const EXCEPTION_NOT_A_CURRENCY = 'not a currency';
    const EXCEPTION_NOT_AN_INTEGER = 'not an integer';
    const EXCEPTION_NOT_POSITIVE = 'not positive';
    const EXCEPTION_TOO_SMALL = 'too small';
    const EXCEPTION_TOO_LARGE = 'too large';
    
    public static function validate($value, $constraints = array()) {
        if (!is_numeric($value)) {
            throw new NumberException(self::EXCEPTION_NOT_A_NUMBER);
        }
        if (array_key_exists('is_currency', $constraints) 
                && $constraints['is_currency']) {
            if ((float)$value != round($value, 2)) {
                throw new NumberException(self::EXCEPTION_NOT_A_CURRENCY);
            }
        }
        if (array_key_exists('positive', $constraints) 
                && $constraints['positive']) {
            if ($value < 0) {
                throw new NumberException(self::EXCEPTION_NOT_POSITIVE);
            }
        }
        if (array_key_exists('min', $constraints)) {
            if ($value < $constraints['min']) {
                throw new NumberException(self::EXCEPTION_TOO_SMALL);
            }
        }
        if (array_key_exists('max', $constraints)) {
            if ($value > $constraints['max']) {
                throw new NumberException(self::EXCEPTION_TOO_LARGE);
            }
        }
        if (array_key_exists('int', $constraints)
                && $constraints['int']) {
            if (!is_int($value)) {
                throw new NumberException(self::EXCEPTION_NOT_AN_INTEGER);
            }
        }
    }
}


