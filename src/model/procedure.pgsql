CREATE OR REPLACE FUNCTION tauxOccupationSeance(dateHeure tseance.pk_timestamp_seance%TYPE, salle tseance.pkfk_nom_salle%TYPE) RETURNS float AS $$
DECLARE
    nbTiceketSeance float;
    nbPlaceSalle float;
BEGIN
    SELECT INTO nbPlaceSalle nb_place FROM tsalle WHERE pk_nom_salle = salle;
	IF NOT FOUND THEN
    RAISE EXCEPTION 'salle % not found', salle;
	END IF;
	
	SELECT INTO nbTiceketSeance count(pk_id_ticket) 
	FROM tticket 
	WHERE fk_nom_salle_seance = salle AND fk_timestamp_seance = dateHeure;
	IF NOT FOUND THEN
    RAISE EXCEPTION 'seance not found';
	END IF;
    return nbTiceketSeance*100/nbPlaceSalle;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION nbAbonne() RETURNS integer AS $$
DECLARE
    nbAbonne integer;
BEGIN
    SELECT INTO nbAbonne count(pkfk_id_personne) FROM tabonne;
	
    return nbAbonne;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION nbFilmSemaine(offsetSemaine integer) RETURNS integer AS $$
DECLARE
    nbFilm integer;
BEGIN
    SELECT INTO nbFilm count(*)
FROM tseance
WHERE pk_timestamp_seance
BETWEEN date_trunc('week', (NOW() + (offsetSemaine || ' weeks')::INTERVAL)) + INTERVAL '2 day'
AND date_trunc('week', (NOW() + (offsetSemaine+1 || ' weeks')::INTERVAL)) + INTERVAL '2 day';
	
    return nbFilm;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION totalEntreFilm(film tfilm.pk_id_film%TYPE) RETURNS integer AS $$
DECLARE
    entre integer;
BEGIN
	SELECT INTO entre count(*)
	FROM tseance s JOIN tticket t ON t.fk_timestamp_seance = s.pk_timestamp_seance AND t.fk_nom_salle_seance = s.pkfk_nom_salle
	WHERE fk_id_film = film; 
	
    return entre;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION revenueFilm(film tfilm.pk_id_film%TYPE) RETURNS float AS $$
DECLARE
    total float;
BEGIN
	SELECT INTO total sum(tar.tarif)
	FROM tseance s 
	JOIN tticket t ON t.fk_timestamp_seance = s.pk_timestamp_seance AND t.fk_nom_salle_seance = s.pkfk_nom_salle
	JOIN ttarif tar ON t.fk_nom_tarif = tar.pk_nom_tarif
WHERE fk_id_film = film; 
	
    return total;
END;
$$ LANGUAGE plpgsql;
