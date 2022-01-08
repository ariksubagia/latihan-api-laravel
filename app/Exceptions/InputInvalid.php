<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class InputInvalid extends HttpException
{
    private $errors = [];

    public function __construct( $errors = [] ){
        parent::__construct( 422, 'input invalid' );

        $this->errors = $errors;
    }

    public function getErrors(){
        return $this->errors;
    }
}
