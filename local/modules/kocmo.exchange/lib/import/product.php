<?php


namespace Kocmo\Exchange\Import;


class Product
{
    public function __construct()
    {

    }

    public function update($full = true, $param = []) : bool{

        if(!is_array($param)){
            return false;
        }

        if( isset($param[0]) && gettype($param[0]) === 'string' ){

            foreach($param as $xmlId) {
                $tree = new \Kocmo\Exchange\Tree\Product(['UID' => $xmlId, 'PRODUCT_LIMIT' => 1]);
                $tree->fillInOutputArr();
                $ra = $tree->getRequestArr();
                $bx = \Kocmo\Exchange\StaticFactory::factory(30);
                $id = $bx->updateOne($ra[0]);

                if(!$id){

                }
            }

//            pr($ra, 50);
//             die( );
        }
        elseif( isset($param[0]) && isset($param[0]['UID']) ) {
            $bx = \Kocmo\Exchange\StaticFactory::factory(30);
            $bx->updateOne($param[0]);
        }


        return true;
    }
}