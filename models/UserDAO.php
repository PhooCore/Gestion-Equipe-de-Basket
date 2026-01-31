<?php
require_once __DIR__ . '/../config/Database.php';

/**
 * Classe UserDAO
 * Gère toutes les opérations de base de données liées aux utilisateurs
 */
class UserDAO {
    private $db;                 // Connexion à la base de données
    private $table_name = "users";  // Nom de la table

    /**
     * Constructeur - Initialise la connexion à la base de données
     */
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Vérifie les identifiants d'un utilisateur
     * @param string $username Nom d'utilisateur
     * @param string $password Mot de passe (en clair)
     * @return array|false Données de l'utilisateur si authentifié, false sinon
     */
    public function verifyUser($username, $password) {
        $query = "SELECT id, username, password FROM " . $this->table_name . " WHERE username = :username";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        
        // Vérifie si l'utilisateur existe
        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch();
            
            // Authentification avec mot de passe de secours (pour développement)
            if ($password === 'basket123') {
                return $user;
            }

            // Authentification standard avec hashage
            if (password_verify($password, $user['password'])) {
                return $user;
            }
        }
        
        return false;
    }

    /**
     * Vérifie si un utilisateur existe
     * @param string $username Nom d'utilisateur à vérifier
     * @return bool True si l'utilisateur existe
     */
    public function userExists($username) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE username = :username";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }

    /**
     * Crée un nouvel utilisateur avec mot de passe hashé
     * @param string $username Nom d'utilisateur
     * @param string $password Mot de passe en clair
     * @return bool True si la création a réussi
     */
    public function createUser($username, $password) {
        // Hashage du mot de passe pour la sécurité
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $query = "INSERT INTO " . $this->table_name . " (username, password) VALUES (:username, :password)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":password", $hashed_password);
        
        return $stmt->execute();
    }
}