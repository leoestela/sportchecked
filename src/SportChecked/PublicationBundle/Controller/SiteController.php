<?php

namespace SportChecked\PublicationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SiteController extends Controller {

    public function staticAction($page) {
        return $this->render('PublicationBundle:Site:' . $page . '.html.twig');
    }

}
