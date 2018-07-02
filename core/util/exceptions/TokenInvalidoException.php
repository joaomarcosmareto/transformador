<?php

namespace core\util\exceptions;

use Exception;

class TokenInvalidoException extends Exception {

    public function __construct() {
        parent::__construct("Token Inválido.", 400);
    }
}
?>