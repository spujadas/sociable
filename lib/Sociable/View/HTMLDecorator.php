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

use Twig_Environment;

class HTMLDecorator extends Decorator {

    protected $title;
    protected $css_files;
    protected $twig;

    public function __construct(Twig_Environment $twig, $appName, $title, $cssFiles) {
        $this->twig = $twig;
        $this->title = $title;
        $this->cssFiles = $cssFiles;
        $this->appName = $appName;
    }

    protected function header() {
        $template = $this->twig->loadTemplate('html-header.tpl.html');
        $template->display(array(
            'page_title' => $this->appName . ' | ' . $this->title,
            'css_files' => $this->cssFiles,
        ));
    }

    protected function footer() {
        $template = $this->twig->loadTemplate('html-footer.tpl.html');
        $template->display(array());
    }

    public function render($content, $print = true) {
        ob_start();
        $this->header();
        echo $content;
        $this->footer();
        $output = ob_get_contents();
        ob_end_clean();

        if ($print)
            echo $output;
        return $output;
    }

}

