<?php
// Inclusion des fichiers nécessaires
require_once __DIR__ . '/../models/UserDAO.php';
require_once __DIR__ . '/../config/auth.php';


/**
 * Contrôleur d'authentification
 * Ce contrôleur gère les opérations de connexion, déconnexion et initialisation de l'administrateur
 */
class AuthController {
    private $userDAO; //Instance du DAO utilisateur

    public function __construct() {
        //Initialisation du DAO utilisateur
        $this->userDAO = new UserDAO();
    }

    /**
     * Authentifie un utilisateur
     * @param string $username Nom d'utilisateur
     * @param string $password Mot de passe
     * @return bool True si authentification réussie, false sinon
     */
    public function login($username, $password) {
        $user = $this->userDAO->verifyUser($username, $password);
        if ($user) {
            Auth::login($user['id'], 'entraineur'); 
            return true;
        }
        return false;
    }

    /**
     * Initialise le compte administrateur si inexistant
     * Crée un utilisateur par défaut avec identifiants prédéfinis
     */
    public function initializeAdmin() {
        if (!$this->userDAO->userExists('entraineur')) {
            $this->userDAO->createUser('entraineur', 'basket123');
        }
    }

    /**
     * Déconnecte l'utilisateur en cours
     */
    public function logout() {
        Auth::logout();
    }
}