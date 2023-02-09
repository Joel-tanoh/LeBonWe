<?php

namespace App\View\Communication;

use App\View\Form;

/** Classe gestionnaire des vues relatives aux commentaires */
class CommentView
{
    private $comment;

    /**
     * Constructeur d'une vue de commentaire.
     */
    public function __construct(\App\Model\Post\Comment $comment = null)
    {
        $this->comment = $comment;
    }

    /**
     * Permet de laisser des commentaires(suggestions) sur l'annonce.
     * @return string
     */
    public static function put()
    {
        $form = new Form($_SERVER["REQUEST_URI"] . "/comment", "mt-4");

        return <<<HTML
        {$form->open()}
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12">
                    <div class="form-group">
                        <textarea id="comment" class="border px-2 w-100" name="comment" cols="45" rows="5" placeholder="Laisser une suggestion en tant qu'administrateur..." required></textarea>
                    </div>
                    <button type="submit" id="submit" class="btn btn-common">Envoyer</button>
                </div>
            </div>
        {$form->close()}
HTML;
    }

    /**
     * Permet d'afficher tous les commentaires.
     * 
     * @return string
     */
    public static function showAll(array $comments)
    {
        $commentsList = null;

        foreach ($comments as $comment) {
            $commentsList .= (new self($comment))->show();
        }

        return <<<HTML
        <div id="comments">
            <div class="comment-box">
                <h3>Commentaires</h3>
                <ol class="comments-list">
                    {$commentsList}
                </ol>
            </div>
        </div>
HTML;
    }

    /**
     * Affiche le dernier commentaire.
     * 
     * @return string
     */
    public function last()
    {
        if (null !== $this->comment) {
            return <<<HTML
            <div id="comments">
                <div class="comment-box">
                    <h5>Dernier commentaire</h5>
                    <ol class="comments-list">
                        {$this->show()}
                    </ol>
                </div>
            </div>
HTML;
        } else {
            return $this->noComments();
        }
    }

    /**
     * Affiche un commentaire avec la photo de profile
     * de l'utilisateur qui l'a postée.
     * 
     * @return string
     */
    public function show()
    {
        return <<<HTML
        <li>
            <div class="media">
                <div class="thumb-left">
                    <img class="img-fluid circle" src="{$this->comment->getPoster()->getAvatarSrc()}" alt="Photo de profil de {$this->comment->getPoster()->getFullName()}">
                </div>
                <div class="info-body">
                    <div class="media-heading">
                        <h6 class="name">{$this->comment->getPoster()->getFullName()}</h6> 
                        <span class="comment-date"><i class="lni-alarm-clock"></i> {$this->comment->getPostedAt()}</span>
                    </div>
                    <p>{$this->comment->getContent()}</p>
                </div>
            </div>
        </li>
HTML;
    }

    /**
     * Retourne pas de commentaires.
     * 
     * @return string
     */
    private function noComments()
    {
        return <<<HTML
        <p class="mt-3 p-3 border rounded">Aucun commentaire</p>
HTML;
    }

}
