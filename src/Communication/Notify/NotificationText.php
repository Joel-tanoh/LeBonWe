<?php

namespace App\Communication\Notify;

/**
 * Permet de gérer toutes les notifications d'error, de succès et d'informations
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license_name
 * @link     Link
 */
class NotificationText extends Notify
{
    /**
     * Retourne "les ... que vous voulez supprimer seront affichés ici".
     * 
     * @param string $title 
     * 
     * @return string
     */
    public function nothingToDelete($title)
    {
        return "Les $title que vous voulez supprimer seront affiché(e)s ici.";
    }

    /**
     * Retourne une chaine de caractère 'Ajout éffectué avec succès'
     * 
     * @author Joel
     * @return string [[Description]]
     */
    public function addSuccess(): string
    {
        return "Ajout éffectué avec succès !";
    }

    /**
     * Retourne une chaine de 'le nom ou le titre est invalide'.
     * 
     * @author Joel
     * @return string [[Description]]
     */
    public function titleIsEmpty(): string
    {
        return "Veuillez insérer un titre !";
    }

    /**
     * Retourne une chaine de 'le nom ou le titre est invalide'.
     * 
     * @author Joel
     * @return string [[Description]]
     */
    public function titleContainsHTML(): string
    {
        return "Veuillez vérifier que le titre ne contient pas de code HTML !";
    }

    /**
     * Retourne une chaîne de caractère "Veuillez entrer une description".
     * 
     * @return string
     */
    public function descriptionIsEmpty()
    {
        return "Veuillez entrer une description !";
    }

    /**
     * Retourne une chaine de caractère.
     * 
     * @return string
     */
    public function descriptionContainsHTML()
    {
        return "Veuillez vérifier que la description ne contient pas de code HTML !";
    }

    /**
     * Retourne le login passé en paramètre est déjà dans la base de données.
     *
     * @author Joel
     * @return string
     */
    public function loginIsUsed(): string
    {
        return "Ce login est déjà utilisé !";
    }

    /**
     * Le login ne doit pas contenir de code HTML
     * 
     * @return string
     */
    public function loginContainsHTML()
    {
        return "Le login ne doit pas contenir de code HTML !";
    }

    /**
     * Retourne une chaine de carctère, veuillez saisir un login
     * 
     * @return string
     */
    public function loginIsEmpty()
    {
        return "Veuillez saisir un login !";
    }

    /**
     * Retourne "Veuillez saisir une valeur correcte pour dans le champ " suivi du
     * nom du champ.
     * 
     * @param string $name Le nom à afficher dans la notification.
     * 
     * @return string
     */
    public function isNotInt(string $name)
    {
        return "Veuillez saisir une valeur correcte pour dans le champ " . $name . " !";
    }

    /**
     * Retourne que l'adresse email est vide.
     * 
     * @author Joel
     * @return string
     */
    public function emailIsEmpty(): string
    {
        return 'Veuillez saisir une adresse email! !';
    }

    /**
     * Retourne que l'adresse email n'est pas valide.
     * 
     * @author Joel
     * @return string
     */
    public function emailIsInvalid(): string
    {
        return 'Veuillez entrer une adresse email valide !';
    }

    /**
     * Retourne que le login n'est pas valide.
     * 
     * @author Joel
     * @return string
     */
    public function loginIsInvalid(): string
    {
        return "Veuillez vérifier que la taille du login > 4 et qu'il ne contient aucun code HTML !";
    }

    /**
     * Retourne une chaîne "Veuillez remplir tous les champs".
     * 
     * @author Joel
     * @return string [[Description]]
     */
    public function inputsEmpty(): string
    {
        return 'Veuillez remplir les champs !';
    }

    /**
     * Retourne une chaîne de caractère 'Identifiants incorrects'.
     * 
     * @author Joel
     * @return string [[Description]]
     */
    public function errorAuthentification(): string
    {
        return 'Vos identifiants sont incorrects, veuillez réessayer !';
    }

    /**
     * Retourne une chaine de caractère 'Modification effectuée avec succès'.
     * 
     * @author Joel
     * @return string [[Description]]
     */
    public function modificationSucceed()
    {
        return 'Modification effectuée avec succès !';
    }

    /**
     * Retourne une chaîne de caractère 'Echec de la modification'.
     * 
     * @author Joel
     * @return string [[Description]]
     */
    public function modificationFailed(): string
    {
        return "Echec de la modification !";
    }

    /**
     * Retourne une chaîne de caractères
     * 'La description ne doit pas excéder 250 caractères'.
     * 
     * @author Joel
     * @return string [[Description]]
     */
    public function descriptionLengthIsInvalid(): string
    {
        return "La description ne doit pas excéder 400 caractères !";
    }

    /**
     * Retourne une chaine de caractère 'Fichier non chargé,
     * modification possible ultérieurement'.
     * 
     * @author Joel
     * @return string [[Description]]
     */
    public function fileNotUpload(): string
    {
        return "Le fichier chargé n'a pas été enrégistrée, vous pouvez modifier cela ultérieurement.";
    }

