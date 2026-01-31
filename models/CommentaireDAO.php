<?php
// Inclusion du fichier de configuration de la base de données
require_once __DIR__ . '/../config/Database.php';

/**
 * Classe CommentaireDAO
 * Gère toutes les opérations de base de données liées aux commentaires
 */
class CommentaireDAO {
    private $db;          // Connexion à la base de données
    private $table = 'Commentaire_Joueur';  // Nom de la table

    /**
     * Constructeur - Initialise la connexion à la base de données
     */
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /** 
     * Récupérer tous les commentaires
     * @return array Tableau de tous les commentaires triés par date décroissante
     */
    public function getAll() {
        try {
            // Requête SQL pour récupérer tous les commentaires
            $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY date_commentaire DESC");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            // Journalisation de l'erreur et retour d'un tableau vide
            error_log("Erreur DAO getAll Commentaire: " . $e->getMessage());
            return [];
        }
    }

    /** 
     * Récupérer un commentaire par ID
     * @param int $id ID du commentaire
     * @return array|false Données du commentaire ou false en cas d'erreur
     */
    public function getById($id) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE id_commentaire = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Erreur DAO getById Commentaire: " . $e->getMessage());
            return false;
        }
    }

    /** 
     * Récupérer tous les commentaires d'un joueur
     * @param int $id_joueur ID du joueur
     * @return array Tableau des commentaires du joueur
     */
    public function getByJoueur($id_joueur) {
        try {
            $sql = "SELECT * FROM {$this->table} 
                    WHERE id_joueur = :id_joueur 
                    ORDER BY date_commentaire DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id_joueur', $id_joueur, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erreur DAO getByJoueur Commentaire: " . $e->getMessage());
            return [];
        }
    }

    /** 
     * Ajouter un commentaire
     * @param array $data Données du commentaire [texte, id_joueur]
     * @return bool True si l'insertion a réussi, false sinon
     */
    public function create($data) {
        try {
            $sql = "INSERT INTO {$this->table} (Texte, id_joueur)
                    VALUES (:texte, :id_joueur)";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':texte' => $data['texte'],
                ':id_joueur' => $data['id_joueur']
            ]);
        } catch (PDOException $e) {
            error_log("Erreur DAO create Commentaire: " . $e->getMessage());
            return false;
        }
    }

    /** 
     * Modifier un commentaire
     * @param int $id ID du commentaire à modifier
     * @param array $data Nouvelles données [texte]
     * @return bool True si la mise à jour a réussi
     */
    public function update($id, $data) {
        try {
            $sql = "UPDATE {$this->table}
                    SET Texte = :texte
                    WHERE id_commentaire = :id";

            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':texte' => $data['texte']
            ]);
        } catch (PDOException $e) {
            error_log("Erreur DAO update Commentaire: " . $e->getMessage());
            return false;
        }
    }

    /** 
     * Supprimer un commentaire
     * @param int $id ID du commentaire à supprimer
     * @return bool True si la suppression a réussi
     */
    public function delete($id) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE id_commentaire = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur DAO delete Commentaire: " . $e->getMessage());
            return false;
        }
    }
}