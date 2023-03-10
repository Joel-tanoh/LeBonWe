<?php

namespace App\Controller\UserController;

use App\Action\Action;
use App\Auth\Connexion;
use App\Auth\Cookie;
use App\Auth\Session;
use App\Communication\MailContentManager;
use App\Communication\Notify\NotifyByHTML;
use App\Communication\Notify\NotifyByMail;
use App\Controller\UserController\AdministratorController;
use App\Controller\PostController\AnnounceController;
use App\Model\Post\Announce;
use App\Model\Category;
use App\Model\Model;
use App\Model\User\Registered;
use App\Model\User\User;
use App\Model\User\Visitor;
use App\Utility\Utility;
use App\Utility\Validator;
use App\View\Model\AnnounceView;
use App\View\Model\User\AdministratorView;
use App\View\Model\User\RegisteredView;
use App\View\Model\User\UserView;
use App\View\Page\Page;
use App\View\View;
use Exception;

class RegisteredController extends VisitorController
{
    /**
     * Le controller pour la sign-in d'un utilisateur.
     */
    public static function signIn()
    {
        if (User::isAuthenticated()) {
            Utility::redirect(User::authenticated()->getProfileLink() . "/posts");
        }

        $error = null;

        if (Action::dataPosted()) {
            
            $connexion = new Connexion("email_address", $_POST["password"], DB_NAME, DB_LOGIN, DB_PASSWORD, User::TABLE_NAME);
            $connexion->execute();

            if ($connexion->getError()) {
                $error = NotifyByHTML::error($connexion->getError());
            } else {

                (new Visitor(Session::getVisitorId()))->identify($_POST["email_address"]);

                Session::activateRegistered($_POST["email_address"]);
                
                if (!empty($_POST["remember_me"])) {
                    Cookie::setRegistered($_POST["email_address"]);
                }

                $registered = new Registered($_POST["email_address"]);

                if ($registered->isAdministrator()) {
                    Utility::redirect("administration");
                } else {
                    Utility::redirect($registered->getProfileLink() . "/posts");
                }
            }
        }

        $page = new Page("Je m'identifie &#149; L'indice", (new UserView())->signIn($error));
        $page->setDescription("Connecter vous et acc??der ?? de nombreuses annonces, des d??tails ??poustouflants, des articles, des posts repondant ?? vos besoins.");
        $page->show();
    }

    /**
     * Controlleur de cr??ation d'une nouvelle annonce.
     */
    public static function post()
    {
        User::askToAuthenticate("/sign-in");

        $page = new Page;
        
        if (Action::dataPosted()) {
            $createResult = AnnounceController::create();

            if ($createResult["resultType"] === false) {
                $page->setMetatitle("Poster une annonce &#149; L'indice");
                $page->setView((new AnnounceView())->create($createResult["notification"]));
            } else {

                NotifyByMail::administrators("Une annonce a ??t?? cr????e", $createResult["post"]->newPostMail());
                
                $page->setMetatitle("Votre annonce a ??t?? cr????e avec succ??s &#149; L'indice");
                $page->setView(
                    View::success(
                        "Annonce cr????e avec succ??s"
                        , "L'annonce a ??t?? cr????e avec succ??s, Merci de nous faire confiance pour vos annonces."
                        , "Mes annonces"
                        , User::authenticated()->getProfileLink() . "/posts"
                        , "Succ??s de la cr??ation de l'annonce"
                    )
                );
            }
        } else {
            $page->setMetatitle("Poster une annonce &#149; L'indice");
            $page->setView((new AnnounceView())->create());
        }

        $page->show();
    }

    /**
     * Controller permetant l'utilisateur authentifi?? de
     * de modifier une annonce.
     */
    public static function manageAnnounce(array $params)
    {
        User::askToAuthenticate("/sign-in");

        if (isset($params[1]) && !empty($params[1])
            && isset($params[2]) && !empty($params[2])
            && Category::valueIssetInDB("slug", $params[1], Category::TABLE_NAME)
            && Announce::valueIssetInDB("slug", $params[2], Announce::TABLE_NAME)
        ) {
            $announce = Announce::getBySlug($params[2], Announce::TABLE_NAME, "App\Model\Post\Announce");
            $user = $announce->getOwner();

            if ($announce->hasOwner(User::authenticated()) || User::authenticated()->isAdministrator()) {

                switch ($params[3]) {

                    case "update" :
                        AnnounceController::update($announce);
                        break;

                    case "validate" :
                        AnnounceController::validateAnnounce($announce);
                        break;

                    case "suspend" :
                        AnnounceController::suspendAnnounce($announce);
                        break;

                    case "comment" :
                        AdministratorController::commentAnnounce($announce);
                        break;

                    case "delete" :
                        AnnounceController::delete($announce);
                        break;

                    default :
                        Utility::redirect($user->getProfileLink()."/posts");
                        break;
                }

            } else {
                Utility::redirect(User::authenticated()->getProfileLink()."/posts");
            }

        } else {
            throw new Exception("Ressource non trouv??e !");
        }
    }

    /**
     * Controller de l'index de la partie administration pour le registered.
     */
    public static function administrationIndex()
    {
        User::askToAuthenticate("sign-in");

        if (User::authenticated()->isAdministrator()) {
            $view = (new AdministratorView(User::authenticated()))->administrationIndex();
        } else {
            $view = (new RegisteredView(User::authenticated()))->administrationIndex();
        }
        
        $page = new Page("Administration - " . User::authenticated()->getFullName() . " &#149; L'indice", $view);
        $page->show();
    }

