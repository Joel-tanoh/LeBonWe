<?php

namespace App\View\Communication;

use App\Communication\MailContentManager;
use App\Communication\MailSender;
use App\View\Form;
use App\View\Snippet;

class NewsletterView extends Snippet
{
    /**
     * La section qui permet au visiteur de s'abonner à la newsletter.
     * 
     * @return string
     */
    public function suscribeNewsletterSection()
    {
        $form = new Form("newsletters/register");

        return <<<HTML
        <section class="subscribes section-padding">
            <div class="container">
                <div class="row wrapper-sub">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <h3>Abonnez-vous à la newsletter</h3>
                        <p>Rejoignez nos plus de 1000 abonnés et accédez aux meilleures annonces de Côte d'Ivoire !</p>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        {$form->open()}
                            <div class="subscribe">
                                <input class="form-control" name="email_address" placeholder="Votre email ici" required type="email">
                                <button class="btn btn-common" type="submit">S'inscrire</button>
                            </div>
                        {$form->close()}
                    </div>
                </div>
            </div>
        </section>
HTML;
    }

    /**
     * Affiche un texte et un champ pour s'abonner à la newsletter.
     * 
     * @return string
     */
    public function inFooter()
    {
        return <<<HTML
        <h3 class="block-title">Newsletter</h3>
        <p class="text-sub">Rejoignez nos plus de 1000 abonnés et accédez aux meilleures annonces de Côte d'Ivoire !</p>
        <form action="newsletters/register" method="post" id="subscribe-form" name="subscribe-form" class="validate">
            <div class="form-group is-empty">
                <input type="email" name="email_address" class="form-control" id="EMAIL" placeholder="Adresse Email" required>
                <button type="submit" name="subscribe" id="subscribes" class="btn btn-common sub-btn"><i class="lni-check-box"></i></button>
                <div class="clearfix"></div>
            </div>
        </form>
HTML;
    }

    /**
     * Retourne la notification d'accueil pour l'utilisateur qui vient de s'enregister
     * dans la newsletter.
     * 
     * @return string
     */
    public static function welcomeNotification()
    {
        $content = <<<HTML
        <p>Nous sommes ravis de vous compter parmi nos abonnés. <br>Nous vous tiendrons informé régulièrement des nouveautés.</p>
HTML;
        return MailContentManager::setContent($content);
    }

}