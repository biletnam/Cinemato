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

DROP TABLE IF EXISTS tfilm CASCADE;
DROP TABLE IF EXISTS tgenre CASCADE;
DROP TABLE IF EXISTS tdistributeur CASCADE;
DROP TABLE IF EXISTS tseance CASCADE;
DROP TABLE IF EXISTS tsalle CASCADE;
DROP TABLE IF EXISTS tpersonne CASCADE;
DROP TABLE IF EXISTS tabonne CASCADE;
DROP TABLE IF EXISTS tvendeur CASCADE;
DROP TABLE IF EXISTS tproducteurs_film CASCADE;
DROP TABLE IF EXISTS trealisateurs_film CASCADE;
DROP TABLE IF EXISTS tticket CASCADE;
DROP TABLE IF EXISTS trechargement CASCADE;
DROP TABLE IF EXISTS ttarif CASCADE;
DROP TABLE IF EXISTS tproduit CASCADE;
DROP TABLE IF EXISTS tproduit_boisson CASCADE;
DROP TABLE IF EXISTS tproduit_alimentaire CASCADE;
DROP TABLE IF EXISTS tproduit_autre CASCADE;
DROP TABLE IF EXISTS tvente_produit CASCADE;

DROP SEQUENCE IF EXISTS sequence_film;
DROP SEQUENCE IF EXISTS sequence_distributeur;
DROP SEQUENCE IF EXISTS sequence_ticket;
DROP SEQUENCE IF EXISTS sequence_personne;
DROP SEQUENCE IF EXISTS sequence_rechargement;

CREATE SEQUENCE sequence_film;
CREATE SEQUENCE sequence_distributeur;
CREATE SEQUENCE sequence_ticket;
CREATE SEQUENCE sequence_personne;
CREATE SEQUENCE sequence_rechargement;

CREATE TABLE tfilm(
	pk_id_film				integer,
	titre					varchar(255),
	date_sortie				date,
	age_min 				integer,
	fk_nom_genre			varchar(255) NOT NULL,
	fk_id_distributeur		integer NOT NULL
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
	fk_id_film				integer NOT NULL,
	doublage				varchar(255)
	);
CREATE TABLE tsalle(
	pk_nom_salle			varchar(255),
	nb_place				integer NOT NULL
	);
CREATE TABLE tpersonne(
	pk_id_personne			integer,
	nom						varchar(255),
	prenom					varchar(255)
	);
