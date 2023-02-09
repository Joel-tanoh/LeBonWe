<?php

namespace App\View\Model;

use App\Communication\SocialNetwork\SocialNetwork;
use App\Model\Post\Announce;
use App\View\Snippet;
use App\View\View;
use App\View\Form;
use App\Model\User\User;
use App\View\Model\User\UserView;
use App\View\Communication\CommentView;

/**
 * Classe de gestion des vues des annonces.
 */
class AnnounceView extends View
{
    protected $announce;

    /**
     * Constructeur de la vue des annonces.
     * 
     * @param \App\Model\Post\Announce $announce
     */
    public function __construct(\App\Model\Post\Announce $announce = null)
    {
        $this->announce = $announce;
    }

    /**
     * Affiche toutes les announces.
     * 
     * @param array $announces
     * @return string
     */
    public function show(array $announces, string $smallTitle)
    {
        $categoryView = new CategoryView();

        return <<<HTML
        <section class="row">
            {$categoryView->sidebar()}
            {$this->list($announces, $smallTitle)}
        </section>
HTML;
    }

    /**
     * Vue de la création d'une annonce.
     * 
     * @param string $notification Le notification retourné en fonction de l'issue de 
     *                        l'action.
     * 
     * @return string
     */
    public function create(string $notification = null)
    {
        return parent::administrationTemplate($this->createAnnounceForm(), "Poster une annonce", "Poster une annonce", $notification);
    }

    /**
     * Permet  d'afficher la vue des détails de l'annonce.
     * 
     * @return string Le code HTML de la vue.
     */
    public function read()
    {
        return parent::sliderWithTopAdvertisingTemplate($this->details());
    }

    /**
     * La vue qui permet de modifier une announce.
     * 
     * @return string
     */
    public function update(string $notification = null)
    {
        return parent::administrationTemplate($this->createAnnounceForm(), $this->announce->getTitle() . " - Modification", $this->announce->getTitle() . " / Modifier", $notification);
    }

    /**
     * La vue qui affiche la liste des annonces.
     * 
     * @param array $announces
     * 
     * @return string
     */
    public function list(array $announces, string $smallTitle)
    {
        $snippet = new Snippet;

        return <<<HTML
        <div class="col-lg-9 col-md-12 col-xs-12 page-content">
            <h4 class="py-1">{$smallTitle}</h4>
            {$snippet->viewChanger()}
            <div class="adds-wrapper">
                <div class="tab-content">
                    {$this->gridView($announces)}
                    {$this->listView($announces)}
                </div>
            </div>
        </div>
HTML;
    }

    /**
     * Affiche les annonces les plus vues.
     * 
     * @return string
     */
    public static function mostViewed()
    {
        $content = null;
        $announces = Announce::getMostViewed(10);

        if (empty($announces)) {
            $content = Snippet::noResult();
        } else {
            $content = null;
            foreach ($announces as $announce) {
                $content .= (new self($announce))->scrollingCard();
            }
        }

        return Snippet::hScrolling("Les plues vues", $content);
    }

    /**
     * Affiches les dernières annonces postées.
     * 
     * @param int $nbr Le nombre d'élémentd à afficher.
     * 
     * @return string
     */
    public function latest(int $nbr = null)
    {
        $content = null;
        $announces = Announce::getLastPosted($nbr);

        if (empty($announces)) {
            $content = Snippet::noResult();
        } else {
            foreach ($announces as $announce) {
                $content .= (new self($announce))->card("col-xs-6 col-sm-6 col-md-4 col-lg-4");
            }
        }

        return Snippet::listingAnnouncesSection("Postés récemment", $content);
    }

    /**
     * Affichage sous forme de grille.
     * 
     * @return string
     */
    private function gridView(array $announces)
    { 
        $content = null;

        if (empty($announces)) {
            $content = Snippet::noResult();
        } else {
            foreach ($announces as $announce) {
                $content .= (new self($announce))->card("col-xs-12 col-sm-12 col-md-6 col-lg-6");
            }
        }

        return <<<HTML
        <div id="grid-view" class="tab-pane fade">
            <div class="row">
                {$content}
            </div>
        </div>
HTML;
    }

