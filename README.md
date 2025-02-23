# Arti - Plateforme de Gestion des Artistes et de leurs Œuvres

## Description du projet

**Arti** est une plateforme web innovante dédiée à la gestion des artistes, de leurs œuvres et de leur interaction avec la communauté. Ce projet permet non seulement aux artistes de créer et de gérer leur profil, mais aussi d'interagir de manière dynamique avec leurs œuvres et leur audience. Conçu pour simplifier et enrichir l'expérience des créateurs, **Arti** intègre des fonctionnalités puissantes pour faciliter la gestion des œuvres, des statistiques détaillées et la communication en temps réel avec les utilisateurs.

Ce système robuste est construit sur **Symfony**, en utilisant les meilleures pratiques de développement web et une architecture orientée services. **Arti** vise à transformer la manière dont les artistes partagent, gèrent et interagissent avec leurs créations et leur public.

## Fonctionnalités principales

### 1. Gestion des profils d'artistes
- **Création et personnalisation du profil** : Les artistes peuvent créer un profil complet avec des informations biographiques, des photos et des liens vers leurs réseaux sociaux.
- **Modification dynamique** : Mise à jour facile des informations personnelles, bio, et photos.
- **Tableau de bord interactif** : Une vue d'ensemble permettant aux artistes de voir leurs performances (œuvres, likes, vues).

### 2. Gestion des œuvres et créations
- **Téléchargement et gestion d'œuvres** : Ajout d'images, descriptions et catégories d'œuvres.
- **Mise à jour et suppression** : Facilité de mise à jour et de suppression des œuvres.
- **Gestion des catégories** : Les œuvres sont classées par catégories (peinture, sculpture, photographie, etc.) pour faciliter la navigation.
- **Système de visibilité** : Choix de la visibilité publique ou restreinte sur les œuvres.

### 3. Interaction avec la communauté
- **Commentaires et discussions** : Les utilisateurs peuvent commenter et échanger avec les artistes directement sur leurs œuvres.
- **Messages privés** : Communication directe entre les artistes et les utilisateurs via un système de messagerie privé.
- **Suivi des artistes** : Les utilisateurs peuvent suivre leurs artistes préférés pour recevoir des mises à jour sur leurs nouvelles œuvres.

### 4. Statistiques et analytics avancées
- **Suivi des performances des œuvres** : Visualisation des vues, des likes, des commentaires et des partages pour chaque œuvre.
- **Statistiques globales** : Données sur la popularité de l'artiste et l’engagement de son public.
- **Graphiques dynamiques** : Utilisation de graphiques interactifs pour représenter les performances.

### 5. Sécurité et permissions
- **Gestion des accès** : Différenciation entre les utilisateurs réguliers et les administrateurs/artistes pour sécuriser les fonctionnalités critiques.
- **Validation des actions** : Système de validation pour les actions importantes (modification du profil, publication d'œuvres, etc.).

## Architecture technique

### Backend
- **Symfony** : Framework PHP solide, basé sur le modèle MVC, permettant une organisation claire du code et une extensibilité facile.
- **Doctrine ORM** : Gestion efficace des bases de données relationnelles avec des entités et des relations bien définies.
- **SecurityBundle** : Système de sécurité pour l'authentification et l'autorisation des utilisateurs, garantissant un contrôle d'accès rigoureux.

### Frontend
- **Twig** : Moteur de templates puissant pour la gestion dynamique des pages.
- **Bootstrap 4** : Framework CSS pour garantir une interface utilisateur responsive et moderne.
- **JavaScript (ES6)** : Utilisation de JavaScript pour des interactions côté client, rendant l'application fluide et réactive.

### Base de données
- **MySQL** : Base de données relationnelle avec une structure optimisée pour stocker les informations des utilisateurs, des artistes et de leurs œuvres.

## Installation

### Prérequis
- PHP 7.4 ou supérieur
- Composer
- MySQL (ou MariaDB)
- Symfony CLI (facultatif mais recommandé)

### Étapes d'installation
Clonez le repository :
```bash
git clone https://github.com/votre-utilisateur/Arti.git
cd Arti
