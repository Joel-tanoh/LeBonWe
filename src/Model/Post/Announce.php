<?php

namespace App\Model\Post;

use App\Action\Create\InsertInDb;
use App\Action\Update\UpdateDb;
use App\Auth\Session;
use App\Auth\Cookie;
use App\Communication\MailContentManager;
use App\File\Image\Image;
use App\Database\SqlQueryFormater;
use App\File\File;
use App\Model\User\Registered;
use App\Utility\Utility;
use App\Model\Post\Comment;
use App\Model\Category;

/**
 * Classe de gestion d'une annonce.
 */
class Announce extends Post
{
    private $price;
    private $userToJoin;
    private $phoneNumber;
    private $location;
    private $direction;
    private $type;
    private $status;
    private $views;
    private $iconClass;
    private $premiumImgPath;
    private $premiumImgSrc;
    private $productImgPath;
    private $productImgSrc;
    private $productInfoImgSrc;
    private $artInFooterImgPath;
    private $artInFooterImgSrc;
    const TABLE_NAME = "ind_announces";
    const IMG_DIR_PATH = Image::IMG_DIR_PATH . DIRECTORY_SEPARATOR . "productinfo" . DIRECTORY_SEPARATOR;
    const IMG_DIR_URL = Image::IMG_DIR_URL . "/productinfo";
    const DEFAULT_THUMBS = Image::IMG_DIR_URL . "/defaul-thumbs" . Image::EXTENSION;
    private static $statutes = ["suspended", "pending", "validated", "premium"];

    /**
     * Constructeur de l'objet annonce.
     * 
     * @param int $id
     */
    public function __construct(int $id)
    {
        $queryFormatter = new SqlQueryFormater();

        $query = $queryFormatter->select(
            "id, title, description, slug, id_category, id_sub_category, price,
            user_email_address, user_to_join, phone_number, location, direction, type,
            status, created_at, posted_at, updated_at, views, icon_class"
        )->from(self::TABLE_NAME)->where("id = ?")->returnQueryString();

        $req = parent::connectToDb()->prepare($query);
        $req->execute([$id]);

        $result = $req->fetch();

        $this->id = $result["id"];
        $this->title = $result["title"];
        $this->description = $result["description"];
        $this->slug = $result["slug"];
        $this->category = new Category($result["id_category"]);
        $this->subCategory = $result["id_sub_category"];
        $this->price = $result["price"];
        $this->userEmailAddress = $result["user_email_address"];
        $this->owner = new Registered($result["user_email_address"]);
        $this->userToJoin = $result["user_to_join"];
        $this->phoneNumber = $result["phone_number"];
        $this->location = $result["location"];
        $this->direction = $result["direction"];
        $this->type = $result["type"];
        $this->status = (int)$result["status"];
        $this->createdAt = $result["created_at"];
        $this->postedAt = $result["posted_at"];
        $this->updatedAt = $result["updated_at"];
        $this->views = (int)$result["views"];
        $this->iconClass = $result["icon_class"];
        $this->tableName = self::TABLE_NAME;

        $this->premiumImgPath = Image::PREMIUM_DIR_PATH . $this->slug . Image::EXTENSION;
        $this->premiumImgSrc = Image::PREMIUM_DIR_URL . "/" . $this->slug . Image::EXTENSION;

        $this->productImgPath = Image::PRODUCT_DIR_PATH . $this->slug . Image::EXTENSION;
        $this->productImgSrc = Image::PRODUCT_DIR_URL . "/" . $this->slug . Image::EXTENSION;

        $this->productInfoImgPath = Image::PRODUCT_INFO_DIR_PATH . $this->slug . Image::EXTENSION;
        $this->productInfoImgSrc = Image::PRODUCT_INFO_DIR_URL . "/" . $this->slug . Image::EXTENSION;

        $this->artInFooterImgPath = Image::ART_IN_FOOTER_PATH . $this->slug . Image::EXTENSION;
        $this->artInFooterImgSrc = Image::ART_IN_FOOTER_URL . "/" . $this->slug . Image::EXTENSION;
    }

    /**
     * Retourne la catégorie de l'annonce.
     * 
     * @return \App\Model\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Retourne la sous-catégorie de l'annonce.
     * 
     * @return SubCategory
     */
    public function getSubCategory()
    {
        return $this->subCategory;
    }

