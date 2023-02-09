<?php
/**
 * Description
 * 
 * PHP version 7.1.9
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license_name
 * @version  "CVS: cvs_id"
 * @link     Link
 */

namespace App\View;

/**
 * Classe qui gère les formulaires.
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license_name
 * @version  "Release: package_version"
 * @link     Link
 */
class Form extends View
{
    /** @var string */
    private $method;

    /** @var string */
    private $action;

    /** @var string Id du formulaire afin d'y accéder via Js. */
    private $id;

    /** @var string */
    private $class;

    /** @var string Attribut role */
    private $role;

    /** @var string Attribut name du formulaire */
    private $name;

    /** @var string Le contenu du formulaire. */
    private $content;

    /**
     * Constructeur d'un formulaire.
     * 
     * @param string $action
     * @param string $class
     * @param string $method
     * @param string $id
     * @param string $role
     * @param string $name
     */
    public function __construct(
        string $action,
        string $class = null, 
        string $method = "post", 
        string $id = null,
        string $role = null,
        string $name = null
    ) {
        $this->method = $method;
        $this->action = $action;
        $this->id = $id;
        $this->class = $class;
        $this->role = $role;
        $this->name = $name;
    }

    /**
     * Retourne le code pour un input pour entrer le login d'un compte qu'on veut
     * créer.
     * 
     * @param  $login Valeur par défaur à afficher dans le formulaire. 
     * @param string $class La classe à donner à l'input.
     * 
     * @return string
     */
    public function login(string $login = null, string $class = null)
    {
        $label = $this->label("login", "Login");
        extract($_POST);
        $input = $this->text('login', 'login', $login, "Login", $class);

        return <<<HTML
        <div class="form-group">
            {$label}
            {$input}
        </div>
HTML;
    }

    /**
     * Retourne un input pour saisir un mot de passe.
     * 
     * @param string $class 
     * 
     * @return string
     */
    public function password(string $label = null, string $id = null, string $name = null, string $class = null, string $placeholder = null)
    {
        return <<<HTML
        <div class="form-group">
            {$this->label($id, $label)}
            {$this->input("password", $name, $id, null, $placeholder, $class)}
        </div>
HTML;
    }

    /**
     * Retourne un champ pour saisir un email avec son label.
     * 
     * @return string
     */
    public function email(string $id = null, string $label = null, string $name = "email", string $email = null, string $placeholder = null, string $class = null)
    {
        extract($_POST);

        return <<<HTML
        <div class="form-group">
            {$this->label($id, $label)}
            {$this->input("email", $name, $id, $email, $placeholder, $class)}
        </div>
HTML;
    }

    /**
     * Retourne deux boutons radios pour choisir le type de compte.
     * 
     * @return string
     */
    public function userRole()
    {
        return <<<HTML
        {$this->label(null, "Type d'utilisateur :")}
        <div class="row mb-2">
            <span class="col-6">
                {$this->radio("role", "1", "Administrateur droits limités")}
            </span>
            <span class="col-6">
                {$this->radio("role", "2", "Administrateur tous droits")}
            </span>
        </div>
HTML;
    }

    /**
     * Retourne un input pour confirmer le mot de passe.
     * 
     * @param string $name 
     * @param string $class
     * 
     * @return string
     */
    public function confirmPassword(string $name = null, string $class = null)
    {
        return <<<HTML
        <div class="form-group">
            {$this->label("confirmPassword", "Confirmez le mot de passe")}
            {$this->password("Confirmer le mot de passe", "confirmPassword", $name, $class, "Confirmer le mot de passe...")}
        </div>
HTML;
    }

    /**
     * Retourne un champ dans le formulaire pour le titre.
     * 
     * @param string $title 
     * @param string $class
     * 
     * @return string Le code HTML pour le champ.
     */
    public function title(string $name = "title", string $id = "title", string $title = null, string $placeholder = "Saisir le titre", string $class)
    {
        extract($_POST);

        return <<<HTML
        <div class="form-group">
            {$this->label("title", "Titre")}
            {$this->text($name, $id, $title, $placeholder, $class)}
        </div>
HTML;
    }

    /**
     * Retourne un champ de type textarea pour le champ de la description
     * de l'item à ajouter.
     * 
     * @param $item 
     * 
     * @return string Le code HTML de la description.
     */
    public function description(string $label = "Saisir la description", string $id = "description", string $name = "description", string $description = null, string $placeholder = "Saisir la description...")
    {
        // extract($_POST);

        return <<<HTML
        <div class="form-group">
            {$this->label($id, $label)}
            {$this->textarea($name, $id, $placeholder, $description, "form-control", "10")}
        </div>
HTML;
    }
    
