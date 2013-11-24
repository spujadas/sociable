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
 * Imports languages from TSV file
 * 
 * SYNTAX
 * php import_languages.php <file.tsv>
 * e.g.
 * php import_languages.php languages.tsv
 * 
 * NOTES
 * Make *absolutely sure* that the language file is UTF-8-encoded.
 * Lines containing a '#' are ignored
 * Line format is:
 * <language code>   <display name>
 * e.g.
 * en	English
 */

require '../bootstrap.php' ;
require '../config.inc.php' ; // initialises $config

use Sociable\Model\Language;

if ($argc < 2) {
    echo 'Syntax: ' . $argv[0] . ' <language_file>';
    return;
}

$file = $argv[1];
$lines = file($file);

$dm = $config->getDocumentManager() ;

foreach ($lines as $line) {
    if (preg_match('/#/', $line)) {
        continue;
    }
    list($code, $display_name) = explode("\t", trim($line));
    $language = new Language();
    $language->setCode($code);
    $language->setDisplayName($display_name);
    $language->validate();
    echo "$code\n" ;
    $dm->persist($language);
}

$dm->flush();

?>