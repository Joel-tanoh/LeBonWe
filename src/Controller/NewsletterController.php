<?php

namespace App\Controller;

use App\Communication\MailSender;
use App\Communication\Newsletter;
use App\Utility\Utility;
use App\Utility\Validator;
use App\View\Communication\NewsletterView;
use App\View\Page\Page;
use App\View\View;

/**
 * Controller de la newsletter.
 */
abstract class NewsletterController extends AppController
{
    /**
     * Pour enregister un visiteur qui veut s'inscrire à la newsletter.
     */
    public static function register()
    {
        $validator = new Validator();
        $validator->email("email_address", $_POST["email_address"]);
        $page = new Page();

        if (!$validator->getErrors()) {
            if (Newsletter::register($_POST["email_address"])) {
                $email = new MailSender(
                    $_POST["email_address"], "Bienvenue sur L'indice.com", (new NewsletterView)->welcomeNotification()
                );
                if ($email->send()) {
                    $page->setMetaTitle("Abonnement à la Newsletter réussie &#149; L'indice");
                    $page->setView(View::success("Félicitations !", "Vous êtes bien enregistré dans la newsletter ! Vous recevrez un email de confirmation."));
                    $page->show();
                } else {
                    Utility::redirect("/");
                }
            }
        }
    }
}