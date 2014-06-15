<?php
namespace model\Dao;

use \PDO;
use model\Entite\Personne;
use model\Entite\PersonneAbonne;
use model\Entite\PersonneVendeur;
use model\Entite\Rechargement;

class PersonneDAO
{

    private $dao;

    public function __construct($dao)
    {
        $this->dao = $dao;
    }

    private function getDao()
    {
        return $this->dao;
    }

    public function create(& $personne)
    {
        $success = false;

        $query = "select nextval('sequence_personne') as val";
        $query2 = "INSERT INTO tpersonne(pk_id_personne, nom, prenom)" . " VALUES(:id,:nom,:prenom)";
        $query3 = "INSERT INTO tabonne(pkfk_id_personne)" . " VALUES(:id)";
        $query4 = "INSERT INTO tvendeur(pkfk_id_personne)" . " VALUES(:id)";
        $connection = $this->getDao()->getConnexion();
        if (! is_null($connection)) {
            try {
                $statement = $connection->prepare($query);
                $statement->execute();

                if ($donnees = $statement->fetch(PDO::FETCH_ASSOC)) {
                    $personne->setId($donnees['val']);
                }
                $statement = null;
                if (! is_null($connection)) {
                    $statement = $connection->prepare($query2);
                    $success = $statement->execute(array(
                        'id' => $personne->getId(),
                        'nom' => $personne->getNom(),
                        'prenom' => $personne->getPrenom()
                    ));
                }
                $statement = null;
                if ($personne instanceof PersonneAbonne) {
                    if (! is_null($connection)) {
                        $statement = $connection->prepare($query3);
                        $success = $statement->execute(array(
                            'id' => $personne->getId(),
                            'nbPlace' => $personne->getPlaceRestante()
                        ));
                        foreach ($personne->getRecharges() as $recharge) {
                            if ($recharge->getId() == null) {
                                $this->getDao()
                                    ->getRechargementDAO()
                                    ->create($recharge, $personne);
                            } else
                                $this->getDao()
                                    ->getRechargementDAO()
                                    ->update($recharge);
                        }
                        $this->getDao()
                            ->getRechargementDAO()
                            ->deleteRechargeOrphelineUser($personne);
                    }
                }
                $statement = null;
                if ($personne instanceof PersonneVendeur) {
                    if (! is_null($connection)) {
                        $statement = $connection->prepare($query4);
                        $success = $statement->execute(array(
                            'id' => $personne->getId()
                        ));
                    }
                }
            } catch (\PDOException $e) {
                throw $e;
            }
        }

        return $success;
    }

    public function find($id)
    {
        $personne = null;

        $query = 'SELECT p.pk_id_personne as idP,' .
            ' p.nom as nom,' .
            ' p.prenom as prenom,' .
            ' v.pkfk_id_personne as idV,' .
            ' a.pkfk_id_personne as idA' .
            ' FROM tpersonne p' .
            ' LEFT JOIN tabonne a ON a.pkfk_id_personne = p.pk_id_personne' .
            ' LEFT JOIN tvendeur v ON v.pkfk_id_personne = p.pk_id_personne' .
            ' WHERE p.pk_id_personne = :id';

        $connection = $this->getDao()->getConnexion();
        if (! is_null($connection)) {
            try {
                $statement = $connection->prepare($query);
                $statement->execute(array(
                    'id' => $id
                ));

                if ($donnees = $statement->fetch(PDO::FETCH_ASSOC)) {
                    $personne = $this->bind($donnees);
                }
            } catch (\PDOException $e) {
                throw $e;
            }
        }
        return $personne;
    }

    public function findFirstVendeur()
    {
        $personne = null;

        $query = 'SELECT p.pk_id_personne as idP,' . 'p.nom as nom,' . ' p.prenom as prenom,' . 'v.pkfk_id_personne as idV' . ' FROM tpersonne p' . ' LEFT JOIN tvendeur v ON v.pkfk_id_personne = p.pk_id_personne ORDER BY p.pk_id_personne ASC LIMIT 1';
        $connection = $this->getDao()->getConnexion();

        if (! is_null($connection)) {
            try {
                $statement = $connection->prepare($query);
                $statement->execute();

                if ($donnees = $statement->fetch(PDO::FETCH_ASSOC)) {
                    $personne = $this->bind($donnees);
                }
            } catch (\PDOException $e) {
                throw $e;
            }
        }

        return $personne;
    }

    public function findAll()
    {
        $personnes = array();

        $query = 'SELECT p.pk_id_personne as idP,' .
            'p.nom as nom,' .
            ' p.prenom as prenom,' .
            ' v.pkfk_id_personne as idV,' .
            ' a.pkfk_id_personne as idA' .
            ' FROM tpersonne p' .
            ' LEFT JOIN tabonne a ON a.pkfk_id_personne = p.pk_id_personne' .
            ' LEFT JOIN tvendeur v ON v.pkfk_id_personne = p.pk_id_personne';

        $connection = $this->getDao()->getConnexion();

        if (! is_null($connection)) {
            try {
                $statement = $connection->prepare($query);
                $statement->execute();
                while ($donnees = $statement->fetch(PDO::FETCH_ASSOC)) {
                    $personne = $this->bind($donnees);
                    array_push($personnes, $personne);
                }
            } catch (\PDOException $e) {
                throw $e;
            }
        }
        return $personnes;
    }

