<?php

namespace SportChecked\ContactBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use SportChecked\ContactBundle\Entity\ContactList;
use SportChecked\ContactBundle\Entity\ListMember;
use SportChecked\ContactBundle\Form\Frontend\ContactListType;

class DefaultController extends Controller {

    public function listCheckedAction($listId) {
        $session = $this->getRequest()->getSession();
        $all = $this->container->getParameter('SportChecked.all_lists');

        if ($all == $listId)
            $session->set('list', null);
        else
            $session->set('list', $listId);

        return new RedirectResponse($this->generateUrl('index'));
    }

    public function listMenuElementsAction() {
        $all = $this->container->getParameter('SportChecked.all_lists');
        $my = $this->container->getParameter('SportChecked.my_lists');
        $em = $this->getDoctrine()->getManager();

        $user = $this->get('security.context')->getToken()->getUser();
        $lists = $em->getRepository('ContactBundle:ContactList')->findListsMenu($user);
        $contacts = $user->getNFollowing();

        $items = count($lists) + 1;
        $columns = ceil($items / 15);
        if ($columns > 4) {
            $columns = 4;
        }
        $size = 140 * $columns;

        $session = $this->getRequest()->getSession();

        if ($session->has('list')) {
            $checked = $session->get('list');
            if (!$checked)
                $checked = $all;
        } else {
            if ($contacts > 0) {
                $checked = $my;
                $session->set('list', 'my');
            } else {
                $checked = $all;
                $session->set('list', null);
            }
        }

        return $this->render('ContactBundle:Default:listMenuElements.html.twig', array('lists' => $lists, 'contacts' => $contacts,
                    'size' => $size, 'all' => $all, 'my' => $my, 'checked' => $checked));
    }

    public function listNewAction() {
        $user = $this->get('security.context')->getToken()->getUser();

        $request = $this->getRequest();

        $list = new ContactList();
        $list->setUser($user);

        $form = $this->createForm(new ContactListType(), $list);

        if ($request->getMethod() == 'POST') {

            $form->bind($request);

            if ($form->isValid()) {

                $user->increaseNLists();

                $em = $this->getDoctrine()->getManager();
                $em->persist($list);
                $em->persist($user);
                $em->flush();

                return $this->redirect($this->generateUrl('app_modal_onload'));
            }
        }

        return $this->render('ContactBundle:Default:listModalNew.html.twig', array('form' => $form->createView()));
    }

    public function listNewFromContactAction($contactSlug) {
        $user = $this->get('security.context')->getToken()->getUser();

        $request = $this->getRequest();

        $list = new ContactList();
        $list->setUser($user);

        $form = $this->createForm(new ContactListType(), $list);

        if ($request->getMethod() == 'POST') {

            $form->bind($request);

            if ($form->isValid()) {

                $user->increaseNLists();

                $em = $this->getDoctrine()->getManager();
                $em->persist($list);
                $em->persist($user);
                $em->flush();

                return $this->redirect($this->generateUrl('user_modal_contact_lists', array('userSlug' => $user->getSlug(), 'contactSlug' => $contactSlug)));
            }
        }

        return $this->render('ContactBundle:Default:listModalNewFromContact.html.twig', array('form' => $form->createView(), 'contactSlug' => $contactSlug));
    }

    public function listEditAction($listId) {

        $request = $this->getRequest();

        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $list = $em->getRepository('ContactBundle:ContactList')->findOneBy(array('id' => $listId, 'user' => $user));

        if ($list) {

            $form = $this->createForm(new ContactListType(), $list);

            if ($request->getMethod() == 'POST') {

                $form->bind($request);

                if ($form->isValid()) {

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($list);
                    $em->flush();

                    return $this->redirect($this->generateUrl('app_modal_onload'));
                }
            }

            return $this->render('ContactBundle:Default:listModalEdit.html.twig', array('form' => $form->createView(), 'list' => $list));
        } else {
            return $this->redirect($this->generateUrl('static_page', array('page' => 'notFound')));
        }
    }

    public function listAdviceAction($listId) {

        $em = $this->getDoctrine()->getManager();
        $list = $em->getRepository('ContactBundle:ContactList')->findOneBy(array('id' => $listId));
        return $this->render('ContactBundle:Default:listModalAdvice.html.twig', array('list' => $list));
    }

    public function listDeleteAction($listId) {
        $user = $this->get('security.context')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $list = $em->getRepository('ContactBundle:ContactList')->findOneBy(array('id' => $listId, 'user' => $user));
        //$result = $em->getRepository('PublicationBundle:Publication')->deletePublicationActionBoard($boardId);
        if ($list) {
            $result = $em->getRepository('ContactBundle:Contact')->deleteListMembers($list); 
            $user->decreaseNLists();

            $em->remove($list);
            $em->persist($user);
            $em->flush();

            return $this->redirect($this->generateUrl('app_modal_onload'));
        } else {
            return $this->redirect($this->generateUrl('static_page', array('page' => 'notFound')));
        }
    }

    public function listAddMemberAction($listId, $contactId) {
        $user = $this->get('security.context')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $list = $em->getRepository('ContactBundle:ContactList')->findOneBy(array('id' => $listId, 'user' => $user));
        $contact = $em->getRepository('ContactBundle:Contact')->findOneBy(array('id' => $contactId, 'user' => $user));

        if ($list && $contact) {
            $list->increaseNMembers();

            $listMember = new ListMember();
            $listMember->setContactList($list);
            $listMember->setContact($contact);

            $em->persist($listMember);
            $em->persist($list);
            $em->flush();

            return $this->redirect($this->generateUrl('user_modal_contact_lists', array('userSlug' => $user->getSlug(), 'contactSlug' => $contact->getContact()->getSlug())));
        } else {
            return $this->redirect($this->generateUrl('static_page', array('page' => 'notFound')));
        }
    }

    public function listDeleteMemberAction($listId, $contactId) {
        $user = $this->get('security.context')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $list = $em->getRepository('ContactBundle:ContactList')->findOneBy(array('id' => $listId, 'user' => $user));
        if ($list) {
            $listMember = $em->getRepository('ContactBundle:ListMember')->findOneBy(array('contactlist' => $listId, 'contact' => $contactId));
            $contact = $em->getRepository('ContactBundle:Contact')->findOneBy(array('id' => $contactId));

            if ($listMember) {
                $list->decreaseNMembers();
                $list->getMembers()->remove($listMember->getId());

                $contact->getLists()->remove($listMember->getId());

                $listMember->setContactList(null);
                $listMember->setContact(null);

                $em->persist($list);
                $em->persist($contact);
                $em->persist($listMember);
                $em->flush();
            }

            return $this->redirect($this->generateUrl('user_modal_contact_lists', array('userSlug' => $user->getSlug(), 'contactSlug' => $contact->getContact()->getSlug())));
        } else {
            return $this->redirect($this->generateUrl('static_page', array('page' => 'notFound')));
        }
    }

}
