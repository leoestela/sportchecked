<?php

namespace SportChecked\SportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use SportChecked\SportBundle\Form\Frontend\SportSearchType;

class DefaultController extends Controller {

    public function sportCheckedAction($sportId) {
        $session = $this->getRequest()->getSession();
        $all = $this->container->getParameter('SportChecked.all_sports');
        if ($all == $sportId)
            $session->set('sport', null);
        else
            $session->set('sport', $sportId);

        return new RedirectResponse($this->generateUrl('index'));
    }

    public function sportMenuElementsAction() {
        $all = $this->container->getParameter('SportChecked.all_sports');
        $em = $this->getDoctrine()->getManager();

        $sports = $em->getRepository('SportBundle:Sport')->findSportsMenu($this->container->getParameter('SportChecked.sports_menu_limit'));

        $items = count($sports) + 1;
        $columns = ceil($items / 15);
        if ($columns > 4) {
            $columns = 4;
        }
        $size = 140 * $columns;

        $session = $this->getRequest()->getSession();

        if ($session->has('sport')) {
            $checked = $session->get('sport');
            if (!$checked)
                $checked = $all;
        } else {
            $checked = $all;
            $session->set('sport', null);
        }

        return $this->render('SportBundle:Default:sportMenuElements.html.twig', array('sports' => $sports,
                    'size' => $size, 'all' => $all, 'checked' => $checked));
    }

    public function userSportCheckedAction($userSportId) {
        $session = $this->getRequest()->getSession();
        $all = $this->container->getParameter('SportChecked.all_sports');
        if ($all == $userSportId)
            $session->set('userSport', null);
        else
            $session->set('userSport', $userSportId);

        return new RedirectResponse($this->generateUrl('index'));
    }

    public function userSportMenuElementsAction() {
        $all = $this->container->getParameter('SportChecked.all_sports');
        $my = $this->container->getParameter('SportChecked.my_sports');

        $em = $this->getDoctrine()->getManager();

        $user = $this->get('security.context')->getToken()->getUser();

        $userSports = $em->getRepository('SportBundle:UserSport')->findUserSportsMenu($user, $this->container->getParameter('SportChecked.sports_menu_limit'));


        $items = count($userSports) + 1;
        $columns = ceil($items / 15);
        if ($columns > 4) {
            $columns = 4;
        }
        $size = 140 * $columns;

        $session = $this->getRequest()->getSession();

        if ($session->has('userSport')) {
            $checked = $session->get('userSport');
            if (!$checked)
                $checked = $all;
        } else {
            if ($userSports) {
                $checked = $my;
                $session->set('userSport', 'my');
            } else {
                $checked = $all;
                $session->set('userSport', null);
            }
        }
        return $this->render('SportBundle:Default:userSportMenuElements.html.twig', array('userSports' => $userSports, 'size' => $size,
                    'all' => $all, 'my' => $my, 'checked' => $checked));
    }

    public function sportPublicationsAction($sportId, $modalityId = null) {

        $em = $this->getDoctrine()->getManager();

        $userConnected = $this->get('security.context')->getToken()->getUser();

        $sport = $em->getRepository('SportBundle:Sport')->findSportByUser($sportId, $userConnected);

        if ($sport) {
            $publications = $em->getRepository('PublicationBundle:Publication')->findPublicationsBySport(
                    $sport, $modalityId, $userConnected, $this->container->getParameter('SportChecked.publication_limit'));
            return $this->render('SportBundle:Default:sportPublications.html.twig', array('sport' => $sport, 'modality' => $modalityId,
                        'publications' => $publications));
        }

        return $this->redirect($this->generateUrl('index'));
    }

    public function sportPublicationsLoadAction($sportId, $lastElement, $modalityId = null) {
        if (strrpos($lastElement, "_") === false) {
            $lastPublication = $lastElement;
            $lastAction = null;
        } else {
            $parameters = explode("_", $lastElement);
            $lastPublication = $parameters[0];
            $lastAction = $parameters[1];
        }

        $em = $this->getDoctrine()->getManager();
        $userConnected = $this->get('security.context')->getToken()->getUser();
        $sport = $em->getRepository('SportBundle:Sport')->findSportByUser($sportId, $userConnected);

        if ($sport) {
            $publications = $em->getRepository('PublicationBundle:Publication')->findPublicationsBySport(
                    $sport, $modalityId, $userConnected, $this->container->getParameter('SportChecked.publication_limit')
                    , $lastAction, $lastPublication);

            return $this->render('PublicationBundle:Default:publicationLoad.html.twig', array('publications' => $publications));
        }

        return $this->redirect($this->generateUrl('index'));
    }

