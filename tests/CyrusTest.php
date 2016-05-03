<?php
 
use Livy\Cyrus;
 
class CyrusTest extends PHPUnit_Framework_TestCase {
 
  public function testCyrusCreateElement()
  {
    $element = new Cyrus;
    $this->assertContains( 'test-class', $element->setClass( 'test-class' )->addContent( 'This is a test' )->construct() );
  }
 
}