<?php

/*
 * This file is part of the Sociable package.
 *
 * Copyright 2013 by Sébastien Pujadas
 *
 * For the full copyright and licence information, please view the LICENCE
 * file that was distributed with this source code.
 */

namespace Sociable\Controller;

use Sociable\Common\Configuration,
    Sociable\Utility\Crypto;

class SessionUtils {
    public static function startSession(Configuration $config) {
        if (!session_id()) {
            session_start();
        }

        // language
        if (!array_key_exists('language', $_SESSION)) {
            $_SESSION['language'] = $config->getParam('defaultLanguageCode');
        }

        // anti-CSRF token
        if (!isset($_SESSION['token'])) {
            $_SESSION['token'] = Crypto::getRandom64HexString();
        }
    }
}


