<?php

/**
 * Fichier de classe
 * 
 * PHP verison 7.1.9
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license
 * @version  "GIT: <Joel-tanoh>"
 * @link     Link
 */

namespace App\Utility;

use App\Auth\Password;
use App\File\FileUploaded;
use App\Communication\Notify\NotificationText;

/**
 * Permet de faire toutes les vérifications sur les données entrées dans les
 * formulaires.
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license
 * @link     Link
 */
class Validator
{
    /**
     * RegEx pour les comparaison HTML
     * 
     * @var string
     */
    const HTML_REGEX = "#<.*>#";

    /**
     * RegEx pour comparer aux adresses emails.
     * 
     * @var string
     */
    const EMAIL_REGEX = "#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#i";

    /**
     * La longeur minimal des mots de passes.
     * 
     * @var string
     */
    const PASSWORD_LENGTH = 8;

    /**
     * Tableau contenant les erreurs après validation de variables.
     * 
     * @var array
     */
    private $errors = [];

    /**
     * Tableau contenant les variables à valider.
     * 
     * @var array
     */
    private $toValidate = [];

    /**
     * Le notificateur.
     * 
     * @var NotificationText
     */
    private $notifier;

    /**
     * Instancie un objet pour la validation.
     * 
     * @author Joel 
     */
    public function __construct()
    {
        $this->notifier = new NotificationText();
    }

    /**
     * Retourne true s'il n'y a aucune erreur.
     * 
     * @return bool
     */
    public function noErrors() : bool
    {
        return count($this->errors) == 0;
    }

    /**
     * Retourne les erreurs à l'issu de la validation des données. Chaque champ
     * du tableau a pour nom le nom issu du POST ou du GET.
     * 
     * @return array Le tableau contenant les erreurs.
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Permet d'ajouter une erreur soi-même à la liste des erreurs.
     * 
     * @param string $arrayKey La clé de l'erreur dans le tableau qui contient les
     *                         les erreurs.
     * @param string $text     Le texte à affiché.
     * 
     * @return void
     */
    public function addError(string $arrayKey, string $text)
    {
        $this->errors[$arrayKey] = $text;
    }

    /**
     * Valide le titre de l'item qu'on veut créer.
     * 
     * @param string $name La valeur de l'attribut name dans le formulaire.
     * @param string $title Le titre de l'item à valider.
     */
    public function title(string $name, string $title)
    {
        $this->toValidate[$name] = $title;

        if (empty($title)) {
            $this->errors[$name] = $this->notifier->titleIsEmpty();
        } elseif ($this->containsHTML($title)) {
            $this->errors[$name] = $this->notifier->titleContainsHTML();
        }
    }

    /**
     * Valide la description, retourne une chaine de caractère si la description
     * est invalide.
     * 
     * @param string $name La valeur de l'attribut name dans le formulaire.
     * @param string $description La description à valider.
     */
    public function description(string $name, string $description)
    {
        $this->toValidate[$name] = $description;

        if (empty($description)) {
            $this->errors[$name] = $this->notifier->descriptionIsEmpty();
        }
    }

    /**
     * Permet de faire la validation d'un mot de passe.
     * 
     * @param string $name La valeur de l'attribut name dans le formulaire.
     * @param string $password
     * @param string $confirmationPassword 
     */
    public function password(string $name, string $password, string $confirmationPassword)
    {
        $this->toValidate[$name] = $password;
        $password = new Password($password);

        $password->validate($confirmationPassword);
        
        if (!$password->noErrors()) {
            $this->errors[$name] = $password->getErrors();
        }
    }

    /**
     * Permet de vérifier que l'article a un contenu.
     * 
     * @param string $name La valeur de l'attribut name dans le formulaire.
     * @param string $content Le contenu de l'article.
     */
    public function article(string $name, string $content)
    {
        $this->toValidate[$name] = $content;

        if (empty($content)) {
            $this->errors[$name] = $this->notifier->articleContentIsEmpty();
        }
    }

    /**
     * Permet de vérifier que le price saisi l'utilisateur est un entier.
     *
     * @param string $name La valeur de l'attribut name dans le formulaire.
     * @param string $price Le price saisi par l'utilisateur.
     * 
     * @return string Une notification si le price n'est pas un entier.
     */
    public function price(string $name, string $price)
    {
        $this->toValidate[$name] = $price;

        if (!is_int((int)$price)) {
            $this->errors[$name] = $this->notifier->IsNotInt($name);
        }
    }

    /**
     * Permet de vérifier que le rang saisi l'utilisateur est un entier.
     * 
     * @param string $name La valeur de l'attribut name dans le formulaire.
     * @param string $rang Le rang saisi par l'utilisateur.
     * 
     * @return string Une notification si le rang n'est pas un entier.
     */
    public function rank(string $name, string $rank)
    {
        $rank = (int)$rank;
        $this->toValidate[$name] = $rank;

        if (!is_int($rank)) {
            $this->errors[$name] = $this->notifier->IsNotInt($name);
        }
    }

    /**
     * Permet de valider le login saisi par l'utilisateur.
     * 
     * @param string $name La valeur de l'attribut name dans le formulaire.
     * @param string $login Le login saisi par l'utilisateur.
     * 
     * @return string Une notification si le login est invalide.
     */
    public function login(string $name, string $login)
    {
        $this->toValidate[$name] = $login;

        if (empty($login)) {
            $this->errors[$name] = $this->notifier->loginIsEmpty();
        } elseif ($this->containsHTML($login)) {
            $this->errors[$name] = $this->notifier->loginContainsHTML();
        }
    }

