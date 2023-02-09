<?php

namespace App\Model;

class BookLink extends Model
{
    /**
     * Nom de la table dans la base de données.
     */
    const TABLE_NAME = "book_link";
    
    /**
     * Permet de récupérer le lien de l'image.
     * 
     * @return string
     */
    public static function getBookLink() : string
    {
        $req = parent::connectToDb()->query("SELECT link FROM " . self::TABLE_NAME);
        return $req->fetch()["link"];
    }

    /**
     * Permet de mettre à jour le lien du livre en base de données.
     * 
     * @param string $bookLink Le lien du livre.
     * 
     * @return bool
     */
    public static function updateBookLink(string $bookLink) : bool
    {
        $query = "UPDATE " . self::TABLE_NAME . " SET link = :book_link";
        $req = parent::connectToDb()->prepare($query);
        if ($req->execute([
            "book_link" => $bookLink
        ])) {
            return true;
        }
        return false;
    }
}