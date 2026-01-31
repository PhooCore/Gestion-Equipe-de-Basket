<?php
// Inclusion du DAO match
require_once __DIR__ . '/../models/MatchDAO.php';

/**
 * Contrôleur des matchs
 * Gère les opérations CRUD sur les matchs et leurs participants
 */
class MatchController {
    private $matchDAO; // Instance du DAO match

    public function __construct() {
        $this->matchDAO = new MatchDAO();
    }

    /**
     * Récupère tous les matchs
     * @return array Liste des matchs
     */
    public function getAll() {
        return $this->matchDAO->getAll();
    }

    /**
     * Récupère un match par son ID
     * @param int $id ID du match
     * @return array|null Données du joueur ou null
     */
    public function getById($id) {
        return $this->getMatch($id);
    }

    /**
     * Récupère un match par son ID
     * @param int $id ID du match
     * @return array|null Données du match ou null
     */
    public function getMatch($id) {
        return $this->matchDAO->getById($id);
    }

    /**
     * Liste tous les matchs
     * @return array Liste des matchs
     */
    public function listerMatchs() {
        return $this->matchDAO->getAll();
    }

    /**
     * Crée un nouveau match
     * @param array $data Données du match
     * @return bool True si création réussie
     * @throws Exception Si validation échoue ou date dans le passé
     */
    public function creerMatch($data) {
        $erreurs = $this->validerDonneesMatch($data);
        if (!empty($erreurs)) {
            throw new Exception(implode(', ', $erreurs));
        }

        $data['date_heure'] = $this->combinerDateHeure($data['date_match'], $data['heure_match']);
        
        // Vérifier que la date n'est pas dans le passé
        $dateMatch = strtotime($data['date_heure']);
        $dateActuelle = time();
        if ($dateMatch <= $dateActuelle) {
            throw new Exception("La date du match ne peut pas être dans le passé");
        }
        // Nettoyage des données
        $cleanData = [
            'date_heure' => $data['date_heure'],
            'equipe_adverse' => trim($data['equipe_adverse']),
            'lieu' => $data['lieu'],
            'resultat' => $data['resultat'],
            'score_propre' => !empty($data['score_propre']) ? (int)$data['score_propre'] : null,
            'score_adverse' => !empty($data['score_adverse']) ? (int)$data['score_adverse'] : null,
            'commentaire_match' => trim($data['commentaire_match'] ?? '')
        ];

        return $this->matchDAO->create($cleanData);
    }

    /**
     * Modifie un match existant
     * @param int $id ID du match
     * @param array $data Nouvelles données
     * @return bool True si mise à jour réussie
     * @throws Exception Si validation échoue ou match déjà joué
     */
    public function modifierMatch($id, $data) {
        // Récupérer le match existant
        $matchExistant = $this->getMatch($id);
        if (!$matchExistant) {
            throw new Exception("Match non trouvé");
        }
        
        // Vérifier si le match n'a pas encore eu lieu
        $dateMatchExistant = strtotime($matchExistant['date_heure']);
        $dateActuelle = time();
        if ($dateMatchExistant <= $dateActuelle) {
            throw new Exception("Impossible de modifier un match qui a déjà eu lieu");
        }
        
        // Validation des données
        $erreurs = $this->validerDonneesMatch($data);
        if (!empty($erreurs)) {
            throw new Exception(implode(', ', $erreurs));
        }
        
        // Vérifier que la nouvelle date n'est pas dans le passé
        $nouvelleDateHeure = $this->combinerDateHeure($data['date_match'], $data['heure_match']);
        $nouvelleDate = strtotime($nouvelleDateHeure);
        if ($nouvelleDate <= $dateActuelle) {
            throw new Exception("La nouvelle date du match ne peut pas être dans le passé");
        }
        
        $cleanData = [
            'date_heure' => $nouvelleDateHeure,
            'equipe_adverse' => trim($data['equipe_adverse']),
            'lieu' => $data['lieu'],
            'resultat' => $data['resultat'],
            'score_propre' => !empty($data['score_propre']) ? (int)$data['score_propre'] : null,
            'score_adverse' => !empty($data['score_adverse']) ? (int)$data['score_adverse'] : null,
            'commentaire_match' => trim($data['commentaire_match'] ?? '')
        ];
        
        // Si le match est à venir, le résultat doit être "À venir"
        if ($cleanData['date_heure'] > date('Y-m-d H:i:s')) {
            $cleanData['resultat'] = 'À venir';
            $cleanData['score_propre'] = null;
            $cleanData['score_adverse'] = null;
        }
        
        return $this->matchDAO->update($id, $cleanData);
    }

    /**
     * Vérifie si un match peut être supprimé
     * @param int $id ID du match
     * @return bool True si suppression autorisée
     */
    public function peutSupprimerMatch($id) {
        $match = $this->getMatch($id);
        if (!$match) {
            return false;
        }
        
        $dateMatch = strtotime($match['date_heure']);
        $dateActuelle = time();
        
        // Un match ne peut être supprimé que s'il n'a pas encore eu lieu
        return $dateMatch > $dateActuelle;
    }

