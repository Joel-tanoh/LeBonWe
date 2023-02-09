<?php

namespace App\Model\Location;

use App\Action\Create\InsertInDb;

/** Classe de gestion de pays. */
class Town extends Location
{
    /** @var string Le nom de la table */
    const TABLE_NAME = "towns";

    /** @var App\Model\Location\Country Le pays dans lequel se trouve la ville. */
    private $country;

    /**
     * Constructeur d'une ville.
     * 
     * @param int $id
     */
    public function __construct(int $id)
    {
        $req = parent::connectToDb()->prepare("SELECT id, name, id_country FROM " . self::TABLE_NAME . " WHERE id = :id");
        $req->execute([
            "id" => $id
        ]);
        $result = $req->fetch();

        $this->id = $result["id"];
        $this->name = $result["name"];
        $this->country = new Country($result["id_country"]);
        $this->tableName = self::TABLE_NAME;
    }

    /**
     * Retourne le pays de cette ville.
     * @return App\Model\Location\Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Retourne toutes les villes.
     * 
     * @return array
     */
    public static function getAll()
    {
        $req = parent::connectToDb()->query("SELECT id FROM " . self::TABLE_NAME);

        $towns = [];
        foreach ($req->fetchAll() as $town) {
            $towns[] = new self($town["id"]);
        }

        return $towns;
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

}