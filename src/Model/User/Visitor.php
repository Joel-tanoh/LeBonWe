<?php

namespace App\Model\User;

use App\Utility\Utility;
use App\Auth\Session;
use DateTime;

/**
 * Fichier de classe gestionnaire des visites sur l'app.
 * 
 * @author Joel <joel.developpeur@gmail.com>
 */
class Visitor extends User
{
    /** @var string Valeur de session */
    protected $sessionValue;

    /** @var string Adresse email de session du visiteur*/
    protected $sessionEmailAddress;

    /** @var string Date de la visite. */
    protected $date;

    /** @var string La date de la dernière action. */
    protected $lastActionDate;

    protected $sessionId;

    /**
     * @var string Nom de la table.
     */
    const TABLE_NAME = "visitors";

    /** Temps après lequel on considère
     * qu'un utlisateur n'est plus en ligne
     */
    const ONLINE_TIMEOUT = 300;

    /**
     * Constructeur.
     * 
     * @param string $sessionValue
     * 
     * @return void
     */
    public function __construct(string $sessionValue)
    {
        $query = "SELECT id, session_value, session_email_address, date, last_action_date"
            . " FROM " . self::TABLE_NAME
            . " WHERE session_value = :session_value";
        
        $req = parent::connectToDb()->prepare($query);
        $req->execute([
            "session_value" => $sessionValue
        ]);

        $result = $req->fetch();
        $this->id = $result["id"];
        $this->sessionId = $result["id"];
        $this->sessionValue = $result["session_value"];
        $this->sessionEmailAddress = $result["session_email_address"];
        $this->date = $result["date"];
        $this->lastActionDate = $result["last_action_date"];
        $this->tableName = self::TABLE_NAME;
    }

    /**
     * Retourne la valeur de la session.
     * 
     * @return string
     */
    public function getSessionValue()
    {
        return $this->sessionValue;
    }

    /**
     * Retourne la date de la visite.
     * 
     * @return string
     */
    public function getDate(string $part = null, bool $shortLy = null)
    {
        return Utility::formatDate($this->date, $part, $shortLy);
    }

    /**
     * Permet de mettre à jour le compteur de visite de l'app.
     * 
     * @return void 
     */
    public static function manage()
    {
        if (!self::isInitiated()) {
            self::create();
        } else {
            $visitor = new self(Session::getVisitorId());
            $visitor->updateLastActionDate();
        }
    }

    /**
     * Permet de vérifier si une instance de visite existe.
     * 
     * @return bool
     */
    private static function isInitiated()
    {
        // return self::sessionValueIssetInDb(Session::getVisitorId()) || self::sessionValueIssetInDb(Session::getRegistered());
        return self::sessionValueIssetInDb(Session::getVisitorId());
    }

    /**
     * Permet de mettre à jour la date de la dernière action pour savoir
     * si l'utilisateur est toujours en ligne.
     * 
     * @return bool
     */
    public function updateLastActionDate()
    {
        $req = parent::connectToDb()->prepare("UPDATE " . self::TABLE_NAME . " set last_action_date = :last_action_date, last_action_timestamp = :last_action_timestamp WHERE id = :id");
        $req->execute([
            "last_action_date" => date("Y-m-d H:i:s"),
            "last_action_timestamp" => date('U'),
            "id" => $this->sessionId
        ]);
        return true;
    }

    /**
     * Permet de retourner le nombre total de visiteur sur l'appli.
     * @return int
     */
    public static function getAllNumber()
    {
        $req = parent::connectToDb()->query("SELECT COUNT(session_value) as all_visitors_number FROM " . self::TABLE_NAME);
        $result = $req->fetch();

        return $result["all_visitors_number"];
    }

    /**
     * Retourne la liste de tous les visiteurs.
     * 
     * @return array
     */
    public static function getAll()
    {
        $req = parent::connectToDb()->query("SELECT session_value FROM " . self::TABLE_NAME);
        $result = $req->fetchAll();

        $visitors = [];
        foreach ($result as $visitor) {
            $visitors[] = new self($visitor["session_value"]);
        }

        return $visitors;
    }

    /**
     * Permet d'identifier le visiteur en changer son ID de visite inconnu à son arrivée
     * par son adresse email.
     * 
     * @param string $sessionEmailAddress
     * 
     * @return bool
     */
    public function identify(string $sessionEmailAddress) : bool
    {
        $req = parent::connectToDb()
            ->prepare("UPDATE $this->tableName SET session_email_address = :session_email_address WHERE id = :id");
        $req->execute([
            "session_email_address" => $sessionEmailAddress,
            "id" => $this->sessionId
        ]);
        
        return true;
    }

