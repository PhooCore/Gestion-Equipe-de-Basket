<?php
require_once __DIR__ . '/../config/Database.php';

/**
 * Classe JoueurDAO
 * Gère toutes les opérations de base de données liées aux joueurs
 */
class JoueurDAO {
    private $db;           // Connexion à la base de données
    private $table = 'Joueur';  // Nom de la table

    /**
     * Constructeur - Initialise la connexion à la base de données
     */
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Récupère tous les joueurs
     * @return array Tableau de tous les joueurs triés par nom et prénom
     */
    public function getAll() {
        try {
            $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY nom, prenom");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erreur DAO getAll: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupère un joueur par son ID
     * @param int $id ID du joueur
     * @return array|false Données du joueur ou false en cas d'erreur
     */
    public function getById($id) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE id_joueur = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Erreur DAO getById: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Crée un nouveau joueur
     * @param array $data Données du joueur
     * @return bool True si la création a réussi
     */
    public function create($data) {
        try {
            $sql = "INSERT INTO {$this->table} (numero_licence, nom, prenom, date_naissance, taille, poids, statut) 
                    VALUES (:numero_licence, :nom, :prenom, :date_naissance, :taille, :poids, :statut)";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':numero_licence' => $data['numero_licence'],
                ':nom' => $data['nom'],
                ':prenom' => $data['prenom'],
                ':date_naissance' => $data['date_naissance'],
                ':taille' => $data['taille'],
                ':poids' => $data['poids'],
                ':statut' => $data['statut']
            ]);
        } catch (PDOException $e) {
            error_log("Erreur DAO create: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Met à jour les informations d'un joueur
     * @param int $id ID du joueur
     * @param array $data Nouvelles données
     * @return bool True si la mise à jour a réussi
     */
    public function update($id, $data) {
        try {
            $sql = "UPDATE {$this->table} 
                    SET numero_licence = :numero_licence, 
                        nom = :nom, 
                        prenom = :prenom, 
                        date_naissance = :date_naissance, 
                        taille = :taille, 
                        poids = :poids, 
                        statut = :statut
                    WHERE id_joueur = :id";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':numero_licence' => $data['numero_licence'],
                ':nom' => $data['nom'],
                ':prenom' => $data['prenom'],
                ':date_naissance' => $data['date_naissance'],
                ':taille' => $data['taille'],
                ':poids' => $data['poids'],
                ':statut' => $data['statut']
            ]);
        } catch (PDOException $e) {
            error_log("Erreur DAO update: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Supprime un joueur
     * @param int $id ID du joueur à supprimer
     * @return bool True si la suppression a réussi
     */
    public function delete($id) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE id_joueur = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur DAO delete: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Vérifie si un joueur existe par son numéro de licence
     * @param string $numero_licence Numéro de licence à vérifier
     * @param int|null $exclude_id ID à exclure de la recherche (pour les mises à jour)
     * @return bool True si le joueur existe
     */
    public function joueurExists($numero_licence, $exclude_id = null) {
        try {
            $sql = "SELECT id_joueur FROM {$this->table} WHERE numero_licence = :numero_licence";
            
            // Exclusion d'un ID spécifique (utile pour les mises à jour)
            if ($exclude_id) {
                $sql .= " AND id_joueur != :exclude_id";
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':numero_licence', $numero_licence, PDO::PARAM_STR);
            
            if ($exclude_id) {
                $stmt->bindValue(':exclude_id', $exclude_id, PDO::PARAM_INT);
            }
            
            $stmt->execute();
            return $stmt->fetch() !== false;
        } catch (PDOException $e) {
            error_log("Erreur DAO joueurExists: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère les joueurs par statut
     * @param string $statut Statut des joueurs à récupérer
     * @return array Liste des joueurs avec le statut spécifié
     */
    public function getByStatut($statut) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE statut = :statut ORDER BY nom, prenom";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':statut', $statut, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erreur DAO getByStatut: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Recherche des joueurs par terme
     * @param string $term Terme de recherche
     * @return array Liste des joueurs correspondant à la recherche
     */
    public function search($term) {
        try {
            $sql = "SELECT * FROM {$this->table} 
                    WHERE nom LIKE :term OR prenom LIKE :term OR numero_licence LIKE :term
                    ORDER BY nom, prenom";
            $stmt = $this->db->prepare($sql);
            $searchTerm = "%$term%";
            $stmt->bindValue(':term', $searchTerm, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erreur DAO search: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupère les statistiques détaillées par joueur
     * @return array Tableau de statistiques pour chaque joueur
     */
    public function getStatsParJoueur() {
        $sql = "
        SELECT 
            j.id_joueur,
            j.nom,
            j.prenom,
            j.statut,

            COUNT(p.id_match) AS matchs_joues,
            SUM(p.titulaire = 1) AS titularisations,
            SUM(p.titulaire = 0) AS remplacements,
            ROUND(AVG(p.evaluation), 2) AS moyenne_evaluation,

            SUM(CASE WHEN m.resultat = 'Victoire' AND p.id_match IS NOT NULL THEN 1 ELSE 0 END) AS victoires_jouees,

            (
                SELECT libelle_poste
                FROM Participer p2
                WHERE p2.id_joueur = j.id_joueur
                GROUP BY libelle_poste
                ORDER BY COUNT(*) DESC
                LIMIT 1
            ) AS poste_prefere

        FROM Joueur j
        LEFT JOIN Participer p ON j.id_joueur = p.id_joueur
        LEFT JOIN Match_basket m ON p.id_match = m.id_match
        GROUP BY j.id_joueur
        ORDER BY j.nom, j.prenom
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Calcule le nombre de sélections consécutives d'un joueur
     * @param int $id_joueur ID du joueur
     * @return int Nombre de matchs consécutifs où le joueur a été sélectionné
     */
    public function getSelectionsConsecutives($id_joueur) {
        $sql = "
            SELECT m.id_match
            FROM Match_basket m
            LEFT JOIN Participer p 
                ON m.id_match = p.id_match 
                AND p.id_joueur = :id
            WHERE m.resultat != 'À venir'
            ORDER BY m.date_heure DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id_joueur, PDO::PARAM_INT);
        $stmt->execute();

        $count = 0;
        foreach ($stmt->fetchAll() as $row) {
            if ($row['id_match'] === null) {
                break; // rupture de la série
            }
            $count++;
        }
        return $count;
    }
}