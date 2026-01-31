<?php
session_start();
require_once __DIR__ . '/../../controllers/MatchController.php';

// Vérification de l'authentification
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../views/login.php');
    exit();
}

// Récupération de tous les matchs
$matchController = new MatchController();
$tousMatchs = $matchController->getAll();

// Catégorisation des matchs
$matchsAVenir = [];
$matchsTerminesAvecResultat = [];
$matchsTerminesSansResultat = [];

$dateActuelle = time();

foreach ($tousMatchs as $match) {
    $dateMatch = strtotime($match['date_heure']);
    
    if ($dateMatch > $dateActuelle) {
        $matchsAVenir[] = $match;
    } else {
        if ($match['resultat'] === null || $match['resultat'] === 'À venir') {
            $matchsTerminesSansResultat[] = $match;
        } else {
            $matchsTerminesAvecResultat[] = $match;
        }
    }
}

// Gestion des messages
$message = '';
if (isset($_GET['success'])) {
    $message = "success:" . $_GET['success'];
}
if (isset($_GET['error'])) {
    $message = "error:" . $_GET['error'];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Matchs</title>
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
                    <a class="lien-navigation actif" href="liste.php">Matchs</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <div class="conteneur page">
        <!-- En-tête avec bouton d'ajout -->
        <div class="entete-page">
            <h1>Liste des Matchs</h1>
            <a href="ajouter.php" class="bouton bouton-succes">+ Nouveau Match</a>
        </div>

        <!-- Affichage des messages -->
        <?php 
        if ($message) {
            $type = strpos($message, 'success:') === 0 ? 'succes' : 'danger';
            $texte = str_replace(['success:', 'error:'], '', $message);
            echo '<div class="alerte alerte-' . $type . ' marge-bas">' . $texte . '</div>';
        }
        ?>

        <!-- Section des Matchs à Venir -->
        <div class="marge-bas-grande">
            <div class="section-header">
                <div class="section-title">
                    <h2>Matchs à Venir</h2>
                    <span class="badge fond-avertissement"><?= count($matchsAVenir) ?> match(s)</span>
                </div>
            </div>

            <?php if (empty($matchsAVenir)): ?>
                <!-- Message si aucun match à venir -->
                <div class="carte">
                    <div class="corps-carte centrer-texte">
                        <p class="texte-mute">Aucun match à venir.</p>
                        <a href="ajouter.php" class="bouton bouton-succes marge-haut">Planifier un match</a>
                    </div>
                </div>
            <?php else: ?>
                <!-- Tableau des matchs à venir -->
                <table class="tableau tableau-bande tableau-survol">
                    <thead class="entete-tableau-sombre">
                        <tr>
                            <th>Date</th>
                            <th>Équipe Adverse</th>
                            <th>Lieu</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($matchsAVenir as $match): ?>
                        <tr>
                            <td><?= date('d/m/Y H:i', strtotime($match['date_heure'])) ?></td>
                            <td><?= $match['equipe_adverse'] ?></td>
                            <td>
                                <span class="badge <?= $match['lieu'] === 'Domicile' ? 'fond-succes' : 'fond-info' ?>">
                                    <?= $match['lieu'] ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge-a-venir">À venir</span>
                            </td>
                            <td>
                                <div class="ligne" style="gap: 5px; flex-wrap: nowrap;">
                                    <!-- Bouton Composer -->
                                    <a href="feuille.php?id=<?= $match['id_match'] ?>" 
                                       class="bouton bouton-petit bouton-composer"
                                       title="Composer la feuille de match">
                                        Composer
                                    </a>
                                    <!-- Bouton Modifier -->
                                    <a href="modifier.php?id=<?= $match['id_match'] ?>" 
                                       class="bouton bouton-petit bouton-modifier"
                                       title="Modifier le match">
                                        Modifier
                                    </a>
                                    <!-- Bouton Supprimer -->
                                    <a href="supprimer.php?id=<?= $match['id_match'] ?>" 
                                       class="bouton bouton-petit bouton-supprimer"
                                       title="Supprimer le match"
                                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce match ?');">
                                        Supprimer
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <!-- Section des Matchs Terminés SANS résultat -->
        <?php if (!empty($matchsTerminesSansResultat)): ?>
        <div class="marge-bas-grande">
            <div class="section-header">
                <div class="section-title">
                    <h2>Matchs Terminés (sans résultat)</h2>
                    <span class="badge fond-danger"><?= count($matchsTerminesSansResultat) ?> match(s)</span>
                </div>
                <small class="texte-mute">Ces matchs sont passés mais n'ont pas encore de résultat</small>
            </div>

            <table class="tableau tableau-bande tableau-survol">
                <thead class="entete-tableau-sombre">
                    <tr>
                        <th>Date</th>
                        <th>Équipe Adverse</th>
                        <th>Lieu</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($matchsTerminesSansResultat as $match): ?>
                    <tr class="match-passe">
                        <td><?= date('d/m/Y H:i', strtotime($match['date_heure'])) ?></td>
                        <td><?= $match['equipe_adverse'] ?></td>
                        <td>
                            <span class="badge <?= $match['lieu'] === 'Domicile' ? 'fond-succes' : 'fond-info' ?>">
                                <?= $match['lieu'] ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge-sans-resultat">
                                Résultat manquant
                            </span>
                        </td>
                        <td>
                            <div class="ligne" style="gap: 5px; flex-wrap: nowrap;">
                                <!-- Boutons pour matchs terminés sans résultat -->
                                <a href="feuille.php?id=<?= $match['id_match'] ?>" 
                                   class="bouton bouton-petit bouton-composer"
                                   title="Voir la feuille de match">
                                    Voir
                                </a>
                                <a href="noter_joueurs.php?id=<?= $match['id_match'] ?>" 
                                   class="bouton bouton-petit bouton-info"
                                   title="Noter les joueurs">
                                    Noter
                                </a>
                                <a href="modifier.php?id=<?= $match['id_match'] ?>" 
                                   class="bouton bouton-petit bouton-modifier"
                                   title="Mettre à jour le résultat">
                                    Mettre résultat
                                </a>
                                <a href="supprimer.php?id=<?= $match['id_match'] ?>" 
                                   class="bouton bouton-petit bouton-supprimer"
                                   title="Supprimer le match"
                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce match terminé ? Cette action est irréversible.');">
                                    Supprimer
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>

        <!-- Section des Matchs Terminés AVEC résultat -->
        <div>
            <div class="section-header">
                <div class="section-title">
                    <h2>Matchs Terminés (avec résultat)</h2>
                    <span class="badge fond-info"><?= count($matchsTerminesAvecResultat) ?> match(s)</span>
                </div>
            </div>

            <?php if (empty($matchsTerminesAvecResultat)): ?>
                <!-- Message si aucun match terminé avec résultat -->
                <div class="carte">
                    <div class="corps-carte centrer-texte">
                        <p class="texte-mute">Aucun match terminé avec résultat.</p>
                    </div>
                </div>
            <?php else: ?>
                <!-- Tableau des matchs terminés avec résultat -->
                <table class="tableau tableau-bande tableau-survol">
                    <thead class="entete-tableau-sombre">
                        <tr>
                            <th>Date</th>
                            <th>Équipe Adverse</th>
                            <th>Lieu</th>
                            <th>Résultat</th>
                            <th>Score</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($matchsTerminesAvecResultat as $match): ?>
                        <tr class="match-passe">
                            <td><?= date('d/m/Y H:i', strtotime($match['date_heure'])) ?></td>
                            <td><?= $match['equipe_adverse'] ?></td>
                            <td>
                                <span class="badge <?= $match['lieu'] === 'Domicile' ? 'fond-succes' : 'fond-info' ?>">
                                    <?= $match['lieu'] ?>
                                </span>
                            </td>
                            <td>
                                <!-- Badge coloré selon le résultat -->
                                <span class="badge <?= $match['resultat'] === 'Victoire' ? 'fond-succes' : ($match['resultat'] === 'Nul' ? 'fond-avertissement' : 'fond-danger') ?>">
                                    <?= $match['resultat'] ?>
                                </span>
                            </td>
                            <td>
                                <?php if (!empty($match['score_propre']) && !empty($match['score_adverse'])): ?>
                                    <?= $match['score_propre'] ?> - <?= $match['score_adverse'] ?>
                                <?php else: ?>
                                    <span class="texte-mute">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="ligne" style="gap: 5px; flex-wrap: nowrap;">
                                    <!-- Boutons pour matchs terminés avec résultat -->
                                    <a href="feuille.php?id=<?= $match['id_match'] ?>" 
                                       class="bouton bouton-petit bouton-composer"
                                       title="Voir la feuille de match">
                                        Voir
                                    </a>
                                    <a href="noter_joueurs.php?id=<?= $match['id_match'] ?>" 
                                       class="bouton bouton-petit bouton-info"
                                       title="Noter les joueurs">
                                        Noter
                                    </a>
                                    <a href="modifier.php?id=<?= $match['id_match'] ?>" 
                                       class="bouton bouton-petit bouton-modifier"
                                       title="Modifier le match">
                                        Modifier
                                    </a>
                                    <!-- Attention : suppression avec confirmation renforcée -->
                                    <a href="supprimer.php?id=<?= $match['id_match'] ?>" 
                                       class="bouton bouton-petit bouton-supprimer"
                                       title="Supprimer le match"
                                       onclick="return confirm('ATTENTION : Ce match a déjà un résultat. Êtes-vous vraiment sûr de vouloir le supprimer ? Cette action supprimera également toutes les notes et participations associées.');">
                                        Supprimer
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>