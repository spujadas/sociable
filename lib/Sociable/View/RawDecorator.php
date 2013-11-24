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

class RawDecorator extends Decorator {
    public function render($content, $print = true) {
        ob_start();
        echo $content;
        $output = ob_get_contents();
        ob_end_clean();

        if ($print)
            echo $output;
        return $output;
    }

}

