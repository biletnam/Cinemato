<?php

namespace model\Dao;

class Dao
{
	private static $_instance = null;

	const db_host = "tuxa.sme.utc";
	const db_port = "5432";
	const db_name = "dbnf17p157";
	const db_user = "nf17p157";
	const db_pass = "1zlTCOp7";

	private static function getInstance() {
		if (is_null(self::$_instance)) {
			self::$_instance = new Dao();
		}
	}

	//Quand vous récupéré une connexion, vous utiliser ensuite une statement
	//il faut TOUJOURS finir par mettre votre statement PUIS votre connexion à NULL
	public function getConnexion() {
		$connection = null;

		try {
			$connection = new PDO("pgsql:host=self::db_host;dbname=self::db_name;port=self::db_port", self::db_user, self::db_pass);
		} catch (PDOException $e) {}

		return $connection;
	}

	public function getFilmDAO() {
		return new FilmDAO(self::getInstance());
	}

	public static function f1TestDAO() {
		$film = self::getInstance()->getFilmDAO()->find(1);
		return print_r($film,true);
	}
}
