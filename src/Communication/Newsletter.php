<?php

namespace App\Communication;

use App\Model\Model;

/**
 * Classe de gestion de la newsletter
 */
class Newsletter
{
    /** @var string Nom de la table. */
    const TABLE_NAME = "newsletters";

    /**
     * Permet d'envoyer un email à la newsletter.
     */

    /**
     * Retourne toutes les addresse email de la newsletter : ceux qui se sont abonné
     * et ceux qui sont inscrit.
     * 
     * @return array
     */
    public static function all()
    {
        $req = Model::connectToDb()->query("SELECT email_address FROM " . self::TABLE_NAME);

        $emails = [];
        foreach ($req->fetchAll() as $r) {
            $emails[] = $r["email_address"];
        }

        return $emails;
    }

    /**
     * Permet d'enregistrer une adresse email dans la newsletter.
     * 
     * @param string $emailAddress
     * @return bool
     */
    public static function register(string $emailAddress)
    {
        if(!self::emailAddressIsset($emailAddress)) {
            if (self::save($emailAddress)) {
                return true;
            }
        } else {
            return true;
        }
    }

    /**
     * Insère une adresse email dans la table.
     * 
     * @param string $emailAddress
     * @return bool
     */
    private static function save(string $emailAddress)
    {
        $req = Model::connectToDb()->prepare("INSERT INTO " . self::TABLE_NAME . "(email_address) VALUES(:email_address)");

        $req->execute([
            "email_address" => $emailAddress
        ]);

        return true;
    }

    /**
     * Permet de vérifier qu'une adresse email est dans la newsletter.
     * 
     * @param string $emailAddress
     * @return bool
     */
    public static function emailAddressIsset(string $emailAddress)
    {
        return in_array($emailAddress, self::all());
    }
}