    /**
     * Affichage sous forme de liste.
     * 
     * @return string
     */
    private function listView(array $announces)
    {
        $content = null;

        if (empty($announces)) {
            $content = Snippet::noResult();
        } else {
            foreach ($announces as $announce) {
                $content .= (new self($announce))->card("col-xs-12 col-sm-12 col-md-12 col-lg-12");
            }
        }

        return <<<HTML
        <div id="list-view" class="tab-pane fade active show">
            <div class="row">
                {$content}
            </div>
        </div>
HTML;
    }

    /**
     * Permet d'afficher les informations sur le nombre d'annonce affichée
     * appartenant à cette catégorie.
     * 
     * @param \App\Model\Category $category
     * 
     * @return string
     */
    private function announceFilterShortName($category)
    {
        return <<<HTML
        <div class="short-name">
            <span>Annonces (1 - 12 sur {$category->getAnnouncesNumber()})</span>
        </div>
HTML;
    }

    /**
     * Détails de l'annonce.
     * 
     * @return string
     */
    private function details()
    {
        return <<<HTML
        <div class="my-3">
            {$this->detailsInfos()}
        </div>
HTML;
    }

    /**
     * Last posted in footer code.
     * 
     * @return string
     */
    public function lastPostedCardInFooter()
    {
        return <<<HTML
        <li>
            <div class="media-left">
                <img class="img-fluid" src="{$this->announce->getArtInFooterImgSrc()}" alt="Photo de {$this->announce->getTitle()}">
                <div class="overlay">
                    <span class="price">{$this->announce->getPrice()}</span>
                </div>
            </div>
            <div class="media-body">
                <h4 class="post-title"><a href="{$this->announce->getLink()}">{$this->announce->getTitle(20)}</a></h4>
                <span class="date">{$this->announce->getCreatedAt()}</span>
            </div>
        </li>
HTML;
    }

    /**
     * Contenu de la page de création d'une annonce.
     * 
     * @return string
     */
    private function createAnnounceForm()
    {
        $form = new Form($_SERVER["REQUEST_URI"], "row page-content");

        return <<<HTML
        {$form->open("validator")}
            {$this->enterAnnounceDetails($form)}
            {$this->contactDetails($form)}
        {$form->close()}
HTML;
    }

    /**
     * Bloc des infos sur l'annonce. Elle affiche le titre et les informations
     * connexes sur l'annonce.
     * 
     * @return string
     */
    private function detailsInfos()
    {
        return <<<HTML
        <div class="product-info row pb-4">
            {$this->productInfosImgSection()}
            {$this->detailsBox()}
        </div>
HTML;
    }
    /**
     * Bloc des spécifications de l'annonce.
     * 
     * @return string
     */
    private function specifications()
    {
        return <<<HTML
        <section class="mb-4">
            <h5 class="mb-2">Specifications :</h4>
            <ul class="list-specification">
                <li><i class="lni-check-mark-circle"></i> Statut : {$this->announce->getStatus("fr")}</li>
                <li><i class="lni-check-mark-circle"></i> Sens : {$this->announce->getDirection()}</li>
                <li><i class="lni-check-mark-circle"></i> Type : {$this->announce->getType()}</li>
            </ul>
        </section>
        
HTML;
    }

    /**
     * Affiche les images descriptives de l'annonce sur la page détails.
     * 
     * @param string $bootstrapColClass
     * 
     * @return string
     */
    private function productInfosImgSection(string $bootstrapColClass = "col-lg-7 col-md-12 col-xs-12")
    {
        $imgSection = null;
        foreach ($this->announce->getProductAllImg() as $src) {
            $imgSection .= $this->productInfoImg($src, "Photo de " . $this->announce->getSlug(), $this->announce->getPrice());
        }
        
        return <<<HTML
        <div class="{$bootstrapColClass}">
            <div class="details-box ads-details-wrapper">
                <div id="owl-demo" class="owl-carousel owl-theme">
                    {$imgSection}
                </div>
            </div>
        </div>
HTML;
    }

