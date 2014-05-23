<?php

namespace model\Dao;

class DAO {
	private static $instance;
	private static final $cHost = "foo";
	private static final $cPort = "foo";
	private static final $cHost = "foo";
	private static final $cUser = "foo";
	private static final $cPass = "foo";
	private static function getInstance() {
		if ($instance == NULL)
			$this::$instance = new DAO ();
	}
	//Quand vous récupéré une connexion, vous utiliser ensuite une statement 
	//il faut TOUJOURS finir par mettre votre statement PUIS votre connexion à NULL
	public function getConnexion() {
		try {
			$conn = new PDO ( "pgsql:host=$cHost;dbname=$cHost", "$cUser", "$cPass" );
		} catch ( PDOException $e ) {
			$conn = null;
		}
		return $conn;
	}
	public function getFilmDAO() {
		return new FilmDAO ( $this::getInstance () );
	}
}