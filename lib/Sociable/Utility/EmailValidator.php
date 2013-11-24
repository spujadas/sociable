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

abstract class EmailValidator {
    const MAX_LENGTH = 254;
    
    const EXCEPTION_INVALID_ADDRESS = 'invalid address';

    public static function validateAndNormaliseAddress($address) {
        self::validateAddress($address);
        list($user, $domain) = explode('@', trim($address));
        return $user . '@' . strtolower($domain);
    }

    public static function validateAddress($address) {
        StringValidator::validate($address, array(
                'max_length' => self::MAX_LENGTH
                )
        );
        $trimmed_email = trim($address);
        if (!(bool) filter_var($trimmed_email, FILTER_VALIDATE_EMAIL))
            throw new EmailException(self::EXCEPTION_INVALID_ADDRESS);
    }
}




