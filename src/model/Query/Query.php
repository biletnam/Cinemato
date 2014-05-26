<?php

namespace model\Query;

abstract class Query
{
    private $connection;

    public function __construct($connection) {
        $this->setConnection($connection);
    }

    protected function setConnection($connection) {
        $this->connection = $connection;

        return $this;
    }

    protected function getConnection() {
        return $this->connection;
    }
}
