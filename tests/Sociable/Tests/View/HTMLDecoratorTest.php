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

use Sociable\View\HTMLDecorator;

class HTMLDecoratorTest extends \PHPUnit_Framework_TestCase {
    protected $decorator;
    const HEADER = 'header';
    const CONTENT = 'content';
    const FOOTER = 'footer';
    
    protected $twig;
    const APPNAME = 'appname';
    const TITLE = 'title';
    protected $css_files = array('file.css', 'file2.css');
    
    public function setUp() {
        $this->twig = $this->getMockBuilder('Twig_Environment')
                     ->disableOriginalConstructor()
                     ->getMock();
        $this->template = $this->getMockBuilder('Twig_TemplateInterface')
                     ->disableOriginalConstructor()
                     ->getMock();
        
        // http://stackoverflow.com/a/292423
        $this->template->expects($this->any())
             ->method('display')
             ->will($this->returnCallback('Sociable\Tests\View\ViewUtil::display'));
        $this->twig->expects($this->any())
             ->method('loadTemplate')
             ->will($this->returnValue($this->template));
        $this->decorator = new HTMLDecorator($this->twig, self::APPNAME, self::TITLE, $this->css_files);
    }
    
    public function test__construct() {
        $this->assertInstanceOf('Sociable\View\HTMLDecorator', $this->decorator);
    }
    
    public function testRender() {
        $expectedRender = '{"page_title":"' . self::APPNAME . ' | ' .  self::TITLE . 
                '","css_files":' . json_encode($this->css_files) . '}' 
                . self::CONTENT . json_encode(array());
        $this->assertEquals($expectedRender, $this->decorator->render(self::CONTENT, false));
        $this->expectOutputString($expectedRender);
        $this->assertEquals($expectedRender, $this->decorator->render(self::CONTENT));
    }
        
    public function tearDown() {
        unset($this->decorator);
    }
}

function display ($data) {
    echo json_encode($data);
}


