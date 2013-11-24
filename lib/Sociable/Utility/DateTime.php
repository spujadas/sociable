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

class DateTime {
	// from http://php.net/manual/en/function.checkdate.php
	public static function validateDate($date, $format = 'Y-m-d H:i:s') {
	    $d = \DateTime::createFromFormat($format, $date);
	    return ($d && $d->format($format) == $date)?$d:null;
	}
}


