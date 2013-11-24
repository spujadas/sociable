<?php

/*
 * This file is part of the Sociable package.
 *
 * Copyright 2013 by Sébastien Pujadas
 *
 * For the full copyright and licence information, please view the LICENCE
 * file that was distributed with this source code.
 */

/*
 * Imports currency codes from flat file
 * 
 * SYNTAX
 * php import_currenies.php <file.txt>
 * e.g.
 * php import_currency.php ISO_3166-1-alpha-2.txt
 * 
 * NOTES
 * File should be in ASCII or UTF-8.
 * Lines containing a '#' are ignored
 * Line format is:
 * <currency code>
 * e.g.
 * EUR
 */

require '../bootstrap.php' ;
require '../config.inc.php' ; // initialises $config

use Sociable\Model\Currency;

if ($argc < 2) {
    echo 'Syntax: ' . $argv[0] . ' <currency_file>';
    return;
}

$file = $argv[1];
$lines = file($file);

$dm = $config->getDocumentManager() ;

foreach ($lines as $line) {
    if (preg_match('/#/', $line)) {
        continue;
    }
    $code = trim($line) ;
    $currency = new Currency();
    $currency->setCode($code);
    $currency->validate();
    echo "$code\n" ;
    $dm->persist($currency);
}

$dm->flush();

?>