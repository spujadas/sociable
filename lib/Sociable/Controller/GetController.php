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

use Sociable\View\Decorator,
    Sociable\Common\Configuration;

class GetController {
    protected $module;
    protected $decorator;
    protected $content;
    
    protected static $actions;

    /* abstract public static function init(); */
    
    const EXCEPTION_NON_EXISTENT_CLASS = 'non-existent class';
    const EXCEPTION_NON_EXISTENT_METHOD = 'non-existent method';

    
    public function __construct(Decorator $decorator) {
        $this->decorator = $decorator;
    }

    public function dispatch($action, $request, $query, Configuration $config) {
        // start session to get $_SESSION environment variable
        SessionUtils::startSession($config);
        
        // get action or default to 404
        if (!array_key_exists($action, self::$actions)) {
            header("Location: /404.php");
            return;
        }
        
        $use_array = self::$actions[$action];

        // process
        if (isset($use_array['object'])) {
            // get view object
            if (!class_exists($use_array['object'])) {
                throw new ControllerException(self::EXCEPTION_NON_EXISTENT_CLASS);
            }
            $this->module = new $use_array['object']($request, $query, $config);

            // get view method
            if (!method_exists($this->module, $use_array['method'])) {
                throw new ControllerException(self::EXCEPTION_NON_EXISTENT_METHOD);
            }

            // get output and result
            try {
                ob_start();
                $result = $this->module->$use_array['method']();
                $this->content = ob_get_contents();
                ob_end_clean();
            }
            catch (\Exception $e) {
                try {
                    $config->getLogger()->addError(__FUNCTION__.' in '.__FILE__.' at '.__LINE__ 
                        . ' - ' . $e->getTraceAsString());
                }
                catch (\Exception $e) {}
                header("Location: /maintenance.html");
                return;
            }
            
            // route if result
            if ($result) {
                if (array_key_exists('routes', $use_array)
                    && array_key_exists($result, $use_array['routes'])) {
                    if (!is_null($use_array['routes'][$result])) {
                        header('Location: ' . $use_array['routes'][$result]);
                    }
                    return;
                }
                if (array_key_exists($result, self::$actions['default']['routes'])) {
                    header('Location: ' . self::$actions['default']['routes'][$result]);
                    return;
                }
                header("Location: /404.php");
                return;
            }

            // render if output
            if (isset($use_array['raw_render']) && $use_array['raw_render']) {
                echo $this->content;
            }
            else {
                $this->decorator->render($this->content);
            }

            // empty flash
            unset($_SESSION['message']);
        }
    }

    public function getContent() {
        return $this->content;
    }
}


