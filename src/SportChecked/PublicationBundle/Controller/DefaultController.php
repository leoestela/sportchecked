<?php

namespace SportChecked\PublicationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SportChecked\PublicationBundle\Entity\Publication;
use SportChecked\PublicationBundle\Entity\PublicationAction;
use SportChecked\PublicationBundle\Entity\PublicationComment;
use SportChecked\PublicationBundle\Form\Frontend\PublicationType;
use SportChecked\PublicationBundle\Form\Frontend\PublicationCommentType;
use SportChecked\PublicationBundle\Form\Frontend\PublicationSearchType;

class DefaultController extends Controller {

    public function indexAction() {

        $session = $this->getRequest()->getSession();

        if ($session->has('modality')) {
            $modality = $session->get('modality');
        } else {
            $modality = null;
        }
        if ($session->has('category')) {
            $category = $session->get('category');
        } else {
            $category = null;
        }
        $em = $this->getDoctrine()->getManager();
        if (!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            if ($session->has('sport')) {
                $sport = $session->get('sport');
            } else {
                $sport = null;
            }
            $publications = $this->getDoctrine()->getRepository('PublicationBundle:Publication')
                    ->findPublicationsIndex($sport, $modality, $category, $this->container->getParameter('SportChecked.publication_limit'));
        } else {
            $user = $this->get('security.context')->getToken()->getUser();
            if ($session->has('userSport')) {
                $userSport = $session->get('userSport');
            } else {
                if ($user->getNSports() > 0)
                    $userSport = $this->container->getParameter('SportChecked.my_sports');
                else
                    $userSport = null;
            }
            if ($session->has('list')) {
                $list = $session->get('list');
            } else {
                if ($user->getNFollowing() > 0)
                    $list = $this->container->getParameter('SportChecked.my_lists');
                else
                    $list = null;
            }
            $publications = $this->getDoctrine()->getRepository('PublicationBundle:Publication')
                    ->findPublicationsIndexByUser
                    ($user, $userSport, $modality, $category, $list, $this->container->getParameter('SportChecked.publication_limit'));
        }

        return $this->render('PublicationBundle:Default:index.html.twig', array('publications' => $publications));
    }

    public function publicationLoadAction($lastElement) {

        $session = $this->getRequest()->getSession();

        $modality = $session->get('modality');
        $category = $session->get('category');
        if (strrpos($lastElement, "_") === false) {
            $lastPublication = $lastElement;
            $lastAction = null;
        } else {
            $parameters = explode("_", $lastElement);
            $lastPublication = $parameters[0];
            $lastAction = $parameters[1];
        }

        if (!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $sport = $session->get('sport');
            $publications = $this->getDoctrine()->getRepository('PublicationBundle:Publication')
                    ->findPublicationsIndex(
                    $sport, $modality, $category, $this->container->getParameter('SportChecked.publication_limit'), $lastAction, $lastPublication);
        } else {
            $user = $this->get('security.context')->getToken()->getUser();
            $userSport = $session->get('userSport');
            $list = $session->get('list');
            $publications = $this->getDoctrine()->getRepository('PublicationBundle:Publication')
                    ->findPublicationsIndexByUser
                    ($user, $userSport, $modality, $category, $list, $this->container->
                    getParameter('SportChecked.publication_limit'), $lastAction, $lastPublication);
        }

        return $this->render('PublicationBundle:Default:publicationLoad.html.twig', array('publications' => $publications));
    }

