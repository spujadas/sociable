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
 * Imports business sectors from TSV file
 * 
 * SYNTAX
 * php import_business_sectors.php <file.tsv>
 * e.g.
 * php import_business_sectors.php business_sectors.tsv
 * 
 * NOTES
 * Make *absolutely sure* that the file is UTF-8-encoded.
 * Lines containing a '#' are ignored
 * Line format is:
 * <code>   <French name>  <English name>
 * e.g.
 * education	Enseignement	Education
 */

require '../bootstrap.php' ;
require '../config.inc.php' ; // initialises $config

use Sociable\Model\BusinessSector,
    Sociable\Model\MultiLanguageString;

if ($argc < 2) {
    echo 'Syntax: ' . $argv[0] . ' <business_sector_file>';
    return;
}

$file = $argv[1];
$lines = file($file);

$dm = $config->getDocumentManager() ;

foreach ($lines as $line) {
    if (preg_match('/#/', $line)) {
        continue;
    }
    list($code, $name_fr, $name_en) = explode("\t", trim($line));
    $businessSector = new BusinessSector();
    $businessSector->setCode($code);
    $name = new MultiLanguageString();
    $name->addStringByLanguageCode($name_en, 'en');
    $name->addStringByLanguageCode($name_fr, 'fr');
    $name->setDefaultLanguageCode('fr');
    $businessSector->setName($name);
    $businessSector->validate();
    echo "$code\n" ;
    $dm->persist($businessSector);
}

$dm->flush();

?>