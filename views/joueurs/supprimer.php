<?php
session_start();
require_once __DIR__ . '/../../controllers/JoueurController.php';

// Vérification de l'authentification
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../views/login.php');
    exit();
}

$joueurController = new JoueurController();
$idJoueur = $_GET['id'] ?? 0;

// Validation de l'ID
if (!$idJoueur) {
    header('Location: liste.php?error=id_manquant');
    exit();
}

// Récupération des données du joueur
$joueur = $joueurController->getById($idJoueur);

// Vérification de l'existence du joueur
if (!$joueur) {
    header('Location: liste.php?error=joueur_introuvable');
    exit();
}

// Vérification de la confirmation de suppression
$confirmation = isset($_GET['confirm']) && $_GET['confirm'] === 'true';

// Traitement de la suppression si confirmée
if ($confirmation) {
    if ($joueurController->delete($idJoueur)) {
        header('Location: liste.php?success=joueur_supprime');
        exit();
    } else {
        header('Location: liste.php?error=erreur_suppression');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer un Joueur</title>
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
                    <a class="lien-navigation actif" href="liste.php">Joueurs</a>
                    <a class="lien-navigation" href="../statistiques/stats.php">Statistiques</a>
                    <a class="lien-navigation" href="../matchs/liste.php">Matchs</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <div class="conteneur page">
        <div class="ligne centrer">
            <div class="tablette-un-tiers">
                <!-- Carte de confirmation de suppression -->
                <div class="carte">
                    <div class="entete-carte entete-danger">
                        <h4 class="texte-blanc">Confirmation de suppression</h4>
                    </div>
                    <div class="corps-carte">
                        <!-- Message d'avertissement -->
                        <div class="alerte alerte-avertissement">
                            <strong>Attention !</strong> Vous êtes sur le point de supprimer définitivement ce joueur.
                        </div>

                        <!-- Informations du joueur à supprimer -->
                        <div class="marge-bas">
                            <h5>Joueur à supprimer :</h5>
                            <p><strong>Nom :</strong> <?= $joueur['nom'] ?></p>
                            <p><strong>Prénom :</strong> <?= $joueur['prenom'] ?></p>
                            <p><strong>Licence :</strong> <?= $joueur['numero_licence'] ?></p>
                            <p><strong>Statut :</strong> <?= $joueur['statut'] ?></p>
                        </div>

                        <!-- Liste des conséquences -->
                        <div class="alerte alerte-danger">
                            <strong>Conséquences :</strong>
                            <ul>
                                <li>Toutes les données du joueur seront perdues</li>
                                <li>Les participations aux matchs seront supprimées</li>
                                <li>Cette action est irréversible</li>
                            </ul>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="actions-boutons">
                            <a href="liste.php" class="bouton bouton-retour">Annuler</a>
                            <!-- Lien de confirmation avec double confirmation -->
                            <a href="supprimer.php?id=<?= $idJoueur ?>&confirm=true" 
                               class="bouton bouton-supprimer" 
                               onclick="return confirm('Êtes-vous ABSOLUMENT SÛR ?')">
                                Confirmer la suppression
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>