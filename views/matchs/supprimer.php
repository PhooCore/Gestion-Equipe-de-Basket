<?php
session_start();
require_once __DIR__ . '/../../controllers/MatchController.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../views/login.php');
    exit();
}

$matchController = new MatchController();
$idMatch = $_GET['id'] ?? 0;

// Validation de l'ID du match
if (!$idMatch) {
    header('Location: liste.php?error=id_manquant');
    exit();
}

// Récupération des informations du match
$match = $matchController->getMatch($idMatch);

if (!$match) {
    header('Location: liste.php?error=match_introuvable');
    exit();
}

// Vérification si le match peut être supprimé
$dateMatch = strtotime($match['date_heure']);
$dateActuelle = time();

// Un match ne peut être supprimé que s'il n'a pas encore eu lieu
if ($dateMatch <= $dateActuelle) {
    header('Location: liste.php?error=Impossible de supprimer un match qui a déjà eu lieu');
    exit();
}

// Traitement de la confirmation de suppression
$confirmation = isset($_GET['confirm']) && $_GET['confirm'] === 'true';

if ($confirmation) {
    try {
        if ($matchController->supprimerMatch($idMatch)) {
            header('Location: liste.php?success=match_supprime');
            exit();
        } else {
            header('Location: liste.php?error=erreur_suppression');
            exit();
        }
    } catch (Exception $e) {
        header('Location: liste.php?error=' . urlencode($e->getMessage()));
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer un Match</title>
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
            <div class="colonne-md-6">
                <!-- Carte de confirmation de suppression -->
                <div class="carte">
                    <div class="entete-carte entete-danger">
                        <h4 class="texte-blanc">Confirmation de suppression</h4>
                    </div>
                    <div class="corps-carte">
                        <!-- Message d'avertissement -->
                        <div class="alerte alerte-avertissement">
                            <strong>Attention !</strong> Vous êtes sur le point de supprimer définitivement ce match.
                        </div>

                        <!-- Informations du match à supprimer -->
                        <div class="marge-bas">
                            <h5>Match à supprimer :</h5>
                            <p><strong>Date :</strong> <?= date('d/m/Y H:i', strtotime($match['date_heure'])) ?></p>
                            <p><strong>Équipe adverse :</strong> <?= htmlspecialchars($match['equipe_adverse']) ?></p>
                            <p><strong>Lieu :</strong> <?= $match['lieu'] ?></p>
                            <p><strong>Résultat :</strong> 
                                <!-- Badge coloré selon le résultat -->
                                <span class="badge <?= 
                                    $match['resultat'] === 'Victoire' ? 'fond-succes' : 
                                    ($match['resultat'] === 'Défaite' ? 'fond-danger' : 
                                    ($match['resultat'] === 'Nul' ? 'fond-avertissement' : 'fond-secondaire')) 
                                ?>">
                                    <?= $match['resultat'] ?>
                                </span>
                            </p>
                            <?php if ($match['resultat'] !== 'À venir'): ?>
                                <p><strong>Score :</strong> <?= $match['score_propre'] ?> - <?= $match['score_adverse'] ?></p>
                            <?php endif; ?>
                            
                            <?php if ($match['commentaire_match']): ?>
                                <p><strong>Commentaire :</strong> <?= htmlspecialchars($match['commentaire_match']) ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Liste des conséquences -->
                        <div class="alerte alerte-danger">
                            <strong>Conséquences :</strong>
                            <ul>
                                <li>Toutes les données du match seront perdues</li>
                                <li>Les participations des joueurs seront supprimées</li>
                                <li>Cette action est irréversible</li>
                            </ul>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="actions-boutons">
                            <a href="liste.php" class="bouton bouton-secondaire">Annuler</a>
                            <!-- Bouton de confirmation avec double confirmation -->
                            <a href="supprimer.php?id=<?= $idMatch ?>&confirm=true" 
                               class="bouton bouton-danger" 
                               onclick="return confirm('Êtes-vous ABSOLUMENT SÛR ? Cette action est irréversible.')">
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