    /**
     * Retourne un champ de type textarea pour écrire le contenu d'un article.
     * 
     * @return string Le code HTML pour le champ du contenu de l'article.
     */
    public function article(string $name = "article", string $id = "article", string $article = null, string $placeholder = null, $rows = null)
    {
        extract($_POST);

        return <<<HTML
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    {$this->textarea($name, $id, $placeholder, $article, "summernote", $rows)}
                </div>
            </div>
        </div>
HTML;
    }

    /**
     * Retourne un champ dans le formulaire pour le price.
     * 
     * @param  $price 
     * @param string $label 
     * 
     * @return string Le code HTML pour le champ.
     */
    public function price($price = null, $label = null)
    {
        extract($_POST);

        return <<<HTML
        <div class="form-group">
            <div class="card">
                <div class="card-body">
                    {$this->label("price", $label)}
                    {$this->number('price', 'Prix', $price, "Prix", "col-12 form-control", 0)}
                </div>
            </div>
        </div>
HTML;
    }
      
    /**
     * Champ pour entrer le lien d'une vidéo.
     * 
     * @param $link 
     * 
     * @return string
     */
    public function video($link = null)
    {
        $label = <<<HTML
        Entrer le lien de la vidéo :
        <p class="notice">Cette vidéo peut être une vidéo de description</p>
HTML;
        extract($_POST);

        return <<<HTML
        <div class="form-group">
            <div class="card">
                <div class="card-body">
                    {$this->label("videoLink", $label)}
                    {$this->text('youtube_video_link', 'videoLink', $link, 'www.youtube.com?v=...', "col-12 form-control")}
                </div>
            </div>
        </div>
HTML;
    }
       
    /**
     * Retourne un champ de type file pour pouvoir uploader un fichier image.
     * 
     * @return string Le code HTML pour le champ.
     */
    public function avatar(string $id = "avatar", string $label =  "Importer un avatar", string $name = "avatar")
    {
        return <<<HTML
        <div class="form-group">
            <div class="card">
                <div class="card-body">
                    {$this->label($id, $label)}
                    {$this->file($name, $id)}
                </div>
            </div>
        </div>
HTML;
    }

    /**
     * Retourne un champ de type file pour pouvoir uploader un fichier image.
     * 
     * @param bool $image_uploaded Permet de dire si le formulaire doit contenir
     *                             un champ pour une image de couverture.
     * 
     * @return string Le code HTML pour le champ.
     */
    public function image(string $id = "image", string $label = "Importer une image de couverture", string $name = "image")
    {
        return <<<HTML
        <div class="form-group">
            <div class="card">
                <div class="card-body">
                    {$this->label($id, $label)}
                    {$this->file($name, $id)}
                </div>
            </div>
        </div>
HTML;
    }

    /**
     * Retourne une balise HTML label
     * 
     * @param string $for   [[Description]]
     * @param string $label [[Description]]
     * @param string $class
     * 
     * @author Joel
     * @return string
     */
    public function label(string $for = null, string $label = null, string $class = null) : string
    {
        return <<<HTML
		<label for="{$for}" class="{$class}">{$label}</label>
HTML;
    }

    /**
     * Retourne un bouton de submit.
     * 
     * @param string $name  [[Description]]
     * @param string $text  [[Description]]
     * @param string $class [[Description]]
     * 
     * @return string
     */
    public function submit(string $name = null,  string $text = null, string $class = null)
    {
        return $this->button("submit", $name, $text, "btn-sm btn-success");
    }

    /**
     * Retourne un input.
     * 
     * @param string $type        
     * @param string $name        [[Description]]
     * @param string $id          [[Description]]
     * @param string $value       [[Description]] 
     * @param string $placeholder [[Description]] 
     * @param string $class       
     * @param int    $min         
     * @param int    $max  
     * 
     * @return string
     */
    public function input(string $type = null, string $name = null, string $id = null, string $value = null, string $placeholder = null, string $class = null, int    $min = null, int    $max = null)
    {
        return <<<HTML
        <input type="{$type}" name="{$name}" id="{$id}" value="{$value}" placeholder="{$placeholder}" min="{$min}" max="{$max}" class="{$class}"/>
HTML;
    }
    