    /**
     * Retourne l'adresse email de l'utilisateur à joindre.
     * 
     * @return string
     */
    public function getUserToJoin()
    {
        if ($this->userToJoin) {
            return $this->userToJoin;
        } else {
            return $this->owner->getEmailAddress();
        }
    }

    /**
     * Retourne l'addresse email de celui qui a posté l'annonce.
     * 
     * @return string
     */
    public function getUserEmailAddress()
    {
        return $this->userEmailAddress;
    }

    /**
     * Retourne le numéro de téléphone enregistré lors de la création de l'annonce.
     * 
     * @return string
     */
    public function getPhoneNumber()
    {
        if ($this->phoneNumber) {
            return $this->phoneNumber;
        } else {
            return $this->owner->getPhoneNumber();
        }
    }

    /**
     * Retourne l'état de l'annonce.
     * 
     * @param string $lang
     * @return string
     */
    public function getStatus(string $lang = null)
    {
        if (in_array($lang, ["fr", "french", "français"])) {
            $statusInFrench = ["suspendue", "en attente", "validée", "premium"];
            return ucfirst($statusInFrench[$this->status]);
        } else {
            return ucfirst(self::$statutes[$this->status]);
        }
    }

    /**
     * Retourne la date de post de l'annonce.
     * 
     * @return string
     */
    public function getPostedAt()
    {
        return Utility::formatDate($this->postedAt, "day", true);
    }

    /**
     * Retourne le nombre de vue de l'annonce.
     * 
     * @return int
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * Retourne l'icon de l'annonce.
     * 
     * @return string
     */
    public function getIconClass()
    {
        return $this->iconClass;
    }

    /**
     * Retourne la source de l'image dans la carte de premium de
     * format 600x400.
     * 
     * @return string
     */
    public function getPremiumImgSrc()
    {
        if (file_exists($this->premiumImgPath))
            return $this->premiumImgSrc;
        else
            return Image::PREMIUM_DIR_URL . "/default-img" . Image::EXTENSION;
    }

    /**
     * Retourne la source de l'image dans la carte product de format
     * 640x420.
     * 
     * @return string
     */
    public function getProductImgSrc()
    {
        if (file_exists($this->productImgPath))
            return $this->productImgSrc;
        else
            return Image::PRODUCT_DIR_URL . "/default-img" . Image::EXTENSION;
    }
    
    /**
     * Retourne la source de l'image qui se trouve dans le dossier
     * product info de format 625x415.
     * 
     * @return string
     */
    public function getProductInfoImgSrc()
    {
        if (file_exists(Image::PRODUCT_INFO_DIR_PATH . $this->slug . Image::EXTENSION))
            return $this->productInfoImgSrc;
        else
            return Image::PRODUCT_INFO_DIR_URL . "/default-img" . Image::EXTENSION;
    }

    /**
     * Retourne le lien de l'image dans le footer.
     * 
     * @return string
     */
    public function getArtInFooterImgSrc()
    {
        if (file_exists($this->artInFooterImgPath))
            return $this->artInFooterImgSrc;
        else 
            return Image::ART_IN_FOOTER_URL . "/default-img" . Image::EXTENSION;
    }

    /**
     * Retourne toutes les images de product info.
     * 
     * @return array
     */
    public function getProductAllImg()
    {
        if (file_exists(Image::PRODUCT_INFO_DIR_PATH . "/$this->slug" . "-0" . Image::EXTENSION)) {
            return [
                Image::PRODUCT_INFO_DIR_URL . "/$this->slug" . "-0" . Image::EXTENSION,
                Image::PRODUCT_INFO_DIR_URL . "/$this->slug" . "-1" . Image::EXTENSION,
                Image::PRODUCT_INFO_DIR_URL . "/$this->slug" . "-2" . Image::EXTENSION,
            ];
        } else {
            return [
                Image::PRODUCT_INFO_DIR_URL . "/default-img" . Image::EXTENSION,
            ];
        }
    }

    /**
     * Retourne la location de l'annonce.
     * 
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Retourne la direction de l'annonce.
     * @return string
     */
    public function getDirection()
    {
        return ucfirst($this->direction);
    }

    /**
     * Retourne le type d'annonce, particulier ou professionnel.
     * @return string
     */
    public function getType()
    {
        return ucfirst($this->type);
    }