    /**
     * Affiche une image sur la page de détails d'une annonce.
     */
    private function productInfoImg(string $imgSrc, string $altText, string $price)
    {
        return <<<HTML
        <div class="item">
            <div class="product-img">
                <img class="img-fluid" src="{$imgSrc}" alt="{$altText}">
            </div>
            <span class="price">{$price}</span>
        </div>
HTML;
    }

    /**
     * Affiche le titre et les autres infos textuelles de l'annonce concernée.
     * 
     * @return string
     */
    private function detailsBox()
    {
        return <<<HTML
        <div class="col-lg-5 col-md-12 col-xs-12">
            <div class="details-box">
                <div class="ads-details-info">
                    <h2>{$this->announce->getTitle()}</h2>
                    {$this->manageButtons()}
                    {$this->showDescription()}
                    {$this->metadata()}
                </div>
                <ul class="advertisement mb-4">
                    <li>
                        <p><strong><i class="lni-folder"></i></strong> <a href="{$this->announce->getCategory()->getSlug()}">{$this->announce->getCategory()->getTitle()}</a></p>
                    </li>
                    <li>
                        <p><a href="/users/{$this->announce->getOwner()->getPseudo()}/posts"><i class="lni-users"></i> Plus d'annonces de <span>{$this->announce->getOwner()->getFullName()}</span></a></p>
                    </li>
                </ul>
                {$this->specifications()}
                {$this->infosForJoinUser()}
                {$this->shareMe()}
                {$this->showComments()}
                {$this->putComments()}
            </div>
        </div>
HTML;
    }

    /**
     * Affiche la description de l'annonce.
     * 
     * @return string
     */
    private function showDescription()
    {
        return <<<HTML
        <div class="description">
            <h5>Description</h5>
            <p>{$this->announce->getDescription()}</p>
        </div>
HTML;
    }

    /**
     * Affiche l'heure, le lieu et le nom de l'utilisateur.
     * 
     * @return string
     */
    private function metadata()
    {
        return <<<HTML
        <div class="details-meta">
            <span><i class="lni-alarm-clock"></i> {$this->announce->getCreatedAt()}</span>
            <span>{$this->showLocation()}</span>
            <span><i class="lni-eye"></i> {$this->announce->getViews()} vue(s)</span>
        </div>
HTML;
    }

    /**
     * Affiche l'adresse email et le numéro à joindre pour l'annonce.
     * 
     * @return string
     */
    private function infosForJoinUser()
    {
        return <<<HTML
        <div class="ads-btn mb-2">
            <h5>Contact : </h5>
            <a class="btn btn-common text-white btn-reply mb-2"><i class="lni-envelope"></i> {$this->announce->getUserToJoin()}</a>
            <a class="btn btn-common text-white mb-2"><i class="lni-phone-handset"></i> {$this->announce->getPhoneNumber()}</a>
        </div>
HTML;
    }

    /**
     * Bloc de code qui permet de partager l'annonce en cours.
     * 
     * @return string
     */
    private function shareMe()
    {
        return SocialNetwork::shareThis();
    }