    /**
     * Supprime un match
     * @param int $id ID du match
     * @return bool True si suppression réussie
     * @throws Exception Si match déjà joué
     */
    public function supprimerMatch($id) {
        // Vérifier si le match peut être supprimé
        if (!$this->peutSupprimerMatch($id)) {
            throw new Exception("Impossible de supprimer un match qui a déjà eu lieu");
        }
        
        return $this->matchDAO->delete($id);
    }

    /**
     * Récupère les prochains matchs
     * @return array Liste des matchs à venir
     */
    public function getProchainsMatchs() {
        return $this->matchDAO->getProchains();
    }

    /**
     * Récupère les matchs terminés
     * @return array Liste des matchs passés
     */
    public function getMatchsTermines() {
        return $this->matchDAO->getTermines();
    }

    /**
     * Récupère les participants d'un match
     * @param int $match_id ID du match
     * @return array Liste des participants
     */
    public function getParticipants($match_id) {
        return $this->matchDAO->getParticipants($match_id);
    }

    /**
     * Définit les participants d'un match
     * @param int $match_id ID du match
     * @param array $participants Liste des participants
     * @return bool True si opération réussie
     * @throws Exception Si erreur lors de l'opération
     */
    public function setParticipants($match_id, $participants) {
        try {
            return $this->matchDAO->setParticipants($match_id, $participants);
        } catch (Exception $e) {
            error_log("Erreur setParticipants: " . $e->getMessage());
            throw $e;
        }
    }

    
    /**
     * Validation des données d'un match
     * @param array $data Données à valider
     * @return array Liste des erreurs de validation
     */
    private function validerDonneesMatch($data) {
        $erreurs = [];
        // Validation des champs obligatoires
        if (empty($data['date_match'])) $erreurs[] = "La date est obligatoire";
        if (empty($data['heure_match'])) $erreurs[] = "L'heure est obligatoire";
        if (empty(trim($data['equipe_adverse']))) $erreurs[] = "L'équipe adverse est obligatoire";
        
        // Ajouter la validation des scores
        $erreursScores = $this->validerScores($data);
        $erreurs = array_merge($erreurs, $erreursScores);
        
        return $erreurs;
    }
    /**
     * Met à jour l'évaluation d'un participant
     * @param int $match_id ID du match
     * @param int $joueur_id ID du joueur
     * @param int $evaluation Note d'évaluation
     * @return bool True si mise à jour réussie
     */
    public function updateParticipantEvaluation($match_id, $joueur_id, $evaluation) {
        return $this->matchDAO->updateParticipantEvaluation($match_id, $joueur_id, $evaluation);
    }

    /**
     * Combine une date et une heure en une chaîne formatée
     * @param string $date Date au format YYYY-MM-DD
     * @param string $heure Heure au format HH:MM
     * @return string Date et heure combinées
     * @throws InvalidArgumentException Si date ou heure manquante
     */
    private function combinerDateHeure($date, $heure) {
        if (empty($date) || empty($heure)) {
            throw new InvalidArgumentException("La date et l'heure sont obligatoires");
        }
        return $date . ' ' . $heure . ':00';
    }

    /**
     * Validation des scores en fonction du résultat
     * @param array $data Données contenant les scores
     * @return array Liste des erreurs de validation
     */
    private function validerScores($data) {
        $erreurs = [];
        
        // Si le résultat n'est pas "À venir", les scores sont obligatoires
        if ($data['resultat'] !== 'À venir') {
            if (empty($data['score_propre'])) {
                $erreurs[] = "Le score propre est obligatoire pour un match terminé";
            }
            if (empty($data['score_adverse'])) {
                $erreurs[] = "Le score adverse est obligatoire pour un match terminé";
            }
            
            // Si les deux scores sont présents, valider la logique
            if (!empty($data['score_propre']) && !empty($data['score_adverse'])) {
                $scorePropre = (int)$data['score_propre'];
                $scoreAdverse = (int)$data['score_adverse'];
                
                switch ($data['resultat']) {
                    case 'Victoire':
                        if ($scorePropre <= $scoreAdverse) {
                            $erreurs[] = "En cas de victoire, notre score doit être supérieur au score adverse";
                        }
                        break;
                    case 'Défaite':
                        if ($scorePropre >= $scoreAdverse) {
                            $erreurs[] = "En cas de défaite, notre score doit être inférieur au score adverse";
                        }
                        break;
                    case 'Nul':
                        if ($scorePropre !== $scoreAdverse) {
                            $erreurs[] = "En cas de match nul, les scores doivent être égaux";
                        }
                        break;
                }
            }
        }
        
        return $erreurs;
    }

}
?>