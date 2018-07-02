<?php

namespace core\util\exceptions;

use Exception;

class PermissaoNegadaException extends Exception {

    public function __construct() {
        parent::__construct("Você não tem permissão para realizar esta operação.", 401);
    }
}
?>
