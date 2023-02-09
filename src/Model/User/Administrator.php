<?php

namespace App\Model\User;

use App\Model\Post\Comment;

/**
 * Classe de gestion d'un administrateur.
 */
class Administrator extends Registered
{
    /**
     * Constructeur d'un User inscrit.
     * 
     * @param string $emailAddress
     */
    public function __construct(string $emailAddress)
    {
        parent::__construct($emailAddress);
        $this->type = 1;
    }

    /**
     * Permet à l'administrateur de changer le status d'un compte.
     * 
     * @param int   $itemId     L'id de l'item dont on veut changer le status
     * @param mixed $newStatus  La valeur du nouveau status, peut être une chaîne
     *                          de caractère ou un entier.
     * @param string $tableName Le nom de la table où se trouve l'item.
     * @return bool|null
     */
    public function changeStatus(int $itemId, $newStatus, string $tableName)
    {
        if (\is_string($newStatus)) {
            $newStatus = parent::convertStatus($newStatus);
        }

        $req = parent::connectToDb()->prepare("UPDATE $tableName SET status = :status WHERE id = :id");
        $req->execute([
            "status" => $newStatus,
            "id" => $itemId
        ]);

        return true;
    }

    /**
     * Permet à l'administrateur de supprimer un compte.
     * 
     * @param int $registeredId
     * @return bool|null
     */
    public function deleteRegistered(int $registeredId)
    {
        $query = "DELETE FROM " . self::TABLE_NAME . " WHERE id = ?";
        $req = parent::connectToDb()->prepare($query);
        if ($req->execute([$registeredId])) {
            return true;
        }
    }

    /**
     * Retourne les commentaires laissés par l'administrateur.
     * 
     * @return array
     */
    public function getComments()
    {
        $req = parent::connectToDb()->prepare("SELECT id FROM " . Comment::TABLE_NAME . " WHERE user_email_address = :user_email_address");
        $req->execute([
            "user_email_address" => $this->emailAddress
        ]);

        $comments = [];
        foreach ($req->fetchAll() as $comment) {
            $comments[] = new Comment($comment["id"]);
        }
        
        return $comments;
    }

    /**
     * Retourne la liste des adresse email des administrators.
     * 
     * @return array
     */
    public static function getEmailAddresses()
    {
        return parent::get("email_address", Administrator::TABLE_NAME, "type", 1);
    }

    /**
     * Retourne tous les administrateurs.
     * @return array
     */
    public static function getAll()
    {
        $rep = parent::connectToDb()->prepare("SELECT email_address FROM " . self::TABLE_NAME . " WHERE type = :type");
        $rep->execute([
            "type" => 1
        ]);
        
        $administrators = [];

        foreach ($rep->fetchAll() as $user) {
            $administrators[] = new self($user["email_address"]);
        }

        return $administrators;
    }

}