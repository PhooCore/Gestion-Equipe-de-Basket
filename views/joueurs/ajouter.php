<?php
session_start();
require_once __DIR__ . '/../../controllers/JoueurController.php';

// Vérification de l'authentification de l'utilisateur
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../views/login.php');
    exit();
}

// Initialisation du contrôleur de joueurs
$joueurController = new JoueurController();
$message = ''; // Variable pour les messages de succès
$erreurs = []; // Tableau pour stocker les erreurs de validation

// Traitement du formulaire d'ajout de joueur
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération et nettoyage des données du formulaire
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $numeroLicence = trim($_POST['numero_licence'] ?? '');
    $dateNaissance = $_POST['date_naissance'] ?? '';
    $taille = $_POST['taille'] ?? '';
    $poids = $_POST['poids'] ?? '';
    $statut = $_POST['statut'] ?? 'Actif';

    try {
        // Préparation des données pour la création du joueur
        $donneesJoueur = [
            'nom' => $nom,
            'prenom' => $prenom,
            'numero_licence' => $numeroLicence,
            'date_naissance' => $dateNaissance,
            'taille' => (float)$taille,
            'poids' => (float)$poids,
            'statut' => $statut
        ];

        // Tentative de création du joueur
        if ($joueurController->creerJoueur($donneesJoueur)) {
            $message = "Joueur ajouté avec succès!";
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
    <title>Ajouter un Joueur</title>
    <link href="../../css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Barre de navigation principale -->
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

    <!-- Contenu principal de la page -->
    <div class="conteneur page">
        <div class="ligne centrer">
            <div class="tablette-deux-tiers">
                <!-- Carte du formulaire d'ajout -->
                <div class="carte">
                    <div class="entete-carte entete-primaire">
                        <h4 class="texte-blanc">Ajouter un Joueur</h4>
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

                        <!-- Formulaire d'ajout de joueur -->
                        <form method="POST">
                            <!-- Ligne pour Nom et Prénom -->
                            <div class="ligne">
                                <div class="tablette-demi marge-bas">
                                    <label for="nom" class="etiquette-formulaire">Nom *</label>
                                    <input type="text" class="champ-formulaire" id="nom" name="nom" 
                                           value="<?= $_POST['nom'] ?? '' ?>" required>
                                </div>
                                <div class="tablette-demi marge-bas">
                                    <label for="prenom" class="etiquette-formulaire">Prénom *</label>
                                    <input type="text" class="champ-formulaire" id="prenom" name="prenom" 
                                           value="<?= $_POST['prenom'] ?? '' ?>" required>
                                </div>
                            </div>

                            <!-- Champ Numéro de licence -->
                            <div class="marge-bas">
                                <label for="numero_licence" class="etiquette-formulaire">Numéro de licence *</label>
                                <input type="text" class="champ-formulaire" id="numero_licence" name="numero_licence" 
                                       value="<?= $_POST['numero_licence'] ?? '' ?>" required>
                            </div>

                            <!-- Champ Date de naissance -->
                            <div class="marge-bas">
                                <label for="date_naissance" class="etiquette-formulaire">Date de naissance *</label>
                                <input type="date" class="champ-formulaire" id="date_naissance" name="date_naissance" 
                                       value="<?= $_POST['date_naissance'] ?? '' ?>" required>
                            </div>

                            <!-- Ligne pour Taille et Poids -->
                            <div class="ligne">
                                <div class="tablette-demi marge-bas">
                                    <label for="taille" class="etiquette-formulaire">Taille (cm) *</label>
                                    <input type="number" step="0.1" class="champ-formulaire" id="taille" name="taille" 
                                           value="<?= $_POST['taille'] ?? '' ?>" required>
                                </div>
                                <div class="tablette-demi marge-bas">
                                    <label for="poids" class="etiquette-formulaire">Poids (kg) *</label>
                                    <input type="number" step="0.1" class="champ-formulaire" id="poids" name="poids" 
                                           value="<?= $_POST['poids'] ?? '' ?>" required>
                                </div>
                            </div>

                            <!-- Sélecteur de Statut -->
                            <div class="marge-bas">
                                <label for="statut" class="etiquette-formulaire">Statut *</label>
                                <select class="selection-formulaire" id="statut" name="statut" required>
                                    <option value="Actif" <?= ($_POST['statut'] ?? '') === 'Actif' ? 'selected' : '' ?>>Actif</option>
                                    <option value="Blessé" <?= ($_POST['statut'] ?? '') === 'Blessé' ? 'selected' : '' ?>>Blessé</option>
                                    <option value="Suspendu" <?= ($_POST['statut'] ?? '') === 'Suspendu' ? 'selected' : '' ?>>Suspendu</option>
                                    <option value="Absent" <?= ($_POST['statut'] ?? '') === 'Absent' ? 'selected' : '' ?>>Absent</option>
                                </select>
                            </div>

                            <!-- Boutons d'action -->
                            <div class="actions-boutons">
                                <a href="liste.php" class="bouton bouton-retour">Retour</a>
                                <button type="submit" class="bouton bouton-succes">Ajouter le joueur</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>