<?php
session_start();
require_once __DIR__ . '/../../controllers/CommentaireController.php';
require_once __DIR__ . '/../../controllers/JoueurController.php';

// Vérification de l'authentification de l'utilisateur
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../views/login.php');
    exit();
}

// Récupération de l'ID du joueur depuis les paramètres GET
$idJoueur = $_GET['id'] ?? 0;
// Initialisation des contrôleurs
$joueurController = new JoueurController();
$joueur = $joueurController->getById($idJoueur); // Récupération des infos du joueur
$commentaireController = new CommentaireController();
$listeCommentaires = $commentaireController->getByJoueur($idJoueur); // Récupération des commentaires
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commentaires du Joueur</title>
    <link href="../../css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Barre de navigation -->
    <nav class="barre-navigation">
        <div class="conteneur">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <a class="marque-navigation" href="../index.php">Gestion Basket</a>
                <div class="liens-navigation">
                    <a class="lien-navigation" href="../index.php">Accueil</a>
                    <a class="lien-navigation actif" href="../joueurs/liste.php">Joueurs</a>
                    <a class="lien-navigation" href="../statistiques/stats.php">Statistiques</a>
                    <a class="lien-navigation" href="../matchs/liste.php">Matchs</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <div class="conteneur page">
        <!-- En-tête de page avec titre et bouton d'ajout -->
        <div class="entete-page">
            <h1>Commentaires du Joueur</h1>
            <a href="ajouter.php?id=<?= $idJoueur ?>" class="bouton bouton-succes">Ajouter un commentaire</a>
        </div>

        <!-- Section d'informations du joueur -->
        <div class="info-joueur">
            <h4><?= $joueur['prenom'] . ' ' . $joueur['nom'] ?></h4>
            <p>
                <strong>Numéro de licence:</strong> <?= $joueur['numero_licence'] ?> | 
                <strong>Taille:</strong> <?= $joueur['taille'] ?>cm | 
                <strong>Poids:</strong> <?= $joueur['poids'] ?>kg |
                <!-- Affichage du statut avec un badge coloré -->
                <strong>Statut:</strong> 
                <span class="badge <?= 
                    $joueur['statut'] === 'Actif' ? 'fond-succes' : 
                    ($joueur['statut'] === 'Blessé' ? 'fond-avertissement' : 
                    ($joueur['statut'] === 'Suspendu' ? 'fond-danger' : 'fond-secondaire')) 
                ?>">
                    <?= $joueur['statut'] ?>
                </span>
            </p>
        </div>
        <br>

        <!-- Section des commentaires -->
        <?php if (empty($listeCommentaires)): ?>
            <!-- Message si aucun commentaire n'existe -->
            <div class="alerte alerte-info">Aucun commentaire trouvé.</div>
        <?php else: ?>
            <!-- Tableau des commentaires -->
            <table class="tableau tableau-bande tableau-survol">
                <thead class="entete-tableau-sombre">
                    <tr>
                        <th>Date</th>
                        <th>Commentaire</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Boucle d'affichage des commentaires -->
                    <?php foreach ($listeCommentaires as $commentaire): ?>
                    <tr>
                        <td><?= $commentaire['date_commentaire'] ?></td>
                        <td><?= $commentaire['Texte'] ?></td>
                        <td>
                            <!-- Boutons de modification et suppression -->
                            <a href="modifier.php?id=<?= $commentaire['id_commentaire'] ?>" class="bouton bouton-petit bouton-modifier">Modifier</a>
                            <a href="supprimer.php?id=<?= $commentaire['id_commentaire'] ?>" class="bouton bouton-petit bouton-supprimer">Supprimer</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>