    public function findAllVendeur()
    {
        $personnes = array();
        $query = 'SELECT p.pk_id_personne as idp,' . ' p.nom as nom,' . ' p.prenom as prenom,' . ' v.pkfk_id_personne as idv' . ' FROM tpersonne p' . ' JOIN tvendeur v ON v.pkfk_id_personne = p.pk_id_personne';
        $connection = $this->getDao()->getConnexion();
        if (! is_null($connection)) {
            try {
                $statement = $connection->prepare($query);
                $statement->execute();
                while ($donnees = $statement->fetch(PDO::FETCH_ASSOC)) {
                    $personne = $this->bind($donnees);
                    array_push($personnes, $personne);
                }
            } catch (\PDOException $e) {
                throw $e;
            }
        }
        return $personnes;
    }

    public function findAllAbonne()
    {
        $personnes = array();
        $query = 'SELECT p.pk_id_personne as idp,' . 'p.nom as nom,' . ' p.prenom as prenom,' . ' a.pkfk_id_personne as ida' . ' FROM tpersonne p' . ' JOIN tabonne a ON a.pkfk_id_personne = p.pk_id_personne';
        $connection = $this->getDao()->getConnexion();

        if (! is_null($connection)) {
            try {
                $statement = $connection->prepare($query);
                $statement->execute();
                while ($donnees = $statement->fetch(PDO::FETCH_ASSOC)) {
                    $personne = $this->bind($donnees);
                    array_push($personnes, $personne);
                }
            } catch (\PDOException $e) {
                throw $e;
            }
        }
        return $personnes;
    }

    public function update($personne)
    {
        $query = 'UPDATE tpersonne SET nom = :nom, prenom = :prenom WHERE pk_id_personne = :id';
        $connection = $this->getDao()->getConnexion();
        if ($personne instanceof PersonneAbonne) {
            if (! is_null($connection)) {
                foreach ($personne->getRecharges() as $recharge) {
                    if ($recharge->getId() == null) {
                        $this->getDao()
                            ->getRechargementDAO()
                            ->create($recharge, $personne);
                    } else
                        $this->getDao()
                            ->getRechargementDAO()
                            ->update($recharge);
                }
                $this->getDao()
                    ->getRechargementDAO()
                    ->deleteRechargeOrphelineUser($personne);
            }
        }
        $statement = null;
        $connection = null;
        $connection = $this->getDao()->getConnexion();
        if (! is_null($connection)) {
            try {
                $statement = $connection->prepare($query);
                $statement->execute(array(
                    'id' => $personne->getId(),
                    'nom' => $personne->getNom(),
                    'prenom' => $personne->getPrenom()
                ));
            } catch (\PDOException $e) {
                throw $e;
            }
        }
    }

    public function delete($personne)
    {
        $query = 'DELETE FROM tabonne WHERE pkfk_id_personne = :id';
        $query2 = 'DELETE FROM tvendeur WHERE pkfk_id_personne = :id';
        $query3 = 'DELETE FROM tpersonne WHERE pk_id_personne = :id';
        try {
            $connection = $this->getDao()->getConnexion();
            if ($personne instanceof PersonneAbonne) {
                if (! is_null($connection)) {
                    $statement = $connection->prepare($query);
                    $statement->execute(array(
                        'id' => $personne->getId()
                    ));
                    $statement = null;
                    $connection = null;
                }
                foreach ($personne->getRecharges() as $recharge) {
                    $this->getDao()
                        ->getRechargementDAO()
                        ->delete($recharge);
                }
            } else
                if ($personne instanceof PersonneVendeur) {
                    $connection = $this->getDao()->getConnexion();
                    if (! is_null($connection)) {
                        $statement = $connection->prepare($query2);
                        $statement->execute(array(
                            'id' => $personne->getId()
                        ));
                        $statement = null;
                        $connection = null;
                    }
                }
            $connection = $this->getDao()->getConnexion();
            if (! is_null($connection)) {
                $statement = $connection->prepare($query3);
                $statement->execute(array(
                    'id' => $personne->getId()
                ));
                $statement = null;
                $connection = null;
            }
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    public function bind($donnes)
    {
        $personne = null;

        if (array_key_exists('idv', $donnes) && $donnes['idv'] != null) {
            $personne = new PersonneVendeur();
            $personne->setId($donnes['idp']);
        } else {
            if (array_key_exists('ida', $donnes) && $donnes['ida'] != null) {
                $personne = new PersonneAbonne();
                $personne->setId($donnes['idp']);
                $personne->setRecharges($this->getDao()
                    ->getRechargementDAO()
                    ->findAllByAbonne($personne));
            } else {
                $personne = new Personne();
                $personne->setId($donnes['idp']);
            }
        }

        if ($personne) {
            $personne->setNom($donnes['nom']);
            $personne->setPrenom($donnes['prenom']);
        }

        return $personne;
    }
}

