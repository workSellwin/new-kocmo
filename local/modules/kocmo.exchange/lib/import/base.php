<?php


namespace Kocmo\Exchange\Import;


class Base
{
    protected $errors = [];

    function __construct()
    {
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     */
    public function setErrors(array $errors)
    {
        $this->errors = $errors;
    }
}