    /**
     * Permet d'enregistrer un nouveau visiteur.
     * 
     * @return bool
     */
    private static function create()
    {
        do {
            $sessionValue = Utility::generateCode();
        } while (self::sessionValueIssetInDb($sessionValue));

        if (self::saveVisitorDataInDb($sessionValue)) {
            Session::activateVisitor($sessionValue);
        }

        return true;
    }

    /**
     * Enregistre l'id de session et la date dans la base de données.
     * 
     * @param string $sessionValue La valeur de la session.
     * 
     * @return bool
     */
    private static function saveVisitorDataInDb(string $sessionValue)
    {
        $query = "INSERT INTO " . self::TABLE_NAME
            . "(session_value, last_action_timestamp) VALUES(:session_value, :last_action_timestamp)";
        $req = parent::connectToDb()->prepare($query);
        $req->execute([
            "session_value" => $sessionValue,
            "last_action_timestamp" => date('U'),
        ]);

        return true;
    }

    /**
     * Permet de vérifier que la variable de session est enregistrée.
     * 
     * @param string $sessionValue
     * @return bool
     */
    public static function sessionValueIssetInDb($sessionValue)
    {
        $req = parent::connectToDb()
            ->prepare("SELECT COUNT(id) as counter FROM " . self::TABLE_NAME . " WHERE session_value = :session_value");
        $req->execute([
            "session_value" => $sessionValue
        ]);

        return $req->fetch()["counter"] != 0;
    }

    /**
     * Permet de récupérer les visiteurs en ligne.
     * 
     * @return array
     */
    public static function onlines()
    {
        $query = "SELECT session_value FROM " . self::TABLE_NAME . " WHERE last_action_timestamp >= ?";

        $rep = parent::connectToDb()->prepare($query);
        $rep->execute([
            time() - self::ONLINE_TIMEOUT
        ]);
        $result = $rep->fetchAll();

        $visitorsOnlines = [];
        foreach($result as $visitor) {
            $visitorsOnlines[] = new self($visitor["session_value"]);
        }

        return $visitorsOnlines;
    }

    /**
     * Permet de retourner le nombre de personne en ligne.
     * @return int
     */
    public static function onlineNumber()
    {
        $query = "SELECT COUNT(session_value) as online_number FROM " . self::TABLE_NAME . " WHERE last_action_timestamp >= ?";
        $rep = parent::connectToDb()->prepare($query);
        $rep->execute([
            time() - (5*60)
        ]);
        $result = $rep->fetch();

        return $result["online_number"];
    }

    /**
     * Retourne les visiteurs selon un intervalle de date.
     * 
     * @param string $firstDate
     * @param string $secondDate
     * 
     * @return array
     */
    public function getVisitorIdsListByDateInterval(string $firstDate, string $secondDate) : array
    {
        return [];
    }

    /**
     * Retourne la liste des visiteurs de la journée en cours.
     */
    public static function getCurrentDayVisitorsList() : array
    {
        return self::getDailyVisitorsList(date('Y-m-d'));
    }

    /**
     * Retourne le nombre de visite de la journée en cours.
     */
    public static function getCurrentDayVisitsNumber() : int
    {
        $query = "SELECT COUNT(session_value) as visits_number FROM " . self::TABLE_NAME . " WHERE DATE(date) = :date";
        $req = parent::connectToDb()->prepare($query);
        $req->execute([
            "date" => date('Y-m-d')
        ]);
        $result = $req->fetch();

        return $result["visits_number"];
    }

    /**
     * Retourne la liste des visiteurs selon un jour donné.
     * 
     * @param string $date La date en format Y-m-d.
     */
    public static function getDailyVisitorsList(string $date) : array
    {
        $query = "SELECT session_value FROM " . self::TABLE_NAME . " WHERE DATE(date) = :date";
        $req = parent::connectToDb()->prepare($query);
        $req->execute([
            "date" => $date
        ]);
        $result = $req->fetchAll();

        $dailyVisitors = [];
        foreach($result as $value) {
            $dailyVisitors[] = new self($value["session_value"]);
        }

        return $dailyVisitors;
    }

    public static function getMonthlyVisitorsList(string $month) : array
    {
        return [];
    }

    public static function getYearlyVisitorsList(string $year) : array
    {
        return [];
    }

}