    /**
     * Les champs pour entrer les détails de l'annonce lors de sa création.
     * 
     * @param Form $form Un objet Form
     * 
     * @return string
     */
    private function enterAnnounceDetails(Form $form)
    {
        $categoryView = new CategoryView();
        $userView = new UserView();

        
        if(isset($_POST["title"]) && !empty($_POST["title"]))
            $title = $_POST["title"];
        elseif ($this->announce)
            $title = $this->announce->getTitle();
        else 
            $title = null;

        
        if(isset($_POST["description"]) && !empty($_POST["description"]))
            $description = $_POST["description"];
        elseif ($this->announce)
            $description = $this->announce->getDescription();
        else
            $description = null;

        if(isset($_POST["price"]) && !empty($_POST["price"]))
            $price = $_POST["price"];
        elseif ($this->announce)
            $price = $this->announce->getPrice(false);
        else
            $price = null;

        $checkedPriceOnCall = (isset($_POST["price_on_call"]) || (isset($this->announce) && $this->announce->getPrice() === "Prix à l'appel")) ? "checked" : null;

        if (isset($_POST["id_category"]) && !empty($_POST["id_category"]))
            $categoryOptionId = $_POST["id_category"];
        elseif ($this->announce)
            $categoryOptionId = $this->announce->getCategory()->getId();
        else
            $categoryOptionId = null;

        if (isset($_POST["location"]) && !empty($_POST["location"]))
            $townSelectListOptionId = $_POST["location"];
        elseif ($this->announce)
            $townSelectListOptionId = $this->announce->getLocation();
        else
            $townSelectListOptionId = null;

        return <<<HTML
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-7">
            <div class="inner-box">
                <div class="dashboard-box">
                    <h1 class="dashboard-title">Détails de l'annonce</h1>
                </div>
                <div class="dashboard-wrapper">
                    <div class="form-group mb-3">
                        <label class="control-label">Titre</label>
                        <input class="form-control input-md" name="title" placeholder="Entrer le titre" type="text" value="{$title}" required>
                        {$form->helpBlockText()}
                    </div>
                    <div class="form-group mb-3 tg-inputwithicon">
                        <label class="control-label">Catégories</label>
                        <div class="tg-select form-control">
                            <select name="id_category" required>
                                <option value="0">Sélectionner une catégorie</option>
                                {$categoryView->selectOptions($categoryOptionId)}
                            </select>
                        </div>
                        {$form->helpBlockText()}
                    </div>
                    <div class="row my-2 pt-2">
                        <div class="col-6">
                            {$this->chooseDirection($form)}
                        </div>
                        <div class="col-6">
                            {$this->chooseType($form)}
                        </div>
                    </div>
                    <div class="form-group mb-3 tg-inputwithicon">
                        <label class="control-label">Ville</label>
                        <div class="tg-select form-control">
                            {$userView->townsSelectList("location", $townSelectListOptionId)}
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <div id="enter_price_box">
                            <label class="control-label">Prix</label>
                            <input type="number" step="5" min="0" class="form-control input-md" name="price" placeholder="Ajouter le prix (F CFA)" value="{$price}">
                        </div>
                        <div class="tg-checkbox mt-3">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="price_on_call" id="tg-priceoncall" {$checkedPriceOnCall}>
                                <label class="custom-control-label" for="tg-priceoncall">Me contacter pour avoir les informations financières.</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <section id="editor">
                            <textarea name="description" id="summernote" required>{$description}</textarea>
                        </section>
                    </div>
                    <label id="uploadImgLabel" class="tg-fileuploadlabel" for="tg-photogallery">
                        <span>Glissez votre fichier pour le charger</span>
                        <span>Ou</span>
                        <span class="btn btn-common">Cliquez et séléctionner 3 images max.</span>
                        <span>Taille maximum d'une image : 2 MB</span>
                        <input id="tg-photogallery" class="tg-fileinput" type="file" name="images[]" multiple>
                        <span class="preview"></span>
                    </label>
                </div>
            </div>
        </div>
HTML;
    }

