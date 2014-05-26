<?php

namespace model\Query;

use \PDO;

class SelectQuery extends Query implements QueryableInterface
{
    private $fields;

    private $table;

    private $where;

    private $join;

    private $limit;

    public function __construct($connection, $table = '', $fields = '', $join = '', $where = '', $limit = '') {
        $this->setConnection($connection);
        $this->setTable($table);
        $this->setFields($fields);
        $this->setJoin($join);
        $this->setWhere($where);
        $this->setLimit($limit);

        return $this;
    }

    public function execute($params = array()) {
        $statement = $this->getConnection()->prepare($this->getQuery());
        $statement->execute($params);

        return $statement;
    }

    public function fetch($params = array(), $fetchMode = PDO::FETCH_ASSOC) {
        $statement = $this->execute($params);

        return $statement->fetch($fetchMode);
    }

    public function fetchAll($params = array(), $fetchMode = PDO::FETCH_ASSOC) {
        $statement = $this->execute($params);

        return $statement->fetchAll($fetchMode);
    }

    public function getQuery() {
        $query = 'SELECT ' . $this->getFields() . ' FROM ' . $this->getTable();

        if ($this->join) {
            $query .= ' LEFT JOIN ' . $this->getJoin();
        }

        if ($this->where) {
            $query .= ' WHERE ' . $this->getWhere();
        }

        if ($this->limit) {
            $query .= ' LIMIT ' . $this->getLimit();
        }

        return $query;
    }

    public function setTable($table) {
        $this->table = $table;

        return $this;
    }

    public function getTable() {
        return $this->table;
    }

    public function setFields($fields) {
        $this->fields = $fields;

        return $this;
    }

    public function addFields($fields) {
        if (is_null($this->fields)) {
            $this->fields = '';
        }

        $this->fields .= $fields;

        return $this;
    }

    public function getFields() {
        return $this->fields;
    }

    public function setJoin($join) {
        $this->join = $join;

        return $this;
    }

    public function addJoin($join) {
        if (is_null($this->join)) {
            $this->join = '';
        }

        $this->join .= $join;

        return $this;
    }

    public function getJoin() {
        return $this->join;
    }

    public function setWhere($where) {
        $this->where = $where;

        return $this;
    }

    public function addWhere($where) {
        if (is_null($this->where)) {
            $this->where = '';
        }

        $this->where .= $where;

        return $this;
    }

    public function getwhere() {
        return $this->where;
    }

    public function setLimit($limit) {
        $this->limit = $limit;

        return $this;
    }

    public function getLimit() {
        return $this->limit;
    }
}