    /**
     * Retourne une chaîne de caractère 'Child inexistant'.
     * 
     * @author Joel 
     * @return string [[Description]]
     */
    public function itemNotExist(): string
    {
        return "Element introuvable !";
    }


    /**
     * Retourne 'Veuillez selectionner un élément'.
     * 
     * @author Joel
     * @return string [[Description]]
     */
    public function nothingSelected(): string
    {
        return "Veuillez selectionner au moins un élément à supprimer !";
    }

    /**
     * Retourne qu'il n'y a pas de compte administrateurs dans la base de données.
     * 
     * @author Joel
     * @return string
     */
    public function noAdministrateurs(): string
    {
        return "Les comptes utilisateurs et administrateurs s'afficheront ici.";
    }

    /**
     * Retourne une chaîne de caractères : "Les $categories que vous créerer s'afficheront
     * ici".
     * 
     * @param string categorie La catégorie à afficher dans la chaîne de caractère.
     * 
     * @author Joel
     * @return string [[Description]]
     */
    public function noItems(string $categorie): string
    {
        if ($categorie == "themes")  $categorie = "thèmes";
        if ($categorie == "motivation-plus") $categorie = "vidéos de motivation +";
        if ($categorie == "etapes")  $categorie = "étapes";
        if ($categorie == "videos") $categorie = "vidéos";

        return "Les " . $categorie . " que vous créerez seront affiché(e)s ici.";
    }

    /**
     * Retourne 'Une formation possède déjà ce titre'.
     * 
     * @author Joel
     * @return string [[Description]]
     */
    public function issetFormation(): string
    {
        return "Une formation porte ce titre !";
    }

    /**
     * Retourne string 'Rang déjà occupé'.
     * 
     * @author Joel
     * @return [[Type]] [[Description]]
     */
    public function rankIsUsed(): string
    {
        return "Une étape occupe déjà le rank que vous avez donné à celle-ci ! Nous n'avons pas ajouté ce rank, vous pourrez le modifier plus tard.";
    }

    /**
     * Retourne une chaîne de caractère
     * 'Veuillez entrer une valeur correcte pour le nombre de page !'.
     * 
     * @return string
     */
    public function nombrePageIsInvalid(): string
    {
        return "Veuillez entrer une valeur correcte pour le nombre de page !";
    }

    /**
     * Retourne une chaîne de caractère
     * 'Veuillez saisir une date correcte pour l\'annéee de parution !'.
     * 
     * @return string
     */
    public function anneeParutionIsInvalid(): string
    {
        return "Veuillez saisir une date correcte pour l'annéee de parution !";
    }

    /**
     * Retourne une chaîne de caractère
     * 'Veuillez saisir le nom de l\'auteur et
     * verifier qu\'il n\'excède pas 250 caractères !''.
     * 
     * @return string
     */
    public function auteurNameIsInvalid(): string
    {
        return "Veuillez saisir le nom de l'auteur et verifier qu'il n'excède pas 250 caractères !";
    }

    /**
     * Retourne une chaîne de caractère
     * 'Veuillez saisir le nom de la maison d\'edition et
     * verifier qu\'elle n\'excède pas 250 caractères !'.
     * 
     * @return string
     */
    public function maisonEditionNameIsInvalid(): string
    {
        return "Veuillez saisir le nom de la maison d'edition et verifier qu'elle n'excède pas 250 caractères !";
    }

    /**
     * Retourne une chaîne de caractère "le lien de la vidéo est invalide".
     * 
     * @return string
     */
    public function videoLinkIsInvalid()
    {
        return "Veuillez vérifier le lien de la vidéo de description !";
    }

    /**
     * Retourne une chaine de caractère "Veuillez vérifier que vous avez bien
     * charger une image".
     * 
     * @return string
     */
    public function imageIsInvalid()
    {
        return "Veuillez charger une image de taille inférieure à 2 Mo !";
    }

    /**
     * Retourne "Veuillez charger un fichier PDF."
     * 
     * @return string
     */
    public function isNotPdfFile()
    {
        return "Veuillez charger un fichier PDF !";
    }

    /**
     * Retourne une chaine de caractère 'opération effectée avec succès'.
     * 
     * @return string
     */
    public function succeed()
    {
        return "Enregistrement effectué avec succès !";
    }

    /**
     * Retourne une chaine de caractère 'échec de l'enregistrement avec succès'.
     * 
     * @return string
     */
    public function failed()
    {
        return "Echec de l'enregistrement !";
    }

    /**
     * Retourne une chaîne de caractère "ce compte administrateur n'existe pas".
     *
     * @return string
     */
    public function adminNotExist()
    {
        return "Ce compte n'existe pas !";
    }

    /**
     * Retourne une chaine de caractère pour dire que le contenu de l'article
     * est vide.
     * 
     * @return string
     */
    public function articleContentIsEmpty()
    {
        return "Veuillez saisir le contenu de l'article !";
    }
}
