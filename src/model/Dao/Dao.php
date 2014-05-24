<?php

namespace model\Dao;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;

use \PDO;
use \PDOException;

class Dao
{
	private static $_instance = null;

	private $db_host;
	private $db_port;
	private $db_name;
	private $db_user;
	private $db_pass;

	public static function getInstance() {
		if (is_null(self::$_instance)) {
			$yaml = new Parser();

			try {
				$parameters = $yaml->parse(file_get_contents(__DIR__ . '/../../../app/config/parameters.yml'));
				self::$_instance = new Dao($parameters);
			} catch (ParseException $e) {
				die(printf("Unable to parse the YAML string: %s", $e->getMessage()));
			}
		}

		return self::$_instance;
	}

	public function __construct($parameters) {
		// echo '<pre>';
		// exit(var_dump($parameters));
		$this->db_host = $parameters['database']['db_host'];
		$this->db_port = $parameters['database']['db_port'];
		$this->db_name = $parameters['database']['db_name'];
		$this->db_user = $parameters['database']['db_user'];
		$this->db_pass = $parameters['database']['db_pass'];

		return $this;
	}

	//Quand vous récupéré une connexion, vous utiliser ensuite une statement
	//il faut TOUJOURS finir par mettre votre statement PUIS votre connexion à NULL
	public function getConnexion() {
		$connection = null;

		try {
			$connection = new PDO('pgsql:host=' . $this->db_host . ';dbname=' . $this->db_name . ';port=' . $this->db_port, $this->db_user, $this->db_pass);
		} catch (PDOException $e) {}

		return $connection;
	}

	public function getFilmDAO() {
		return new FilmDAO(self::getInstance());
	}
}
