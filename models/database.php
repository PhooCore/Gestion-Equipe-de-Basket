<?php
// Inclusion du fichier de configuration de la base de données
require_once __DIR__ . '/../config/database.php';

/**
 * Classe Database - Gestionnaire de connexion à la base de données
 * Assure qu'une seule instance de connexion existe dans l'application
 */
class Database {
    private static $instance = null;  // Instance unique de la classe
    private $connection;               // Connexion PDO

    /**
     * Constructeur privé - Crée la connexion à la base de données
     * @throws Exception Si la connexion échoue
     */
    private function __construct() {
        // Construction du DSN (Data Source Name)
        $dsn = "mysql:host=" . DatabaseConfig::HOST . 
               ";dbname=" . DatabaseConfig::DB_NAME . 
               ";charset=" . DatabaseConfig::CHARSET;
        
        try {
            // Création de la connexion PDO
            $this->connection = new PDO($dsn, DatabaseConfig::USERNAME, DatabaseConfig::PASSWORD);
            // Configuration des attributs PDO
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            throw new Exception("Erreur de connexion: " . $e->getMessage());
        }
    }

    /**
     * Méthode statique pour obtenir l'instance unique (Singleton)
     * @return Database Instance de la classe Database
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    /**
     * Retourne la connexion PDO
     * @return PDO Objet de connexion à la base de données
     */
    public function getConnection() {
        return $this->connection;
    }
}
?>