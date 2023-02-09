<?php

namespace App\Controller\PostController;

use App\Action\Action;
use App\Action\Update\Update;
use App\Communication\MailContentManager;
use App\File\Image\Image;
use App\Model\Post\Announce;
use App\Communication\Notify\NotifyByHTML;
use App\Communication\Notify\NotifyByMail;
use App\Controller\AppController;
use App\File\File;
use App\Model\User\User;
use App\Utility\Validator;
use App\View\Model\AnnounceView;
use App\View\Page\Page;
use App\View\View;

abstract class AnnounceController extends AppController
{
    /**
     * Permet de créer une annonce.
     * 
     * @return array Un champ nomme resultType : true ou false et la notification contenant les erreurs
     *               dans le cas ou le resultType  = false.            
     */
    public static function create()
    {
        if (Action::dataPosted()) {
            $htmlNotifier = new NotifyByHTML();

            if (!empty(self::validation(false)->getErrors())) {
                return [
                    "resultType" => false,
                    "notification" => $htmlNotifier->errorsByToast(self::validation(false)->getErrors(), "danger")
                ];
            } else {
                $announcePosted = Announce::create();
                return [
                    "resultType" => true,
                    "notification" => null,
                    "post" => $announcePosted
                ];
            }
        
        }
    }    

    /**
     * Permet de mettre à jour une annonce.
     * @param array $params
     */
    public static function update(\App\Model\Post\Announce $announce)
    {
        $htmlNotifier = new NotifyByHTML();
        $notification = null;
        $page = new Page($announce->getTitle() . " - Modification &#149; L'indice", (new AnnounceView($announce))->update());

        if (Update::dataPosted()) {

            if (!empty(self::validation(true, false)->getErrors())) {
                $notification = $htmlNotifier->errorsByToast(self::validation(true)->getErrors(), "danger");
                $page->setView(
                    (new AnnounceView($announce))->update($notification)
                );

            } else {
                if ($announce->update()) {

                    $announce = Announce::actualize("App\Model\Post\Announce", $announce->getId());

                    if ($announce->getLastComment()) {
                        NotifyByMail::administrators(
                            "Nouvelle mise à jour d'annonce",
                            MailContentManager::setContent($announce->updatingEmailNotification())
                        );
                    }

                    $page->setMetatitle("Mise à jour effectuée avec succès &#149; L'indice");
                    $page->setView(
                        View::success(
                            "Mise à jour effectuée avec succès"
                            , "La mise à jour a été effectuée avec succès, Merci de nous faire confiance pour vos annonces."
                            , "Voir"
                            , $announce->getLink()
                            , "Succès de la mise à jour"
                        )
                    );
                } else {

                    $announce = Announce::actualize("App\Model\Post\Announce", $announce->getId());

                    $page->setMetatitle("Oup's ! Nous avons rencontré une erreur lors de la modification de votre annonce &#149; L'indice");
                    $page->setView(
                        View::failed(
                            "Oup's ! Erreur lors de la modification"
                            , "Nous avons rencontré une erreur lors de la mise à jour de votre annonce, veuillez réessayer ultérieurement."
                            , "Retour"
                            , $announce->getLink()
                            , "Echec de la mise à jour"
                        )
                    );
                }
            }
        }

        $page->show();
    }

    /**
     * Permet de supprimer une annonce.
     * @param \App\Model\Post\Announce $announce
     */
    public static function delete(\App\Model\Post\Announce $announce)
    {
        $page = new Page();

        $announceDeleted = $announce;

        if ($announce->delete()) {

            NotifyByMail::administrators("Une annonce a été supprimée", MailContentManager::postDeleted($announceDeleted));

            $link = User::authenticated()->isAdministrator() ? "administration/annonces" : User::authenticated()->getProfileLink() . "/posts";
            $page->setMetatitle("Suppression effectuée avec succès &#149; L'indice");
            $page->setView(
                View::success(
                    "Suppression effectuée avec succès"
                    , "L'annonce a été supprimée avec succès."
                    , "Dashboard"
                    , $link
                    , "Suppression d'annonce"
                )
            );
            $page->show();

        } else {
            $page->setMetatitle("Echec de la suppression &#149; L'indice");
            $page->setView(
                View::failed(
                    "Echec de la suppression",
                    "Nous avons rencontré une erreur lors de la tentative de suppression, veuillez réessayer ultérieurement",
                    "Retour",
                    $announce->getLink(),
                    "Suppression d'annonce"
                )
            );
            $page->show();
        }
    }

    /**
     * Permet de valider une annonce.
     */
    public static function validateAnnounce(\App\Model\Post\Announce $announce)
    {
        $page = new Page();

        if (User::authenticated()->isAdministrator()) {
            if (User::authenticated()->changeStatus($announce->getId(), Announce::convertStatus("validated"), Announce::TABLE_NAME)) {
                $page->setMetatitle("Validation effectuée avec succès &#149; L'indice");
                $page->setView(
                    View::success(
                        "Annonce validée avec succès",
                        "L'annonce a été validée avec succès, merci de nous faire confiance pour vos annonces.",
                        "Voir",
                        $announce->getLink(),
                        "Validation d'annonce"
                    )
                );

                NotifyByMail::users(
                    $announce->getOwner()->getEmailAddress(),
                    $announce->validatedAnnounceEmailSubject(),
                    $announce->validatedAnnounceEmailContent()
                );
            }
        } else {
            $page->setMetatitle("Validation impossible &#149; L'indice");
            $page->setView(
                View::failed(
                    "Echec de la validation",
                    "Oup's ! Nous avons rencontré une erreur lors de la validation de l'annonce, veuillez réessayer utltérieurement.",
                    "Retour",
                    $announce->getLink(),
                    "Validation d'annonce"
                )
            );
        }

        $page->show();
    }

