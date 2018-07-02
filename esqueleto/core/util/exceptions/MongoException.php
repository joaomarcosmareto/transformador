<?php

namespace core\util;

// use PDO;
use Exception;

class MongoException extends Exception
{
    public function __construct($query, $mongoReturn) {
        parent::__construct(
            "#".json_encode($query, JSON_UNESCAPED_UNICODE)."#".json_encode($mongoReturn, JSON_UNESCAPED_UNICODE),
            "9999",
            null
        );
    }
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}