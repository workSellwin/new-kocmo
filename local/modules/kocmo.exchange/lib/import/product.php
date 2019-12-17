<?php


namespace Kocmo\Exchange\Import;


class Product
{
    public function __construct()
    {

    }

    public function update($full = true, $param = []) : bool{


        $bx = \Kocmo\Exchange\StaticFactory::factory(30);
        $bx->updateOne($param);

        return true;
    }
}