    public function sportWellcomeAction() {
        $request = $this->getRequest();

        $form = $this->createForm(new SportSearchType());

        $formData = $request->get($form->getName());

        $text = $formData['text'];

        $textQuery = '%' . $text . '%';

        $em = $this->getDoctrine()->getManager();
        $userConnected = $this->get('security.context')->getToken()->getUser();
        $sports = $em->getRepository('SportBundle:Sport')->findSportsByText($textQuery
                , $this->container->getParameter('SportChecked.sports_limit'), $userConnected);

        return $this->render('SportBundle:Default:sportWellcome.html.twig', array('sports' => $sports, 'text' => $text));
    }

    public function sportWellcomeLoadAction($lastElement) {
        if (strrpos($lastElement, "_") === false) {
            $lastSport = $lastElement;
        } else {
            $parameters = explode("_", $lastElement);
            $lastSport = $parameters[1];
        }

        $em = $this->getDoctrine()->getManager();
        $userConnected = $this->get('security.context')->getToken()->getUser();
        $sports = $em->getRepository('SportBundle:Sport')->findSports($userConnected, $this->container->getParameter('SportChecked.publication_limit')
                , $lastSport);

        return $this->render('SportBundle:Default:sportSearchLoad.html.twig', array('sports' => $sports));
    }

    public function sportSearchFormAction() {

        $request = $this->getRequest();

        $form = $this->createForm(new SportSearchType());

        if ($request->getMethod() == 'POST') {

            $form->handleRequest($request);

            if ($form->isValid()) {

                $formData = $request->get($form->getName());

                return $this->redirect($this->generateUrl('sport_search'));
            }
        }
        return $this->render('SportBundle:Default:sportSearchForm.html.twig', array('form' => $form->createView()));
    }

    public function sportSearchAction() {
        $request = $this->getRequest();

        $form = $this->createForm(new SportSearchType());

        $formData = $request->get($form->getName());

        $text = $formData['text'];

        $textQuery = '%' . $text . '%';

        $em = $this->getDoctrine()->getManager();
        $userConnected = $this->get('security.context')->getToken()->getUser();
        $sports = $em->getRepository('SportBundle:Sport')->findSportsByText($textQuery
                , $this->container->getParameter('SportChecked.sports_limit'), $userConnected);

        return $this->render('SportBundle:Default:sportWellcome.html.twig', array('sports' => $sports, 'text' => $text));
    }

    public function sportSearchButtonAction() {
        $session = $this->getRequest()->getSession();

        $text = $session->get('textQuery');

        $textQuery = '%' . $text . '%';

        $session->set('searchType', 'sports');

        $em = $this->getDoctrine()->getManager();
        $userConnected = $this->get('security.context')->getToken()->getUser();
        $sports = $em->getRepository('SportBundle:Sport')->findSportsByText($textQuery
                , $this->container->getParameter('SportChecked.sports_limit'), $userConnected);

        return $this->render('SportBundle:Default:sportSearch.html.twig', array('sports' => $sports, 'text' => $text));
    }

    public function sportSearchLoadAction($lastElement) {
        $session = $this->getRequest()->getSession();

        $text = $session->get('textQuery');

        $textQuery = '%' . $text . '%';

        $session->set('searchType', 'sports');
        if (strrpos($lastElement, "_") === false) {
            $lastSport = $lastElement;
        } else {
            $parameters = explode("_", $lastElement);
            $lastSport = $parameters[1];
        }

        $em = $this->getDoctrine()->getManager();
        $userConnected = $this->get('security.context')->getToken()->getUser();
        $sports = $em->getRepository('SportBundle:Sport')->findSportsByText($textQuery
                , $this->container->getParameter('SportChecked.sports_limit'), $userConnected, $lastSport);

        return $this->render('SportBundle:Default:sportSearchLoad.html.twig', array('sports' => $sports));
    }

}