    /**
     * Permet d'enter les détails sur le contact pour l'annonce.
     * 
     * @return string
     */
    private function contactDetails(Form $form)
    {
        $userToJoin = null;
        $phoneNumber = null;

        if(isset($_POST["user_to_join"]) && !empty($_POST["user_to_join"])) {
            $userToJoin = $_POST["user_to_join"];
        } elseif (null !== $this->announce) {
            $userToJoin = $this->announce->getUserToJoin();
        }

        if(isset($_POST["phone_number"]) && !empty($_POST["phone_number"])) {
            $phoneNumber = $_POST["phone_number"];
        } elseif (null !== $this->announce) {
            $phoneNumber = $this->announce->getPhoneNumber();
        }

        return <<<HTML
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-5">
            <div class="inner-box">
                <div class="tg-contactdetail">
                    <div class="dashboard-box">
                        <h2 class="dashbord-title">Contacts</h2>
                    </div>
                    <div class="dashboard-wrapper">
                        <!-- <div class="form-group mb-3">
                            <strong>Qui contacter ?</strong>
                            <div class="tg-selectgroup">
                                <span class="tg-radio">
                                    <input id="tg-sameuser" type="radio" name="usertype" value="current_user">
                                    <label for="tg-sameuser">Moi</label>
                                </span>
                                <span class="tg-radio">
                                    <input id="tg-someoneelse" type="radio" name="usertype" value="someone_else">
                                    <label for="tg-someoneelse">Une autre personne</label>
                                </span>
                            </div>
                        </div> -->
                        <div id="someone_else">
                            <div class="form-group mb-3">
                                <label class="control-label">Adresse email</label>
                                <input type="email" class="form-control input-md" name="user_to_join" value="{$userToJoin}">
                                {$form->helpBlockText()}
                            </div>
                            <div class="form-group mb-3">
                                <label class="control-label">Téléphone</label>
                                <input class="form-control input-md" name="phone_number" type="text" placeholder="+XXX XXXXXXXXXX" value="{$phoneNumber}">
                            </div>
                        </div>
                        <button class="btn btn-common" type="submit">Envoyer</button>
                    </div>
                </div>
            </div>
        </div>
HTML;
    }

    /**
     * Permet de choisir la direction de l'offre, demande ou offre.
     * @return string
     */
    private function chooseDirection(Form $form)
    {
        $checkOffer = null;
        $checkAsking = null;

        if (isset($_POST["direction"])
        ) {
            if ($_POST["direction"] == "offre") {
                $checkOffer = "checked";
            } elseif ($_POST["direction"] == "demande") {
                $checkAsking = "checked";
            }
        } elseif ($this->announce) {
            $checkOffer = strtolower($this->announce->getDirection()) === "offre" ? "checked" : null;
            $checkAsking = strtolower($this->announce->getDirection()) === "demande" ? "checked" : null;
        }

        return <<<HTML
        <div class="form-group mb-3">
            <strong>Sens :</strong>
            <div class="tg-selectgroup">
                <span class="tg-radio">
                    <input id="tg-offre" type="radio" name="direction" value="offre" $checkOffer required>
                    <label for="tg-offre" title="Je propose un produit">Offre</label>
                </span>
                <span class="tg-radio">
                    <input id="tg-demande" type="radio" name="direction" value="demande" $checkAsking required>
                    <label for="tg-demande" title="J'ai besoin d'un produit">Demande</label>
                </span>
            </div>
            {$form->helpBlockText()}
        </div>
HTML;
    }

