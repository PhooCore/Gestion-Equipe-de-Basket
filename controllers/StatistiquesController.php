<?php
// Inclusion des DAO nécessaires
require_once __DIR__ . '/../models/MatchDAO.php';
require_once __DIR__ . '/../models/JoueurDAO.php';
/**
 * Contrôleur des statistiques
 * Gère la récupération des statistiques globales et par joueur
 */
class StatistiquesController {

    private $matchDAO;// Instance du DAO match
    private $joueurDAO;// Instance du DAO joueur

    public function __construct() {
        $this->matchDAO = new MatchDAO();
        $this->joueurDAO = new JoueurDAO();
    }

    /**
     * Récupère les statistiques globales des matchs
     * @return array Statistiques des matchs
     */
    public function getStatsGlobales() {
        return $this->matchDAO->getStatsMatchs();
    }

    /**
     * Récupère les statistiques par joueur
     * @return array Statistiques individuelles des joueurs
     */
    public function getStatsJoueurs() {
        return $this->joueurDAO->getStatsParJoueur();
    }

    /**
     * Récupère le nombre de sélections consécutives d'un joueur
     * @param int $id_joueur ID du joueur
     * @return int Nombre de sélections consécutives
     */
    public function getSelecCons($id_joueur){
        return $this->joueurDAO->getSelectionsConsecutives($id_joueur);
    }
}
