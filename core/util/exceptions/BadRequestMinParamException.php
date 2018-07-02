<?php

namespace core\util\exceptions;

use Exception;

class BadRequestMinParamException extends Exception {

    public function __construct() {
        parent::__construct("Bad Request.", 400);
    }
}
?>
