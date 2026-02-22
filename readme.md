# 🛒 Groupy - Boutique de Vente Groupée

**Groupy** est un projet d'école réalisé en **PHP pur** (sans framework). C'est une plateforme de commerce spécialisée dans la **vente groupée**, permettant de dynamiser les achats via un système de préventes.

## Présentation du projet
L'objectif est de proposer des produits à la vente où la transaction n'est finalisée que si un certain nombre de participants (préventes) est atteint. 

### Rôles Utilisateurs :
* **Admin :** Gestion globale de la plateforme, validation des utilisateurs et statistiques.
* **Vendeur :** Création et gestion des produits, suivi des préventes.
* **Client :** Consultation du catalogue, participation aux ventes groupées et gestion du profil.

## Fonctionnalités clés
* **Gestion des produits :** Système de mise en ligne avec seuil de prévente.
* **Système de Prévente :** Suivi en temps réel de l'objectif de commande pour valider la vente.
* **Espace Membre :** Inscription, connexion et gestion sécurisée des sessions.
* **Sécurité :** Utilisation de variables d'environnement (`.env`) pour protéger les accès à la base de données.

## Installation et Configuration

### 1. Prérequis
* Un serveur local (AMPPS, XAMPP, Laragon ou MAMP).
* PHP 7.4 ou supérieur.
* MySQL.

### 2. Configuration de la base de données
1. Créez une base de données nommée `vente_groupe`.
2. Importez le fichier SQL (non disponible pour le moment) dans votre interface PHPMyAdmin.

### 3. Variables d'environnement
Le projet utilise un fichier `.env` pour la sécurité.
1. Copiez le fichier `.env.example` et renommez-le en `.env`.
2. Modifiez les informations à l'intérieur pour correspondre à votre configuration locale :
   ```text
   DB_HOST=127.0.0.1
   DB_NAME=vente_groupe
   DB_USER=votre_utilisateur
   DB_PASS=votre_mot_de_passe

### 4. Structure du projet 

Pour ce premier projet d'école j'ai choisis une structure MVC : 

* /Controlleur : Logique métier et gestion de la base de données.
* /Vue : Fichiers HTML/PHP pour l'affichage.
* /Model : Gestion des données (si architecture MVC).
* index.php : Point d'entrée principal.

Fait avec ❤️ par un passioné de programation. 