    /**
     * Permet de choisir le type d'annonce.
     * @return string
     */
    private function chooseType(Form $form)
    {
        $checkParticular = null;
        $checkPro = null;

        if (isset($_POST["type"])) {
            if ($_POST["type"] == "particulier") {
                $checkParticular = "checked";
            } elseif ($_POST["type"] == "professionnel") {
                $checkPro = "checked";
            }
        } elseif ($this->announce) {
            $checkParticular = strtolower($this->announce->getType()) === "particulier" ? "checked" : null;
            $checkPro = strtolower($this->announce->getType()) === "professionnel" ? "checked" : null;
        }

        return <<<HTML
        <div class="form-group mb-3">
            <strong>Type :</strong>
            <div class="tg-selectgroup">
                <span class="tg-radio">
                    <input id="tg-professionnal" type="radio" name="type" value="professionnel" $checkPro required>
                    <label for="tg-professionnal" title="Je poste une annonce concernant une entreprise">Professionnel</label>
                </span>
                <span class="tg-radio">
                    <input id="tg-particular" type="radio" name="type" value="particulier" $checkParticular required>
                    <label for="tg-particular" title="Je poste une annonce ne concernant pas une entreprise">Particulier</label>
                </span>
            </div>
            {$form->helpBlockText()}
        </div>
HTML;
    }
    /**
     * Une ligne qui affiche une annonce et quelque détails.
     * 
     * @return string
     */
    public function registeredDashboardRow()
    {
        return <<<HTML
        <tr data-category="active">
            <td>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="{$this->announce->getId()}">
                    <label class="custom-control-label" for="{$this->announce->getId()}"></label>
                </div>
            </td>
            <td class="photo"><img class="img-fluid" src="{$this->announce->getProductImgSrc()}" alt="Image de {$this->announce->getSlug()}"></td>
            <td data-title="Title">
                <h3>{$this->announce->getTitle()}</h3>
                <span>ID: {$this->announce->getId()}</span>
            </td>
            <td data-title="Category"><span class="adcategories">{$this->announce->getCategory()->getTitle()}</span></td>
            <td data-title="Ad Status">{$this->dashboardAnnounceStatus()}</td>
            <td data-title="Price">
                <h3>{$this->announce->getPrice()}</h3>
            </td>
            <td data-title="Action">
                {$this->dashboardActions()}
            </td>
        </tr>
HTML;
    }

    /**
     * Permet d'afficher les boutons qui permettent de faire des actions
     * sur l'annonce dans le dashboard.
     * 
     * @return string
     */
    private function dashboardActions()
    {
        if (User::isAuthenticated()) {

            $editButton = $this->announce->hasOwner(User::authenticated())
                ? '<a class="btn-action btn-edit" href="' . $this->announce->getManageLink("update"). '"><i class="lni-pencil"></i></a>'
                : null;

            return <<<HTML
            <div class="btns-actions">
                <a class="btn-action btn-view" href='{$this->announce->getLink()}'><i class="lni-eye"></i></a>
                {$editButton}
                <a class="btn-action btn-delete" href='{$this->announce->getManageLink("delete")}'><i class="lni-trash"></i></a>
            </div>
HTML;
        }
    }
    
    /**
     * Permet d'afficher le statut de l'annonce dans le tableau
     * du dashboard de l'utilisateur.
     * 
     * @return string
     */
    private function dashboardAnnounceStatus()
    {
        if ($this->announce->getStatus() == "Pending") {
            $statusClass = "adstatusactive bg-warning";
            $statusText = "En attente";
        } elseif ($this->announce->getStatus() == "Validated") {
            $statusClass = "adstatusactive bg-success";
            $statusText = "Validée";
        } else {
            $statusClass = "adstatusexpired";
            $statusText = "Suspendue";
        }

        return <<<HTML
        <span class="adstatus {$statusClass}">{$statusText}</span>
HTML;
    }

    /**
     * Affiche un tableau contenant les données meta de cette
     * announce.
     * 
     * @return string
     */
    private function metadataTable()
    {
        return <<<HTML
        <h4>Infos</h4>
        <table class="table">
            {$this->metadataTableRow("ID", $this->announce->getId())}
            {$this->metadataTableRow("Titre", $this->announce->getTitle())}
            {$this->metadataTableRow("Slug", $this->announce->getSlug())}
            {$this->metadataTableRow("Vues", $this->announce->getViews())}
            {$this->metadataTableRow("Statut", $this->announce->getStatus())}
        </table>
HTML;
    }

    /**
     * Affiche une ligne dans le tableau des données de l'annonce
     */
    private function metadataTableRow(string $index, string $value)
    {
        return <<<HTML
        <tr><td>{$index}</td><td>{$value}</td></tr>
HTML;
    }

