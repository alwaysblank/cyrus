<?php
/**
 * Created by PhpStorm.
 * User: ben
 * Date: 2/19/2018
 * Time: 8:01 PM
 */

namespace Livy\Cyrus;

use \Zenodorus\Strings as Util;

use Livy\Cyrus\Brick\Mold;

class Cyrus
{
    protected $cart = [];

    protected function fill(Mold $Brick)
    {
        $this->cart[] = $Brick;
    }

    public function handleAttach(Mold $Brick)
    {
        $this->fill($Brick);
    }

    protected function filterBricks(Mold $Brick, $property, $value) : bool
    {
        return $Brick->{$property}()
    }

    protected function getBrickBy(string $property, $value) : Mold
    {
        $results = array_column($this->cart, $property, 'uid');

//        $results = array_filter($this->cart, function($Brick) use ($property, $value) {
//            return $Brick->property() = $value;
//        });


    }
}