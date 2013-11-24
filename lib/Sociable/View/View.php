<?php

/*
 * This file is part of the Sociable package.
 *
 * Copyright 2013 by Sébastien Pujadas
 *
 * For the full copyright and licence information, please view the LICENCE
 * file that was distributed with this source code.
 */

namespace Sociable\View;

use Sociable\Common\Configuration;

abstract class View extends \Sociable\ODM\ManagedObject {
    protected $request = null;
    protected $query = null;

    /** @var TODO typehint */
    protected $template = null;
    
    public function __construct ($request, $query, Configuration $config) {
        $this->config = $config;
        $this->request = $request;
        $this->query = $query;
    }

    protected function emptyImage() {
        header('Cache-Control: no-cache');
        header('Content-type: image/gif');
        header('Content-length: 43');

        echo
        base64_decode('R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==');
    }

    protected function loadTemplate($template) {
        $this->template = $this->config->getTwig()->loadTemplate($template);
    }

    protected function displayTemplate($params = array()) {
        if (is_null($this->template)) { return; }
        $params['session'] = $_SESSION;
        $this->template->display($params);
    }
}


