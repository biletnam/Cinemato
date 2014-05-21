## NA17 - Cinématographe

---

### Installation de Silex et du projet

##### Configuration

VirtualHost : pour configurer un environnement comme en production, et être certain que l'URL Rewriting fonctionne
Ajouter les lignes suivantes dans un fichier conf d'apache (habituellement /Applications/MAMP/conf/apache/extra/httpd-vhosts.conf)

```
Listen 127.0.0.1:8001

<VirtualHost 127.0.0.1:8001>
  DocumentRoot "/Votre/dossier/web/cinemato"
  DirectoryIndex index.php

  <Directory "/Votre/dossier/web/cinemato">
    AllowOverride All
    Order allow,deny
    # Allow from all permettra notamment au .htaccess du projet de surcharger celui d'apache2 par défaut.
    Allow from All
  </Directory>
</VirtualHost>
```

##### Installation du projet

A. Clôner le dossier git ou récupérer les sources

```
cd /Votre/dossier/web/cinemato
git init
git remote add origin git.
git pull origin master
```

B. Mettre à jour les sources (c'est mieux de temps en temps, au cas-où...)

```
php composer.phar self-update
php composer.phar update
```

C. Accès

La configuration est terminée, vous devriez pouvoir accéder au site en tapant, dans un navigateur :
http://localhost:8001/

---


### Développement

#### Model

##### Entité

```
// Namespace : chemin du dossier de l'entité avec des \ comme séparateurs [CaseSensitive !!]
namespace model\Entite;

// Class proprement dite
class NewEntite
{
    private $id;

    private $isActive;

    // Un constructeur avec éventuellement des paramètres obligatoires, requis lors d'un appel :
    // $entite = new NewEntite();
    public function __construct() {
        // [set up default values]
        $this->isActive = true;

        return $this;
    }

    // Des Accesseurs (Get / Set)
    public function getId()
    {
        return $this->id;
    }

    public function setIsActive(boolean $isActive) {
        $this->isActive = $isActive;

        return $this;
    }

    public function getIsActive() {
        return $this->isActive();
    }

    // Eventuellement, des fonctions supplémentaires qui vous semblent utiles...
    // --> A documenter !!
    /**
     * Instance alias of getIsActive
     *
     * @return boolean
     */
    public function isActive() {
        return $this->getIsActive();
    }

    /**
     * Useless example method
     *
     * @param string $word
     * @return string
     */
    public function talk($word) {
        return 'I would just say : ' . $word;
    }
}

```

##### DAO

Is Coming Soon :)

#### Contrôleurs

##### Manipulation des données avec Data Access Object

Quelques exemples de manipulation des données DAO

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