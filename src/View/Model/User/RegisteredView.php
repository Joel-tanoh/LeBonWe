<?php

namespace App\View\Model\User;

use App\Communication\MailSender;
use App\Model\Post\Announce;
use App\Model\User\User;
use App\View\Snippet;
use App\View\Form;
use App\View\Model\AnnounceView;

/**
 * Classe qui gère la vue de l'utilisateur inscrit sur le site.
 */
class RegisteredView extends UserView
{
    protected $user;

    /**
     * Constructeur de la vue du registered.
     * 
     * @param \App\Model\User\Registered $user
     */
    public function __construct(\App\Model\User\Registered $user = null)
    {
        $this->user = $user;
    }

    /**
     * Affiche le dashboard de l'utilisateur.
     * 
     * @param array  $announces
     * @param string $dashboardTitle Le titre du tableau.
     * @param string $current        Pour indiquer à l'utilisateur où il se trouve.
     * 
     * @return string
     */
    public function dashboard(array $announces, string $dashboardTitle, string $current)
    {
        $content = <<<HTML
        {$this->dashboardTitle($dashboardTitle)}
        {$this->dashboardContent($announces)}
HTML;
        return parent::administrationTemplate($content, $current, $current);
    }

    /**
     * Affiche le profile de l'utilisateur, ses statistiques.
     * 
     * @return string
     */
    public function myProfile()
    {
        $content = <<<HTML
        <section>
            <h4>Mon profil</h4>
        </section>
HTML;

        return parent::administrationTemplate($content, "Mon profil", "Profil / " . $this->user->getFullName());
    }

    /**
     * Affiche le titre du tableau qui affiche les announces.
     * 
     * @param string $dashboardTitle Le titre du tableau.
     * 
     * @return string
     */
    private function dashboardTitle(string $dashboardTitle)
    {
        return <<<HTML
        <div class="dashboard-box">
            <h1 class="dashboard-title">{$dashboardTitle}</h1>
        </div>
HTML;
    }

    /**
     * Affiche le contenu du tableau.
     * 
     * @param array $announces
     * 
     * @return string
     */
    private function dashboardContent(array $announces)
    {
        return <<<HTML
        <div class="dashboard-wrapper">
            {$this->dashboardTableNav()}
            {$this->dashboardContentTable($announces)}
        </div>
HTML;
    }

    /**
     * Affiche des boutons pour filtrer les annonces à afficher
     * dans le dashboard.
     * 
     * @return string
     */
    protected function dashboardTableNav()
    {
        if ($this->user !== null) {
            return <<<HTML
            <nav class="nav-table">
                <ul>
                    {$this->dashbaordNavStatus($this->user->getProfileLink()."/posts", "Tous", $this->user->getAllPostsNumber())}
                    {$this->dashbaordNavStatus($this->user->getProfileLink()."/posts/pending", "En attente", $this->user->getPendingPostsNumber())}
                    {$this->dashbaordNavStatus($this->user->getProfileLink()."/posts/validated", "Validées", $this->user->getValidatedPostsNumber())}
                    {$this->dashbaordNavStatus($this->user->getProfileLink()."/posts/suspended", "Suspendues", $this->user->getSuspendedPostsNumber())}
                </ul>
            </nav>
HTML;
        } else {
            return <<<HTML
            <nav class="nav-table">
                <ul>
                    {$this->dashbaordNavStatus("/administration/annonces", "Tous", Announce::getAllNumber())}
                    {$this->dashbaordNavStatus("/administration/annonces/pending", "En attente", Announce::getPendingNumber())}
                    {$this->dashbaordNavStatus("/administration/annonces/validated", "Validées", Announce::getValidatedNumber())}
                    {$this->dashbaordNavStatus("/administration/annonces/suspended", "Suspendues", Announce::getSuspendedNumber())}
                </ul>
            </nav>
HTML;
        }
    }

    /**
     * Affiche un bouton sur le dahsboard de l'utilisateur pour lui permettre de 
     * filter le résultat.
     * 
     * @return string
     */
    public function dashbaordNavStatus(string $href, string $text, int $nbr)
    {
        return <<<HTML
        <li><a href="{$href}">{$text} ({$nbr})</a></li>
HTML;
    }

    /**
     * Affiche le tableau dans lequel les annonces sont affichées
     * sur le dashboard.
     * 
     * @param array $announces
     * 
     * @return string
     */
    protected function dashboardContentTable(array $announces)
    {
        if (!empty($announces)) {
            $form = new Form($_SERVER["REQUEST_URI"] . "/delete");
            
            return <<<HTML
            {$form->open()}
                <table class="table table-responsive dashboardtable tablemyads">
                    {$this->dashboardContentTableHead()}
                    {$this->dashboardContentTableBody($announces)}
                </table>
            {$form->close()}
HTML;
        } else {
            return Snippet::noResult();
        }
    }

