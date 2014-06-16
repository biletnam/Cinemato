<?php

namespace model\Dao;

use \DateTime;

use \PDO;
use \PDOException;

use model\Entite\Seance;
use model\Entite\Ticket;

class TicketDAO
{
    private $dao;

    public function __construct($dao) {
        $this->dao = $dao;
    }

    private function getDao() {
        return $this->dao;
    }

    public function find($id) {
        $ticket = null;

        $connection = $this->getDao()->getConnexion();

        if (!is_null($connection)) {
            $query = "SELECT * FROM tticket WHERE pk_id_ticket = :id";

            try {
                $statement = $connection->prepare($query);
                $statement->execute(array(
                    'id' => $id
                ));

                if ($donnees = $statement->fetch(PDO::FETCH_ASSOC)) {
                    $ticket = $this->bind($donnees);
                }
            }
            catch (PDOException $e) {
                throw $e;
            }
        }


        return $ticket;
    }

    public function findAll() {
    	$tickets = array ();
    	$connection = $this->getDao ()->getConnexion ();

    	if (! is_null ( $connection )) {
            $query = 'SELECT * FROM tticket';

    		try {
    			$statement = $connection->prepare ( $query );
    			$statement->execute ( array () );
    			while ( $donnees = $statement->fetch ( PDO::FETCH_ASSOC ) ) {
    				$ticket = $this->bind ( $donnees );
    				array_push ( $tickets, $ticket );
    			}
    		} catch (PDOException $e) {
    			throw $e;
    		}
    	}
    	return $tickets;
    }

    public function create(&$ticket) {
        $success = false;

    	$connection = $this->getDao()->getConnexion();

        if (! is_null ( $connection )) {
            $query = 'INSERT INTO tticket(pk_id_ticket, timestamp_vente, note, fk_id_seance, fk_id_personne_abonne, fk_id_personne_vendeur, fk_nom_tarif) VALUES(nextval(\'sequence_ticket\'), :dateVente, :note, :seance, :abonne, :vendeur, :tarif)';

            try {
                $statement = $connection->prepare($query);
                $success = $statement->execute ( array (
                    'dateVente' => $ticket->getDateDeVente()->format('Y-m-d H:i:s'),
                    'note' => $ticket->getNote(),
                    'seance' => $ticket->getSeance()->getId(),
                    'abonne' => ($ticket->getAbonne() ? $ticket->getAbonne()->getId() : null),
                    'vendeur' => $ticket->getVendeur()->getId(),
                    'tarif' => $ticket->getTarif()->getNom()
                ));
            } catch (PDOException $e) {
                throw $e;
            }
        }

        return $success;
    }

    public function update($ticket) {
        $success = false;
    	$connection = $this->getDao ()->getConnexion ();

    	if (! is_null ( $connection )) {
            $query = 'UPDATE tticket SET timestamp_vente = :dateVente, note = :note, fk_id_seance = :seance, fk_id_personne_abonne = :abonne, fk_id_personne_vendeur = :vendeur, fk_nom_tarif = :tarif WHERE pk_id_ticket = :id';

            try {
    			$statement = $connection->prepare ( $query );
    			$success = $statement->execute ( array (
					'id' => $ticket->getId(),
					'dateVente' => $ticket->getDateDeVente()->format('Y-m-d H:i:s'),
					'note' => $ticket->getNote(),
                    'seance' => $ticket->getSeance()->getId(),
                    'abonne' => ($ticket->getAbonne() ? $ticket->getAbonne()->getId() : null),
					'vendeur' => $ticket->getVendeur()->getId(),
                    'tarif' => $ticket->getTarif()->getNom()
    			) );
    		} catch (PDOException $e) {
    			throw $e;
    		}
    	}

        return $success;
    }
    public function delete($ticket) {
        $success = false;
    	$connection = $this->getDao ()->getConnexion ();

        if (! is_null ( $connection )) {
            $query = 'DELETE FROM tticket WHERE pk_id_ticket = :id ';

            try {
                $statement = $connection->prepare ( $query );
                $success = $statement->execute ( array (
                    'id' => $ticket->getId()
                ));
            } catch (PDOException $e ) {
                throw $e;
            }
        }

        return $success;
    }

    public function bind($donnees) {
        $ticket = new Ticket();
        $ticket->setId($donnees['pk_id_ticket']);
        $ticket->setDateDeVente(new DateTime($donnees['timestamp_vente']));

        $ticket->setNote($donnees['note']);

        if (!is_null($donnees['fk_id_seance'])) {
            $ticket->setSeance($this->getDao()->getSeanceDAO()->find($donnees['fk_id_seance']));
        }

        if (!is_null($donnees['fk_id_personne_abonne'])) {
           $ticket->setAbonne($this->getDao()->getPersonneDAO()->find($donnees['fk_id_personne_abonne']));
        }

        if (!is_null($donnees['fk_id_personne_vendeur'])) {
           $ticket->setVendeur($this->getDao()->getPersonneDAO()->find($donnees['fk_id_personne_vendeur']));
        }

        if (!is_null($donnees['fk_nom_tarif'])) {
           $ticket->setTarif($this->getDao()->getTarifDAO()->find($donnees['fk_nom_tarif']));
        }

        return $ticket;
    }
}
