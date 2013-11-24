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
 * Imports country locations from TSV file
 * 
 * SYNTAX
 * php import_country_locations.php <file.tsv>
 * e.g.
 * php import_country_locations.php country_sublocations.tsv
 * 
 * NOTES
 * Make *absolutely sure* that the country file is UTF-8-encoded.
 * Lines containing a '#' are ignored
 * Line format is:
 * <parent country code>   <code>  <name>  [<locations name>]
 * e.g.
 * FR	FR75	Paris
 */

require '../bootstrap.php' ;
require '../config.inc.php' ; // initialises $config

use Sociable\Model\Country,
    Sociable\Model\Location;

if ($argc < 2) {
    echo 'Syntax: ' . $argv[0] . ' <country_locations_file>';
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
    $country = $dm->getRepository('Sociable\Model\Country')->findOneByCode($components[0]) ;
    if (is_null($country)) {
        throw new Exception ('Country ' . $components[0] . ' not found in database') ;
    }
    $location = new Location() ;
    $location->setLabel($components[1]) ;
    $location->setName($components[2]) ;
    $location->setParentCountry($country) ;
    if (isset($components[3])) {
        $country->setSublocationsName($components[3]) ;
    }
    $location->validate();
    echo $components[1] . "\n" ;
    $dm->persist($location);
}

$dm->flush();

?>