    /**
     * Retourne le lien de l'annonce.
     * 
     * @param string $type Permet de dire si on veut le lien relatif au site
     *                     ou le lien total contenant le domaine.
     * 
     * @return string
     */
    public function getLink(string $type = null)
    {
        if (in_array($type, ["all", "total", "domain", "with domain", "with_domain"])) {
            return APP_URL . "/" . $this->category->getSlug() . "/" . $this->slug;
        } else {
            return $this->category->getSlug() . "/" . $this->slug;
        }
    }

    /**
     * Retourne le lien vers la page qui permet de modifier l'annonce.
     * 
     * @return string
     */
    public function getManageLink(string $action = null)
    {
        return null === $action
            ? $this->getLink() // Si on ne passe pas d'action on affiche l'annonce
            : $this->getLink()."/$action"; // Si on passe une annonce.
    }

    /**
     * Retourne le prix.
     * 
     * @param bool $withCurrency
     * @return string
     */
    public function getPrice(bool $withCurrency = true)
    {
        if ($this->price === null) {
            return "Gratuit";
        } elseif ($this->price == "price_on_call") {
            return "Prix à l'appel";
        } else {
            return $withCurrency ? $this->price . " F CFA" : (int)$this->price;
        }
    }

    /**
     * Retourne les commentaires de cette annonce.
     */
    public function getComments()
    {
        $req = parent::connectToDb()->prepare("SELECT id FROM " . Comment::TABLE_NAME . " WHERE subject_id = ?");
        $req->execute([$this->id]);
        $result = $req->fetchAll();
        
        $comments = [];
        foreach ($result as $comment) {
            $comments[] = new Comment($comment["id"]);
        }

        return $comments;
    }

    /**
     * Retourne le dernier commentaire d'une annonce.
     * 
     * @return \App\Model\Post\Comment
     */
    public function getLastComment()
    {
        $req = parent::connectToDb()->prepare(
            "SELECT id FROM " . Comment::TABLE_NAME . " WHERE subject_id = :subject_id AND subject_type = :subject_type ORDER BY posted_at DESC limit 0, 1"
        );

        $req->execute([
            "subject_id" => $this->id,
            "subject_type" => $this->tableName
        ]);

        if ($req->fetch()) {
            $commentId = $req->fetch()["id"];
            if ($commentId) {
                return new Comment((int)$commentId);
            }
        }
    }

    /**
     * Permet de vérifier si c'est une annonce suspendue.
     * @return bool
     */
    public function isSuspended() : bool
    {
        return $this->status === 0;
    }

    /**
     * Permet de vérifier si c'est une annonce en attente de validation.
     * @return bool
     */
    public function isPending() : bool
    {
        return $this->status === 1;
    }

    /**
     * Permet de vérifier si c'est une annonce validée.
     * @return bool
     */
    public function isValidated() : bool
    {
        return $this->status === 2 || $this->isPremium();
    }

    /**
     * Permet de vérifier si c'est une annonce premium.
     * @return bool
     */
    public function isPremium() : bool
    {
        return $this->status === 3;
    }

    /**
     * Retourne un certain nombre d'annonces en fonction des paramètres
     * passés à la méthode.
     * 
     * @param int $nbr      Pour spécifier le nombre d'annonce qu'on veut récupérer.
     * @param int $start    L'élément à partir duquel on veut récupérer les annonces.
     */
    public static function getAll(int $nbr = null, int $start = null)
    {
        $query = "SELECT id FROM " . self::TABLE_NAME;

        if ($nbr && !$start) {
            $query .= " LIMIT $nbr";
        } elseif ($nbr && $start) {
            $query .= " LIMIT $nbr OFFSET $start";
        }
        $query .= " ORDER BY created_at DESC";

        $req = parent::connectToDb()->query($query);
        $result = $req->fetchAll();

        $announces = [];
        foreach($result as $announce) {
            $announces[] = new self($announce["id"]);
        }

        return $announces;
    }