    /**
     * Affiche une carte.
     * 
     * @param string $responsiveDesignClass La classe bootstrap pour gérer la disposition
     *                                      de la carte sur les différents types d'écran.
     * @return string
     */
    public function card(string $responsiveDesignClass = null)
    {
        return <<<HTML
        <div class="{$responsiveDesignClass}">
            <div class="featured-box">
                {$this->cardImg()}
                {$this->cardContent()}
            </div>
        </div>
HTML;
    }

    /**
     * Affiche la carte adéquate dans la section de défilement
     * horizontal.
     */
    private function scrollingCard()
    {
        return <<<HTML
        <div class="item">
            <div class="product-item">
                {$this->cardImg(false)}
                <div class="px-3 pb-3">
                    <h3 class="product-title"><a href="{$this->announce->getLink()}">{$this->announce->getTitle()}</a></h3>
                    {$this->showViews()}
                </div>
            </div>
        </div>
HTML;
    }

    /**
     * Permet d'afficher l'image de couverture de l'annonce dans les cartes.
     * 
     * @return string
     */
    private function cardImg(bool $heartIcon = true)
    {
        if ($heartIcon) {
            $heartIcon = '<div class="icon"><i class="lni-heart"></i></div>';
        } else {
            $heartIcon = null;
        }

        return <<<HTML
        <figure>
            {$heartIcon}
            <a href="{$this->announce->getLink()}">
                <img class="img-fluid" src="{$this->announce->getProductImgSrc()}" alt="annonce-{$this->announce->getSlug()}">
            </a>
            <div class="overlay">
            </div>
        </figure>
HTML;
    }

    /**
     * Affiche le contenu sur les cartes premium.
     * 
     * @return string
     */
    private function cardContent()
    {
        return <<<HTML
        <div class="feature-content">
            <div class="product">
                <a href="{$this->announce->getCategory()->getSlug()}"><i class="lni-folder"></i> {$this->announce->getCategory()->getTitle()}</a>
            </div>
            <h4><a href="{$this->announce->getLink()}">{$this->announce->getTitle(20)}</a></h4>
            <span>{$this->announce->getUpdatedAt()}</span>
            <ul class="address">
                <li>
                    {$this->showLocation()}
                </li>
                <li>
                    <i class="lni-alarm-clock"></i> {$this->announce->getCreatedAt()}
                </li>
                <li>
                    <a href="users/{$this->announce->getOwner()->getPseudo()}/posts"><i class="lni-user"></i> {$this->announce->getOwner()->getFullName()}</a>
                </li>
            </ul>
            <div class="listing-bottom">
                <h3 class="price float-left">{$this->announce->getPrice()}</h3>
                <a href="{$this->announce->getCategory()->getSlug()}" class="btn-verified float-right"><i class="lni-check-box"></i> Annonce validée</a>
            </div>
        </div>
HTML;
    }

    /**
     * Permet d'afficher le lieu de l'annonce.
     * @return string|null
     */
    private function showLocation()
    {
        return '<a><i class="lni-map-marker"></i>'. $this->announce->getLocation() .'</a>';
    }

    /**
     * Affiche le tag nouveau si cette annonce est nouvelle.
     * 
     * @return string
     */
    private function newTag()
    {
        if ($this->announce->isNew()) {
            return <<<HTML
            <div class="btn-product bg-yellow">
                <a href="announces">Nouveau</a>
            </div>
HTML;
        }
    }

    /**
     * Retourne le tag discout si cette annonce est en promotion.
     * 
     * @return string
     */
    private function discountTag()
    {
//         if ($this->announce->isDiscounted()) {
//             return <<<HTML
//             <div class="btn-product bg-red">
//                 <a href="#">En promotion {$this->announce->getDiscountValue()}%</a>
//             </div>
// HTML;
//         }
    }