    /**
     * Permet d'afficher le profil de l'utilisateur.
     */
    public static function myProfile(array $params)
    {
        User::askToAuthenticate("/sign-in");

        $page = new Page();
        $user = Registered::getByPseudo($params[3]); // $params[3] = pseudo

        if (User::authenticated()->getPseudo() === $user->getPseudo()) {
            $page->setMetatitle("Administration | Profil " . $user->getFullName() . " &#149; L'indice");
            $view = (new RegisteredView($user))->myProfile();
        } elseif (User::authenticated()->isAdministrator()) {
            $page->setMetatitle("Administration | Profil " . $user->getFullName() . " &#149; L'indice");
            $view = (new AdministratorView(User::authenticated()))->readUserProfile($user);
        } else {
            Utility::redirect($user->getProfileLink());
        }

        $page->setView($view);
        $page->show();
    }

    /**
     * Controller pour g??rer le dashboard d'un utlisateur.
     * @param array $params
     */
    public static function myDashboard(array $params = null)
    {
        User::askToAuthenticate("/sign-in");
        
        $user = Registered::getByPseudo($params[3]);
        $page = new Page();

        if (User::authenticated()->getPseudo() === $user->getPseudo() || User::authenticated()->isAdministrator()) {
           
            if (!empty($params[5])) {
                $status = $params[5];

                if (!in_array($status, Announce::getStatutes())) {
                    $announces = [];
                } elseif ($status === "pending") {
                    $announces = $user->getPendingPosts();
                } elseif ($status === "validated") {
                    $announces = $user->getValidatedPosts();
                } elseif ($status === "suspended") {
                    $announces = $user->getSuspendedPosts();
                }
            } else {
                $announces = $user->getAllPosts();
            }

            $title = User::authenticated()->getPseudo() === $user->getPseudo() ? $user->getFullName() . " - Mes annonces" : "Les annonces de " . $user->getFullName();

            $page->setMetatitle("Administration | " . $title . " &#149; L'indice");
            $page->setView(
                (new RegisteredView($user))->dashboard($announces, $title, $user->getFullName() . " / Annonces")
            );
            
            $page->setDescription("Cette page affiche les annonces post??es par " . $user->getFullName());
            $page->show();

        } else {
            Utility::redirect(User::authenticated()->getProfileLink());
        }

    }

    /**
     * Controller de gestion d'un compte utilisateur.
     * 
     * @param array $params
     */
    public static function selfManage(array $params)
    {
        if (Model::valueIssetInDB("pseudo", $params[2], User::TABLE_NAME)) {
            $registered = User::authenticated();
            $user = Registered::getByPseudo($params[2]);

            if ($params[3] === "update") {
                self::updateAccount($params);
            } elseif ($params[3] === "delete") {
                AdministratorController::deleteUser($user);
            } else {
                Utility::redirect($registered->getProfileLink());
            }

        } else {
            throw new Exception("D??sol??, nous n'avons pas trouver la ressource que vous avez demand?? !");
        }
    }

    /**
     * Controlleur de mise ?? jour d'un user.
     */
    public static function updateAccount(array $params)
    {}

    /**
     * Controller de gestion de la d??connexion d'un utilisateur authentifi??.
     */
    public static function signOut()
    {
        Registered::signOut();
    }

    /**
     * Controller de mot de passe oubli??.
     */
    public static function forgotPassword()
    {
        $error = null;

        if (Action::dataPosted()) {
            $validate = new Validator;
            $validate->email("email_address", $_POST["email_address"]);

            if ($validate->noErrors() && Registered::valueIssetInDB("email_address", $_POST["email_address"], Registered::TABLE_NAME)) {
                $newPwd = Utility::generateCode(8);
                $user = new Registered($_POST["email_address"]);

                if ($user->set("password", $newPwd, $user->getId())) {
                    NotifyByMail::registered($_POST["email_address"], "Nouveau mot de passe", MailContentManager::passwordChanged($user, $newPwd));
                    // $metaTitle = "Mot de passe envoy?? avec succ??s";
                    // $view = View::success(
                    //     "Mot de passe envoy?? avec succ??s !",
                    //     "Vous recevrez dans quelques instant un mail avec votre nouveau mot de passe. Veuillez vous connecter avec ce nouveau mot de passe et le modifier ?? la premi??re connexion.",
                    //     "Page de connexion",
                    //     "sign-in",
                    //     "Modification du mot de passe"
                    // );
                } else {
                    $error = (new NotifyByHTML)->toast("Nous avons rencontr?? un souci lors de la modification du mot de passe, veuillez r??essayer ult??rieurement.", "danger");
                    $metaTitle = "Mot de passe oubli??";
                    // $view = UserView::forgotPassword();
                }
            } else {
                $error = (new NotifyByHTML)->errors($validate->getErrors());
                $metaTitle = "Mot de passe oubli??";
                $view = UserView::forgotPassword($error);
            }
        } else {
            $metaTitle = "Mot de passe oubli??";
            $view = UserView::forgotPassword();
        }

        $page = new Page($metaTitle . " &#149; L'indice", $view);
        $page->show();
    }

    /**
     * Controller de mise ?? jour du mot de passe.
     */
    public static function updatePassword()
    {
        $page = new Page();
    }

}