    /**
     * Retourne les annonces en fonction de la catégorie.
     * 
     * @param int $idCategory   L'Id de la catégorie.
     * @param int $nbr          Pour spécifier le nombre d'annonce qu'on veut récupérer.
     * @param int $start        L'élément à partir duquel on veut récupérer les annonces.
     * 
     * @return array
     */
    public static function getByCategory(int $idCategory, int $nbr = null, int $start = null) : array
    {
        $query = "SELECT id FROM " . self::TABLE_NAME . " WHERE id_category = :id_category";
        
        if ($nbr && !$start) {
            $query .= " LIMIT $nbr";
        } elseif ($nbr && $start) {
            $query .= " LIMIT $nbr OFFSET $start";
        }
        $query .= " ORDER BY created_at DESC";
        
        $req = parent::connectToDb()->prepare($query);
        $req->execute([
            "id_category" => $idCategory
        ]);
        $result = $req->fetchAll();

        $announces = [];
        foreach($result as $announce) {
            $announces[] = new self($announce["id"]);
        }

        return $announces;
    }

    /**
     * Retourne la liste des annonces selon le status.
     * 
     * @param string    $status     Le statut des annonces en format string.
     * @param int       $nbr        Pour spécifier le nombre d'annonce qu'on veut récupérer.
     * @param int       $start      L'élément à partir duquel on veut récupérer les annonces.
     * 
     * @return array
     */
    public static function getByStatus(string $status, int $nbr = null, int $start = null) : array
    {
        $query = "SELECT id FROM " . self::TABLE_NAME . " WHERE status = :status";

        if ($nbr && !$start) {
            $query .= " LIMIT $nbr";
        } elseif ($nbr && $start) {
            $query .= " LIMIT $nbr OFFSET $start";
        }
        $query .= " ORDER BY created_at DESC";

        $req = parent::connectToDb()->prepare($query);
        $req->execute([
            "status" => self::convertStatus($status)
        ]);

        $result = $req->fetchAll();

        $announces = [];
        foreach($result as $announce) {
            $announces[] = new self($announce["id"]);
        }

        return $announces;
    }

    /**
     * Retourne une liste d'annonces en fonction d'une catégorie et d'un statut.
     * 
     * @param int       $idCategory
     * @param string    $status
     * @param int       $nbr        Pour spécifier le nombre d'annonce qu'on veut récupérer.
     * @param int       $start      L'élément à partir duquel on veut récupérer les annonces.
     * 
     * @return array
     */
    public static function getByCategoryAndStatus(int $idCategory, string $status, int $nbr = null, int $start = null)
    {
        $query = "SELECT id FROM " . self::TABLE_NAME . " WHERE id_category = :id_category AND status = :status";
        
        if ($nbr && !$start) {
            $query .= " LIMIT $nbr";
        } elseif ($nbr && $start) {
            $query .= " LIMIT $nbr OFFSET $start";
        }
        $query .= " ORDER BY created_at DESC";
        
        $req = self::connectToDb()->prepare($query);
        $req->execute([
            "id_category"   => $idCategory
            , "status"      => self::convertStatus($status)
        ]);
        $result = $req->fetchAll();

        $announces = [];
        foreach($result as $announce) {
            $announces[] = new self($announce["id"]);
        }

        return $announces;
    }

    /**
     * Retourne le nombre total d'annonces postées.
     * 
     * @return int
     */
    public static function getAllNumber()
    {
        $query = "SELECT COUNT(id) as announces_number FROM " . self::TABLE_NAME;
        $req = parent::connectToDb()->query($query);
        $result = $req->fetch();
        return $result["announces_number"];
    }

    /**
     * Retourne les annonces par orde de création, de la plus récente à la plus
     * ancienne.
     * 
     * @param int $nbr Pour spécifier si on veut un nombre d'annonces précis.
     * 
     * @return array
     */
    public static function getLastPosted(int $nbr = null) : array
    {
        $query = "SELECT id FROM " . self::TABLE_NAME . " WHERE status IN (2, 3) ORDER BY status DESC, created_at DESC";

        if ($nbr) {
            $query .= " LIMIT 0, $nbr";
        }

        $req = parent::connectToDb()->query($query);
        $result = $req->fetchAll();

        $announces = [];

        foreach($result as $announce) {
            $announces[] = new self($announce["id"]);
        }

        return $announces;
    }

