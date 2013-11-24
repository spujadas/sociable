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

abstract class ManagedObject {
	/** @var Sociable\Common\Configuration */
	protected $config = null; // initialised by children

    protected function getById($object, $id) {
        return ObjectDocumentMapper::getById(
        	$this->config->getDocumentManager(),
        	$object,
        	$id
    	);
    }

    protected function getByUrlSlug($object, $urlSlug) {
        return ObjectDocumentMapper::getByUrlSlug(
        	$this->config->getDocumentManager(),
        	$object,
        	$urlSlug
    	);
    }

    protected function getByEmail($object, $email) {
        return ObjectDocumentMapper::getByEmail(
        	$this->config->getDocumentManager(),
        	$object,
        	$email
    	);
    }

    protected function getByLabel($object, $label) {
        return ObjectDocumentMapper::getByLabel(
            $this->config->getDocumentManager(),
            $object,
            $label
        );
    }

    protected function getByCode($object, $code) {
        return ObjectDocumentMapper::getByCode(
            $this->config->getDocumentManager(),
            $object,
            $code
        );
    }

    protected function getByName($object, $name) {
        return ObjectDocumentMapper::getByName(
        	$this->config->getDocumentManager(),
        	$object,
        	$name
    	);
    }
}

