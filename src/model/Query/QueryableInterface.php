<?php

namespace model\Query;

interface QueryableInterface
{
    public function getQuery();

    public function execute($params = array());
}
