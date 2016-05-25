<?php

use Livy\Cyrus;

class CyrusTest extends PHPUnit_Framework_TestCase
{
    public function testCyrusCreateElement()
    {
        $element = new Cyrus();
        $this->assertContains('test-class', $element->setClass('test-class')->addContent('This is a test')->construct());
    }

    public function testCyrusUnterminatedChild()
    {
        $test = new Livy\Cyrus();
        $this->assertContains('test-outer', $test->setClass('test-outer')->openChild()->setClass('inner')->addContent('test content')->construct());
    }

    public function testCyrusTerminatedNesting()
    {
        $element = new Cyrus();
        $element->openChild('firstChild')->addContent('nested')->closeChild();
        if (true) :
        $element->nest('firstChild')->addContent('deep')->closeChild();
        endif;
        $testString = $element->construct();
        $this->assertContains('nested deep', $testString);
    }
}
