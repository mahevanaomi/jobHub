# JobHub

## Vue d'ensemble

JobHub est maintenant une application PHP/MySQL fonctionnelle pour :

- inscription candidat et entreprise
- connexion sécurisée
- gestion de profils
- publication et édition d'offres
- candidature à une offre
- dashboards candidat et entreprise
- formulaire de contact persistant

L'architecture repose sur :

- `app/bootstrap.php` pour charger l'application
- `app/repository.php` pour les accès base
- `app/auth.php` pour l'authentification
- `app/views/` pour le layout partagé

## Installation

1. Créer la base et importer le schéma :

```bash
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS recrutement CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -p recrutement < DB/recrutement.sql
```

2. Configurer si besoin les variables d'environnement :

```bash
export JOBHUB_DB_HOST=127.0.0.1
export JOBHUB_DB_PORT=3306
export JOBHUB_DB_NAME=recrutement
export JOBHUB_DB_USER=root
export JOBHUB_DB_PASS=
```

3. Lancer le serveur PHP depuis le dossier `jobHub` :

```bash
php -S localhost:8000
```

4. Ouvrir :

```text
http://localhost:8000/index.php
```

## Comptes démo

- administrateur : `admin@jobhub.cm`
- entreprise : `demo@jobhub.cm`
- candidat : `talent@jobhub.cm`
- mot de passe : `password`

## Dashboards

- **Admin** (`/views/dashboard-admin.php`) : statistiques globales, gestion utilisateurs/offres/candidatures/messages, pipeline, activité récente, top entreprises, répartition par catégorie/ville
- **Entreprise** (`/views/dashboard-entreprise.php`) : KPIs recrutement, pipeline visuel, performance des offres, gestion candidatures, score marque employeur, analytics
- **Candidat** (`/views/dashboard-candidat.php`) : suivi candidatures par statut, timeline, taux de réponse, complétude profil détaillée, offres recommandées avec compatibilité skills

## Limites restantes

- l'envoi de CV et les uploads fichiers ne sont pas encore branchés
- les emails automatiques ne sont pas encore implémentés