    /**
     * Retourne les annonces les plus vues.
     * 
     * @param int $nbr Pour spécifier si on veut un nombre d'annonces précis.
     * 
     * @return array
     */
    public static function getMostViewed(int $nbr = null) : array
    {
        $query = "SELECT id FROM " . self::TABLE_NAME . " WHERE status IN (2, 3) ORDER BY views DESC";

        if ($nbr) {
            $query .= " LIMIT 0, $nbr";
        }

        $req = parent::connectToDb()->query($query);
        $result = $req->fetchAll();

        $announces = [];
        foreach($result as $announce) {
            $announces[] = new self($announce["id"]);
        }

        return $announces;
    }

    /**
     * Permet de créer une nouvelle ligne d'annonce et d'enregistrer les données.
     * 
     * @return self
     */
    public static function create() : self
    {
        // $image = new Image();
        $data["title"] = htmlspecialchars($_POST["title"]);
        $data["description"] = htmlspecialchars($_POST["description"]);
        $data["id_category"] = htmlspecialchars($_POST["id_category"]);
        $data["location"] = htmlspecialchars($_POST["location"]);
        $data["type"] = htmlspecialchars($_POST["type"]);
        $data["direction"] = htmlspecialchars($_POST["direction"]);

        if (empty($_POST["price"]) && isset($_POST["price_on_call"])) {
            $data["price"] = "price_on_call";
        } else {
            $data["price"] = htmlspecialchars($_POST["price"]);
        }

        if (isset($_POST["usertype"]) && $_POST["usertype"] === "someone_else") {
            $data["user_to_join"] = $_POST["user_to_join"];
            $data["phone_number"] = $_POST["phone_number"];
        }

        $data["user_email_address"] = Session::getRegistered() ?? Cookie::getRegistered();

        $insertion = new InsertInDb($data, self::TABLE_NAME, DB_NAME, DB_LOGIN, DB_PASSWORD);
        $insertion->run();

        $currentAnnounce = new self($insertion->getPDO()->lastInsertId());

        $slug = Utility::slugify($_POST["title"]) . "-" . $currentAnnounce->getId();
        $currentAnnounce->set("slug", $slug, "id", $currentAnnounce->getId());

        $currentAnnounce = new self($currentAnnounce->getId()); // On réinstancie l'objet pour récupérer le slug

        if (File::fileIsUploaded("images")) {
            $currentAnnounce->saveImages($currentAnnounce->getSlug());
        }
        
        return $currentAnnounce;
    }

    /**
     * Permet de mettre à jour l'annonce.
     * 
     * @return bool
     */
    public function update()
    {
        // $image = new Image();
        $data["title"] = htmlspecialchars($_POST["title"]);
        $data["description"] = htmlspecialchars($_POST["description"]);
        $data["id_category"] = htmlspecialchars($_POST["id_category"]);
        $data["location"] = htmlspecialchars($_POST["location"]);
        $data["type"] = htmlspecialchars($_POST["type"]);
        $data["direction"] = htmlspecialchars($_POST["direction"]);
        
        //=== Fonctionnalité rétirée pour le moment ====/
        // $data["id_sub_category"] = htmlspecialchars($_POST["id_sub_category"]);

        //=== Si l'user veut qu'on l'appelle pour le prix ======================/
        if (empty($_POST["price"]) && isset($_POST["price_on_call"])) {
            $data["price"] = "price_on_call";
        } else {
            $data["price"] = htmlspecialchars($_POST["price"]);
        }

        //=== Si user à choisi un autre utilisateur à contacter =================/
        if ((isset($_POST["usertype"]) && $_POST["usertype"] === "someone_else")
            || (!empty($_POST["user_to_join"]) && !empty($_POST["phone_number"]))
        ) {
            $data["user_to_join"] = $_POST["user_to_join"];
            $data["phone_number"] = $_POST["phone_number"];
        }

        // Si le tire ne change pas
        if ($this->title === $_POST["title"]) {
            // nouvelles images
            if (!empty($_FILES["images"]["name"][0])) {
                // on enregistre les images avec les anciens noms
                $this->saveImages($this->slug);
            }
        }
        // Si le titre change
        else {
            // on reformate le slug = slug du tire + id
            $slug = Utility::slugify($_POST["title"]) ."-". $this->id;
            // on reformate le nom des images
            $imgName = $slug;
            // Si aucunes images postées
            if (empty($_FILES["images"]["name"][0])) {
                // on renomme les images
                $this->renameImages($imgName);
            } else {
                // Si des images ont été postées
                if (!empty($_FILES["images"]["name"][0])) {
                    // on supprime les anciennes
                    $this->deleteImages();
                    // on enregistre les nouvelles avec les noms
                    $this->saveImages($imgName);
                }
            }
            $this->set("slug", $slug, "id", $this->id);
        }

        // Mise à jour des données
        $update = new UpdateDb($data, DB_NAME, $this->tableName, DB_LOGIN, DB_PASSWORD, ["id" => $this->id]);
        $update->run();
        return true;
    }