    /**
     * Permet d'afficher des étoiles pour un système de notation.
     * 
     * @return string
     */
    private function stars()
    {
        return <<<HTML
        <span class="icon-wrap">
            <i class="lni-star-filled"></i>
            <i class="lni-star-filled"></i>
            <i class="lni-star-filled"></i>
            <i class="lni-star"></i>
            <i class="lni-star"></i>
        </span>
HTML;
    }

    /**
     * Affiche le nombre de vues.
     * 
     * @return string
     */
    private function showViews()
    {
        return <<<HTML
        <span class="count-review">
            <small><strong><em>Vues {$this->announce->getViews()} fois</em></strong></small>
        </span>
HTML;
    }

    /**
     * Affiche le bouton pour supprimer ou modifier l'annonce.
     * 
     * @return string
     */
    private function manageButtons()
    {
        if (User::isAuthenticated() && ($this->announce->getOwner()->getEmailAddress() === User::authenticated()->getEmailAddress() || User::authenticated()->isAdministrator())) {
            
            return <<<HTML
            <nav class="mb-3">
                {$this->editButton()}
                {$this->validateButton()}
                {$this->suspendButton()}
                {$this->deleteButton()}
            </nav>
HTML;
        }
    }

    /**
     * Affiche les commentaires de cette annonces. Avant d'afficher
     * les commentaires on verifie si l'utilisateur est propriétaire
     * de l'annonce ou est un administrateur.
     * 
     * @return string
     */
    private function showComments()
    {
        if (User::isAuthenticated()) {

            if ($this->announce->getOwner()->getEmailAddress() === User::authenticated()->getEmailAddress()
                || User::authenticated()->isAdministrator()
            ) {
                return (new CommentView($this->announce->getLastComment()))->last();
            }
        }
    }

    /**
     * Permet de laisser des commentaires(suggestions) sur l'annonce.
     * @return string
     */
    private function putComments()
    {
        if (User::isAuthenticated() && User::authenticated()->isAdministrator()) {
            return CommentView::put();
        }
    }

    /**
     * Affiche le tag validée si cetta annonce est validée.
     * 
     * @return string
     */
    private function validatedTag()
    {
        return <<<HTML
        <div class="float-right">
            <div class="icon">
                <i class="lni-heart"></i>
            </div>
        </div>
HTML;
    }
    
    /**
     * Affiche un bouton pour editer l'annonce.
     * @return string
     */
    private function editButton()
    {
        if (User::isAuthenticated() && $this->announce->getOwner()->getEmailAddress() === User::authenticated()->getEmailAddress()) {
            return <<<HTML
            <a href="{$this->announce->getManageLink('update')}" class="btn-sm btn-primary">Editer</a>
HTML;
        }
    }

    /**
     * Affiche le bouton pour valider l'annonce.
     * @return string
     */
    private function validateButton()
    {
        if (User::isAuthenticated() && User::authenticated()->isAdministrator() && !$this->announce->isValidated()) {
            return <<<HTML
            <a href="{$this->announce->getManageLink('validate')}" class="btn-sm btn-success">Valider</a>
HTML;
        }
    }

    /**
     * Affiche le bouton qui permet de suspendre l'annonce.
     * @return string
     */
    private function suspendButton()
    {
        if (User::isAuthenticated() && User::authenticated()->isAdministrator()) {
            return <<<HTML
            <a href="{$this->announce->getManageLink('suspend')}" class="btn-sm btn-warning">Suspendre</a>
HTML;
        }
    }
    
    /**
     * Affiche le bouton pour supprimer l'annonce sur la page qui affiche
     * l'annonce.
     * @return string
     */
    private function deleteButton()
    {
        if (User::isAuthenticated()
            && ($this->announce->getOwner()->getEmailAddress() === User::authenticated()->getEmailAddress() || User::authenticated()->isAdministrator())
        ) {
            return <<<HTML
            <a class="btn-sm btn-danger" href="{$this->announce->getManageLink('delete')}">Supprimer</a>
HTML;
        }
    }

}