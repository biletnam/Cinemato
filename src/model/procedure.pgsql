CREATE OR REPLACE FUNCTION tauxOccupationSeance(idSeance tseance.pk_id_seance%TYPE) RETURNS float AS $$
DECLARE
    nbTicketSeance float;
    nbPlaceSalle float;
BEGIN

    SELECT INTO nbTicketSeance count(pk_id_ticket)
    FROM tticket
    WHERE fk_id_seance = idSeance;
    IF NOT FOUND THEN
        RAISE EXCEPTION 'Seance not found';
    END IF;

    SELECT INTO nbPlaceSalle tsalle.nb_place
    FROM tsalle
    JOIN tseance ON tseance.fk_nom_salle = tsalle.pk_nom_salle
    WHERE tseance.pk_id_seance = idSeance;

    IF NOT FOUND THEN
        RAISE EXCEPTION 'salle % not found', salle;
    END IF;
    return nbTicketSeance * 100 / nbPlaceSalle;
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
SELECT INTO nbFilm count(fk_id_film)
FROM ( select distinct fk_id_film FROM tseance
WHERE timestamp_seance
BETWEEN
date_trunc('week',
 (NOW() +
 CASE WHEN EXTRACT(DOW FROM NOW()) = 0 THEN (offsetSemaine || ' weeks')::INTERVAL
 WHEN EXTRACT(DOW FROM NOW()) = 1 THEN (offsetSemaine-1 || ' weeks')::INTERVAL
 WHEN EXTRACT(DOW FROM NOW()) = 2 THEN (offsetSemaine-1 || ' weeks')::INTERVAL
 WHEN EXTRACT(DOW FROM NOW()) = 3 THEN (offsetSemaine || ' weeks')::INTERVAL
 WHEN EXTRACT(DOW FROM NOW()) = 4 THEN (offsetSemaine || ' weeks')::INTERVAL
 WHEN EXTRACT(DOW FROM NOW()) = 5 THEN (offsetSemaine || ' weeks')::INTERVAL
 WHEN EXTRACT(DOW FROM NOW()) = 6 THEN (offsetSemaine || ' weeks')::INTERVAL
 END
 )) + INTERVAL '2 day'
AND
date_trunc('week',
 (NOW() +
 CASE WHEN EXTRACT(DOW FROM NOW()) = 0 THEN (offsetSemaine +1 || ' weeks')::INTERVAL
 WHEN EXTRACT(DOW FROM NOW()) = 1 THEN (offsetSemaine-1 +1 || ' weeks')::INTERVAL
 WHEN EXTRACT(DOW FROM NOW()) = 2 THEN (offsetSemaine-1 +1 || ' weeks')::INTERVAL
 WHEN EXTRACT(DOW FROM NOW()) = 3 THEN (offsetSemaine +1|| ' weeks')::INTERVAL
 WHEN EXTRACT(DOW FROM NOW()) = 4 THEN (offsetSemaine +1|| ' weeks')::INTERVAL
 WHEN EXTRACT(DOW FROM NOW()) = 5 THEN (offsetSemaine +1|| ' weeks')::INTERVAL
 WHEN EXTRACT(DOW FROM NOW()) = 6 THEN (offsetSemaine +1|| ' weeks')::INTERVAL
 END
 )) + INTERVAL '2 day') as s
 ;

    return nbFilm;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION nbSeanceSemaine(offsetSemaine integer) RETURNS integer AS $$
DECLARE
    nbFilm integer;
BEGIN
    SELECT INTO nbFilm count(fk_id_film)
FROM tseance
WHERE timestamp_seance
BETWEEN
date_trunc('week',
 (NOW() +
 CASE WHEN EXTRACT(DOW FROM NOW()) = 0 THEN (offsetSemaine || ' weeks')::INTERVAL
 WHEN EXTRACT(DOW FROM NOW()) = 1 THEN (offsetSemaine-1 || ' weeks')::INTERVAL
 WHEN EXTRACT(DOW FROM NOW()) = 2 THEN (offsetSemaine-1 || ' weeks')::INTERVAL
 WHEN EXTRACT(DOW FROM NOW()) = 3 THEN (offsetSemaine || ' weeks')::INTERVAL
 WHEN EXTRACT(DOW FROM NOW()) = 4 THEN (offsetSemaine || ' weeks')::INTERVAL
 WHEN EXTRACT(DOW FROM NOW()) = 5 THEN (offsetSemaine || ' weeks')::INTERVAL
 WHEN EXTRACT(DOW FROM NOW()) = 6 THEN (offsetSemaine || ' weeks')::INTERVAL
 END
 )) + INTERVAL '2 day'
AND
date_trunc('week',
 (NOW() +
 CASE WHEN EXTRACT(DOW FROM NOW()) = 0 THEN (offsetSemaine +1 || ' weeks')::INTERVAL
 WHEN EXTRACT(DOW FROM NOW()) = 1 THEN (offsetSemaine-1 +1 || ' weeks')::INTERVAL
 WHEN EXTRACT(DOW FROM NOW()) = 2 THEN (offsetSemaine-1 +1 || ' weeks')::INTERVAL
 WHEN EXTRACT(DOW FROM NOW()) = 3 THEN (offsetSemaine +1|| ' weeks')::INTERVAL
 WHEN EXTRACT(DOW FROM NOW()) = 4 THEN (offsetSemaine +1|| ' weeks')::INTERVAL
 WHEN EXTRACT(DOW FROM NOW()) = 5 THEN (offsetSemaine +1|| ' weeks')::INTERVAL
 WHEN EXTRACT(DOW FROM NOW()) = 6 THEN (offsetSemaine +1|| ' weeks')::INTERVAL
 END
 )) + INTERVAL '2 day';

    return nbFilm;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION totalEntreFilm(film tfilm.pk_id_film%TYPE) RETURNS integer AS $$
DECLARE
    entre integer;
BEGIN
	SELECT INTO entre count(*)
	FROM tseance s
    JOIN tticket t ON t.fk_id_seance = s.pk_id_seance
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
	JOIN tticket t ON t.fk_id_seance = s.pk_id_seance
	JOIN ttarif tar ON t.fk_nom_tarif = tar.pk_nom_tarif
WHERE fk_id_film = film;

    return total;
END;
$$ LANGUAGE plpgsql;
