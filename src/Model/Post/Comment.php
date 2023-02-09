<?php

namespace App\Model\Post;

use App\Model\Model;
use App\Model\User\Registered;
use App\Utility\Utility;

/**
 * Classe de gestion des commentaires.
 */
class Comment extends Model
{
    const TABLE_NAME = "comments";

    /** @var App\Model\User\Registered La personne qui a laissé le commentaire */
    private $poster;

    /** L'item concerné par le commentaire. */
    private $subjectId;

    /** Le type de l'item concerné par le commentaire */
    private $subjectType;

    /** @var string Le contenu du commentaire. */
    private $content;

    /** @var string La date de post du commentaire */
    private $postedAt;

    /**
     * Constructeur d'un commentaire.
     * 
     * @param int $id L'id du commentaire.
     */
    public function __construct(int $id)
    {
        $query = "SELECT id, user_email_address, subject_id, subject_type, content, posted_at
            FROM " . self::TABLE_NAME . " WHERE id = :id";
        $req = parent::connectToDb()->prepare($query);
        $req->execute([
            "id" => $id
        ]);

        $result = $req->fetch();

        $this->id = $result["id"];
        $this->content = $result["content"];
        $this->poster = new Registered($result["user_email_address"]);
        $this->subjectType = $result["subject_type"];
        $this->subjectId = $result["subject_id"];
        $this->postedAt = $result["posted_at"];
        $this->tableName = self::TABLE_NAME;
    }

    /** 
     * Retourne l'utilisateur qui a posté l'annonce
     * 
     * @return \App\Model\User\Registered
     */
    public function getPoster()
    {
        return $this->poster;
    }

    /**
     * Retourne le sujet du commentaire.
     */
    public function getSubject()
    {
        return $this->subjectId;
    }

    /**
     * Retourne le contenu du commentaire.
     */
    public function getContent()
    {
        return ucfirst(trim($this->content));
    }

    /**
     * Retourne le type de l'item concerné par le commentaire.
     */
    public function getSubjectType()
    {
        return $this->subjectType;
    }

    /**
     * Permet d'ajouter un commentaire.
     * @param string $userEmailAddress L'adresse email de l'utilisateur qui a posté cette annonce.
     * @param string $subjectId        Le sujet commenté.
     * @param string $content          Le contenu du commentaire.
     * @param string $subjectType      Le type du sujet commenté, optionnel.
     * @return bool
     */
    public static function add(string $userEmailAddress, $subjectId, string $content, $subjectType = null)
    {
        $query = "INSERT INTO " . Comment::TABLE_NAME . "(user_email_address, subject_id, subject_type, content) VALUES(:user_email_address, :subject_id, :subject_type, :content)";

        $req = parent::connectToDb()->prepare($query);

        $req->execute([
            "user_email_address" => $userEmailAddress,
            "subject_id" => $subjectId,
            "subject_type" => $subjectType,
            "content" => htmlspecialchars($content),
        ]);

        return true;
    }

    /**
     * Retourne la date à laquelle le commentaire a été posté.
     * @return string
     */
    public function getPostedAt()
    {
        return Utility::formatDate($this->postedAt);
    }

    /**
     * Permet de retourner tous les commentaires postés.
     * 
     * @return array
     */
    public static function getAll()
    {
        $req = parent::connectToDb()->query("SELECT id FROM " . self::TABLE_NAME . " ORDER BY posted_at DESC");
        $req->execute();

        $comments = [];
        foreach($req->fetchAll() as $comment) {
            $comments[] = new self($comment["id"]);
        }

        return $comments;
    }

    /**
     * Retourne les commentaires par sujet et par type de sujet sur l'appli.
     * 
     * @param string 
     * @param string
     */
    public static function getBySubject(string $subjectType, string $subjectId)
    {
        $req = parent::connectToDb()->prepare("SELECT id FROM " . self::TABLE_NAME
            . " WHERE subject_type = :subject_type AND subject_id = :subject_id"
        );
        $req->execute([
            "subject_type" => $subjectType,
            "subject_id" => $subjectId
        ]);

        $comments = [];
        foreach($req->fetchAll() as $rep) {
            $comments[] = new self($rep["id"]);
        }
        return $comments;
    }

    /**
     * Permet de retourner les commentaires par utilisateur.
     */
    public static function getByUser(string $emailAddress)
    {
        $req = parent::connectToDb()->prepare("SELECT id FROM " . self::TABLE_NAME . " WHERE user_email_address = :user_email_address");
        $req->execute([
            "user_email_address" => $emailAddress
        ]);

        $comments = [];
        foreach($req->fetchAll() as $rep) {
            $comments[] = new self($rep["id"]);
        }

        return $comments;
    }

    /**
     * Retourne le dernier élément d'une table, pour retourner cet élément, l'ordre
     * est fait sur la date de création et on récupère que le premier élément.
     * 
     * @param string $colForInstantiate Le nom de la colonne qui est utilisée pour instantier
     *                                  l'occurrence obtenue et le retourner sous forme d'objet.
     * @param string $tableName         Le nom de la table dans laquelle on récupère l'occurrence.
     * @param string $class             Le nom de la classe, il faut mettre le chemin total de la classe.
     * 
     * @return $object Un objet.
     */
    public static function getLast(string $colForInstantiate = null, string $tableName = null, string $class = null)
    {
        $req = self::connectToDb()->query("SELECT id FROM ". self::TABLE_NAME ." ORDER BY posted_at DESC limit 0, 1");
        return new self($req->fetch()[$colForInstantiate]);
    }

}