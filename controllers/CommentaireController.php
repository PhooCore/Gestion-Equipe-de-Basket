<?php
// Inclusion du DAO commentaire
require_once __DIR__ . '/../models/CommentaireDAO.php';

/**
 * Contrôleur des commentaires
 * Gère les opérations CRUD sur les commentaires des joueurs
 */
class CommentaireController {
    private $commentaireDAO; // Instance du DAO commentaire

    public function __construct() {
        $this->commentaireDAO = new CommentaireDAO();
    }

    /** 
    *Récupère tous les commentaires
     * @return array Liste des commentaires
     */
    public function getAll() {
        return $this->commentaireDAO->getAll();
    }

    /**
     * Récupère un commentaire par son ID
     * @param int $id ID du commentaire
     * @return array|null Données du commentaire ou null
     */
    public function getById($id) {
        return $this->commentaireDAO->getById($id);
    }

    /**
     * Récupère les commentaires d'un joueur
     * @param int $id_joueur ID du joueur
     * @return array Liste des commentaires du joueur
     */
    public function getByJoueur($id_joueur) {
        return $this->commentaireDAO->getByJoueur($id_joueur);
    }

    /**
     * Crée un nouveau commentaire
     * @param array $data Données du commentaire
     * @return bool True si création réussie
     */
    public function create($data) {
        return $this->creerCommentaire($data);
    }

    /**
     * Met à jour un commentaire existant
     * @param int $id ID du commentaire
     * @param array $data Nouvelles données
     * @return bool True si mise à jour réussie
     */
    public function update($id, $data) {
        return $this->modifierCommentaire($id, $data);
    }

    /**
     * Supprime un commentaire
     * @param int $id ID du commentaire
     * @return bool True si suppression réussie
     */
    public function delete($id) {
        return $this->supprimerCommentaire($id);
    }


    /** Liste complète */
    public function listerCommentaires() {
        return $this->commentaireDAO->getAll();
    }

    /** Un seul commentaire */
    public function getCommentaire($id) {
        return $this->commentaireDAO->getById($id);
    }

    /** Commentaires d’un joueur */
    public function getCommentairesJoueur($id_joueur) {
        return $this->commentaireDAO->getByJoueur($id_joueur);
    }

    /** Ajout */
    public function creerCommentaire($data) {
        $erreurs = $this->validerDonnees($data);
        if (!empty($erreurs)) {
            throw new Exception(implode(', ', $erreurs));
        }

        return $this->commentaireDAO->create($data);
    }

    /** Modification */
    public function modifierCommentaire($id, $data) {
        if (empty(trim($data['texte']))) {
            throw new Exception("Le texte du commentaire ne peut pas être vide");
        }

        return $this->commentaireDAO->update($id, $data);
    }

    /** Suppression */
    public function supprimerCommentaire($id) {
        return $this->commentaireDAO->delete($id);
    }

    /** Validation */
    private function validerDonnees($data) {
        $erreurs = [];

        if (empty(trim($data['texte']))) {
            $erreurs[] = "Le texte du commentaire est obligatoire";
        }

        if (empty($data['id_joueur'])) {
            $erreurs[] = "L'id du joueur est obligatoire";
        }

        return $erreurs;
    }
}
