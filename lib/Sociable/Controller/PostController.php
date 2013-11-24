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

class PostController {
    const INVALID_REQUEST = 'invalid request';

    protected static $actions;

    protected static function isValidPost() {
        return isset($_POST)
            && array_key_exists('action', $_POST) 
            && array_key_exists($_POST['action'], self::$actions)
            && array_key_exists('token', $_POST) 
            && ($_POST['token'] == $_SESSION['token']);
    }
    
    public static function process(Configuration $config) {
        // start session to get $_SESSION environment variable
        SessionUtils::startSession($config);

        // valid POST?
        if (!self::isValidPost()) {
            header("Location: /");
            return;
        }
        
        // get and perform action
        $action = $_POST['action'];
        
        try {
            $result = self::performAction($action, $config);
        }
        catch (\Exception $e) {
            try {
                $config->getLogger()->addError(__FUNCTION__.' in '.__FILE__.' at '.__LINE__ 
                    . ' - ' . $e->getTraceAsString());
            }
            catch (\Exception $e) {}
            header("Location: /maintenance.html");
            return self::INVALID_REQUEST;
        }
        
        // route based on result
        self::route($action, $result);
        return $result;
    }

    protected static function performAction($action, Configuration $config) {
        $use_array = self::$actions[$action];
        $obj = new $use_array['object']($config);
        return $obj->$use_array['method']();
    }

    protected static function route($action, $result) {
        $use_array = self::$actions[$action];
        if (is_null($result)) {
            if (isset($use_array['manual_route']) && $use_array['manual_route']) {
                return;
            }            
        }
        else {
            if (isset($use_array['routes'][$result])) {
                if (array_key_exists('request', $_SESSION)) {
                    header('Location: ' . $use_array['routes'][$result] .'/'.$_SESSION['request']);
                    unset($_SESSION['request']);
                }
                else {
                    header('Location: ' . $use_array['routes'][$result]);
                }
                return;
            }
            if (self::$actions['default']['routes'][$result]) {
                header('Location: ' . self::$actions['default']['routes'][$result]);
                return;
            }
        }
        header('Location: /');
    }
    
}

