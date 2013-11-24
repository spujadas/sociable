<?php

/*
 * This file is part of the Sociable package.
 *
 * Copyright 2013 by Sébastien Pujadas
 *
 * For the full copyright and licence information, please view the LICENCE
 * file that was distributed with this source code.
 */

namespace Sociable\Tests\View;

use Sociable\View\RawDecorator;

class RawDecoratorTest extends \PHPUnit_Framework_TestCase {
    protected $decorator;
    const CONTENT = 'content';
    
    public function setUp() {
        $this->decorator = new RawDecorator;
    }

    public function test__construct() {
        $this->assertInstanceOf('Sociable\View\RawDecorator', $this->decorator);
    }
    
    public function testRender() {
        $this->assertEquals(self::CONTENT, $this->decorator->render(self::CONTENT, false));
        $this->expectOutputString(self::CONTENT);
        $this->assertEquals(self::CONTENT, $this->decorator->render(self::CONTENT));
    }
        
    public function tearDown() {
        unset($this->decorator);
    }
}


