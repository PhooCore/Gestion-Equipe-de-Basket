<?php
session_start();
require_once __DIR__ . '/../../controllers/MatchController.php';

// Vérification de l'authentification de l'utilisateur
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../views/login.php');
    exit();
}

// Initialisation du contrôleur de matchs
$matchController = new MatchController();
$message = ''; // Variable pour les messages de succès
$erreurs = []; // Tableau pour stocker les erreurs de validation

// Traitement du formulaire d'ajout de match
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $dateMatch = $_POST['date_match'] ?? '';
    $heureMatch = $_POST['heure_match'] ?? '';
    $equipeAdverse = trim($_POST['equipe_adverse'] ?? '');
    $lieu = $_POST['lieu'] ?? 'Domicile';
    $resultat = $_POST['resultat'] ?? 'À venir';
    $scorePropre = $_POST['score_propre'] ?? '';
    $scoreAdverse = $_POST['score_adverse'] ?? '';
    $commentaireMatch = trim($_POST['commentaire_match'] ?? '');

    try {
        // Tentative de création du match via le contrôleur
        if ($matchController->creerMatch($_POST)) {
            $message = "Match ajouté avec succès!";
            $_POST = []; // Réinitialisation des données du formulaire
        }
    } catch (Exception $e) {
        // Capture des exceptions (validation échouée)
        $erreurs[] = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Match</title>
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
                    <a class="lien-navigation" href="../joueurs/liste.php">Joueurs</a>
                    <a class="lien-navigation" href="../statistiques/stats.php">Statistiques</a>
                    <a class="lien-navigation" href="liste.php">Matchs</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <div class="conteneur page">
        <div class="ligne centrer">
            <div class="colonne-md-8">
                <!-- Carte du formulaire -->
                <div class="carte">
                    <div class="entete-carte entete-primaire">
                        <h4 class="texte-blanc">Ajouter un Match</h4>
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

                        <!-- Formulaire d'ajout de match -->
                        <form method="POST">
                            <!-- Ligne pour Date et Heure -->
                            <div class="ligne">
                                <div class="colonne-md-6 marge-bas">
                                    <label for="date_match" class="etiquette-formulaire">Date du match *</label>
                                    <input type="date" class="champ-formulaire" id="date_match" name="date_match" 
                                           value="<?= $_POST['date_match'] ?? '' ?>" required>
                                </div>
                                <div class="colonne-md-6 marge-bas">
                                    <label for="heure_match" class="etiquette-formulaire">Heure du match *</label>
                                    <input type="time" class="champ-formulaire" id="heure_match" name="heure_match" 
                                           value="<?= $_POST['heure_match'] ?? '' ?>" required>
                                </div>
                            </div>

                            <!-- Champ Équipe adverse -->
                            <div class="marge-bas">
                                <label for="equipe_adverse" class="etiquette-formulaire">Équipe adverse *</label>
                                <input type="text" class="champ-formulaire" id="equipe_adverse" name="equipe_adverse" 
                                       value="<?= $_POST['equipe_adverse'] ?? '' ?>" required>
                            </div>

                            <!-- Sélecteur de Lieu -->
                            <div class="marge-bas">
                                <label for="lieu" class="etiquette-formulaire">Lieu *</label>
                                <select class="selection-formulaire" id="lieu" name="lieu" required>
                                    <option value="Domicile" <?= ($_POST['lieu'] ?? '') === 'Domicile' ? 'selected' : '' ?>>Domicile</option>
                                    <option value="Extérieur" <?= ($_POST['lieu'] ?? '') === 'Extérieur' ? 'selected' : '' ?>>Extérieur</option>
                                </select>
                            </div>

                            <!-- Sélecteur de Résultat -->
                            <div class="marge-bas">
                                <label for="resultat" class="etiquette-formulaire">Résultat *</label>
                                <select class="selection-formulaire" id="resultat" name="resultat" required>
                                    <option value="À venir" <?= ($_POST['resultat'] ?? '') === 'À venir' ? 'selected' : '' ?>>À venir</option>
                                    <option value="Victoire" <?= ($_POST['resultat'] ?? '') === 'Victoire' ? 'selected' : '' ?>>Victoire</option>
                                    <option value="Défaite" <?= ($_POST['resultat'] ?? '') === 'Défaite' ? 'selected' : '' ?>>Défaite</option>
                                    <option value="Nul" <?= ($_POST['resultat'] ?? '') === 'Nul' ? 'selected' : '' ?>>Nul</option>
                                </select>
                            </div>

                            <!-- Ligne pour les Scores -->
                            <div class="ligne">
                                <div class="colonne-md-6 marge-bas">
                                    <label for="score_propre" class="etiquette-formulaire">Score propre</label>
                                    <input type="number" class="champ-formulaire" id="score_propre" name="score_propre" 
                                           value="<?= $_POST['score_propre'] ?? '' ?>">
                                </div>
                                <div class="colonne-md-6 marge-bas">
                                    <label for="score_adverse" class="etiquette-formulaire">Score adverse</label>
                                    <input type="number" class="champ-formulaire" id="score_adverse" name="score_adverse" 
                                           value="<?= $_POST['score_adverse'] ?? '' ?>">
                                </div>
                            </div>

                            <!-- Champ Commentaire -->
                            <div class="marge-bas">
                                <label for="commentaire_match" class="etiquette-formulaire">Commentaire</label>
                                <textarea class="champ-formulaire" id="commentaire_match" name="commentaire_match" 
                                          rows="3"><?= $_POST['commentaire_match'] ?? '' ?></textarea>
                            </div>

                            <!-- Boutons d'action -->
                            <div class="actions-boutons">
                                <a href="liste.php" class="bouton bouton-secondaire">Retour</a>
                                <button type="submit" class="bouton bouton-primaire">Ajouter le match</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>