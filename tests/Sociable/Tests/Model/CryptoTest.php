<?php

/*
 * This file is part of the Sociable package.
 *
 * Copyright 2013 by Sébastien Pujadas
 *
 * For the full copyright and licence information, please view the LICENCE
 * file that was distributed with this source code.
 */

namespace Sociable\Tests\Model;

use Sociable\Utility\Crypto;

class CryptoTest extends \PHPUnit_Framework_TestCase {

    public function testCrypto() {
        $this->assertEquals(64, strlen(Crypto::getRandom64HexString()));
        $random1 = Crypto::getRandom64HexString();
        $random2 = Crypto::getRandom64HexString();
        $this->assertNotEquals($random1, $random2);
    }
}
