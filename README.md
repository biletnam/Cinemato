## NA17 - Cinématographe

---

### Manipulation des données avec Data Access Object

```
// Récupérer le repository des instances voulues [par exemple : Film]
$DAOFilm = DAO::getDAOFilm(); // Manipule toutes les instances de DAOFilm.

// SELECT
// Trouve le DAOFilm avec l'id 1 [enregistrement en base avec l'id 1]
$film = $DAOFilm->get(1);
// Trouve le DAOFilm avec l'id 1 [enregistrement en base avec l'id 1]
$film = $DAOFilm->search('toto');

// CREATE
$film = new Film();
$film->setName($name);
// Mise à jour de l'entité film concernée
$DAOFilm->create($film);

// UPDATE
// Modifier l'objet
$film->setName($newName);
// Mise à jour de l'entité film concernée
$DAOFilm->update($film);

// DELETE
// Supprime l'entité film concernée
$DAOFilm->delete($film);
```