    /**
     * Permet de supprimer une annonce.
     * 
     * @return bool
     */
    public function delete()
    {
        if ($this->deleteImages()) {
            if (parent::delete()) {
                return true;
            }
        }
    }

    /**
     * Retourne les annonces suspendues.
     * 
     * @param $idCategory On peut spécifier la catégorie des annonces à passer.
     * 
     * @return array
     */
    public static function getSuspended(int $idCategory = null) : array
    {
        $query = "SELECT id FROM ". self::TABLE_NAME . " WHERE status = 0";

        if ($idCategory) {
            $query .= " AND id_category = ?";
            $req = parent::connectToDb()->prepare($query);
            $req->execute([$idCategory]);
        } else {
            $req = parent::connectToDb()->query($query);
        }

        $announces = [];

        foreach($req->fetchAll() as $announce) {
            $announces[] = new self($announce["id"]);
        }

        return $announces;
    }

    /**
     * Retourne le nombre d'annonces suspendues.
     * 
     * @return int
     */
    public static function getSuspendedNumber(int $idCategory = null) : int
    {
        $query = "SELECT COUNT(id) as suspended_announces FROM ". self::TABLE_NAME . " WHERE status = 0";

        if ($idCategory) {
            $query .= " AND id_category = ?";
            $req = parent::connectToDb()->prepare($query);
            $req->execute([$idCategory]);
        } else {
            $req = parent::connectToDb()->query($query);
        }

        $result = $req->fetch();

        return $result["suspended_announces"];
    }

    /**
     * Retourne les annonces en attente.
     * 
     * @param $idCategory On peut spécifier la catégorie des annonces à passer.
     * 
     * @return array
     */
    public static function getPending(int $idCategory = null) : array
    {
        $query = "SELECT id FROM ". self::TABLE_NAME . " WHERE status = 1";

        if ($idCategory) {
            $query .= " AND id_category = ?";
            $req = parent::connectToDb()->prepare($query);
            $req->execute([$idCategory]);
        } else {
            $req = parent::connectToDb()->query($query);
        }

        $announces = [];

        foreach($req->fetchAll() as $announce) {
            $announces[] = new self($announce["id"]);
        }

        return $announces;
    }

    /**
     * Retourne le nombre d'annonces en attente.
     * 
     * @return int
     */
    public static function getPendingNumber(int $idCategory = null) : int
    {
        $query = "SELECT COUNT(id) as pending_announces FROM ". self::TABLE_NAME . " WHERE status = 1";

        if ($idCategory) {
            $query .= " AND id_category = ?";
            $req = parent::connectToDb()->prepare($query);
            $req->execute([$idCategory]);
        } else {
            $req = parent::connectToDb()->query($query);
        }

        $result = $req->fetch();

        return $result["pending_announces"];
    }

    /**
     * Retourne les annonces validées.
     * 
     * @return array
     */
    public static function getValidated(int $idCategory = null) : array
    {
        $query = "SELECT id FROM ". self::TABLE_NAME . " WHERE status IN (2, 3) ORDER BY created_at DESC";

        if ($idCategory) {
            $query .= " AND id_category = ?";
            $req = parent::connectToDb()->prepare($query);
            $req->execute([$idCategory]);
        } else {
            $req = parent::connectToDb()->query($query);
        }

        $announces = [];

        foreach($req->fetchAll() as $announce) {
            $announces[] = new self($announce["id"]);
        }

        return $announces;
    }

    /**
     * Retourne le nombre d'annonces validées.
     * 
     * @return int
     */
    public static function getValidatedNumber(int $idCategory = null) : int
    {
        $query = "SELECT COUNT(id) as validated_announces FROM ". self::TABLE_NAME . " WHERE status IN (2, 3)";

        if ($idCategory) {
            $query .= " AND id_category = ?";
            $req = parent::connectToDb()->prepare($query);
            $req->execute([$idCategory]);
        } else {
            $req = parent::connectToDb()->query($query);
        }

        $result = $req->fetch();

        return $result["validated_announces"];
    }

