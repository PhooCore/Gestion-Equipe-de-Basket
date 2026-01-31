<?php
session_start();
require_once __DIR__ . '/../../controllers/MatchController.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../views/login.php');
    exit();
}

$matchController = new MatchController();
$message = '';
$erreurs = [];

// Récupération du match
$idMatch = $_GET['id'] ?? 0;
$match = $matchController->getMatch($idMatch);

if (!$match) {
    header('Location: liste.php?error=Match introuvable');
    exit();
}

// Détermination de l'état du match (passé ou non)
$dateMatch = strtotime($match['date_heure']);
$dateActuelle = time();
$matchPasse = $dateMatch <= $dateActuelle;

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Tentative de modification
        if ($matchController->modifierMatch($idMatch, $_POST)) {
            $message = "Match modifié avec succès!";
            $match = $matchController->getMatch($idMatch);
            $matchPasse = strtotime($match['date_heure']) <= time();
        }
    } catch (Exception $e) {
        $erreurs[] = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Match</title>
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
                <!-- Carte de modification -->
                <div class="carte">
                    <div class="entete-carte entete-primaire">
                        <h4 class="texte-blanc">Modifier le Match</h4>
                    </div>
                    <div class="corps-carte">
                        <!-- Affichage des messages -->
                        <?php if ($message): ?>
                            <div class="alerte alerte-succes"><?= $message ?></div>
                        <?php endif; ?>
                        
                        <!-- Affichage des erreurs -->
                        <?php if (!empty($erreurs)): ?>
                            <div class="alerte alerte-danger">
                                <?php foreach ($erreurs as $erreur): ?>
                                    <div><?= $erreur ?></div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Message d'avertissement pour les matchs passés -->
                        <?php if ($matchPasse): ?>
                            <div class="alerte alerte-avertissement">
                                <strong>Note :</strong> Ce match a déjà eu lieu. Vous pouvez modifier les résultats et commentaires.
                            </div>
                        <?php endif; ?>

                        <!-- Formulaire de modification -->
                        <form method="POST">
                            <!-- Ligne Date et Heure -->
                            <div class="ligne">
                                <div class="colonne-md-6 marge-bas">
                                    <label for="date_match" class="etiquette-formulaire">Date du match *</label>
                                    <input type="date" class="champ-formulaire" id="date_match" name="date_match" 
                                           value="<?= date('Y-m-d', strtotime($match['date_heure'])) ?>" 
                                           <?= $matchPasse ? 'readonly style="background-color: #f8f9fa;"' : '' ?> 
                                           required>
                                </div>
                                <div class="colonne-md-6 marge-bas">
                                    <label for="heure_match" class="etiquette-formulaire">Heure du match *</label>
                                    <input type="time" class="champ-formulaire" id="heure_match" name="heure_match" 
                                           value="<?= date('H:i', strtotime($match['date_heure'])) ?>"
                                           <?= $matchPasse ? 'readonly style="background-color: #f8f9fa;"' : '' ?>
                                           required>
                                </div>
                            </div>

                            <!-- Champ Équipe adverse -->
                            <div class="marge-bas">
                                <label for="equipe_adverse" class="etiquette-formulaire">Équipe adverse *</label>
                                <input type="text" class="champ-formulaire" id="equipe_adverse" name="equipe_adverse" 
                                       value="<?= $match['equipe_adverse'] ?>"
                                       <?= $matchPasse ? 'readonly style="background-color: #f8f9fa;"' : '' ?>
                                       required>
                            </div>

                            <!-- Sélecteur de Lieu -->
                            <div class="marge-bas">
                                <label for="lieu" class="etiquette-formulaire">Lieu *</label>
                                <select class="selection-formulaire" id="lieu" name="lieu" 
                                        <?= $matchPasse ? 'disabled style="background-color: #f8f9fa;"' : '' ?> required>
                                    <option value="Domicile" <?= $match['lieu'] === 'Domicile' ? 'selected' : '' ?>>Domicile</option>
                                    <option value="Extérieur" <?= $match['lieu'] === 'Extérieur' ? 'selected' : '' ?>>Extérieur</option>
                                </select>
                                <!-- Champ caché pour conserver la valeur si le lieu est désactivé -->
                                <?php if ($matchPasse): ?>
                                    <input type="hidden" name="lieu" value="<?= $match['lieu'] ?>">
                                <?php endif; ?>
                            </div>

                            <!-- Sélecteur de Résultat -->
                            <div class="marge-bas">
                                <label for="resultat" class="etiquette-formulaire">Résultat *</label>
                                <select class="selection-formulaire" id="resultat" name="resultat" required>
                                    <option value="">Sélectionner un résultat</option>
                                    <option value="À venir" <?= $match['resultat'] === 'À venir' ? 'selected' : '' ?>>À venir</option>
                                    <option value="Victoire" <?= $match['resultat'] === 'Victoire' ? 'selected' : '' ?>>Victoire</option>
                                    <option value="Défaite" <?= $match['resultat'] === 'Défaite' ? 'selected' : '' ?>>Défaite</option>
                                    <option value="Nul" <?= $match['resultat'] === 'Nul' ? 'selected' : '' ?>>Nul</option>
                                    <option value="Annulé" <?= $match['resultat'] === 'Annulé' ? 'selected' : '' ?>>Annulé</option>
                                </select>
                            </div>

                            <!-- Ligne pour les Scores -->
                            <div class="ligne">
                                <div class="colonne-md-6 marge-bas">
                                    <label for="score_propre" class="etiquette-formulaire">Score propre</label>
                                    <input type="number" class="champ-formulaire" id="score_propre" name="score_propre" 
                                           value="<?= $match['score_propre'] ?? '' ?>" min="0">
                                    <small class="texte-mute">Score de votre équipe</small>
                                </div>
                                <div class="colonne-md-6 marge-bas">
                                    <label for="score_adverse" class="etiquette-formulaire">Score adverse</label>
                                    <input type="number" class="champ-formulaire" id="score_adverse" name="score_adverse" 
                                           value="<?= $match['score_adverse'] ?? '' ?>" min="0">
                                    <small class="texte-mute">Score de l'équipe adverse</small>
                                </div>
                            </div>

                            <!-- Champ Commentaire -->
                            <div class="marge-bas">
                                <label for="commentaire_match" class="etiquette-formulaire">Commentaire</label>
                                <textarea class="champ-formulaire" id="commentaire_match" name="commentaire_match" 
                                          rows="4"><?= $match['commentaire_match'] ?? '' ?></textarea>
                                <?php if ($matchPasse): ?>
                                    <small class="texte-mute">Ajoutez vos observations sur le match qui a eu lieu</small>
                                <?php endif; ?>
                            </div>

                            <!-- Boutons d'action -->
                            <div class="actions-boutons">
                                <a href="liste.php" class="bouton bouton-secondaire">Retour</a>
                                <button type="submit" class="bouton bouton-primaire">
                                    <?= $matchPasse ? 'Mettre à jour les résultats' : 'Modifier le match' ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script pour la gestion conditionnelle des champs scores -->
    <script>
        // Références aux éléments du DOM
        const resultatSelect = document.getElementById('resultat');
        const scorePropreInput = document.getElementById('score_propre');
        const scoreAdverseInput = document.getElementById('score_adverse');
        
        // Fonction pour mettre à jour l'état des champs scores
        function updateScoreFields() {
            const resultat = resultatSelect.value;
            const isMatchTermine = resultat === 'Victoire' || resultat === 'Défaite' || resultat === 'Nul';
            
            if (isMatchTermine) {
                // Scores obligatoires pour les matchs terminés
                scorePropreInput.required = true;
                scoreAdverseInput.required = true;
                scorePropreInput.readOnly = false;
                scoreAdverseInput.readOnly = false;
                scorePropreInput.style.backgroundColor = '';
                scoreAdverseInput.style.backgroundColor = '';
            } else if (resultat === 'À venir' || resultat === 'Annulé') {
                // Scores non requis pour les matchs à venir ou annulés
                scorePropreInput.required = false;
                scoreAdverseInput.required = false;
                scorePropreInput.value = '';
                scoreAdverseInput.value = '';
                scorePropreInput.readOnly = true;
                scoreAdverseInput.readOnly = true;
                scorePropreInput.style.backgroundColor = '#f8f9fa';
                scoreAdverseInput.style.backgroundColor = '#f8f9fa';
            } else {
                // État par défaut
                scorePropreInput.required = false;
                scoreAdverseInput.required = false;
                scorePropreInput.readOnly = false;
                scoreAdverseInput.readOnly = false;
                scorePropreInput.style.backgroundColor = '';
                scoreAdverseInput.style.backgroundColor = '';
            }
        }
        
        // Initialisation de l'état des champs
        updateScoreFields();
        
        // Écouteur d'événement pour les changements du sélecteur de résultat
        resultatSelect.addEventListener('change', updateScoreFields);
        
        // Validation du formulaire avant soumission
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const resultat = resultatSelect.value;
            
            // Vidage des scores pour les matchs à venir ou annulés
            if (resultat === 'À venir' || resultat === 'Annulé') {
                scorePropreInput.value = '';
                scoreAdverseInput.value = '';
            }
            
            // Validation des scores pour les matchs terminés
            if ((resultat === 'Victoire' || resultat === 'Défaite' || resultat === 'Nul') && 
                (!scorePropreInput.value || !scoreAdverseInput.value)) {
                e.preventDefault();
                alert('Veuillez renseigner les scores pour un match terminé.');
                return false;
            }
        });
    </script>
</body>
</html>