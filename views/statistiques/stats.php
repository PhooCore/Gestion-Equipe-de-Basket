<?php
// Démarrage de la session pour vérifier l'authentification
session_start();
require_once __DIR__ . '/../../controllers/StatistiquesController.php';

// Vérification de l'authentification de l'utilisateur
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../views/login.php');
    exit();
}

// Initialisation du contrôleur de statistiques
$controller = new StatistiquesController();

// Récupération des statistiques globales et des statistiques par joueur
$stats = $controller->getStatsGlobales();
$total = $stats['total_matchs']; // Nombre total de matchs joués
$joueurs = $controller->getStatsJoueurs(); // Statistiques détaillées par joueur

/**
 * Calcule le pourcentage d'une valeur par rapport au total
 * @param int $val Valeur à calculer
 * @param int $total Valeur totale
 * @return float Pourcentage arrondi à 1 décimale
 */
function pct($val, $total) {
    return $total > 0 ? round(($val / $total) * 100, 1) : 0;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques Globales - Gestion Basket</title>
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
                    <a class="lien-navigation" href="../joueurs/liste.php">Joueurs</a>
                    <a class="lien-navigation actif" href="../statistiques/stats.php">Statistiques</a>
                    <a class="lien-navigation" href="../matchs/liste.php">Matchs</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenu principal de la page -->
    <div class="conteneur page">
        <!-- En-tête de page avec titre et badge du nombre total de matchs -->
        <div class="entete-page">
            <h1>Statistiques de la Saison</h1>
            <span class="badge fond-info"><?= $total ?> Matchs au total</span>
        </div>

        <!-- Section des statistiques globales en 3 cartes -->
        <div class="ligne marge-bas-grande">
            <!-- Carte des Victoires -->
            <div class="tablette-un-tiers largeur-totale">
                <div class="carte centrer-texte">
                    <div class="entete-carte entete-succes">Victoires</div>
                    <div class="corps-carte">
                        <h2 style="margin:0;"><?= $stats['matchs_gagnes'] ?></h2>
                        <span class="texte-mute"><?= pct($stats['matchs_gagnes'], $total) ?>% du total</span>
                    </div>
                </div>
            </div>
            
            <!-- Carte des Défaites -->
            <div class="tablette-un-tiers largeur-totale">
                <div class="carte centrer-texte">
                    <div class="entete-carte entete-danger">Défaites</div>
                    <div class="corps-carte">
                        <h2 style="margin:0;"><?= $stats['matchs_perdus'] ?></h2>
                        <span class="texte-mute"><?= pct($stats['matchs_perdus'], $total) ?>% du total</span>
                    </div>
                </div>
            </div>
            
            <!-- Carte des Matchs Nuls -->
            <div class="tablette-un-tiers largeur-totale">
                <div class="carte centrer-texte">
                    <div class="entete-carte entete-avertissement">Nuls</div>
                    <div class="corps-carte">
                        <h2 style="margin:0;"><?= $stats['matchs_nuls'] ?></h2>
                        <span class="texte-mute"><?= pct($stats['matchs_nuls'], $total) ?>% du total</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section des statistiques individuelles par joueur -->
        <div class="entete-page">
            <h2>Performances Individuelles</h2>
        </div>
        
        <!-- Tableau des statistiques des joueurs -->
        <div class="carte">
            <div class="tableau-responsive">
                <table class="tableau tableau-bande tableau-survol">
                    <thead class="entete-tableau-sombre">
                        <tr>
                            <th>Joueur</th>
                            <th>Statut</th>
                            <th>Poste</th>
                            <th class="centrer-texte">Tit.</th>
                            <th class="centrer-texte">Rem.</th>
                            <th class="centrer-texte">Moyenne</th>
                            <th class="centrer-texte">% Victoires</th>
                            <th class="centrer-texte">Série</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Boucle d'affichage des statistiques pour chaque joueur -->
                        <?php foreach ($joueurs as $j): 
                            // Calcul du pourcentage de victoires pour ce joueur
                            $pctVictoire = $j['matchs_joues'] > 0 ? pct($j['victoires_jouees'], $j['matchs_joues']) : 0;
                            
                            // Récupération du nombre de sélections consécutives
                            $series = $controller->getSelecCons($j['id_joueur']);
                        ?>
                            <tr>
                                <!-- Nom du joueur -->
                                <td><strong><?= $j['prenom'] . ' ' . $j['nom'] ?></strong></td>
                                
                                <!-- Statut avec badge coloré -->
                                <td>
                                    <span class="badge <?= $j['statut'] === 'Actif' ? 'fond-succes' : 'fond-secondaire' ?>">
                                        <?= $j['statut'] ?>
                                    </span>
                                </td>
                                
                                <!-- Poste préféré -->
                                <td><span class="texte-mute"><?= $j['poste_prefere'] ?? '-' ?></span></td>
                                
                                <!-- Nombre de titularisations -->
                                <td class="centrer-texte"><?= $j['titularisations'] ?></td>
                                
                                <!-- Nombre de remplacements -->
                                <td class="centrer-texte"><?= $j['remplacements'] ?></td>
                                
                                <!-- Moyenne d'évaluation avec badge coloré selon la performance -->
                                <td class="centrer-texte">
                                    <span class="badge <?= $j['moyenne_evaluation'] >= 15 ? 'fond-succes' : ($j['moyenne_evaluation'] >= 10 ? 'fond-info' : 'fond-avertissement') ?>">
                                        <?= $j['moyenne_evaluation'] ?? '-' ?>
                                    </span>
                                </td>
                                
                                <!-- Pourcentage de victoires avec couleur conditionnelle -->
                                <td class="centrer-texte">
                                    <div style="font-weight: 600; color: <?= $pctVictoire >= 50 ? '#27ae60' : '#e74c3c' ?>;">
                                        <?= $pctVictoire ?>%
                                    </div>
                                </td>
                                
                                <!-- Série de sélections consécutives avec badge si >= 3 -->
                                <td class="centrer-texte">
                                    <span class="badge <?= $series >= 3 ? 'fond-primaire' : '' ?>">
                                        <?= $series ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pied de page -->
    <footer class="pied-page centrer-texte">
        <div class="conteneur">
            <p>&copy; <?= date('Y') ?> - Système de Gestion Basket-ball</p>
        </div>
    </footer>
</body>
</html>