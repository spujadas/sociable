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

use Sociable\Model\ConfirmationCode;

class ConfirmationCodeTest extends \PHPUnit_Framework_TestCase {

    protected $confirmationCode;

    public function setUp() {
        $this->confirmationCode = new ConfirmationCode();
    }

    public function test__construct() {
        $this->assertInstanceOf('Sociable\Model\ConfirmationCode', $this->confirmationCode);
    }
    
    public function testSetConfirmed() {
        $this->assertEquals(true, $this->confirmationCode->setConfirmed(true));
        $this->assertEquals(true, $this->confirmationCode->isConfirmed());
        $this->assertEquals(false, $this->confirmationCode->setConfirmed(false));
        $this->assertEquals(false, $this->confirmationCode->isConfirmed());
    }

    public function testConfirmCode() {
        $this->assertEquals(false, $this->confirmationCode->confirmCode(array()));
        $this->assertEquals(false, $this->confirmationCode->isConfirmed());
        $this->assertEquals(false, $this->confirmationCode->confirmCode(null));
        $this->assertEquals(false, $this->confirmationCode->isConfirmed());
        $this->assertEquals(false, $this->confirmationCode->confirmCode(''));
        $this->assertEquals(false, $this->confirmationCode->isConfirmed());
        $this->assertEquals(true, 
                $this->confirmationCode->confirmCode(
                        $this->confirmationCode->getConfirmationCode()));
        $this->assertEquals(true, $this->confirmationCode->isConfirmed());
    }
    
    public function testRenewGenerateCode() {
        $code1 = $this->confirmationCode->getConfirmationCode();
        $this->assertEquals(true, $this->confirmationCode->confirmCode($code1));
        $this->assertEquals(true, $this->confirmationCode->isConfirmed());
        $code2 = $this->confirmationCode->renewConfirmationCode();
        $this->assertEquals(false, $this->confirmationCode->isConfirmed());
        $this->assertEquals(false, $this->confirmationCode->confirmCode($code1));
        $this->assertEquals(false, $this->confirmationCode->isConfirmed());
        $this->assertEquals(true, $this->confirmationCode->confirmCode($code2));
        $this->assertEquals(true, $this->confirmationCode->isConfirmed());
        $this->assertEquals(false, $this->confirmationCode->confirmCode($code1));
        $this->assertEquals(true, $this->confirmationCode->isConfirmed());
    }

    public function tearDown() {
        unset($this->confirmationCode);
    }

}


