<?php

namespace core\util\exceptions;

use Exception;

class TokenExpiradoException extends Exception {
    public function __construct() {
        parent::__construct("Token Expirado.", 400);
    }
}
?>