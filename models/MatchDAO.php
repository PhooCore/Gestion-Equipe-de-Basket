<?php
require_once __DIR__ . '/../config/Database.php';

/**
 * Classe MatchDAO
 * Gère toutes les opérations de base de données liées aux matchs
 */
class MatchDAO {
    private $db;                 // Connexion à la base de données
    private $table_name = "Match_basket";  // Nom de la table

    /**
     * Constructeur - Initialise la connexion à la base de données
     */
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Récupère tous les matchs
     * @return array Tableau de tous les matchs triés par date décroissante
     */
    public function getAll() {
        try {
            $query = "SELECT * FROM " . $this->table_name . " ORDER BY date_heure DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erreur DAO getAll: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupère un match par son ID
     * @param int $id ID du match
     * @return array|null Données du match ou null si non trouvé
     */
    public function getById($id) {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE id_match = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Erreur DAO getById: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Crée un nouveau match
     * @param array $data Données du match
     * @return bool True si la création a réussi
     */
    public function create($data) {
        try {
            $query = "INSERT INTO " . $this->table_name . " 
                     (date_heure, equipe_adverse, lieu, resultat, score_propre, score_adverse, commentaire_match) 
                     VALUES (:date_heure, :equipe_adverse, :lieu, :resultat, :score_propre, :score_adverse, :commentaire_match)";
            
            $stmt = $this->db->prepare($query);
            
            // Liaison des paramètres avec validation et nettoyage
            $stmt->bindValue(":date_heure", $data['date_heure']);
            $stmt->bindValue(":equipe_adverse", trim($data['equipe_adverse']), PDO::PARAM_STR);
            $stmt->bindValue(":lieu", $data['lieu'], PDO::PARAM_STR);
            $stmt->bindValue(":resultat", $data['resultat'], PDO::PARAM_STR);
            $stmt->bindValue(":score_propre", $data['score_propre'] ?? null, PDO::PARAM_INT);
            $stmt->bindValue(":score_adverse", $data['score_adverse'] ?? null, PDO::PARAM_INT);
            $stmt->bindValue(":commentaire_match", trim($data['commentaire_match'] ?? ''), PDO::PARAM_STR);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur DAO create: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Met à jour les informations d'un match
     * @param int $id ID du match
     * @param array $data Nouvelles données
     * @return bool True si la mise à jour a réussi
     */
    public function update($id, $data) {
        try {
            $query = "UPDATE " . $this->table_name . " 
                     SET date_heure = :date_heure, equipe_adverse = :equipe_adverse, lieu = :lieu, 
                         resultat = :resultat, score_propre = :score_propre, score_adverse = :score_adverse, 
                         commentaire_match = :commentaire_match
                     WHERE id_match = :id";
            
            $stmt = $this->db->prepare($query);
            
            // Liaison des paramètres
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->bindValue(":date_heure", $data['date_heure']);
            $stmt->bindValue(":equipe_adverse", trim($data['equipe_adverse']), PDO::PARAM_STR);
            $stmt->bindValue(":lieu", $data['lieu'], PDO::PARAM_STR);
            $stmt->bindValue(":resultat", $data['resultat'], PDO::PARAM_STR);
            $stmt->bindValue(":score_propre", $data['score_propre'] ?? null, PDO::PARAM_INT);
            $stmt->bindValue(":score_adverse", $data['score_adverse'] ?? null, PDO::PARAM_INT);
            $stmt->bindValue(":commentaire_match", trim($data['commentaire_match'] ?? ''), PDO::PARAM_STR);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur DAO update: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Supprime un match et ses participations associées (transactionnel)
     * @param int $id ID du match à supprimer
     * @return bool True si la suppression a réussi
     */
    public function delete($id) {
        try {
            $this->db->beginTransaction();  // Début de la transaction
            
            // Supprimer les participations d'abord (clé étrangère)
            $this->deleteParticipants($id);
            
            // Supprimer le match
            $query = "DELETE FROM " . $this->table_name . " WHERE id_match = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $success = $stmt->execute();
            
            $this->db->commit();  // Validation de la transaction
            return $success;
        } catch (PDOException $e) {
            $this->db->rollBack();  // Annulation en cas d'erreur
            error_log("Erreur DAO delete: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère les prochains matchs à venir
     * @return array Liste des matchs à venir
     */
    public function getProchains() {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE date_heure > NOW() ORDER BY date_heure ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erreur DAO getProchains: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupère les matchs terminés
     * @return array Liste des matchs terminés
     */
    public function getTermines() {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE resultat != 'À venir' ORDER BY date_heure DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erreur DAO getTermines: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupère les participants d'un match
     * @param int $match_id ID du match
     * @return array Liste des participants avec leurs informations
     */
    public function getParticipants($match_id) {
        try {
            $query = "SELECT p.*, j.nom, j.prenom, j.numero_licence 
                    FROM Participer p 
                    JOIN Joueur j ON p.id_joueur = j.id_joueur 
                    WHERE p.id_match = :match_id
                    ORDER BY p.titulaire DESC, j.nom ASC, j.prenom ASC";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":match_id", $match_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erreur DAO getParticipants: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Définit les participants d'un match (transactionnel)
     * @param int $match_id ID du match
     * @param array $participants Liste des participants
     * @return bool True si l'opération a réussi
     */
    public function setParticipants($match_id, $participants) {
        try {
            $this->db->beginTransaction();  // Début de la transaction

            // Supprimer les anciennes participations
            $this->deleteParticipants($match_id);

            // Insérer les nouvelles participations
            $query = "INSERT INTO Participer (id_joueur, id_match, titulaire, evaluation, libelle_poste) 
                     VALUES (:id_joueur, :id_match, :titulaire, :evaluation, :libelle_poste)";
            $stmt = $this->db->prepare($query);

            foreach ($participants as $participant) {
                $stmt->bindValue(":id_joueur", $participant['id_joueur'], PDO::PARAM_INT);
                $stmt->bindValue(":id_match", $match_id, PDO::PARAM_INT);
                $stmt->bindValue(":titulaire", $participant['titulaire'], PDO::PARAM_BOOL);
                $stmt->bindValue(":evaluation", $participant['evaluation'] ?? null, 
                               $participant['evaluation'] ? PDO::PARAM_INT : PDO::PARAM_NULL);
                $stmt->bindValue(":libelle_poste", $participant['libelle_poste'] ?? null, 
                               $participant['libelle_poste'] ? PDO::PARAM_STR : PDO::PARAM_NULL);
                
                $stmt->execute();
            }

            $this->db->commit();  // Validation de la transaction
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();  // Annulation en cas d'erreur
            error_log("Erreur DAO setParticipants: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Supprime tous les participants d'un match (méthode privée)
     * @param int $match_id ID du match
     * @return bool True si la suppression a réussi
     * @throws PDOException Si une erreur survient
     */
    private function deleteParticipants($match_id) {
        try {
            $query = "DELETE FROM Participer WHERE id_match = :match_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":match_id", $match_id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur DAO deleteParticipants: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Récupère les statistiques globales des matchs
     * @return array Statistiques : total_matchs, matchs_gagnes, matchs_perdus, matchs_nuls
     */
    public function getStatsMatchs() {
        try {
            $query = "SELECT 
                    COUNT(*) AS total_matchs,
                    SUM(CASE WHEN resultat = 'Victoire' THEN 1 ELSE 0 END) AS matchs_gagnes,
                    SUM(CASE WHEN resultat = 'Défaite' THEN 1 ELSE 0 END) AS matchs_perdus,
                    SUM(CASE WHEN resultat = 'Nul' THEN 1 ELSE 0 END) AS matchs_nuls
                  FROM " . $this->table_name . "
                  WHERE resultat != 'À venir'";

            $stmt = $this->db->prepare($query);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Erreur DAO getStatsMatchs: " . $e->getMessage());
            // Retour de valeurs par défaut
            return [
                'total_matchs' => 0,
                'matchs_gagnes' => 0,
                'matchs_perdus' => 0,
                'matchs_nuls' => 0
            ];
        }
    }
    
    /**
     * Met à jour l'évaluation d'un participant à un match
     * @param int $match_id ID du match
     * @param int $joueur_id ID du joueur
     * @param int $evaluation Nouvelle évaluation
     * @return bool True si la mise à jour a réussi
     */
    public function updateParticipantEvaluation($match_id, $joueur_id, $evaluation) {
        try {
            $query = "UPDATE Participer 
                     SET evaluation = :evaluation 
                     WHERE id_match = :match_id AND id_joueur = :joueur_id";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":evaluation", $evaluation, PDO::PARAM_INT);
            $stmt->bindValue(":match_id", $match_id, PDO::PARAM_INT);
            $stmt->bindValue(":joueur_id", $joueur_id, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur DAO updateParticipantEvaluation: " . $e->getMessage());
            return false;
        }
    }
}