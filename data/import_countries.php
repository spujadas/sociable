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
 * Imports countries from TSV file
 * 
 * SYNTAX
 * php import_countries.php <file.tsv>
 * e.g.
 * php import_countries.php countries.tsv
 * 
 * NOTES
 * Make *absolutely sure* that the country file is UTF-8-encoded.
 * Lines containing a '#' are ignored
 * Line format is:
 * <country code>   <English name>  <French name>   [<sublocations name>]
 * e.g.
 * GB	UNITED KINGDOM	ROYAUME-UNI
 * FR	FRANCE	FRANCE	département
 */

require '../bootstrap.php' ;
require '../config.inc.php' ; // initialises $config

use Sociable\Model\Country,
    Sociable\Model\MultiLanguageString;

if ($argc < 2) {
    echo 'Syntax: ' . $argv[0] . ' <country_file>';
    return;
}

$file = $argv[1];
$lines = file($file);

$dm = $config->getDocumentManager() ;

foreach ($lines as $line) {
    if (preg_match('/#/', $line)) {
        continue;
    }
    $components = explode("\t", trim($line));
    // list($code, $name_en, $name_fr, $sublocation_name) = explode("\t", trim($line));
    $country = new Country();
    $country->setCode($components[0]);
    $name = new MultiLanguageString();
    $name->addStringByLanguageCode($components[1], 'en');
    $name->addStringByLanguageCode($components[2], 'fr');
    $name->setDefaultLanguageCode('fr');
    $country->setName($name);
    if (isset($components[3])) {
        $country->setLocationsName($components[3]) ;
    }
    $country->validate();
    echo $components[0] . "\n" ;
    $dm->persist($country);
}

$dm->flush();

?>