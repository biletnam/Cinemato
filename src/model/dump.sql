TRUNCATE tfilm * CASCADE;
TRUNCATE tgenre * CASCADE;
TRUNCATE tdistributeur * CASCADE;
TRUNCATE tseance * CASCADE;
TRUNCATE tsalle * CASCADE;
TRUNCATE tpersonne * CASCADE;
TRUNCATE tabonne * CASCADE;
TRUNCATE tvendeur * CASCADE;
TRUNCATE tproducteurs_film * CASCADE;
TRUNCATE trealisateurs_film * CASCADE;
TRUNCATE tticket * CASCADE;
TRUNCATE trechargement * CASCADE;
TRUNCATE ttarif * CASCADE;
TRUNCATE tproduit * CASCADE;
TRUNCATE tproduit_boisson * CASCADE;
TRUNCATE tproduit_alimentaire * CASCADE;
TRUNCATE tproduit_autre * CASCADE;
TRUNCATE tvente_produit * CASCADE;

DROP TABLE tfilm CASCADE;
DROP TABLE tgenre CASCADE;
DROP TABLE tdistributeur CASCADE;
DROP TABLE tseance CASCADE;
DROP TABLE tsalle CASCADE;
DROP TABLE tpersonne CASCADE;
DROP TABLE tabonne CASCADE;
DROP TABLE tvendeur CASCADE;
DROP TABLE tproducteurs_film CASCADE;
DROP TABLE trealisateurs_film CASCADE;
DROP TABLE tticket CASCADE;
DROP TABLE trechargement CASCADE;
DROP TABLE ttarif CASCADE;
DROP TABLE tproduit CASCADE;
DROP TABLE tproduit_boisson CASCADE;
DROP TABLE tproduit_alimentaire CASCADE;
DROP TABLE tproduit_autre CASCADE;
DROP TABLE tvente_produit CASCADE;

CREATE SEQUENCE sequence_film;

CREATE TABLE tfilm(
	pk_id_film				integer,
	titre					varchar(255),
	date_sortie				date,
	age_min 				integer,
	fk_nom_genre			varchar(255),
	fk_id_distributeur		integer
	);
CREATE TABLE tgenre(
	pk_nom_genre			varchar(255)
	);
CREATE TABLE tdistributeur(
	pk_id_distributeur 		integer,
	nom						varchar(255),
	prenom					varchar(255),
	adresse					varchar(255),
	tel						varchar(20)
	);
CREATE TABLE tseance(
	pk_timestamp_seance		timestamp,
	pkfk_nom_salle			varchar(255),
	pkfk_id_film			integer,
	doublage				varchar(255)
	);
CREATE TABLE tsalle(
	pk_nom_salle			varchar(255),
	nb_place				integer
	);
	
CREATE TABLE tpersonne(
	pk_id_personne			integer,
	nom						varchar(255),
	prenom					varchar(255)
	);

CREATE TABLE tabonne(
	pkfk_id_personne		integer,
	place_restante			integer
);

CREATE TABLE tvendeur(
	pkfk_id_personne		integer
);

CREATE TABLE tproducteurs_film(
	pkfk_id_film			integer,
	pkfk_id_personne		integer
);

CREATE TABLE trealisateurs_film(
	pkfk_id_film			integer,
	pkfk_id_personne		integer
);

CREATE TABLE tticket(
	pk_id_ticket			integer,
	timestamp_vente			timestamp,
	note					float,
	fk_timestamp_seance		timestamp,
	fk_nom_salle_seance		varchar(255),
	fk_id_personne_abonne	integer,
	fk_id_personne_vendeur	integer,
	fk_nom_tarif			varchar(255)
);

CREATE TABLE trechargement(
	pk_id_rechargement		integer,
	pkfk_id_personne_abonne	integer,
	nombre_place			integer,
	prix_unitaire			float
);

CREATE TABLE ttarif(
	pk_nom_tarif			varchar(255),
	tarif					float
);

CREATE TABLE tproduit(
	pk_code_barre_produit	integer,
	nom_produit				varchar(255),
	prix					float
);

CREATE TABLE tproduit_boisson(
	pkfk_code_barre_produit	integer
);

CREATE TABLE tproduit_alimentaire(
	pkfk_code_barre_produit	integer
);

CREATE TABLE tproduit_autre(
	pkfk_code_barre_produit	integer
);

CREATE TABLE tvente_produit(
	pkfk_code_barre			integer,
	pkfk_id_personne_vendeur	integer
);



CREATE OR REPLACE VIEW vvendeur AS
SELECT v.pkfk_id_personne, p.nom, p.prenom
FROM tpersonne p, tvendeur v
WHERE p.pk_id_personne = v.pkfk_id_personne;

CREATE OR REPLACE VIEW vabonne AS
SELECT a.pkfk_id_personne, p.nom, p.prenom , a.place_restante
FROM tpersonne p, tabonne a
WHERE p.pk_id_personne = a.pkfk_id_personne;

CREATE OR REPLACE VIEW vtproduit_boisson AS
SELECT p.pk_code_barre_produit, p.nom_produit, p.prix
FROM tproduit p, tproduit_boisson b
WHERE p.pk_code_barre_produit = b.pkfk_code_barre_produit;

CREATE OR REPLACE VIEW vproduit_alimentaire AS
SELECT p.pk_code_barre_produit, p.nom_produit, p.prix
FROM tproduit p, tproduit_alimentaire a
WHERE p.pk_code_barre_produit = a.pkfk_code_barre_produit;

CREATE OR REPLACE VIEW vproduit_autre AS
SELECT p.pk_code_barre_produit, p.nom_produit, p.prix
FROM tproduit p, tproduit_autre a
WHERE p.pk_code_barre_produit = a.pkfk_code_barre_produit;

INSERT INTO tgenre(pk_nom_genre)
VALUES('Horreur');
INSERT INTO tdistributeur(pk_id_distributeur, nom, prenom, adresse, tel) 
VALUES (1, 'KarimCorp', 'KarimCorpi', 'PARIS', '06LOL');
INSERT INTO tfilm(pk_id_film, titre, date_sortie, age_min,fk_nom_genre,fk_id_distributeur)
VALUES (1,'Karim a Compiegne',TIMESTAMP '2011-05-16 15:36:38', 18, 'Horreur', 1);