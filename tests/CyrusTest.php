<?php

use Livy\Cyrus;

class CyrusTest extends PHPUnit_Framework_TestCase
{
    /**
     * Can we successfully construct simple elements
     * @return void
     */
    public function testCyrusCreateElement()
    {
        $element = new Cyrus();
        $this->assertContains('test-class', $element->setClass('test-class')->addContent('This is a test')->construct(), 'We were unable to create a simple Cyrus element.');
    }

    /**
     * Can we successfully construct complex elements
     * @return void
     */
    public function testCyrusBuild()
    {
        $element = new Cyrus();
        $element->setEl('section')->setClass('build-wrapper')->setStyle('background-color', 'blue')
            ->openChild('section_header')->setEl('h1')->setClass('section-header')->addContent('This is a title')->closeChild()
            ->openChild('section_body')->setClass('section-body')->addContent('Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. ')->closeChild();
            if(true) :
                $element->nest('section_body')
                    ->openChild()->setEl('img')->setAttr('src', 'http://placehold.it/350x150')->setClass('nested-image')->closeChild()
                ->closeChild();
            else :
                $element->nest('section_body')
                    ->openChild()->setEl('blockquote')->setID('warning')->addContent('You should not be seeing this.')->closeChild()
                ->closeChild();
            endif;
        $element->addContent('This should follow the image.');
        
        $testContent = "<section class='build-wrapper' style='background-color: blue;'><h1 class='section-header'>This is a title</h1> <div class='section-body'>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.  <img src='http://placehold.it/350x150' class='nested-image'></div> This should follow the image.</section>";

        $this->assertEquals($testContent, $element->construct(), 'We attempted to constuct a large Cyrus element but it does not match the appropriate string.');
    }

    /**
     * Can we nest elements after terminating a method chain
     * @return void
     */
    public function testCyrusTerminatedNesting()
    {
        $element = new Cyrus();
        $element->openChild('firstChild')->addContent('nested')->closeChild();
        if (true) :
            $element->nest('firstChild')->addContent('deep')->closeChild();
        endif;
        $testString = $element->construct();
        $this->assertContains('nested deep', $testString, 'We were unable to deeply nest an object after the top-level object method string was terminated.');
    }

    /**
     * Are unsafe strings correctly rejected
     * @return void
     */
    public function testCyrusSafeStringFalse()
    {
        $element = new Cyrus();
        $this->assertFalse($element->safeString('123%%%*#'), 'Unsafe strings are being incorrectly reported as safe.');
    }

    /**
     * Are safe strings correctly passed?
     * @return void
     */
    public function testCyrusSafeString()
    {
        $element = new Cyrus();
        $this->assertContains('test_string01', $element->safeString('test_string01'), 'Safe strings are being incorrectly reported as unsafe.');
    }
}
