<?php


namespace Kocmo\Exchange\Tree;


class Store extends Builder
{
    function __construct()
    {
        parent::__construct();
        $this->entry = $this->arParams['STORE_ENTRY'];
        $this->fillInOutputArr();
    }
}