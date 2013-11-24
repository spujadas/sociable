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

use Sociable\Common\Configuration;

abstract class PostActions extends \Sociable\ODM\ManagedObject {
    const SUCCESS = 'success';
    const INVALID_DATA = 'invalid data';
    const INVALID_POST = 'invalid POST';
    const INVALID_SESSION = 'invalid session';
    const NOT_SIGNED_IN = 'not signed in';
    const NOT_AUTHORISED = 'not authorised';
    const MAINTENANCE = 'maintenance';

    // AccessPostActions
    const ALREADY_SIGNED_IN = 'already signed in';
    const ALREADY_VALIDATED = 'already validated';
    const SEND_FAILED = 'send failed';
    const NO_PASSWORD_RESET_TOKEN = 'no password reset token';

    // ProfilePostActions
    const PROFILE_NOT_PUBLISHABLE = 'profile not publishable';
    const PROFILE_NOT_EDITABLE = 'profile not editable';

    // ProjectPostActions
    const PROJECT_NOT_EDITABLE = 'project not editable';
    const PROJECT_NOT_PUBLISHABLE = 'project not publishable';

    /** @var Configuration */
    protected $config = null;
    
    public function __construct(Configuration $config) {
        $this->config = $config;
    }

    // TODO - use this everywhere
    public function postHasIndices($indices) {
        foreach ($indices as $index) {
            if (!isset($_POST[$index])) {
                return false;
            }
        }
        return true;
    }
}


