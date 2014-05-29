<?php

namespace model\Dao;

use \PDO;

use model\Entite\Tarif;

class TarifDAO
{
    private $dao;

    public function __construct($dao) {
        $this->dao = $dao;
    }

    private function getDao() {
        return $this->dao;
    }

    public function find($id) {
    }
    
    public function findAll() {
    }
    
    public function create($tarif) {
    }
    public function update($tarif){
    	
    }
    public function delete($tarif){
    	
    }
    
}