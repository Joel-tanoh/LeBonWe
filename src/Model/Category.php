<?php

namespace App\Model;

use App\Database\SqlQueryFormater;
use App\Model\Post\Announce;

/**
 * Classe de gestion des catégories.
 */
class Category extends Model
{
    protected $announces = [];
    private $subCategories = [];
    private $iconClass;
    const TABLE_NAME = "ind_categories";

    /**
     * Constructeur d'une catégorie.
     * 
     * @param int|string $id Id de la catégorie.
     */
    public function __construct($id)
    {
        $queryFormatter = new SqlQueryFormater();

        $query = $queryFormatter->select("id, title, slug, created_at, updated_at, description, icon_class")
            ->from(self::TABLE_NAME)
            ->where("id = ?")
            ->returnQueryString();

        $req = parent::connectToDb()->prepare($query);
        $req->execute([$id]);

        $result = $req->fetch();

        $this->id = $result["id"];
        $this->title = $result["title"];
        $this->slug = $result["slug"];
        $this->createdAt = $result["created_at"];
        $this->updatedAt = $result["updated_at"];
        $this->description = $result["description"];
        $this->iconClass = $result["icon_class"];
        $this->tableName = self::TABLE_NAME;
    }

    /**
     * Retourne les annonces postées qui appartiennent à cette catégorie.
     * 
     * @param string $status
     * 
     * @return array
     */
    public function getAnnounces(string $status = null)
    {
        $announces = [];
        $query = "SELECT id FROM " . Announce::TABLE_NAME . " WHERE id_category = ?";

        if (null !== $status) {
            $query .= " AND status = ? ORDER BY created_at DESC";
            $req = parent::connectToDb()->prepare($query);
            $req->execute([
                $this->id,
                Announce::convertStatus($status)
            ]);

        } else {
            $query .= " ORDER BY created_at DESC";
            $req = parent::connectToDb()->prepare($query);
            $req->execute([$this->id]);
        }

        $result = $req->fetchAll();

        foreach($result as $announce) {
            $announces[] = new Announce($announce["id"]);
        }

        return $announces;
    }

    /**
     * Retourne le nombre d'annonces appartenant à cette catégorie.
     * 
     * @param string $status Le status des announces qu'on veut compter.
     * 
     * @return int
     */
    public function getAnnouncesNumber(string $status = null) : int
    {
        return count($this->getAnnounces($status));
    }

    /**
     * Retourne les sous-catégories de cette catégorie.
     * 
     * @return array
     */
    public function getSubCategories()
    {
        $req = parent::connectToDb()->prepare("SELECT id FROM " . SubCategory::TABLE_NAME . " WHERE id_category = ?");
        $req->execute([$this->id]);
        $result = $req->fetchAll();

        foreach($result as $subCategory) {
            $this->subCategories[] = new SubCategory($subCategory["id"]);
        }

        return $this->subCategories;
    }

    /**
     * Retourne la classe pour l'icône de l'élément courant.
     * 
     * @return string
     */
    public function getIconClass() : string
    {
        return $this->iconClass;
    }

    /**
     * Retourne toutes les catégories.
     * 
     * @return array
     */
    public static function getAll()
    {
        $req = self::connectToDb()->query("SELECT id FROM " . self::TABLE_NAME);

        $categories = [];
        foreach ($req->fetchAll() as $item) {
            $categories[] = new self($item["id"]);
        }

        return $categories;
    }

    /**
     * Permet de vérifier si la variable passée en paramètre est un slug
     * de catégorie.
     * 
     * @param $var
     * 
     * @return bool
     */
    public static function isCategorySlug($var) : bool
    {
        return in_array($var, parent::getSlugs(self::TABLE_NAME));
    }

}