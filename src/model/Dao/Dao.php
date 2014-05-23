<?php

namespace model\Dao;

class Dao {
	private static $instance;
	private static final $cHost = "tuxa.sme.utc";
	private static final $cPort = "5432";
	private static final $cDBNM = "dbnf17p157";
	private static final $cUser = "nf17p157";
	private static final $cPass = "1zlTCOp7";
	private static function getInstance() {
		if ($instance == NULL)
			$this::$instance = new DAO ();
	}
	//Quand vous récupéré une connexion, vous utiliser ensuite une statement
	//il faut TOUJOURS finir par mettre votre statement PUIS votre connexion à NULL
	public function getConnexion() {
		try {
			$conn = new PDO ( "pgsql:host=$this->cHost;dbname=$this->cDBNM;port=$this->cPort", "$this->cUser", "$this->cPass" );
		} catch ( PDOException $e ) {
			$conn = null;
		}
		return $conn;
	}
	public function getFilmDAO() {
		return new FilmDAO ( $this::getInstance () );
	}

	public static function f1TestDAO() {
		$film = self::getInstance()->getFilmDAO()->find(1);
		return print_r($film,true);
	}
}
