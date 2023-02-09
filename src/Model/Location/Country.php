<?php

namespace App\Model\Location;

use App\Action\Create\InsertInDb;

/** Classe de gestion de pays. */
class Country extends Location
{
    /** @var string Le nom de la table */
    const TABLE_NAME = "countries";

    /**
     * Constructeur d'un pays.
     * 
     * @param int $id
     */
    public function __construct(int $id)
    {
        $req = parent::connectToDb()->prepare("SELECT id, name FROM " . self::TABLE_NAME . " WHERE id = :id");
        $req->execute([
            "id" => $id
        ]);
        $result = $req->fetch();

        $this->id = $result["id"];
        $this->name = $result["name"];
        $this->tableName = self::TABLE_NAME;
    }


    /**
     * Retourne toutes les villes.
     * 
     * @return array
     */
    public static function getAll()
    {
        $req = parent::connectToDb()->prepare("SELECT id FROM " . self::TABLE_NAME);

        $countries = [];
        foreach ($req->fetchAll() as $country) {
            $countries[] = new self($country["id"]);
        }

        return $countries;
    }

    /**
     * Permet d'ajouter une nouvelle ville.
     */
    public static function create()
    {
        $data["name"] = htmlspecialchars($_POST["name"]);
        $insertion = new InsertInDb($data, self::TABLE_NAME);
        $insertion->run;
    }

    /**
     * Retourne les villes de ce pays.
     * @return array
     */
    public function getCountries()
    {
        $req = parent::connectToDb()->prepare("SELECT id FROM " . Town::TABLE_NAME . " WHERE id_country = :id_country");
        $req->execute([
            "id_country" => $this->id
        ]);

        $towns = [];
        foreach ($req->fetchAll() as $town) {
            $towns[] = new self($town["id"]);
        }

        return $towns;
    }

}