<?php

namespace model\Dao;

class Dao {
	private static $instance;
	const cHost = "tuxa.sme.utc";
	const cPort = "5432";
	const cDBNM = "dbnf17p157";
	const cUser = "nf17p157";
	const cPass = "1zlTCOp7";
	private static function getInstance() {
		if ($instance == NULL)
			$this::$instance = new DAO ();
	}
	//Quand vous récupéré une connexion, vous utiliser ensuite une statement
	//il faut TOUJOURS finir par mettre votre statement PUIS votre connexion à NULL
	public function getConnexion() {
		try {
			$conn = new PDO ( "pgsql:host=SELF::cHost;dbname=SELF::cDBNM;port=SELF::cPort", SELF::cUser, SELF::cPass );
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