    /**
     * Permet de valider que le fichier uploadé dans le champ image est une image
     * et qu'elle respecte les conditions de poids, d'extension et d'erreur.
     * 
     * @param string $name La valeur de l'attribut name dans le formulaire.
     * 
     * @return string
     */
    public function image(string $name)
    {  
        $this->toValidate[$name] = $_FILES[$name];
        $img = new FileUploaded($_FILES[$name]);
        if (!$img->isAnImageHasValidSizeAndNoError()) {
            $this->errors[$name] = $this->notifier->imageIsInvalid();
        }
    }

    /**
     * Permet de vérifier que le fichier pdf uplaodé est exactement un fichier PDF.
     * 
     * @param string $name La valeur de l'attribut name dans le formulaire.
     * 
     * @return string
     */
    public function pdf(string $name)
    {
        $this->toValidate[$name] = $_FILES[$name];
        $pdfUploaded = new FileUploaded($_FILES[$name]);
        if (!$pdfUploaded->isPdfFile()) {
            $this->errors[$name] = $this->notifier->isNotPdfFile();
        }
    }

    /**
     * Effectue les validations sur le lien de la vidéo.
     * 
     * @param string $name La valeur de l'attribut name dans le formulaire.
     * @param $youtubeVideoLink Lien de la vidéo de description.
     */
    public function videoLink(string $name, string $youtubeVideoLink = null)
    {
        $this->toValidate[$name] = $youtubeVideoLink;
        if ($this->containsHTML($youtubeVideoLink)) {
            $this->errors[$name] = $this->notifier->videoLinkIsInvalid();
        }
    }
    
    /**
     * Effectue les validations sur un nom. Vérifie que le nom ne contient
     * pas de code HTML.
     * 
     * @param string $value    Le nom qu'il faut valider.
     * @param string $notification  Le text à afficher en cas d'erreur.
     * @param string $postName La valeur de l'attribut name dans le
     *                         le formulaire.
     * 
     * @return void
     */
    public function name(string $value, string $notification, string $postName = "name")
    {
        $this->toValidate[$postName] = $value;

        if ($this->containsHTML($value)) {
            $this->errors[$postName] = $notification;
        }
    }
  
    /**
     * Effectue les validations sur un email.
     * 
     * @param string $name La valeur de l'attribut name dans le formulaire.
     * @param string $email Email à vérifier.
     */
    public function email(string $name, string $email)
    {
        $this->toValidate[$name] = $email;

        if (!preg_match(self::EMAIL_REGEX, $email)) {
            $this->errors[$name] = $this->notifier->emailIsInvalid();
        }
    }

    /**
     * Permet de vérifier que la variable passée en paramètre est
     * un format valide de numéro de téléphone.
     * 
     * @param $var
     * 
     * @return bool
     */
    public function phoneNumber($name, $var, string $notification)
    {
        $this->toValidate[$name] = $var;

        if ($this->containsLetter($var)) {
            $this->errors[$name] = $notification;
        }
    }

    /**
     * Permet de vérifier que le nombre de fichier uploadé est
     * respecte la condition entrée.
     * 
     * @param string $name         C'est le contenu de l'attribut name dans le formulaire.
     * @param string $comaparision Le mot ou le signe de la comparaison.
     *                             Exemple : less|more|equal ou <=|=|>=.
     * @param int    $fileNumber   Le nombre de fichier par rapport auquel on fait la comparaison.
     * @param string $notification      Le notification à afficher au cas où la condition n'est
     *                             pas respectée.
     * 
     * @param 
     */
    public function fileNumber(string $name, string $comparison, int $fileNumber, string $notification)
    {
        $this->toValidate[$name] = $_FILES[$name]["name"];

        if ($comparison == "less" || $comparison == "<=") {
            $condition = count($_FILES[$name]["name"]) <= $fileNumber;
        } elseif ($comparison == "more" || $comparison == ">=") {
            $condition = count($_FILES[$name]["name"]) >= $fileNumber;
        } elseif ($comparison == "equal" || $comparison == "=") {
            $condition = count($_FILES[$name]["name"]) == $fileNumber;
        }

        if (!$condition) {
            $this->errors[$name] = $notification;
        }
    }

    /**
     * Permet de vérifier que l'extension d'un fichier est identique
     * à celui passé en paramètre.
     * 
     * @param string $name
     * @param string $extToValidate
     * @param array  $validExtensions
     * @param string $notification
     */
    public function fileExtensions(string $name, string $extToValidate, array $validExtensions, string $notification)
    {
        if (!in_array(mb_strtolower($extToValidate), $validExtensions)) {
            $this->errors[$name] = $notification;
        }
    }

    /**
     * Permet de valider la taille d'un fichier.
     * 
     * @param string $name
     * @param        $fileSize
     * @param array  $validSize
     * @param string $notification
     */
    public function fileSize(string $name, $fileSize, $validSize, string $notification)
    {
        if ((int)$fileSize > $validSize) {
            $this->errors[$name] = $notification;
        }
    }

    /**
     * Retourne true si la chaîne de caractère passée en paramètre contient du code
     * HTML.
     * 
     * @param string $string La chaîne dont il faut faire la vérification.
     * 
     * @return bool
     */
    private function containsHTML(string $string) : bool
    {
        return preg_match(self::HTML_REGEX, $string);
    }

    /**
     * Permet de vérifier si la variable passée en paramètre
     * contient des lettres.
     * 
     * @param $var
     * 
     * @return bool
     */
    private function containsLetter($var)
    {
        return preg_match("#[A-Z]#i", $var);
    }

}