<?php

namespace App\Model\User;

use App\Action\Create\Create;
use App\Action\Create\InsertInDb;
use App\Auth\Authentication;
use App\File\Image\Avatar;
use App\File\Image\Image;
use App\Utility\Utility;
use App\Model\Model;

/**
 * Classe de gestion des utilisateurs.
 */
abstract class User extends Model
{
    protected $code;
    protected $emailAddress;
    const TABLE_NAME = "users";

    /**
     * Retourne le code identifiant d'un user.
     * 
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Retourne l'adresse email.
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * Permet d'enregistrer les données dans la base de données.
     * 
     * @return bool
     */
    public static function save()
    {
        $data = [
            "code" => Utility::generateCode(),
            "name" => htmlspecialchars($_POST["name"]),
            "first_names" => htmlspecialchars($_POST["first_names"]),
            "email_address" => htmlspecialchars($_POST["email_address"]),
            "pseudo" => htmlspecialchars($_POST["pseudo"]),
            "password" => password_hash($_POST["password"], PASSWORD_DEFAULT),
            "phone_number" => htmlspecialchars($_POST["phone_number"]),
        ];

        $insertion = new InsertInDb($data, self::TABLE_NAME);
        $insertion->run();
        
        if (Create::fileIsUploaded("avatar")) {
            $imageManager = new Image();
            $imageManager->save($_FILES["avatar"]["tmp_name"], $_POST["pseudo"], Avatar::AVATARS_DIR_PATH, 80, 80);
        }

        return true;
    }

    /**
     * Retourne un user grace à son code.
     * 
     * @param string $code
     * 
     * @return self
     */
    public static function getByCode($code) : self
    {
        $req = parent::connectToDb()->prepare("SELECT id FROM " . self::TABLE_NAME . " WHERE code = ?");
        $req->execute([$code]);
        $user = $req->fetch();

        if ($user["id"]) {
            return new self($user["id"]);
        }
    }

    /**
     * Permet de vérifier qu'un utilisateur est connecté.
     * 
     * @return bool
     */
    public static function isAuthenticated()
    {
        return Authentication::done();
    }

    /**
     * Permet de retourner l'utilisateur authentifié actuellement.
     * 
     * @return Administrator
     */
    public static function authenticated()
    {
        if (self::isAuthenticated()) {
            $user = new Registered(Authentication::getId());
            return $user->isAdministrator() ? new Administrator(Authentication::getId()) : $user;
        }
    }

    /**
     * Permet de rédiriger l'utilisateur sur sa
     * page de connexion s'il n'est pas authentifié.
     * 
     * @param string $where Le lien vers lequel on redirige l'utilisateur
     *                      s'il n'est pas authentifié.
     */
    public static function askToAuthenticate(string $where = "/sign-in")
    {
        Authentication::askToAuthenticate($where);
    }

    /**
     * Permet de supprimer les éléments postés par cet utilisateur.
     * 
     * @param string $tableName
     * @param string $identifyingCol La colonne clé étrangère dans la table des éléments
     *                               à supprimer.
     * @param mixed $value           La valeur de cette colonne clé étrangère.
     * @return bool Retourne true si la requête s'est exécutée avec succès.
     */
    public function deleteOwned(string $tableName, string $identifyingCol, $value)
    {
        $req = self::connectToDb()->prepare("DELETE FROM $tableName WHERE $identifyingCol = ?");
        $req->execute([
            $value
        ]);
        return true;
    }

    /**
     * Retourne tous les utilisateurs.
     * 
     * @return array
     */
    public static function getAll()
    {
        $rep = parent::connectToDb()->query("SELECT email_address FROM " . self::TABLE_NAME);

        $users = [];
        foreach ($rep->fetchAll() as $user) {
            $users[] = new Registered($user["email_address"]);
        }
        return $users;
    }

}