    /**
     * Affiche le head du tableau qui donne les annonces 
     * dans le dashboard.
     * 
     * @return string
     */
    protected function dashboardContentTableHead()
    {
        return <<<HTML
        <thead>
            <tr>
                <th>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="checkedall">
                        <label class="custom-control-label" for="checkedall"></label>
                    </div>
                </th>
                <th>Photo</th>
                <th>Titre</th>
                <th>Catégorie</th>
                <th>Statut</th>
                <th>Prix</th>
                <th>Actions</th>
            </tr>
        </thead>
HTML;
    }

    /**
     * Affiche le contenu du tableau qui liste les annonces.
     * 
     * @param array $announces
     * 
     * @return string
     */
    protected function dashboardContentTableBody(array $announces)
    {
        $rows = null;
        foreach ($announces as $announce) {
            $rows .= (new AnnounceView($announce))->registeredDashboardRow();
        }

        return <<<HTML
        <tbody>
            {$rows}
        </tbody>
HTML;
    }

    /**
     * Affiche l'avatar de l'utilisateur.
     * 
     * @return string
     */
    public function avatar()
    {
        return <<<HTML
        <img src="{$this->user->getAvatarSrc()}" alt="photo-de-profil-de-{$this->user->getFullName()}" class="img-fluid img-circle"/>
HTML;
    }

    /**
     * Affiche le formulaire pour changer le mot de passe.
     * 
     * @return string
     */
    public function changePassword()
    {
        $content = <<<HTML
        <section>
            <h4>Modification de mon mot de passe</h4>

        </section>
HTML;
        return parent::administrationTemplate($content, "Modification de mon mot passe", $this->user->getFullName() . " / Administration / Modification du mot de passe");
    
    }

    /**
     * Affiche la vue de l'index de l'administration pour cet utilisateur enrgistré.
     * @return string
     */
    public function administrationIndex()
    {
        $content = <<<HTML
        <h4>Administration</h4>
HTML;
        return parent::administrationTemplate($content, "Administration", $this->user->getFullName() . " / Administration");
    }

    /**
     * Retourne une vue pour afficher les annonces de cet utilisateur.
     * @return string
     */
    public function showMyAnnounces()
    {
        return (new AnnounceView())->show($this->user->getValidatedPosts(), "Les annonces de " . $this->user->getFullName());
    }

    /**
     * Un tableau qui affiche la liste des utilisateurs.
     * 
     * @return string
     */
    public function list(array $users)
    {
        $form = new Form("administration/users/delete", "w-100");
        $usersRows = null;

        foreach ($users as $user) {
            $usersRows .= $this->listRow($user);
        }

        return <<<HTML
        <h1 class="dashboard-title">Liste des utilisateurs</h1>
        {$form->open()}
            <table class="table table-hover bg-white">
                {$this->listHead()}
                <tbody>
                    {$usersRows}
                </tbody>
            </table>
        {$form->close()}
HTML;
    }

    /**
     * Affiche une ligne dans le tableau qui affiche la liste
     * des utilisateurs.
     * 
     * @param App\Model\User\Registered $user
     */
    private function listRow(\App\Model\User\Registered $user)
    {
        return <<<HTML
        <tr>
            <td><input type="checkbox" name="{$user->getId()}" id="checkAllUsers"></td>
            <td><a href="{$user->getProfileLink()}">{$user->getName()}</a></td>
            <td>{$user->getFirstNames()}</td>
            <td>{$user->getPseudo()}</th>
            <td>{$user->getAllPostsNumber()}</th>
            <td>{$user->getEmailAddress()}</td>
            <td>{$user->getStatus()}</td>
            <td>{$user->getRegisteredAt()}</td>
        </tr>
HTML;
    }

    /**
     * Affiche les entêtes des colonnes dans le tableau qui liste les utilisateurs.
     * 
     * @return string
     */
    private function listHead()
    {
        return <<<HTML
        <thead>
            <tr>
                <th scope="col"><input type="checkbox" name="users[]" id="checkAllUsers"></th>
                <th scope="col">Nom</th>
                <th scope="col">Prénom(s)</th>
                <th scope="col">Pseudo</th>
                <th scope="col">Nbr. Post</th>
                <th scope="col">Adresse Email</th>
                <th scope="col">Statut</th>
                <th scope="col">Date d'inscription</th>
            </tr>
        </thead>
HTML;
    }

}