    public function publicationNewAction() {
        if (!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {

            return $this->redirect($this->generateUrl('login'));
        }

        $user = $this->get('security.context')->getToken()->getUser();

        $request = $this->getRequest();

        $publication = new Publication();
        $form = $this->createForm(new PublicationType(), $publication, array('attr' => array('user' => $user)));

        if ($request->getMethod() == 'POST') {

            $form->handleRequest($request);

            if ($form->isValid()) {

                $publication->setUser($user);
                $board = $publication->getBoard();
                //$publication->nullBoard();

                $action = new PublicationAction();
                $action->setPublication($publication);
                $action->setUser($user);
                $action->setBoard($board);

                if ($board->getCategory()) {
                    $action->setCategory($board->getCategory());
                }

                $action->setPublished($publication->getCreated());
                $action->setLastAction($publication->getCreated());

                $user->increaseNPublications();
                $board->increasePublications();

                $em = $this->getDoctrine()->getManager();
                $em->persist($publication);
                $em->persist($action);
                $em->persist($user);
                $em->persist($board);
                $em->flush();

                return $this->render('PublicationBundle:Default:publicationModalDetail.html.twig');
            }
        }

        return $this->render('PublicationBundle:Default:publicationNew.html.twig', array('form' => $form->createView()));
    }

    public function publicationEditAction($publicationId) {
        if (!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {

            return $this->redirect($this->generateUrl('login'));
        }

        $user = $this->get('security.context')->getToken()->getUser();

        $request = $this->getRequest();

        $em = $this->getDoctrine()->getManager();

        $publication = $em->getRepository('PublicationBundle:Publication')->findOneBy(array(
            'id' => $publicationId, 'user' => $user->getId()));
        
        $boardOld = $publication->getBoard();

        if ($publication) {
            $form = $this->createForm(new PublicationType(), $publication, array('attr' => array('user' => $user)));

            if ($request->getMethod() == 'POST') {

                $form->handleRequest($request);

                if ($form->isValid()) {

                    $publication->setUser($user);
                    $board = $publication->getBoard();
                    
                    if ($board <> $boardOld){
                        $boardOld->decreasePublications();
                        $board->increasePublications();
                    }
                    //$publication->nullBoard();

                    $action = $em->getRepository('PublicationBundle:PublicationAction')->findOneBy(array(
                        'publication' => $publicationId, 'user' => $user->getId()));
                    $action->setBoard($board);

                    if ($board->getCategory()) {
                        $action->setCategory($board->getCategory());
                    } else {
                        $action->deleteCategory();
                    }

                    $action->setLastAction($publication->getCreated());

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($publication);
                    $em->persist($action);
                    $em->persist($boardOld);
                    $em->persist($board);
                    $em->flush();

                    return $this->render('PublicationBundle:Default:publicationModalDetail.html.twig');
                }
            }

            return $this->render('PublicationBundle:Default:publicationEdit.html.twig', array('form' => $form->createView(), 'publication' => $publication));
        } else {
            return $this->redirect($this->generateUrl('static_page', array('page' => 'notFound')));
        }
    }

    public function publicationModalShareAction($publicationId) {
        if (!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {

            return $this->redirect($this->generateUrl('login'));
        }

        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $publication = $em->getRepository('PublicationBundle:Publication')->findPublicationByUser($publicationId, $user);

        return $this->render('PublicationBundle:Default:publicationModalShare.html.twig', array('publication' => $publication));
    }

    public function publicationShareAction($publicationId) {
        if (!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {

            return $this->redirect($this->generateUrl('login'));
        }

        $em = $this->getDoctrine()->getManager();
        $publication = $em->getRepository('PublicationBundle:Publication')->findOneBy(array('id' => $publicationId));
        $user = $this->get('security.context')->getToken()->getUser();
        $publicationAction = $em->getRepository('PublicationBundle:PublicationAction')->findOneBy(array(
            'publication' => $publicationId, 'user' => $user->getId()));

        if (!$publicationAction) {
            $publicationAction = new PublicationAction();

            $publicationAction->setPublication($publication);
            $publicationAction->setUser($user);
        }

        if (!$publicationAction->getShared()) {
            $publication->increaseNShares();
            $publicationAction->setShared(new \DateTime());
            $publicationAction->setLastAction(new \DateTime());

            $user->increaseNPublications();

            $em->persist($publication);
            $em->persist($publicationAction);
            $em->persist($user);
            $em->flush();
        }

        $return = array("id" => $publication->getId(), "shares" => $publication->getNShares());
        $return = json_encode($return); //jscon encode the array

        return new Response($return, 200, array('Content-Type' => 'application/json')); //make sure it has the correct content type
    }

    public function publicationUnshareAction($publicationId) {
        if (!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {

            return $this->redirect($this->generateUrl('login'));
        }

        $em = $this->getDoctrine()->getManager();
        $publication = $em->getRepository('PublicationBundle:Publication')->findOneBy(array('id' => $publicationId));
        $user = $this->get('security.context')->getToken()->getUser();
        $publicationAction = $em->getRepository('PublicationBundle:PublicationAction')->findOneBy(array(
            'publication' => $publicationId, 'user' => $user->getId()));

        if ($publicationAction) {
            if ($publicationAction->getShared()) {
                if (!$publicationAction->getSaved()) {
                    $publication->getActions()->remove($publicationAction->getId());
                    $publicationAction->setPublication(null);
                } else {
                    $publicationAction->setShared(null);
                }
                $publication->decreaseNShares();
                $user->decreaseNPublications();

                $em->persist($publicationAction);
                $em->persist($publication);
                $em->persist($user);
                $em->flush();
            }
        }

        $return = array("id" => $publication->getId(), "shares" => $publication->getNShares());
        $return = json_encode($return); //jscon encode the array

        return new Response($return, 200, array('Content-Type' => 'application/json')); //make sure it has the correct content type
    }

    public function publicationModalSaveAction($publicationId) {
        if (!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {

            return $this->redirect($this->generateUrl('login'));
        }

        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $publication = $em->getRepository('PublicationBundle:Publication')->findPublicationByUser($publicationId, $user);

        return $this->render('PublicationBundle:Default:publicationModalSave.html.twig', array('publication' => $publication));
    }

    public function publicationSaveAction($publicationId, $boardId) {
        if (!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {

            return $this->redirect($this->generateUrl('login'));
        }

        $em = $this->getDoctrine()->getManager();
        $publication = $em->getRepository('PublicationBundle:Publication')->findOneBy(array('id' => $publicationId));
        $boardOld = $publication->getBoard();
        $user = $this->get('security.context')->getToken()->getUser();
        $board = $em->getRepository('BoardBundle:Board')->findOneBy(array('id' => $boardId, 'user' => $user));
        $publicationAction = $em->getRepository('PublicationBundle:PublicationAction')->findOneBy(array('publication' => $publicationId, 'user' => $user->getId()));
        if ($board) {
            if (!$publicationAction) {
                $publicationAction = new PublicationAction();

                $publicationAction->setPublication($publication);
                $publicationAction->setUser($user);
                $board->increasePublications();
            }

            if ($publicationAction->getPublished()) {
                $publication->setBoard($board);
                $publicationAction->setBoard($board);
                
                if ($boardOld <> $board) {
                    $boardOld->decreasePublications();
                    $board->increasePublications();
                }
            } elseif (!$publicationAction->getSaved()) {
                $publication->increaseNSaves();
                $publicationAction->setBoard($board);
                $publicationAction->setSaved(new \DateTime());
                $publicationAction->setLastAction(new \DateTime());
                $user->increaseNPublications();
                $board->increasePublications();
            } else {
                $publicationAction->setBoard($board);
                
                if ($boardOld <> $board) {
                    $boardOld->decreasePublications();
                    $board->increasePublications();
                }                
            }

            if ($board->getCategory()) {
                $publicationAction->setCategory($board->getCategory());
            } else {
                $publicationAction->deleteCategory();
            }

            $em->persist($publication);
            $em->persist($publicationAction);
            $em->persist($user);
            $em->persist($boardOld);
            $em->persist($board);
            $em->flush();

            if ($publicationAction->getPublished()) {
                $return = array("id" => $publication->getId(), "boardId" => $board->getId(), "boardName" => $board->getName(),
                    "saves" => $publication->getNSaves());
            } else {
                $return = array("id" => $publication->getId(), "saves" => $publication->getNSaves());
            }
            $return = json_encode($return); //jscon encode the array

            return new Response($return, 200, array('Content-Type' => 'application/json')); //make sure it has the correct content type
        } else {
            return $this->redirect($this->generateUrl('static_page', array('page' => 'notFound')));
        }
    }

    public function publicationDeleteAction($publicationId) {
        if (!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {

            return $this->redirect($this->generateUrl('login'));
        }

        $em = $this->getDoctrine()->getManager();
        $publication = $em->getRepository('PublicationBundle:Publication')->findOneBy(array('id' => $publicationId));
        $user = $this->get('security.context')->getToken()->getUser();
        $publicationAction = $em->getRepository('PublicationBundle:PublicationAction')->findOneBy(array('publication' => $publicationId,
            'user' => $user->getId()));

        if ($publicationAction) {
            if ($publicationAction->getSaved()) {
                if (!$publicationAction->getShared()) {
                    $publication->getActions()->remove($publicationAction->getId());
                    $publicationAction->setPublication(null);
                } else {
                    $publicationAction->setSaved(null);
                }

                $publicationAction->deleteCategory();
                $publication->decreaseNSaves();
                $user->decreaseNPublications();

                $em->persist($publicationAction);
                $em->persist($publication);
                $em->persist($user);
                $em->flush();
            }
        }

        $return = array("id" => $publication->getId(), "saves" => $publication->getNSaves());
        $return = json_encode($return); //jscon encode the array

        return new Response($return, 200, array('Content-Type' => 'application/json')); //make sure it has the correct content type
    }

    public function publicationModalCommentsAction($publicationId) {
        $request = $this->getRequest();

        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $publication = $em->getRepository('PublicationBundle:Publication')->findPublicationWithComments($publicationId, $user);

        $comment = new PublicationComment();
        $comment->setUser($user);
        $comment->setPublication($publication);

        $form = $this->createForm(new PublicationCommentType(), $comment);

        if ($request->getMethod() == 'POST') {

            $form->bind($request);

            if ($form->isValid()) {

                $publication->increaseNComments();
                $em->persist($publication);
                $em->flush();

                $em = $this->getDoctrine()->getManager();
                $em->persist($comment);
                $em->flush();

                return $this->redirect($this->generateUrl('publication_modal_comments', array(
                                    'form' => $form->createView(), 'publicationId' => $publicationId)));
            }
        }

        return $this->render('PublicationBundle:Default:publicationModalComments.html.twig', array('form' => $form->createView(),
                    'publication' => $publication));
    }

    public function publicationDeleteCommentAction($publicationId, $commentId) {
        if (!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {

            return $this->redirect($this->generateUrl('login'));
        }

        $em = $this->getDoctrine()->getManager();
        $publication = $em->getRepository('PublicationBundle:Publication')->findOneBy(array('id' => $publicationId));
        $user = $this->get('security.context')->getToken()->getUser();
        $publicationComment = $em->getRepository('PublicationBundle:PublicationComment')->findOneBy(array('id' => $commentId, 'user' => $user));

        if ($publicationComment) {

            $publication->getComments()->remove($publicationComment->getId());
            $publicationComment->setPublication(null);
            $publication->decreaseNComments();

            $em->persist($publicationComment);
            $em->persist($publication);
            $em->flush();
        }

        $return = array("id" => $publication->getId(), "commentId" => $commentId, "comments" => $publication->getNComments());
        $return = json_encode($return); //jscon encode the array

        return new Response($return, 200, array('Content-Type' => 'application/json')); //make sure it has the correct content type
    }

    public function publicationSearchFormAction() {
        $session = $this->getRequest()->getSession();

        $request = $this->getRequest();

        $form = $this->createForm(new PublicationSearchType());

        if ($request->getMethod() == 'POST') {

            $form->handleRequest($request);

            if ($form->isValid()) {

                $formData = $request->get($form->getName());

                $text = $formData['text'];

                $textQuery = '%' . $text . '%';

                $session = $this->getRequest()->getSession();
                $session->set('textQuery', $textQuery);

                return $this->redirect($this->generateUrl('publication_search'));
            }
        }
        return $this->render('PublicationBundle:Default:publicationSearchForm.html.twig', array('form' => $form->createView()));
    }

    public function publicationSearchAction() {

        $session = $this->getRequest()->getSession();

        $searchType = $session->get('searchType');

        $request = $this->getRequest();

        $form = $this->createForm(new PublicationSearchType());

        $formData = $request->get($form->getName());

        $text = $formData['text'];

        $textQuery = '%' . $text . '%';

        $session->set('textQuery', $text);

        if ($searchType == 'sports') {
            $em = $this->getDoctrine()->getManager();
            $userConnected = $this->get('security.context')->getToken()->getUser();
            $sports = $em->getRepository('SportBundle:Sport')->findSportsByText($textQuery
                    , $this->container->getParameter('SportChecked.sports_limit'), $userConnected);

            return $this->render('SportBundle:Default:sportSearch.html.twig', array('sports' => $sports, 'text' => $text));
        } elseif ($searchType == 'users') {
            $em = $this->getDoctrine()->getManager();
            $user = $this->get('security.context')->getToken()->getUser();
            $users = $em->getRepository('UserBundle:User')->findUsersByText($user, $textQuery, $this->container->getParameter('SportChecked.users_limit'));

            return $this->render('UserBundle:Default:userSearch.html.twig', array('users' => $users, 'text' => $text));
        } else {

            /*
              $modality = $session->get('modality');
              $category = $session->get('category');
              if (!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
              $sport = $session->get('sport');

              $publications = $this->getDoctrine()->getRepository('PublicationBundle:Publication')
              ->findPublicationsIndex($sport, $modality, $category, $this->container->getParameter('SportChecked.publication_limit')
              , null, null, $textQuery);
              } else {
              $user = $this->get('security.context')->getToken()->getUser();
              $userSport = $session->get('userSport');
              $list = $session->get('list');
              $publications = $this->getDoctrine()->getRepository('PublicationBundle:Publication')
              ->findPublicationsIndexByUser
              ($user, $userSport, $modality, $category, $list, $this->container->
              getParameter('SportChecked.publication_limit'), null, null, $textQuery);
              }
             */
            if ($this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
                $user = $this->get('security.context')->getToken()->getUser();
                $publications = $this->getDoctrine()->getRepository('PublicationBundle:Publication')
                        ->findPublicationsByUserAndText($user, $textQuery, $this->container->getParameter('SportChecked.publication_limit'));
            } else {
                $publications = $this->getDoctrine()->getRepository('PublicationBundle:Publication')
                        ->findPublicationsByText($textQuery, $this->container->getParameter('SportChecked.publication_limit'));
            }

            return $this->render('PublicationBundle:Default:publicationSearch.html.twig', array('publications' => $publications
                        , 'text' => $text));
        }
    }

    //Búsqueda a partir de la cadena guardada en sesión cuando se acciona el botón de "Publicaciones"
    public function publicationSearchButtonAction() {

        $session = $this->getRequest()->getSession();

        if ($session->has('searchType')) {
            $session->remove('searchType');
        }

        $text = $session->get('textQuery');
        $textQuery = '%' . $text . '%';
        /*
          $modality = $session->get('modality');
          $category = $session->get('category');
          if (!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
          $sport = $session->get('sport');

          $publications = $this->getDoctrine()->getRepository('PublicationBundle:Publication')
          ->findPublicationsIndex($sport, $modality, $category, $this->container->getParameter('SportChecked.publication_limit')
          , null, null, $textQuery);
          } else {
          $user = $this->get('security.context')->getToken()->getUser();
          $userSport = $session->get('userSport');
          $list = $session->get('list');
          $publications = $this->getDoctrine()->getRepository('PublicationBundle:Publication')
          ->findPublicationsIndexByUser
          ($user, $userSport, $modality, $category, $list, $this->container->
          getParameter('SportChecked.publication_limit'), null, null, $textQuery);
          }
         */
        if ($this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $this->get('security.context')->getToken()->getUser();
            $publications = $this->getDoctrine()->getRepository('PublicationBundle:Publication')
                    ->findPublicationsByUserAndText($user, $textQuery, $this->container->getParameter('SportChecked.publication_limit'));
        } else {
            $publications = $this->getDoctrine()->getRepository('PublicationBundle:Publication')
                    ->findPublicationsByText($textQuery, $this->container->getParameter('SportChecked.publication_limit'));
        }
        return $this->render('PublicationBundle:Default:publicationSearch.html.twig', array('publications' => $publications, 'text' => $text));
    }

    public function publicationSearchLoadAction($lastElement) {

        $session = $this->getRequest()->getSession();

        $text = $session->get('textQuery');
        $textQuery = '%' . $text . '%';

        if (strrpos($lastElement, "_") === false) {
            $lastPublication = $lastElement;
            $lastAction = null;
        } else {
            $parameters = explode("_", $lastElement);
            $lastPublication = $parameters[0];
            $lastAction = $parameters[1];
        }
        /*
          $modality = $session->get('modality');
          $category = $session->get('category');

          if (!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
          $sport = $session->get('sport');
          $publications = $this->getDoctrine()->getRepository('PublicationBundle:Publication')
          ->findPublicationsIndex($sport, $modality, $category, $this->container->getParameter('SportChecked.publication_limit')
          , $lastAction, $lastPublication, $textQuery);
          } else {
          $user = $this->get('security.context')->getToken()->getUser();
          $userSport = $session->get('userSport');
          $list = $session->get('list');
          $publications = $this->getDoctrine()->getRepository('PublicationBundle:Publication')
          ->findPublicationsIndexByUser
          ($user, $userSport, $modality, $category, $list, $this->container->
          getParameter('SportChecked.publication_limit'), $lastAction, $lastPublication, $textQuery);
          }
          if ($publications) {
          $session->set('searchLastAction', end($publications)->getLastAction());
          $session->set('searchLastPublication', end($publications)->getPublication()->getId());
          }
         */
        if ($this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $this->get('security.context')->getToken()->getUser();
            $publications = $this->getDoctrine()->getRepository('PublicationBundle:Publication')
                    ->findPublicationsByUserAndText($user, $textQuery, $this->container->getParameter('SportChecked.publication_limit')
                    , $lastAction, $lastPublication);
        } else {
            $publications = $this->getDoctrine()->getRepository('PublicationBundle:Publication')
                    ->findPublicationsByText($textQuery, $this->container->getParameter('SportChecked.publication_limit')
                    , $lastAction, $lastPublication);
        }

        return $this->render('PublicationBundle:Default:publicationLoad.html.twig', array('publications' => $publications));
    }

    public function appModalAddObjectAction() {
        return $this->render('PublicationBundle:Default:appModalAddObject.html.twig');
    }

    public function appModalOnloadAction() {
        return $this->render('PublicationBundle:Default:appModalOnload.html.twig');
    }

    public function helpAction() {
        return $this->render('PublicationBundle:Default:infoGuide.html.twig');
    }

}

