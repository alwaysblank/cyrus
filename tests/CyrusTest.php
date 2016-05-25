<?php
 
use Livy\Cyrus;
 
class CyrusTest extends PHPUnit_Framework_TestCase {
 
  public function testCyrusCreateElement()
  {
    $element = new Cyrus;
    $this->assertContains( 'test-class', $element->setClass( 'test-class' )->addContent( 'This is a test' )->construct() );
  }

  public function testCyrusUnterminatedChild()
  {
    $test = new Livy\Cyrus;
    $this->assertContains( 'test-outer', $test->setClass('test-outer')->openChild()->setClass('inner')->addContent('test content')->construct() );
  }
 
}