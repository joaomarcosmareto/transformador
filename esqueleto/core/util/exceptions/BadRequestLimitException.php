<?php

namespace core\util\exceptions;

use Exception;

class BadRequestLimitException extends Exception {

    public function __construct() {
        parent::__construct("Too Many Requests.", 429);
    }
}
?>
