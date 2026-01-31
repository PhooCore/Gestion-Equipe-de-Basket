<<<<<<< Updated upstream
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
=======
# R3.01_PROJET_PHP
# Application de Gestion d'Équipe de Basket

**Application déployée et fonctionnelle :**
- **URL :** [https://etu-iut-tlse3-saes3.alwaysdata.net/](https://etu-iut-tlse3-saes3.alwaysdata.net/)
- **Identifiant :** `entraineur`
- **Mot de passe :** `basket123`


## Getting started

To make it easy for you to get started with GitLab, here's a list of recommended next steps.

Already a pro? Just edit this README.md and make it your own. Want to make it easy? [Use the template at the bottom](#editing-this-readme)!

## Add your files

- [ ] [Create](https://docs.gitlab.com/ee/user/project/repository/web_editor.html#create-a-file) or [upload](https://docs.gitlab.com/ee/user/project/repository/web_editor.html#upload-a-file) files
- [ ] [Add files using the command line](https://docs.gitlab.com/topics/git/add_files/#add-files-to-a-git-repository) or push an existing Git repository with the following command:

```
cd existing_repo
git remote add origin https://gitlab.info.iut-tlse3.fr/projet_php/r3.01_projet_php.git
git branch -M main
git push -uf origin main
```

## Integrate with your tools

- [ ] [Set up project integrations](https://gitlab.info.iut-tlse3.fr/projet_php/r3.01_projet_php/-/settings/integrations)

## Collaborate with your team

- [ ] [Invite team members and collaborators](https://docs.gitlab.com/ee/user/project/members/)
- [ ] [Create a new merge request](https://docs.gitlab.com/ee/user/project/merge_requests/creating_merge_requests.html)
- [ ] [Automatically close issues from merge requests](https://docs.gitlab.com/ee/user/project/issues/managing_issues.html#closing-issues-automatically)
- [ ] [Enable merge request approvals](https://docs.gitlab.com/ee/user/project/merge_requests/approvals/)
- [ ] [Set auto-merge](https://docs.gitlab.com/user/project/merge_requests/auto_merge/)

## Test and Deploy

Use the built-in continuous integration in GitLab.

- [ ] [Get started with GitLab CI/CD](https://docs.gitlab.com/ee/ci/quick_start/)
- [ ] [Analyze your code for known vulnerabilities with Static Application Security Testing (SAST)](https://docs.gitlab.com/ee/user/application_security/sast/)
- [ ] [Deploy to Kubernetes, Amazon EC2, or Amazon ECS using Auto Deploy](https://docs.gitlab.com/ee/topics/autodevops/requirements.html)
- [ ] [Use pull-based deployments for improved Kubernetes management](https://docs.gitlab.com/ee/user/clusters/agent/)
- [ ] [Set up protected environments](https://docs.gitlab.com/ee/ci/environments/protected_environments.html)

***

# Editing this README

When you're ready to make this README your own, just edit this file and use the handy template below (or feel free to structure it however you want - this is just a starting point!). Thanks to [makeareadme.com](https://www.makeareadme.com/) for this template.

## Suggestions for a good README

Every project is different, so consider which of these sections apply to yours. The sections used in the template are suggestions for most open source projects. Also keep in mind that while a README can be too long and detailed, too long is better than too short. If you think your README is too long, consider utilizing another form of documentation rather than cutting out information.

## Name
Choose a self-explaining name for your project.

## Description
Let people know what your project can do specifically. Provide context and add a link to any reference visitors might be unfamiliar with. A list of Features or a Background subsection can also be added here. If there are alternatives to your project, this is a good place to list differentiating factors.

## Badges
On some READMEs, you may see small images that convey metadata, such as whether or not all the tests are passing for the project. You can use Shields to add some to your README. Many services also have instructions for adding a badge.

## Visuals
Depending on what you are making, it can be a good idea to include screenshots or even a video (you'll frequently see GIFs rather than actual videos). Tools like ttygif can help, but check out Asciinema for a more sophisticated method.

## Installation
Within a particular ecosystem, there may be a common way of installing things, such as using Yarn, NuGet, or Homebrew. However, consider the possibility that whoever is reading your README is a novice and would like more guidance. Listing specific steps helps remove ambiguity and gets people to using your project as quickly as possible. If it only runs in a specific context like a particular programming language version or operating system or has dependencies that have to be installed manually, also add a Requirements subsection.

## Usage
Use examples liberally, and show the expected output if you can. It's helpful to have inline the smallest example of usage that you can demonstrate, while providing links to more sophisticated examples if they are too long to reasonably include in the README.

## Support
Tell people where they can go to for help. It can be any combination of an issue tracker, a chat room, an email address, etc.

## Roadmap
If you have ideas for releases in the future, it is a good idea to list them in the README.

## Contributing
State if you are open to contributions and what your requirements are for accepting them.

For people who want to make changes to your project, it's helpful to have some documentation on how to get started. Perhaps there is a script that they should run or some environment variables that they need to set. Make these steps explicit. These instructions could also be useful to your future self.

You can also document commands to lint the code or run tests. These steps help to ensure high code quality and reduce the likelihood that the changes inadvertently break something. Having instructions for running tests is especially helpful if it requires external setup, such as starting a Selenium server for testing in a browser.

## Authors and acknowledgment
Show your appreciation to those who have contributed to the project.

## License
For open source projects, say how it is licensed.

## Project status
If you have run out of energy or time for your project, put a note at the top of the README saying that development has slowed down or stopped completely. Someone may choose to fork your project or volunteer to step in as a maintainer or owner, allowing your project to keep going. You can also make an explicit request for maintainers.
>>>>>>> Stashed changes