    /**
     * Permet de valider une annonce.
     * @param array $params
     */
    public static function suspendAnnounce(\App\Model\Post\Announce $announce)
    {
        $page = new Page();

        if (User::authenticated()->isAdministrator()) {
            if (User::authenticated()->changeStatus($announce->getId(), Announce::convertStatus("suspended"), Announce::TABLE_NAME)) {
                $page->setMetatitle("Suspension effectuée avec succès &#149; L'indice");
                $page->setView(
                    View::success(
                        "Annonce suspendue avec succès",
                        "L'annonce a été suspendu avec succès, merci de nous faire confiance pour vos annonces.",
                        "Voir",
                        $announce->getLink(),
                        "Suspension d'annonce"
                    )
                );
            }
        } else {
            $page->setMetatitle("Suspension impossible &#149; L'indice");
            $page->setView(
                View::failed(
                    "Echec de la suspension",
                    "Oup's ! Nous avons rencontré une erreur lors de la suspension de l'annonce, veuillez réessayer utltérieurement.",
                    "Retour",
                    $announce->getLink(),
                    "Suspendion d'annonce"
                )
            );
        }

        $page->show();
    }

    /**
     * Permet de faire les validations sur les valeurs postées.
     * à insérer dans la base de données lors de la création ou de la
     * mise à jour d'un compte user.
     * 
     * @param bool $validateImages Permet de dire qu'on veut valider des images.
     * 
     * @return \App\Utilty\Validator
     */
    private static function validation(bool $updating = false, bool $validateImages = true)
    {
        // On fait la validation
        $validate = new Validator();
        $validate->title("title", $_POST["title"]);

        // Validation de la catégorie
        if ($_POST["id_category"] == 0 || empty($_POST["id_category"])) {
            $validate->addError("category", "Veuillez vérifier que vous avez choisi la catégorie de l'annonce.");
        }

        // Valider le sens
        if (empty($_POST["direction"])) {
            $validate->addError("direction", "Veuillez vérifier que vous avez choisi le sens de l'annonce.");
        } else {
            $validate->name($_POST["direction"], "Le sens ne doit pas comporter de code HTML !");
        }

        // Valider le type
        if (empty($_POST["type"])) {
            $validate->addError("type", "Veuillez vérifier que vous avez choisi le type de l'annonce.");
        } else {
            $validate->name($_POST["type"], "Le type ne doit pas comporter de code HTML.");
        }

        // Validation de la localisation
        if (empty($_POST["location"])) {
            $validate->addError("location", "Veuillez vérifier que vous avez choisi la ville.");
        }
        $validate->name($_POST["location"], "Veuillez vérifier que la localisation ne contient pas de code HTML.");

        // Si l'user a rempli le prix
        if (!empty($_POST["price"])) {
            $validate->price("price", $_POST["price"]);
        }

        // Validation de la description
        $validate->description("description", $_POST["description"]);

        // Si user à coché someone_else
        if ((isset($_POST["usertype"]) && $_POST["usertype"] === "someone_else")
            || (!empty($_POST["user_to_join"]) && !empty($_POST["phone_number"]))
        ) {
            $validate->email("user_to_join", $_POST["user_to_join"]);
            $validate->phoneNumber("phone", $_POST["phone_number"], "Veuillez vérifier que vous avez entré un numéro de téléphone valide !");
        }

        if ($validateImages && File::fileIsUploaded('images')) {
            // Si des images ont été postées
            if (!$updating) {
                // Validation du nombre d'images uploadées
                $validate->fileNumber("images", "less", 3, "Veuillez charger 3 images svp !");

                // Validation des extensions
                foreach ($_FILES["images"]["type"] as $extension) {
                    $validate->fileExtensions("images", $extension, ["image/jpeg", "image/png"], "Veuillez vérifier que vous avez chargé des images svp !");
                }

                // Validation des tailles des fichiers
                foreach ($_FILES["images"]["size"] as $size) {
                    $validate->fileSize("images", $size, Image::MAX_VALID_SIZE, "Veuillez charger des fichiers de taille inférieur à 2 Mb svp !");
                }
            } elseif (File::fileIsUploaded('images')) {
                // Validation du nombre d'images uploadées
                $validate->fileNumber("images", "equal", 3, "Veuillez charger 3 images svp !");

                // Validation des extensions
                foreach ($_FILES["images"]["type"] as $extension) {
                    $validate->fileExtensions("images", $extension, ["image/jpeg", "image/png"], "Veuillez vérifier que vous avez chargé des images svp !");
                }

                // Validation des tailles des fichiers
                foreach ($_FILES["images"]["size"] as $size) {
                    $validate->fileSize("images", $size, Image::MAX_VALID_SIZE, "Veuillez charger des fichiers de taille inférieur à 2 Mb svp !");
                }
            }
        }

        return $validate;
    }
}