# MediatekFormation — BTS SIO SLAM

> Ce dépôt est un fork du dépôt d'origine disponible ici :  
> **https://github.com/CNED-SLAM/mediatekformation**  
> Le readme d'origine contient la présentation complète de l'application d'origine.

## Présentation
Ce site, développé avec Symfony 6.4, est basé sur l'application MediatekFormation d'origine.
Des fonctionnalités ont été ajoutées au front office et un back office complet a été développé.

---

## Fonctionnalités ajoutées au front office

### Nombre de formations par playlist
Une colonne "nb formations" a été ajoutée dans la page des playlists. Elle affiche le nombre
de formations contenues dans chaque playlist, avec possibilité de trier par ordre croissant
ou décroissant. Le nombre de formations est également affiché dans la page de détail d'une playlist.

[ CAPTURE : Page /playlists avec la colonne nb formations et les boutons de tri — rapport Tache_2M1 ]

---

## Back office

Le back office est accessible en ajoutant `/admin` à la fin de l'URL.
L'accès est sécurisé par un identifiant et un mot de passe.

[ CAPTURE : Page de connexion /admin — rapport Tache_4M2 ]

### Gestion des formations
Cette page permet de lister, ajouter, modifier et supprimer des formations.
Il est possible de trier par titre, playlist et date, et de filtrer par titre, playlist et catégorie.

[ CAPTURE : Page back office formations avec liste et boutons — rapport Tache_1M2 ]

### Gestion des playlists
Cette page permet de lister, ajouter, modifier et supprimer des playlists.
La suppression n'est possible que si la playlist ne contient aucune formation.

[ CAPTURE : Page back office playlists — rapport Tache_2M2 ]

### Gestion des catégories
Cette page permet de lister, ajouter et supprimer des catégories.
La suppression n'est possible que si la catégorie n'est associée à aucune formation.

[ CAPTURE : Page back office catégories — rapport Tache_3M2 ]

---

## Installation en local

Vérifier que Composer, Git et XAMPP (ou équivalent) sont installés sur l'ordinateur.

Cloner le dépôt dans le dossier `htdocs` de XAMPP :
```bash
git clone https://github.com/orient75015/mediatekformation-bts-sio.git mediatekformation
```

Installer les dépendances :
```bash
composer install
```

Créer un fichier `.env.local` en racine du projet :