    /**
     * Retourne les annonces premium.
     * 
     * @return array
     */
    public static function getPremium(int $nbr)
    {
        $query = "SELECT id FROM ". self::TABLE_NAME . " WHERE status = 3";
        $req = parent::connectToDb()->query($query);

        $result = $req->fetchAll();

        $announces = [];

        foreach($result as $announce) {
            $announces[] = new self($announce["id"]);
        }

        return $announces;
    }

    /**
     * Retourne les statuts.
     * 
     * @return array
     */
    public static function getStatutes()
    {
        return self::$statutes;
    }

    /**
     * Convertit le statut passé en chaîne de caractère
     * en chiffre.
     * 
     * @param string $status
     * 
     * @return int
     */
    public static function convertStatus(string $status)
    {
        if (is_string($status)) {
            $key = array_keys(self::$statutes, strtolower($status));
            if (count($key) === 1) {
                return $key[0];
            } else {
                return null;
            }
        } else {
            return $status;
        }
    }

    /**
     * Permet de vérifier que l'élément passé en paramètre a
     * est parent de cet élément.
     * 
     * @param Category $category
     */
    public function hasCategory(Category $category)
    {
        return $this->category->getId() === $category->getId();
    }

    /**
     * Permet de vérifier que l'utilisateur passé en paramètre est 
     * le owner de cette annonce.
     * 
     * @return bool
     */
    public function hasOwner(\App\Model\User\Registered $registered)
    {
        return $this->owner->getEmailAddress() === $registered->getEmailAddress();
    }

    /**
     * Permet de sauvegarder les images de cette l'annonce.
     */
    private function saveImages(string $imgName)
    {
        $image = new Image();
        // Format premium 600 x 400
        $image->save($_FILES['images']['tmp_name'][0], $imgName, Image::PREMIUM_DIR_PATH, 600, 400);

        // Product 640 x 420
        $image->save($_FILES['images']['tmp_name'][0], $imgName, Image::PRODUCT_DIR_PATH, 640, 420);

        // Art in Footer 240 x 200
        $image->save($_FILES['images']['tmp_name'][0], $imgName, Image::ART_IN_FOOTER_PATH, 240, 200);

        // ProductInfo 625x415
        $arrayLength = count($_FILES["images"]["tmp_name"]);
        for ($i = 0; $i < $arrayLength; $i++) {
            $image->save($_FILES['images']['tmp_name'][$i], $imgName ."-". $i, Image::PRODUCT_INFO_DIR_PATH, 625, 415);
        }

        return true;
    }

    /**
     * Permet de renommer les images de cette annonce.
     * 
     * @param string $newImgPath Le chemin où se trouve le
     */
    private function renameImages(string $newImgName)
    {
        $image = new Image();
        // Premium Img
        $image->rename($this->premiumImgPath, Image::PREMIUM_DIR_PATH . $newImgName . Image::EXTENSION);

        // Product Img
        $image->rename($this->productImgPath, Image::PRODUCT_DIR_PATH . $newImgName . Image::EXTENSION);

        // Art in Footer Img
        $image->rename($this->artInFooterImgPath, Image::ART_IN_FOOTER_PATH . $newImgName . Image::EXTENSION);

        // ProductInfo Img
        for ($i = 0; $i < 3; $i++) {
            $image->rename(Image::PRODUCT_INFO_DIR_PATH . $this->slug ."-". $i . Image::EXTENSION , Image::PRODUCT_INFO_DIR_PATH . $newImgName ."-". $i . Image::EXTENSION);
        }

        return true;
    }

    /**
     * Permet de supprimer les images de cette annonce.
     */
    private function deleteImages()
    {
        $image = new Image();
        
        // Format premium 600 x 400
        $image->delete($this->premiumImgPath);

        // Product 640 x 420
        $image->delete($this->productImgPath);

        // Art in Footer 240 x 200
        $image->delete($this->artInFooterImgPath);

        // ProductInfo 625x415
        for ($i = 0; $i < 3; $i++) {
            $image->delete(Image::PRODUCT_INFO_DIR_PATH . $this->slug ."-". $i . Image::EXTENSION);
        }

        return true;
    }

