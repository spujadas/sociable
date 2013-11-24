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

abstract class SkypeValidator {
    const SKYPE_MIN_LENGTH = 5;
    const SKYPE_MAX_LENGTH = 31;
    
    const EXCEPTION_INVALID_SKYPE_NAME = 'invalid skype name';

    public static function validateName($name) {
        StringValidator::validate($name, array(
            'min_length' => self::SKYPE_MIN_LENGTH,
            'max_length' => self::SKYPE_MAX_LENGTH
                )
        );
        
        // https://support.skype.com/en/faq/FA94/what-is-a-skype-name
        if (!preg_match('/^[a-zA-Z][a-zA-Z0-9_\-\,\.]{5,31}$/i', $name)) {
            throw new SkypeException(self::EXCEPTION_INVALID_SKYPE_NAME);
        }
    }
}


