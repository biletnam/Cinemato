<?php
namespace model\Dao;

use \PDO;
use model\Entite\ProduitVendeur;
use model\Entite\Personne;
use model\Entite\Produit;

class ProduitVendeurDAO
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

    public function create(&$ProduitVendeur)
    {
        $succes = false;
        $query1 = "select nextval('sequence_vente') as val";
        $query2 = "INSERT INTO tvente_produit(pk_id, fk_code_barre, fk_id_personne_vendeur, date_vente)" . " VALUES(:id, :produit, :vendeur, :date);";
        $connection = $this->getDao()->getConnexion();

        if (! is_null($connection)) {
            try {
                $statement = $connection->prepare($query1);
                $statement->execute();
                if ($donnees = $statement->fetch(PDO::FETCH_ASSOC)) {
                    $ProduitVendeur->setId($donnees['val']);
                }
                $statement = null;
                $statement = $connection->prepare($query2);
                $statement->execute(array(
                    'id' => $ProduitVendeur->getId(),
                    'produit' => $ProduitVendeur->getProduit()
                        ->getCodeBarre(),
                    'vendeur' => $ProduitVendeur->getVendeur()
                        ->getId(),
                    'date' => $ProduitVendeur->getDate()
                        ->format('Y-m-d H:i:s')
                ));
                $succes = true;
            } catch (\PDOException $e) {
                throw $e;
            }
        }

        return $succes;
    }

    public function find($id)
    {
        $produitVendeur = null;
        $query = 'SELECT pk_id as id, fk_code_barre as produit, fk_id_personne_vendeur as vendeur, date_vente as date' .
            ' FROM tvente_produit' .
            ' WHERE pk_id = :id';
        $connection = $this->getDao()->getConnexion();

        if (! is_null($connection)) {
            try {
                $statement = $connection->prepare($query);
                $statement->execute(array(
                    'id' => $id
                ));
                if ($donnees = $statement->fetch(PDO::FETCH_ASSOC)) {
                    $produitVendeur = $this->bind($donnees);
                    array_push($produitsVendeur, $produitVendeur);
                }
            } catch (\PDOException $e) {
                throw $e;
            }
        }
        return $produitVendeur;
    }

    public function findAllByProduit($produit)
    {
        $produitsVendeur = array();
        $query = 'SELECT pk_id as id, fk_code_barre as produit, fk_id_personne_vendeur as vendeur, date_vente as date' .
            ' FROM tvente_produit' .
            ' WHERE fk_code_barre = :produit';
        $connection = $this->getDao()->getConnexion();

        if (! is_null($connection)) {
            try {
                $statement = $connection->prepare($query);
                $statement->execute(array(
                    'produit' => $produit->getCodeBarre()
                ));
                while ($donnees = $statement->fetch(PDO::FETCH_ASSOC)) {
                    $produitVendeur = $this->bind($donnees);
                    array_push($produitsVendeur, $produitVendeur);
                }
            } catch (\PDOException $e) {
                throw $e;
            }
        }
        return $produitsVendeur;
    }

    public function findAllByVendeur($vendeur)
    {
        $produitsVendeur = array();
        $query = 'SELECT pk_id as id, fk_code_barre as produit, fk_id_personne_vendeur as vendeur, date_vente as date' .
            ' FROM tvente_produit' .
            ' WHERE fk_id_personne_vendeur = :vendeur';
        $connection = $this->getDao()->getConnexion();

        if (! is_null($connection)) {
            try {
                $statement = $connection->prepare($query);
                $statement->execute(array(
                    'vendeur' => $vendeur->getId()
                ));
                while ($donnees = $statement->fetch(PDO::FETCH_ASSOC)) {
                    $produitVendeur = $this->bind($donnees);
                    array_push($produitsVendeur, $produitVendeur);
                }
            } catch (\PDOException $e) {
                throw $e;
            }
        }
        return $produitsVendeur;
    }

    public function findAll()
    {
        $produitsVendeur = array();
        $query = 'SELECT pk_id as id, fk_code_barre as produit, fk_id_personne_vendeur as vendeur, date_vente as date' .
            ' FROM tvente_produit';
        $connection = $this->getDao()->getConnexion();

        if (! is_null($connection)) {
            try {
                $statement = $connection->prepare($query);
                $statement->execute(array());
                while ($donnees = $statement->fetch(PDO::FETCH_ASSOC)) {
                    $produitVendeur = $this->bind($donnees);
                    array_push($produitsVendeur, $produitVendeur);
                }
            } catch (\PDOException $e) {
                throw $e;
            }
        }
        return $produitsVendeur;
    }

    public function findAllBoissons()
    {
        $produitsVendeur = array();
        $query = 'SELECT vp.pk_id as id,' .
            ' vp.fk_code_barre as produit,' .
            ' vp.fk_id_personne_vendeur as vendeur,' .
            ' vp.date_vente as date' .
            ' FROM tvente_produit vp' .
            ' JOIN tproduit_boisson pb ON vp.fk_code_barre = pb.pkfk_code_barre_produit';
        $connection = $this->getDao()->getConnexion();

        if (! is_null($connection)) {
            try {
                $statement = $connection->prepare($query);
                $statement->execute(array());
                while ($donnees = $statement->fetch(PDO::FETCH_ASSOC)) {
                    $produitVendeur = $this->bind($donnees);
                    array_push($produitsVendeur, $produitVendeur);
                }
            } catch (\PDOException $e) {
                throw $e;
            }
        }

        return $produitsVendeur;
    }

    public function findAllAlimentaires()
    {
        $produitsVendeur = array();
        $query = 'SELECT vp.pk_id as id,' .
            ' vp.fk_code_barre as produit,' .
            ' vp.fk_id_personne_vendeur as vendeur,' .
            ' vp.date_vente as date' .
            ' FROM tvente_produit vp' .
            ' JOIN tproduit_alimentaire pa ON vp.fk_code_barre = pa.pkfk_code_barre_produit';
        $connection = $this->getDao()->getConnexion();

        if (! is_null($connection)) {
            try {
                $statement = $connection->prepare($query);
                $statement->execute(array());
                while ($donnees = $statement->fetch(PDO::FETCH_ASSOC)) {
                    $produitVendeur = $this->bind($donnees);
                    array_push($produitsVendeur, $produitVendeur);
                }
            } catch (\PDOException $e) {
                throw $e;
            }
        }

        return $produitsVendeur;
    }

    public function findAllAutres()
    {
        $produitsVendeur = array();
        $query = 'SELECT vp.pk_id as id,' .
            ' vp.fk_code_barre as produit,' .
            ' vp.fk_id_personne_vendeur as vendeur,' .
            ' vp.date_vente as date' .
            ' FROM tvente_produit vp' .
            ' JOIN tproduit_autre pa ON vp.fk_code_barre = pa.pkfk_code_barre_produit';
        $connection = $this->getDao()->getConnexion();

        if (! is_null($connection)) {
            try {
                $statement = $connection->prepare($query);
                $statement->execute(array());
                while ($donnees = $statement->fetch(PDO::FETCH_ASSOC)) {
                    $produitVendeur = $this->bind($donnees);
                    array_push($produitsVendeur, $produitVendeur);
                }
            } catch (\PDOException $e) {
                throw $e;
            }
        }

        return $produitsVendeur;
    }

    public function update($ProduitVendeur)
    {
        $succes = false;
        $query = 'UPDATE tvente_produit' . ' SET fk_code_barre = :produit, fk_id_personne_vendeur = :vendeur, date_vente = :date' . ' WHERE pk_id = :id';
        $connection = $this->getDao()->getConnexion();

        if (! is_null($connection)) {
            try {
                $statement = $connection->prepare($query);
                $statement->execute(array(
                    'id' => $ProduitVendeur->getId(),
                    'produit' => $ProduitVendeur->getProduit()
                        ->getCodeBarre(),
                    'vendeur' => $ProduitVendeur->getVendeur()
                        ->getId(),
                    'date' => $ProduitVendeur->getDate()
                        ->format('Y-m-d H:i:s')
                ));
                $succes = true;
            } catch (\PDOException $e) {
                throw $e;
            }
        }

        return $succes;
    }

    public function delete(&$ProduitVendeur)
    {
        $succes = false;
        $query = 'DELETE FROM tvente_produit' . ' WHERE pk_id = :id';
        $connection = $this->getDao()->getConnexion();

        if (! is_null($connection)) {
            try {
                $statement = $connection->prepare($query);
                $statement->execute(array(
                    'id' => $ProduitVendeur->getId()
                ));
                $succes = true;
                $ProduitVendeur = null;
            } catch (\PDOException $e) {
                throw $e;
            }
        }

        return $succes;
    }

    public function bind($donnees)
    {
        $produitVendeur = new ProduitVendeur();
        $produitVendeur->setId($donnees['id']);
        $produitVendeur->setDate(new \DateTime($donnees['date']));
        $produitVendeur->setProduit($this->getDao()->getProduitDao()->find($donnees['produit']));
        $produitVendeur->setVendeur($this->getDao()->getPersonneDao()->find($donnees['vendeur']));

        return $produitVendeur;
    }
}