    /**
     * Permet d'incrémenter le nombre de vue.
     * 
     * @return bool
     */
    public function incrementView()
    {
        if ($this->isValidated()) {
            $req = parent::connectToDb()->prepare("UPDATE " . self::TABLE_NAME . " SET views = views + 1 WHERE id = :id");
            $req->execute([
                "id" => $this->id
            ]);
    
            return true;
        }
    }

    /**
     * Retourne la liste des posts fait le jour courrant.
     * 
     * @return array
     */
    public static function getCurrentDayPosts() : array
    {
        $query = "SELECT id FROM " . self::TABLE_NAME . " WHERE DATE(created_at) = :date ORDER BY created_at DESC";
        $req = parent::connectToDb()->prepare($query);
        $req->execute([
            "date" => date("Y-m-d"),
        ]);
        $result = $req->fetchAll();

        $currentDayAnnounces = [];
        foreach($result as $item) {
            $currentDayAnnounces[] = new self($item["id"]);
        }

        return $currentDayAnnounces;
    }

    /**
     * Retourne le nombre de posts fait le jour courrant.
     * 
     * @return int
     */
    public static function getCurrentDayPostsNumber() : int
    {
        $query = "SELECT COUNT(id) as current_day_posts_number FROM " . self::TABLE_NAME . " WHERE DATE(created_at) = :date";
        $req = parent::connectToDb()->prepare($query);
        $req->execute([
            "date" => date("Y-m-d"),
        ]);
        $result = $req->fetch();

        return $result["current_day_posts_number"];
    }

    /**
     * Retourne le contenu de l'email envoyé lorsqu'une nouvelle annonce est créée.
     * 
     * @return string
     */
    public function newPostMail()
    {
        $content = <<<HTML
        <p>Une nouvelle annonce a été créée.</p>
        <p style="font-weight: bold;">Détails : <br>
            Titre : {$this->getTitle()} <br>
            Postée par : {$this->getOwner()->getFullName()}<br>
            Créée le {$this->createdAt}
        </p>
        {$this->emailButtonAction()}
HTML;
        return MailContentManager::setContent($content);
    }

    /**
     * Le contenu du mail envoyé lorsqu'une annonce est supprimée.
     * 
     * @return string
     */
    public function postDeleted()
    {
        $content = <<<HTML
        <p>Une announce a été supprimée.</p>
        <p style="font-weight: bold;">Détails de l'annonce : <br>
            Titre : {$this->getTitle()} <br>
            Postée par : {$this->getOwner()->getFullName()}<br>
            Date de création : {$this->getCreatedAt()} <br>
        </p>
HTML;
        return MailContentManager::setContent($content);
    }

    /**
     * Retourne le sujet du mail envoyé lorsque l'announce est validée.
     * 
     * @return string
     */
    public function validatedAnnounceEmailSubject()
    {
        return "Votre annonce a été validée !";
    }

    /**
     * Retourne le contenu du mail envoyé lorsque l'annonce est validée.
     * 
     * @return string
     */
    public function validatedAnnounceEmailContent()
    {
        $content = <<<HTML
        Félicitations, votre annonce a été validée ! <br>
        Titre : {$this->title}.
        Postée le {$this->createdAt}
        Elle est maintenant visible par tous les utilisateurs.
        {$this->emailButtonAction()}
HTML;
        return MailContentManager::setContent($content);
    }

    /**
     * Notification envoyée lorsqu'une mise à jour vient d'être faite.
     * 
     * @return string
     */
    public function updatingEmailNotification()
    {
        return <<<HTML
        <p>Une annonce vient d'être mise à jour.</p>
        <p>
            Titre : {$this->id}<br>
            Postée par : {$this->owner->getEmailAddress()}
            Date : {$this->createdAt}
        </p>
        {$this->emailButtonAction()}
HTML;
    }

    /**
     * Affiche un bouton "voir l'annonce" dans la notification par email.
     */
    private function emailButtonAction()
    {
        return <<<HTML
        <p>
            <a style="margin:1.3rem 0;padding:0.7rem;background-color:#FE4A49;border-radius:0.3rem;text-decoration:none;color:white" href="{$this->getLink('all')}">Voir l'annonce</a>
        </p>
HTML;
    }
}