    /**
     * Retourne une balise HTML button
     * 
     * @param string $type 
     * @param string $name  
     * @param string $text  
     * @param string $class  
     * 
     * @author Joel
     * @return string [[Description]]
     */
    public function button(string $type = null, string $name = null,  string $text = null, string $class = null, string $value = null)
    {
        return <<<HTML
		<button type="{$type}" name="{$name}" value={$value} class="{$class}">{$text}</button>
HTML;
    }

    /**
     * Retourne le formulaire.
     * 
     * @return string
     */
    public function show()
    {
        return <<<HTML
        {$this->open()}
            <div class="row">
                {$this->content}
                <div class="row">
                    <div class="col-12">
                        {$this->submit('enregistrement', 'Enregistrer')}
                    </div>
                </div>
            </div>
        {$this->close()}
HTML;
    }

    /**
     * Retourne un input pour le formulaire de type file
     * 
     * @param string $name 
     * @param string $id 
     * @param string $class 
     * 
     * @return string
     */
    public function file(string $name = null, string $id = null, string $class = null)
    {
        return <<<HTML
        <div class="{$class}">
            <div class="custom-file">
                {$this->input("file", $name, $id, null, null, "custom-file-input")}
                {$this->label("customFile", "Importer", "custom-file-label")}
            </div>
        </div>
HTML;
    }
    
    /**
     * Retourne une balise input pour le texte.
     * 
     * @param string $name        [[Description]]
     * @param string $id          [[Description]]
     * @param string $value       [[Description]] 
     * @param string $placeholder [[Description]] 
     * @param string $class 
     * 
     * @return string
     */
    public function text(string $name = null,  string $id = null,  string $value = null,  string $placeholder = null, string $class = null)
    {
        return $this->input("text", $name, $id, $value, $placeholder, $class);
    }

    /**
     * Retourne une balise de bouton radio.
     * 
     * @param string $name  Nom de la balise dans la variable superglobale $_POST.
     * @param string $value La valeur que doit contenir la balise.
     * @param string $text  Texte à afficher.
     * @param string $class 
     * 
     * @return string
     */
    public function radio(string $name = null, string $value = null, string $text = null, string $class = null)
    {
        return <<<HTML
        <label>
            <input type="radio" name="{$name}" id="" value="{$value}" class="{$class}"> {$text}
        </label>
HTML;
    }

    /**
     * Retourne une balise HTML input pour de type number.
     * 
     * @param string $name        [[Description]]
     * @param string $id          [[Description]]
     * @param string $value       [[Description]] 
     * @param string $placeholder [[Description]] 
     * @param string $class    
     * @param int    $min         
     * @param int    $max       
     * 
     * @author Joel
     * @return string [[Description]]
     */
    public function number(string $name = null, string $id = null, string $value = null, string $placeholder = null, string $class = null, int    $min = null, int    $max = null)
    {
        return $this->input("number", $name, $id, $value, $placeholder, $class, $min, $max);
    }

    /**
     * Retourne une balise HTML textarea.
     * 
     * @param string $name        
     * @param string $id          
     * @param string $placeholder 
     * @param string $value 
     * @param string $class   
     * @param string $rows        
     * 
     * @author Joel
     * @return string 
     */
    public function textarea(string $name = null, string $id = null, string $placeholder = null, string $value = null, string $class = null, string $rows = null)
    {
        return <<<HTML
        <textarea name="{$name}" id="{$id}" rows="{$rows}" placeholder="{$placeholder}" class="col-12 {$class}">{$value}</textarea>
HTML;
    }

    /**
     * Début du code d'un formulaire
     * 
     * @param string $dataToggle
     * 
     * @author Joel-tanoh
     * 
     * @return string
     */
    public function open(string $dataToggle = null)
    {
        return <<<HTML
        <form id="{$this->id}" method="{$this->method}" enctype="multipart/form-data" action="{$this->action}" class="{$this->class}" role="{$this->role}" name="{$this->name}" data-toggle="{$dataToggle}">
HTML;
    }

    /**
     * Code de fin d'un formulaire
     * 
     * @return string
     */
    public function close()
    {
        return <<<HTML
        </form>
HTML;
    }

    /**
     * Retourne une balise dans lequel un texte est affiché lorsque le formulaire l'est pas valide.
     * 
     * @return string
     */
    public function helpBlockText()
    {
        return <<<HTML
        <small class="help-block with-errors text-danger"></small>
HTML;
    }

}
