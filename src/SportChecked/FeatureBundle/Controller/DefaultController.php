<?php

namespace SportChecked\FeatureBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DefaultController extends Controller {

    public function modalityCheckedAction($modalityId) {

        $parameter = $this->container->getParameter('SportChecked.all_modalities');
        $session = $this->getRequest()->getSession();

        if ($modalityId == $parameter) {
            $session->remove('modality');
        } else {
            $session->set('modality', $modalityId);
        }

        return new RedirectResponse($this->generateUrl('index'));
    }

    public function modalityMenuElementsAction() {
        $all = $this->container->getParameter('SportChecked.all_modalities');
        $em = $this->getDoctrine()->getManager();

        $modalities = $em->getRepository('FeatureBundle:Feature')->findModalitiesMenu();

        $items = count($modalities) + 1;
        $columns = ceil($items / 15);
        if ($columns > 4) {
            $columns = 4;
        }
        $size = 140 * $columns;

        $session = $this->getRequest()->getSession();

        if ($session->has('modality')) {
            $checked = $session->get('modality');
        } else {
            $checked = $all;
        }

        return $this->render('FeatureBundle:Default:modalityMenuElements.html.twig', array('modalities' => $modalities,
                    'size' => $size, 'all' => $all, 'checked' => $checked));
    }

}
