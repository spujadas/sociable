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

abstract class Crypto {
    public static function getRandom64HexString($seed = null) {
        if (!is_string($seed)) {
            $seed = null;
        }
        return hash('sha256', $seed . uniqid(mt_rand(), true));
    }
}


