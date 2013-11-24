<?php

/*
 * This file is part of the Sociable package.
 *
 * Copyright 2013 by Sébastien Pujadas
 *
 * For the full copyright and licence information, please view the LICENCE
 * file that was distributed with this source code.
 */

namespace Sociable\ODM;

use Doctrine\ODM\MongoDB\DocumentManager;

class ObjectDocumentMapper {
    public static function getById(DocumentManager $dm, $object, $id) {
    	return $dm->getRepository($object)->findOneById($id);
    }	

    public static function getByUrlSlug(DocumentManager $dm, $object, $urlSlug) {
    	return $dm->getRepository($object)->findOneByUrlSlug($urlSlug);
    }	

    public static function getByEmail(DocumentManager $dm, $object, $email) {
    	return $dm->getRepository($object)->findOneByEmail($email);
    }	

    public static function getByLabel(DocumentManager $dm, $object, $label) {
        return $dm->getRepository($object)->findOneByLabel($label);
    }

    public static function getByCode(DocumentManager $dm, $object, $code) {
        return $dm->getRepository($object)->findOneByCode($code);
    }

    public static function getByName(DocumentManager $dm, $object, $name) {
    	return $dm->getRepository($object)->findOneByName($name);
    }

    public static function getBusinessSectorsInLanguage(DocumentManager $dm, $language) {
        $nameField = 'name.languageStrings.' . $language;
        return $dm->createQueryBuilder('Sociable\Model\BusinessSector')
            ->select('code', $nameField)
            ->sort($nameField, 'asc')
            ->getQuery()
            ->execute();
    }

    public static function getCountriesInLanguage(DocumentManager $dm, $language) {
        $nameField = 'name.languageStrings.' . $language;
        return $dm->createQueryBuilder('Sociable\Model\Country')
            ->select('code', $nameField)
            ->sort($nameField, 'asc')
            ->getQuery()
            ->execute();
    }
}

