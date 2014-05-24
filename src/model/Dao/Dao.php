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
 		if (self::$instance == NULL)
 			self::$instance = new DAO ();
 		return self::$instance;
 	}
	//Quand vous récupéré une connexion, vous utiliser ensuite une statement
	//il faut TOUJOURS finir par mettre votre statement PUIS votre connexion à NULL
	public function getConnexion() {
		try {
			$strConn = 'pgsql:host='.self::cHost.';dbname='.self::cDBNM.';port='.self::cPort;
			$conn = new PDO ($strConn, self::cUser, self::cPass );
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
