
# 🏀 Gestion d'Équipe de Basket

Application web PHP permettant à un entraîneur de gérer efficacement son équipe de basketball : joueurs, matchs et statistiques.

---

## Table des matières

* [Description](#description)
* [Démo en ligne](#démo-en-ligne)
* [Fonctionnalités](#fonctionnalités)

  * [Gestion des Joueurs](#gestion-des-joueurs)
  * [Gestion des Matchs](#gestion-des-matchs)
  * [Statistiques](#statistiques)
* [Technologies utilisées](#technologies-utilisées)
* [Utilisation](#utilisation)
* [Sécurité](#sécurité)
* [Auteur](#auteur)
* [Licence](#licence)

---

## Description

Ce projet a été réalisé durant la **2e année de BUT Informatique**, en binôme, dans le cadre de la ressource **R3.01 : Développement Web**.

L'application a pour objectif de faciliter la gestion quotidienne d'une équipe de basketball par un **entraîneur unique**. Elle centralise l'ensemble des informations essentielles :

* gestion des joueurs,
* organisation et suivi des matchs,
* consultation des résultats et statistiques.

L'interface se veut **claire, intuitive et fonctionnelle**, afin de permettre un suivi efficace de l'évolution de l'équipe tout au long de la saison.

Vous pouvez retrouver au dessus le MCD qui nous permis de faire notre base de données.

---

## Démo en ligne

Une version de démonstration est accessible en ligne :

* **URL** : [https://etu-iut-tlse3-saes3.alwaysdata.net/](https://etu-iut-tlse3-saes3.alwaysdata.net/)
* **Identifiant** : `entraineur`
* **Mot de passe** : `basket123`

> **Attention** : Cette version est une démonstration. Les données peuvent être modifiées ou réinitialisées à tout moment.

---

## Fonctionnalités

### Gestion des Joueurs

* Visualisation complète des joueurs :

  * Nom et prénom
  * Numéro de licence
  * Taille et poids
  * Date de naissance
  * Statut : actif, blessé, suspendu ou absent
* Modification des informations d'un joueur
* Suppression d'un joueur de l'effectif
* Commentaires personnalisés pour le suivi individuel

---

### Gestion des Matchs

Les matchs sont organisés selon leur état d'avancement.

#### 1. Matchs à venir

* Création de nouveaux matchs
* Informations renseignées :

  * Date et heure
  * Équipe adverse
  * Lieu du match
* Préparation de la composition d'équipe :

  * Sélection de 5 titulaires
  * Désignation des remplaçants

#### 2. Matchs terminés sans résultat

* Matchs joués en attente de saisie du score
* Ajout du résultat : victoire, défaite ou nul
* Saisie du score final
* Consultation de la feuille de match

#### 3. Matchs terminés avec résultat

* Historique complet des matchs
* Affichage des scores finaux
* Notation des performances individuelles
* Accès à la feuille de match détaillée

##### Précision sur la feuille de match

Document récapitulatif non modifiable après le match, comprenant :

* Les informations générales du match
* La composition de l'équipe (titulaires et remplaçants)

---

### Statistiques

#### Statistiques d'équipe

* Nombre total de matchs joués
* Nombre de victoires
* Nombre de défaites
* Nombre de matchs nuls

#### Performances individuelles

* Statut du joueur
* Poste occupé
* Nombre de matchs joués (titulaire / remplaçant)
* Note moyenne
* Pourcentage de victoires

---

## Technologies utilisées

* PHP : logique serveur
* MySQL / phpMyAdmin : base de données relationnelle
* HTML5 / CSS3 : structure et mise en forme
* JavaScript : interactions côté client
* PDO : accès sécurisé à la base de données
* XAMPP : environnement de développement local

---

## Utilisation

### Première utilisation

1. Accéder au tableau de bord depuis la page d'accueil
2. Ajouter les joueurs de l'équipe
3. Créer les matchs à venir
4. Consulter les statistiques au fil de la saison

### Workflow typique

```
1. Ajouter les joueurs
   ↓
2. Créer un match à venir
   ↓
3. Sélectionner titulaires et remplaçants
   ↓
4. Match joué → "terminé sans résultat"
   ↓
5. Ajouter score et notes
   ↓
6. Consulter la feuille de match
   ↓
7. Analyser les statistiques
```

### Gestion d'un match

#### Avant le match

* Accéder à l'onglet Matchs à venir
* Cliquer sur Nouveau match
* Renseigner les informations principales
* Sélectionner la composition de l'équipe

#### Après le match

* Passer le match en Terminé
* Ajouter le score et le résultat
* Noter chaque joueur (de 1 à 5)
* Consulter la feuille de match

---

## Sécurité

Mesures mises en place :

* Requêtes préparées (PDO) contre les injections SQL
* Validation des données côté serveur
* Protection CSRF des formulaires
* Hachage des mots de passe (si authentification activée)

---

## Auteur

* **NGUYEN Tuyet Phuong** | [GitHub-PhooCore](https://github.com/PhooCore)
* **GABARRE CLAVERIA Santiago**

Projet réalisé dans le cadre de la 2e année de BUT Informatique.

---

## Licence

Projet académique réalisé à des fins pédagogiques.

⭐ *Si ce projet vous a été utile, n'hésitez pas à lui donner une étoile !*
