<?php
session_start();
require_once __DIR__ . '/../../controllers/CommentaireController.php';

// Vérification de l'authentification de l'utilisateur
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../views/login.php');
    exit();
}

// Récupération de l'ID du joueur depuis les paramètres GET
$idJoueur = $_GET['id'] ?? 0;
if (!$idJoueur) {
    die("ID joueur invalide."); // Arrêt du script si l'ID est invalide
}

// Initialisation du contrôleur de commentaire
$commentaireController = new CommentaireController();
$message = ''; // Variable pour les messages de succès
$erreurs = []; // Tableau pour stocker les erreurs

// Traitement du formulaire d'ajout de commentaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $texte = trim($_POST['texte'] ?? ''); // Récupération et nettoyage du texte

    try {
        // Préparation des données pour la création du commentaire
        $donneesCommentaire = [
            'texte' => $texte,
            'id_joueur' => $idJoueur
        ];

        // Appel à la méthode de création du commentaire
        if ($commentaireController->creerCommentaire($donneesCommentaire)) {
            $message = "Commentaire ajouté avec succès !";
            $_POST = []; // Réinitialisation du formulaire
        }
    } catch (Exception $e) {
        // Erreurs de validation
        $erreurs[] = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Commentaire</title>
    <link href="../../css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Barre de navigation avec liens vers les différentes sections -->
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

    <!-- Contenu principal de la page -->
    <div class="conteneur page">
        <div class="ligne centrer">
            <div class="tablette-deux-tiers">
                <!-- Carte du formulaire d'ajout -->
                <div class="carte">
                    <div class="entete-carte entete-primaire">
                        <h4 class="texte-blanc">Ajouter un Commentaire</h4>
                    </div>
                    <div class="corps-carte">
                        <!-- Affichage des messages de succès -->
                        <?php if ($message): ?>
                            <div class="alerte alerte-succes"><?= $message ?></div>
                        <?php endif; ?>
                        
                        <!-- Affichage des erreurs de validation -->
                        <?php if (!empty($erreurs)): ?>
                            <div class="alerte alerte-danger">
                                <?php foreach ($erreurs as $erreur): ?>
                                    <div><?= $erreur ?></div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Formulaire d'ajout de commentaire -->
                        <form method="POST">
                            <div class="marge-bas">
                                <label for="texte" class="etiquette-formulaire">Commentaire *</label>
                                <!-- Zone de texte pour le commentaire -->
                                <textarea class="champ-formulaire" id="texte" name="texte" rows="5" required><?= $_POST['texte'] ?? '' ?></textarea>
                            </div>

                            <!-- Boutons d'action -->
                            <div class="actions-boutons">
                                <a href="commentaires.php?id=<?= $idJoueur ?>" class="bouton bouton-retour">Retour</a>
                                <button type="submit" class="bouton bouton-succes">Ajouter le commentaire</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>