CREATE TABLE tabonne(
	pkfk_id_personne		integer,
	place_restante			integer NOT NULL
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
	tarif					float NOT NULL
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

ALTER TABLE tfilm
ADD CONSTRAINT pk_tfilm PRIMARY KEY (pk_id_film);

ALTER TABLE tgenre
ADD CONSTRAINT pk_tgenre PRIMARY KEY (pk_nom_genre);

ALTER TABLE tdistributeur
ADD CONSTRAINT pk_tdistributeur PRIMARY KEY (pk_id_distributeur);

ALTER TABLE tseance
ADD CONSTRAINT pk_tseance PRIMARY KEY (pk_timestamp_seance, pkfk_nom_salle);

ALTER TABLE tsalle
ADD CONSTRAINT pk_tsalle PRIMARY KEY (pk_nom_salle);

ALTER TABLE tpersonne
ADD CONSTRAINT pk_tpersonne PRIMARY KEY(pk_id_personne);

ALTER TABLE tabonne
ADD CONSTRAINT pk_tabonne PRIMARY KEY(pkfk_id_personne);

ALTER TABLE tvendeur
ADD CONSTRAINT pk_tvendeur PRIMARY KEY (pkfk_id_personne);

ALTER TABLE tproducteurs_film
ADD CONSTRAINT pk_tproducteurs_film PRIMARY KEY (pkfk_id_film,pkfk_id_personne);

ALTER TABLE trealisateurs_film
ADD CONSTRAINT pk_trealisateurs_film PRIMARY KEY (pkfk_id_film,pkfk_id_personne);

ALTER TABLE tticket
ADD CONSTRAINT pk_tticket PRIMARY KEY (pk_id_ticket);

ALTER TABLE trechargement
ADD CONSTRAINT pk_trechargement PRIMARY KEY (pk_id_rechargement, pkfk_id_personne_abonne);

ALTER TABLE ttarif
ADD CONSTRAINT pk_ttarif PRIMARY KEY (pk_nom_tarif);

ALTER TABLE tproduit
ADD CONSTRAINT pk_tproduit PRIMARY KEY (pk_code_barre_produit);

ALTER TABLE tproduit_boisson
ADD CONSTRAINT pk_tproduit_boisson PRIMARY KEY (pkfk_code_barre_produit);

ALTER TABLE tproduit_alimentaire
ADD CONSTRAINT pk_tproduit_alimentaire PRIMARY KEY (pkfk_code_barre_produit);

ALTER TABLE tproduit_autre
ADD CONSTRAINT pk_tproduit_autre PRIMARY KEY (pkfk_code_barre_produit);

ALTER TABLE tvente_produit
ADD CONSTRAINT pk_tvente_produit PRIMARY KEY (pkfk_code_barre, pkfk_id_personne_vendeur);


ALTER TABLE tfilm
ADD CONSTRAINT fk_tfilm_tgenre FOREIGN KEY(fk_nom_genre) REFERENCES tgenre(pk_nom_genre),
ADD CONSTRAINT fk_tfilm_distributeur FOREIGN KEY(fk_id_distributeur) REFERENCES tdistributeur(pk_id_distributeur);

ALTER TABLE tseance
ADD CONSTRAINT fk_tseance_tsalle FOREIGN KEY (pkfk_nom_salle) REFERENCES tsalle(pk_nom_salle),
ADD CONSTRAINT fk_tseance_tfilm FOREIGN KEY (fk_id_film) REFERENCES tfilm(pk_id_film);

ALTER TABLE tabonne
ADD CONSTRAINT fk_tabonne_tpersonne FOREIGN KEY (pkfk_id_personne) REFERENCES tpersonne(pk_id_personne);

ALTER TABLE tvendeur
ADD CONSTRAINT fk_tvendeur_tpersonne FOREIGN KEY (pkfk_id_personne) REFERENCES tpersonne(pk_id_personne);

ALTER TABLE tproducteurs_film
ADD CONSTRAINT fk_tproducteurs_film_tfilm FOREIGN KEY  (pkfk_id_film) REFERENCES tfilm(pk_id_film),
ADD CONSTRAINT fk_tproducteurs_film_trealisateur FOREIGN KEY (pkfk_id_personne) REFERENCES tpersonne(pk_id_personne);

ALTER TABLE trealisateurs_film
ADD CONSTRAINT fk_trealisateurs_film_tfilm FOREIGN KEY  (pkfk_id_film) REFERENCES tfilm(pk_id_film),
ADD CONSTRAINT fk_trealisateurs_film_trealisateur FOREIGN KEY (pkfk_id_personne) REFERENCES tpersonne(pk_id_personne);

ALTER TABLE tticket
ADD CONSTRAINT fk_tticket_tseance FOREIGN KEY (fk_timestamp_seance, fk_nom_salle_seance) REFERENCES tseance(pk_timestamp_seance, pkfk_nom_salle),
ADD CONSTRAINT fk_tticket_tabonne FOREIGN KEY (fk_id_personne_abonne) REFERENCES tabonne(pkfk_id_personne),
ADD CONSTRAINT fk_tticket_tvendeur FOREIGN KEY (fk_id_personne_vendeur) REFERENCES tvendeur(pkfk_id_personne),
ADD CONSTRAINT fk_tticket_ttarif FOREIGN KEY (fk_nom_tarif) REFERENCES ttarif(pk_nom_tarif);

ALTER TABLE trechargement
ADD CONSTRAINT fk_trechargement FOREIGN KEY (pkfk_id_personne_abonne) REFERENCES tabonne(pkfk_id_personne);



ALTER TABLE tproduit_boisson
ADD CONSTRAINT fk_tproduit_boisson_tproduit FOREIGN KEY (pkfk_code_barre_produit) REFERENCES tproduit(pk_code_barre_produit);

ALTER TABLE tproduit_alimentaire
ADD CONSTRAINT fk_tproduit_alimentaire_tproduit FOREIGN KEY (pkfk_code_barre_produit) REFERENCES tproduit(pk_code_barre_produit);

ALTER TABLE tproduit_autre
ADD CONSTRAINT fk_tproduit_autre_tproduit FOREIGN KEY (pkfk_code_barre_produit) REFERENCES tproduit(pk_code_barre_produit);


ALTER TABLE tvente_produit
ADD CONSTRAINT fk_tvente_produit_tproduit FOREIGN KEY (pkfk_code_barre) REFERENCES tproduit(pk_code_barre_produit),
ADD CONSTRAINT fk_tvente_produit_tvendeur FOREIGN KEY (pkfk_id_personne_vendeur) REFERENCES tvendeur(pkfk_id_personne);




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
INSERT INTO tgenre(pk_nom_genre)
VALUES('Comédie');
INSERT INTO tgenre(pk_nom_genre)
VALUES('Romance');
INSERT INTO tgenre(pk_nom_genre)
VALUES('Action');

INSERT INTO tdistributeur(pk_id_distributeur, nom, prenom, adresse, tel)
VALUES (nextval('sequence_distributeur'), 'Mars Distribution', '', '14, rue de Colonel Moutarde 75014 PARIS', '0145875522');
INSERT INTO tdistributeur(pk_id_distributeur, nom, prenom, adresse, tel)
VALUES (nextval('sequence_distributeur'), 'Studio Canal', '', '1, avenue des Champs-Elysées 75008 PARIS', '015684955');
INSERT INTO tdistributeur(pk_id_distributeur, nom, prenom, adresse, tel)
VALUES (nextval('sequence_distributeur'), 'Studios Compiègne', '', 'COMPIEGNE', '0345885545');
INSERT INTO tdistributeur(pk_id_distributeur, nom, prenom, adresse, tel)
VALUES (nextval('sequence_distributeur'), 'Lucas Distributions', '', 'Hollywood - Los-Angeles', '147785566');

INSERT INTO tfilm(pk_id_film, titre, date_sortie, age_min,fk_nom_genre,fk_id_distributeur)
VALUES (nextval('sequence_film'),'Johnny English',TIMESTAMP '2003-07-23', 3, 'Comédie', 1);
INSERT INTO tfilm(pk_id_film, titre, date_sortie, age_min,fk_nom_genre,fk_id_distributeur)
VALUES (nextval('sequence_film'),'Love Actualy',TIMESTAMP '2003-12-03', 12, 'Romance', 1);
INSERT INTO tfilm(pk_id_film, titre, date_sortie, age_min,fk_nom_genre,fk_id_distributeur)
VALUES (nextval('sequence_film'),'Rough Fight',TIMESTAMP '2010-12-03', 25, 'Horreur', 3);
INSERT INTO tfilm(pk_id_film, titre, date_sortie, age_min,fk_nom_genre,fk_id_distributeur)
VALUES (nextval('sequence_film'),'Le dépeceur de l oise',TIMESTAMP '2008-08-03', 18, 'Horreur', 3);

INSERT INTO tpersonne(pk_id_personne,nom,prenom)
VALUES(nextval('sequence_personne'),'Lamouri','Karim');
INSERT INTO tpersonne(pk_id_personne,nom,prenom)
VALUES(nextval('sequence_personne'),'Etienne','Homer');
INSERT INTO tpersonne(pk_id_personne,nom,prenom)
VALUES(nextval('sequence_personne'),'Charles','Herlin');
INSERT INTO tpersonne(pk_id_personne,nom,prenom)
VALUES(nextval('sequence_personne'),'Florent','Schildknecht');
INSERT INTO tpersonne(pk_id_personne,nom,prenom)
VALUES(nextval('sequence_personne'),'Thomas','Langmann');
INSERT INTO tpersonne(pk_id_personne,nom,prenom)
VALUES(nextval('sequence_personne'),'Stéphane','Crozat');
INSERT INTO tpersonne(pk_id_personne,nom,prenom)
VALUES(nextval('sequence_personne'),'Paulo','Branco');
INSERT INTO tpersonne(pk_id_personne,nom,prenom)
VALUES(nextval('sequence_personne'),'Quentin','Tarantino');
INSERT INTO tpersonne(pk_id_personne,nom,prenom)
VALUES(nextval('sequence_personne'),'Ryan','Gosling');
INSERT INTO tpersonne(pk_id_personne,nom,prenom)
VALUES(nextval('sequence_personne'),'Scarlett','Johansson');
INSERT INTO tpersonne(pk_id_personne,nom,prenom)
VALUES(nextval('sequence_personne'),'Françis Ford','Coppola');
INSERT INTO tpersonne(pk_id_personne,nom,prenom)
VALUES(nextval('sequence_personne'),'Uma','Thurman');
INSERT INTO tpersonne(pk_id_personne,nom,prenom)
VALUES(nextval('sequence_personne'),'Rowan','Atkinson');

INSERT INTO tabonne(pkfk_id_personne, place_restante)
VALUES(1, 99);
INSERT INTO tabonne(pkfk_id_personne, place_restante)
VALUES(3, 50);

INSERT INTO tvendeur(pkfk_id_personne)
VALUES(2);
INSERT INTO tvendeur(pkfk_id_personne)
VALUES(4);

INSERT INTO ttarif(pk_nom_tarif, tarif)
VALUES('Normal', 8.50);
INSERT INTO ttarif(pk_nom_tarif, tarif)
VALUES('Etudiant', 6.50);
INSERT INTO ttarif(pk_nom_tarif, tarif)
VALUES('Retraité', 5.50);
INSERT INTO ttarif(pk_nom_tarif, tarif)
VALUES('Moins de 10ans', 4.25);

INSERT INTO tsalle(pk_nom_salle, nb_place)
VALUES('Salle Zinédine Zidane', 250);
INSERT INTO tsalle(pk_nom_salle, nb_place)
VALUES('Salle Lino Ventura', 240);
INSERT INTO tsalle(pk_nom_salle, nb_place)
VALUES('Salle Guynemer', 190);

INSERT INTO tseance(pk_timestamp_seance, pkfk_nom_salle, fk_id_film, doublage)
VALUES(TIMESTAMP '2014-06-26 18:00:00', 'Salle Zinédine Zidane', 2, 'Français');
INSERT INTO tseance(pk_timestamp_seance, pkfk_nom_salle, fk_id_film, doublage)
VALUES(TIMESTAMP '2014-06-24 14:00:00', 'Salle Zinédine Zidane', 3, 'Aucun');
INSERT INTO tseance(pk_timestamp_seance, pkfk_nom_salle, fk_id_film, doublage)
VALUES(TIMESTAMP '2014-06-22 10:30:00', 'Salle Lino Ventura', 1, 'Français');
INSERT INTO tseance(pk_timestamp_seance, pkfk_nom_salle, fk_id_film, doublage)
VALUES(TIMESTAMP '2014-06-24 14:00:00', 'Salle Guynemer', 4, 'Aucun');
INSERT INTO tseance(pk_timestamp_seance, pkfk_nom_salle, fk_id_film, doublage)
VALUES(TIMESTAMP '2014-06-24 21:00:00', 'Salle Lino Ventura', 4, 'Aucun');
INSERT INTO tseance(pk_timestamp_seance, pkfk_nom_salle, fk_id_film, doublage)
VALUES(TIMESTAMP '2014-06-26 21:00:00', 'Salle Lino Ventura', 3, 'Aucun');
INSERT INTO tseance(pk_timestamp_seance, pkfk_nom_salle, fk_id_film, doublage)
VALUES(TIMESTAMP '2014-06-26 18:00:00', 'Salle Lino Ventura', 4, 'Aucun');

INSERT INTO tproducteurs_film(pkfk_id_film, pkfk_id_personne)
VALUES( 1, 7);
INSERT INTO tproducteurs_film(pkfk_id_film, pkfk_id_personne)
VALUES( 2, 5);
INSERT INTO tproducteurs_film(pkfk_id_film, pkfk_id_personne)
VALUES( 3, 5);
INSERT INTO tproducteurs_film(pkfk_id_film, pkfk_id_personne)
VALUES( 4, 7);

INSERT INTO trealisateurs_film(pkfk_id_film, pkfk_id_personne)
VALUES( 1, 8);
INSERT INTO trealisateurs_film(pkfk_id_film, pkfk_id_personne)
VALUES( 2, 11);
INSERT INTO trealisateurs_film(pkfk_id_film, pkfk_id_personne)
VALUES( 3, 8);
INSERT INTO trealisateurs_film(pkfk_id_film, pkfk_id_personne)
VALUES( 4, 11);

INSERT INTO tticket(pk_id_ticket, timestamp_vente, note, fk_timestamp_seance, fk_nom_salle_seance, fk_id_personne_abonne, fk_id_personne_vendeur, fk_nom_tarif)
VALUES( nextval('sequence_ticket'), '2014-06-26 17:05:00', 12.0, TIMESTAMP '2014-06-26 18:00:00', 'Salle Zinédine Zidane' , 1, 2, 'Normal');
INSERT INTO tticket(pk_id_ticket, timestamp_vente, note, fk_timestamp_seance, fk_nom_salle_seance, fk_id_personne_abonne, fk_id_personne_vendeur, fk_nom_tarif)
VALUES( nextval('sequence_ticket'), '2014-06-24 17:41:00', 19.88, TIMESTAMP '2014-06-26 18:00:00', 'Salle Zinédine Zidane' , 1, 4, 'Etudiant');
INSERT INTO tticket(pk_id_ticket, timestamp_vente, note, fk_timestamp_seance, fk_nom_salle_seance, fk_id_personne_abonne, fk_id_personne_vendeur, fk_nom_tarif)
VALUES( nextval('sequence_ticket'), '2014-06-24 14:40:00', 4.2, TIMESTAMP '2014-06-24 21:00:00', 'Salle Lino Ventura' , NULL, 2, 'Etudiant');
INSERT INTO tticket(pk_id_ticket, timestamp_vente, note, fk_timestamp_seance, fk_nom_salle_seance, fk_id_personne_abonne, fk_id_personne_vendeur, fk_nom_tarif)
VALUES( nextval('sequence_ticket'), '2014-06-24 20:59:00', 15, TIMESTAMP '2014-06-24 21:00:00', 'Salle Lino Ventura' , NULL, 2, 'Normal');
INSERT INTO tticket(pk_id_ticket, timestamp_vente, note, fk_timestamp_seance, fk_nom_salle_seance, fk_id_personne_abonne, fk_id_personne_vendeur, fk_nom_tarif)
VALUES( nextval('sequence_ticket'), '2014-06-24 18:00:00', 20, TIMESTAMP '2014-06-24 21:00:00', 'Salle Lino Ventura' , NULL, 2, 'Retraité');
INSERT INTO tticket(pk_id_ticket, timestamp_vente, note, fk_timestamp_seance, fk_nom_salle_seance, fk_id_personne_abonne, fk_id_personne_vendeur, fk_nom_tarif)
VALUES( nextval('sequence_ticket'), '2014-06-26 18:00:00', 8.3, TIMESTAMP '2014-06-26 21:00:00', 'Salle Lino Ventura' ,3, 4, 'Retraité');
INSERT INTO tticket(pk_id_ticket, timestamp_vente, note, fk_timestamp_seance, fk_nom_salle_seance, fk_id_personne_abonne, fk_id_personne_vendeur, fk_nom_tarif)
VALUES( nextval('sequence_ticket'), '2014-06-26 20:00:00', 7.58, TIMESTAMP '2014-06-26 21:00:00', 'Salle Lino Ventura' , NULL, 4, 'Normal');
INSERT INTO tticket(pk_id_ticket, timestamp_vente, note, fk_timestamp_seance, fk_nom_salle_seance, fk_id_personne_abonne, fk_id_personne_vendeur, fk_nom_tarif)
VALUES( nextval('sequence_ticket'), '2014-06-26 21:02:00', 18.99, TIMESTAMP '2014-06-26 21:00:00', 'Salle Lino Ventura' , 3, 2, 'Normal');
INSERT INTO tticket(pk_id_ticket, timestamp_vente, note, fk_timestamp_seance, fk_nom_salle_seance, fk_id_personne_abonne, fk_id_personne_vendeur, fk_nom_tarif)
VALUES( nextval('sequence_ticket'), '2014-06-24 13:45:00', 12.6, TIMESTAMP '2014-06-24 14:00:00', 'Salle Guynemer' , NULL, 4, 'Retraité');

INSERT INTO trechargement( pk_id_rechargement, pkfk_id_personne_abonne, nombre_place, prix_unitaire)
VALUES( nextval('sequence_rechargement'), 1, 10, 4.5);
INSERT INTO trechargement( pk_id_rechargement, pkfk_id_personne_abonne, nombre_place, prix_unitaire)
VALUES( nextval('sequence_rechargement'), 1, 15, 4.5);
INSERT INTO trechargement( pk_id_rechargement, pkfk_id_personne_abonne, nombre_place, prix_unitaire)
VALUES( nextval('sequence_rechargement'), 3, 25, 4.40);

INSERT INTO tproduit(pk_code_barre_produit, nom_produit, prix)
VALUES( 232, 'Glace vanille', 3);
INSERT INTO tproduit(pk_code_barre_produit, nom_produit, prix)
VALUES( 451, 'Barre chocolatée', 2.5);
INSERT INTO tproduit(pk_code_barre_produit, nom_produit, prix)
VALUES( 254, 'Pop-Corn', 3.25);
INSERT INTO tproduit(pk_code_barre_produit, nom_produit, prix)
VALUES( 785, 'Cola-Coca', 2.2);
INSERT INTO tproduit(pk_code_barre_produit, nom_produit, prix)
VALUES( 633, 'Chouffe', 3.1);
INSERT INTO tproduit(pk_code_barre_produit, nom_produit, prix)
VALUES( 663, 'Lunette 3D', 4);

INSERT INTO tproduit_boisson(pkfk_code_barre_produit)
VALUES(633);
INSERT INTO tproduit_boisson(pkfk_code_barre_produit)
VALUES(785);
INSERT INTO tproduit_alimentaire(pkfk_code_barre_produit)
VALUES(232);
INSERT INTO tproduit_alimentaire(pkfk_code_barre_produit)
VALUES(451);
INSERT INTO tproduit_alimentaire(pkfk_code_barre_produit)
VALUES(254);
INSERT INTO tproduit_autre(pkfk_code_barre_produit)
VALUES(663);

INSERT INTO tvente_produit(pkfk_code_barre, pkfk_id_personne_vendeur)
VALUES( 633, 2);
INSERT INTO tvente_produit(pkfk_code_barre, pkfk_id_personne_vendeur)
VALUES( 785, 2);
INSERT INTO tvente_produit(pkfk_code_barre, pkfk_id_personne_vendeur)
VALUES( 663, 4);
INSERT INTO tvente_produit(pkfk_code_barre, pkfk_id_personne_vendeur)
VALUES( 785, 4);
INSERT INTO tvente_produit(pkfk_code_barre, pkfk_id_personne_vendeur)
VALUES( 451, 4);
INSERT INTO tvente_produit(pkfk_code_barre, pkfk_id_personne_vendeur)
VALUES( 451, 2);
INSERT INTO tvente_produit(pkfk_code_barre, pkfk_id_personne_vendeur)
VALUES( 663, 2);




