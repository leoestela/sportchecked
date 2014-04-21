<?php

namespace SportChecked\BoardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SportChecked\BoardBundle\Entity\Board;
use SportChecked\BoardBundle\Form\Frontend\BoardType;
use SportChecked\BoardBundle\Form\Frontend\BoardSimpleType;

class DefaultController extends Controller {

    public function boardNewAction() {
        $user = $this->get('security.context')->getToken()->getUser();

        $request = $this->getRequest();

        $board = new Board();
        $board->setUser($user);

        $form = $this->createForm(new BoardType(), $board);

        if ($request->getMethod() == 'POST') {

            $form->bind($request);

            if ($form->isValid()) {

                $user->increaseNFolders();

                $em = $this->getDoctrine()->getManager();
                $em->persist($board);
                $em->persist($user);
                $em->flush();

                return $this->redirect($this->generateUrl('app_modal_onload'));
            }
        }

        return $this->render('BoardBundle:Default:boardModalNew.html.twig', array('form' => $form->createView()));
    }

    public function boardEditAction($boardId) {

        $request = $this->getRequest();

        $em = $this->getDoctrine()->getManager();

        $user = $this->get('security.context')->getToken()->getUser();
        $board = $em->getRepository('BoardBundle:Board')->findOneBy(array('id' => $boardId, 'user' => $user));

        if ($board) {

            $form = $this->createForm(new BoardType(), $board);

            if ($request->getMethod() == 'POST') {

                $form->bind($request);

                if ($form->isValid()) {

                    $em = $this->getDoctrine()->getManager();
                    $result = $em->getRepository('PublicationBundle:Publication')->updatePublicationActionCategory($board
                            , $board->getCategory());
                    $em->persist($board);
                    $em->flush();

                    return $this->redirect($this->generateUrl('app_modal_onload'));
                }
            }

            return $this->render('BoardBundle:Default:boardModalEdit.html.twig', array('form' => $form->createView(), 'board' => $board));
        } else {
            return $this->redirect($this->generateUrl('static_page', array('page' => 'notFound')));
        }
    }

    public function boardAdviceAction($boardId) {

        $em = $this->getDoctrine()->getManager();
        $board = $em->getRepository('BoardBundle:Board')->findOneBy(array('id' => $boardId));

        return $this->render('BoardBundle:Default:boardModalAdvice.html.twig', array('board' => $board));
    }

    public function boardDeleteAction($boardId) {

        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $board = $em->getRepository('BoardBundle:Board')->findOneBy(array('id' => $boardId, 'user' => $user));

        if ($board) {
            $user->decreaseNFolders();
            $user->abateNPublications($board->getPublications());
            
            $result = $em->getRepository('PublicationBundle:Publication')->deletePublicationBoard($boardId);
            $result = $em->getRepository('PublicationBundle:Publication')->deletePublicationActionBoard($boardId);

            $em->remove($board);
            $em->persist($user);
            $em->flush();

            return $this->redirect($this->generateUrl('app_modal_onload'));
        } else {
            return $this->redirect($this->generateUrl('static_page', array('page' => 'notFound')));
        }
    }

    public function boardComboBoxAction($publication) {
        $user = $this->get('security.context')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $boards = $this->getDoctrine()->getRepository('BoardBundle:Board')->findBy(array('user' => $user));

        if ($boards) {
            $action = $em->getRepository('PublicationBundle:PublicationAction')->findOneBy(array('publication' => $publication, 'user' => $user));

            if ($action) {
                if ($action->getBoard()) {
                    return $this->render('BoardBundle:Default:boardComboBox.html.twig', array('boards' => $boards, 'publication' => $publication, 'selectedBoard' => $action->getBoard()));
                } else {
                    return $this->render('BoardBundle:Default:boardComboBox.html.twig', array('boards' => $boards, 'publication' => $publication, 'selectedBoard' => null));
                }
            } else {
                return $this->render('BoardBundle:Default:boardComboBox.html.twig', array('boards' => $boards, 'publication' => $publication, 'selectedBoard' => null));
            }
        }

        return $this->render('BoardBundle:Default:boardComboBox.html.twig', array('boards' => $boards, 'publication' => $publication, 'selectedBoard' => null));
    }

    public function boardSimpleFormAction($boardId) {
        $user = $this->get('security.context')->getToken()->getUser();

        $request = $this->getRequest();

        $em = $this->getDoctrine()->getManager();
        $publication = $em->getRepository('PublicationBundle:Publication')->findOneBy(array('id' => $id));
        $board = new Board();
        $board->setUser($user);

        $form = $this->createForm(new BoardSimpleType(), $board);

        if ($request->getMethod() == 'POST') {

            $form->bind($request);

            if ($form->isValid()) {
                $user->increaseNFolders();

                $em = $this->getDoctrine()->getManager();
                $em->persist($board);
                $em->persist($user);
                $em->flush();
            }
            return $this->render('PublicationBundle:Default:modalSave.html.twig', array('publication' => $publication));
        }

        return $this->render('BoardBundle:Default:boardSimpleForm.html.twig', array('form' => $form->createView(), 'id' => $id));
    }

    /* Inserts new BOARD and reloads combo-box */

    public function boardNewFromComboAction() {

        $user = $this->get('security.context')->getToken()->getUser();

        $request = $this->get('request');
        $name = $request->request->get('boardName');

        $board = new Board();

        $board->setUser($user);
        $board->setName($name);

        $user->increaseNFolders();

        $em = $this->getDoctrine()->getManager();
        $em->persist($board);
        $em->persist($user);
        $em->flush();

        $boards = $this->getDoctrine()->getRepository('BoardBundle:Board')->findBy(array('id' => $board->getId()));

        return $this->render('BoardBundle:Default:boardComboElements.html.twig', array('boards' => $boards));
    }

}
