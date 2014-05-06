<?php

namespace SportChecked\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use SportChecked\UserBundle\Entity\User;
use SportChecked\SportBundle\Entity\UserSport;
use SportChecked\ContactBundle\Entity\Contact;
use SportChecked\FollowerBundle\Entity\Follower;
use SportChecked\UserBundle\Form\Frontend\UserType;
use SportChecked\UserBundle\Form\Frontend\UserProfileType;
use SportChecked\UserBundle\Form\Frontend\UserSearchType;

class DefaultController extends Controller {

    public function loginAction() {
        $request = $this->getRequest();
        $session = $request->getSession();

        $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR, $session->get(SecurityContext::AUTHENTICATION_ERROR));

        return $this->render('UserBundle:Default:userLogin.html.twig', array('last_username' => $session->get(SecurityContext::LAST_USERNAME),
                    'error' => $error));
    }

    public function signupAction() {

        $request = $this->getRequest();
        $user = new User();
        $form = $this->createForm(new UserType(), $user);
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $encoder = $this->get('security.encoder_factory')
                        ->getEncoder($user);
                $user->setSalt(md5(time()));
                $passwordCoded = $encoder->encodePassword(
                        $user->getPassword(), $user->getSalt()
                );
                $user->setPassword($passwordCoded);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                $token = new UsernamePasswordToken(
                        $user, $user->getPassword(), 'user', $user->getRoles()
                );
                $this->container->get('security.context')->setToken($token);

                $contact = new Contact();
                $contact->setContact($user);
                $contact->setUser($user);

                $follower = new Follower();
                $follower->setFollower($user);
                $follower->setUser($user);

                $em->persist($contact);
                $em->persist($follower);

                $em->flush();

                return $this->redirect($this->generateUrl('intro'));
            }
        }
        return $this->render(
                        'UserBundle:Default:userSignup.html.twig', array('form' => $form->createView())
        );
    }

    public function introAction() {
        return $this->render('UserBundle:Default:intro.html.twig');
    }

    public function profileAction() {

        $request = $this->getRequest();
        $user = $this->get('security.context')->getToken()->getUser();
        $form = $this->createForm(new UserProfileType(), $user);
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                return $this->redirect($this->generateUrl('user_publications', array('userSlug' => $user->getSlug())));
            }
        }
        return $this->render(
                        'UserBundle:Default:userProfile.html.twig', array('user' => $user, 'form' => $form->createView())
        );
    }

    public function userWellcomeAction() {
        $userQuery = '%%';

        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $users = $em->getRepository('UserBundle:User')->findUsersByText($user, $userQuery, $this->container->getParameter('SportChecked.users_limit'));

        return $this->render('UserBundle:Default:userWellcome.html.twig', array('users' => $users, 'text' => null));
    }

    public function userWellcomeSearchAction() {
        $request = $this->getRequest();

        $form = $this->createForm(new UserSearchType());

        $formData = $request->get($form->getName());

        $text = $formData['text'];

        $textQuery = '%' . $text . '%';

        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $users = $em->getRepository('UserBundle:User')->findUsersByText($user, $textQuery, $this->container->getParameter('SportChecked.users_limit'));

        return $this->render('UserBundle:Default:userWellcome.html.twig', array('users' => $users, 'text' => $text));
    }

    public function userWellcomeSearchLoadAction($lastElement, $text = null) {
        $session = $this->getRequest()->getSession();

        $textQuery = '%' . $text . '%';

        if (strrpos($lastElement, "_") === false) {
            $lastUser = $lastElement;
        } else {
            $parameters = explode("_", $lastElement);
            $lastUser = $parameters[0];
        }

        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $users = $em->getRepository('UserBundle:User')->findUsersByText($user, $textQuery
                , $this->container->getParameter('SportChecked.users_limit'), $lastUser);

        return $this->render('UserBundle:Default:userSearchLoad.html.twig', array('users' => $users));
    }

    public function userIntroBoardsAction() {

        $userSlug = $this->get('security.context')->getToken()->getUser()->getSlug();
        $em = $this->getDoctrine()->getManager();
        $boards = $em->getRepository('BoardBundle:Board')->findBoardsByUserSlug($userSlug, $this->container->getParameter('SportChecked.board_limit'));

        if (!$boards) {
            $user = $em->getRepository('UserBundle:User')->findOneBy(array('slug' => $userSlug));
        } else {
            $user = $boards[0]->getUser();
        }

        return $this->render('UserBundle:Default:userIntroBoards.html.twig', array('user' => $user, 'boards' => $boards));
    }

    public function userIntroBoardsLoadAction($lastElement) {

        $userSlug = $this->get('security.context')->getToken()->getUser()->getSlug();
        $em = $this->getDoctrine()->getManager();
        $boards = $em->getRepository('BoardBundle:Board')->findBoardsByUserSlug($userSlug, $this->container->getParameter('SportChecked.board_limit')
                , $lastElement);

        return $this->render('BoardBundle:Default:boardLoad.html.twig', array('boards' => $boards));
    }

    public function userPublicationsAction($userSlug) {

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('UserBundle:User')->findOneBy(array('slug' => $userSlug));
        $userConnected = $this->get('security.context')->getToken()->getUser();
        $publications = $em->getRepository('PublicationBundle:Publication')->findPublicationsByUser(
                $user, $userConnected, $this->container->getParameter('SportChecked.publication_limit'));
        
        $user = $em->getRepository('UserBundle:User')->findUserBySlug($userConnected, $userSlug);
        
        return $this->render('UserBundle:Default:userPublications.html.twig', array('user' => $user, 'publications' => $publications));
    }

    public function userPublicationsLoadAction($userSlug, $lastElement) {

        if (strrpos($lastElement, "_") === false) {
            $lastPublication = $lastElement;
            $lastAction = null;
        } else {
            $parameters = explode("_", $lastElement);
            $lastPublication = $parameters[0];
            $lastAction = $parameters[1];
        }

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('UserBundle:User')->findOneBy(array('slug' => $userSlug));
        $userConnected = $this->get('security.context')->getToken()->getUser();
        $publications = $em->getRepository('PublicationBundle:Publication')->findPublicationsByUser(
                $user, $userConnected, $this->container->getParameter('SportChecked.publication_limit'), $lastAction, $lastPublication);

        return $this->render('PublicationBundle:Default:publicationLoad.html.twig', array('publications' => $publications));
    }

    public function userBoardsAction($userSlug) {

        $em = $this->getDoctrine()->getManager();
        $boards = $em->getRepository('BoardBundle:Board')->findBoardsByUserSlug($userSlug, $this->container->getParameter('SportChecked.board_limit'));
        $userConnected = $this->get('security.context')->getToken()->getUser();
        $user = $em->getRepository('UserBundle:User')->findUserBySlug($userConnected, $userSlug);

        return $this->render('UserBundle:Default:userBoards.html.twig', array('user' => $user, 'boards' => $boards));
    }

    public function userBoardsLoadAction($userSlug, $lastElement) {

        $em = $this->getDoctrine()->getManager();
        $boards = $em->getRepository('BoardBundle:Board')->findBoardsByUserSlug($userSlug, $this->container->getParameter('SportChecked.board_limit')
                , $lastElement);

        return $this->render('BoardBundle:Default:boardLoad.html.twig', array('boards' => $boards));
    }

    public function userBoardPublicationsAction($userSlug, $boardId) {

        $em = $this->getDoctrine()->getManager();
        $userConnected = $this->get('security.context')->getToken()->getUser();
        $publications = $em->getRepository('PublicationBundle:Publication')->findPublicationsByBoard(
                $boardId, $userConnected, $this->container->getParameter('SportChecked.publication_limit'));

        if ($publications) {
            $board = $publications[0]->getBoard();
        } else {
            $board = $em->getRepository('BoardBundle:Board')->findOneBy(array('id' => $boardId));
        }
        
        $user = $em->getRepository('UserBundle:User')->findUserBySlug($userConnected, $userSlug);

        return $this->render('UserBundle:Default:userBoardPublications.html.twig', array(
                    'user' => $user, 'board' => $board, 'publications' => $publications));
    }

    public function userBoardPublicationsLoadAction($userSlug, $boardId, $lastElement) {

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
        $publications = $em->getRepository('PublicationBundle:Publication')->findPublicationsByBoard(
                $boardId, $userConnected, $this->container->getParameter('SportChecked.publication_limit'), $lastAction, $lastPublication);

        if ($publications) {
            $user = $publications[0]->getBoard()->getUser();
            $board = $publications[0]->getBoard();
        } else {
            $user = $em->getRepository('UserBundle:User')->findOneBy(array('slug' => $userSlug));
            $board = $em->getRepository('BoardBundle:Board')->findOneBy(array('id' => $boardId));
        }

        return $this->render('PublicationBundle:Default:publicationLoad.html.twig', array('publications' => $publications));
    }

    public function userSportsAction($userSlug) {

        $em = $this->getDoctrine()->getManager();
        $userConnected = $this->get('security.context')->getToken()->getUser();
        $sports = $em->getRepository('SportBundle:UserSport')->findUserSportsByUser($userSlug, $userConnected
                , $this->container->getParameter('SportChecked.sports_limit'));

        $user = $em->getRepository('UserBundle:User')->findUserBySlug($userConnected, $userSlug);

        return $this->render('UserBundle:Default:userSports.html.twig', array('user' => $user, 'sports' => $sports));
    }

    public function userSportsLoadAction($userSlug, $lastElement) {

        if (strrpos($lastElement, "_") === false) {
            $lastSport = $lastElement;
            $lastSportStart = null;
        } else {
            $parameters = explode("_", $lastElement);
            $lastSport = $parameters[0];
            $lastSportStart = $parameters[2];
        }

        $em = $this->getDoctrine()->getManager();
        $userConnected = $this->get('security.context')->getToken()->getUser();
        $sports = $em->getRepository('SportBundle:UserSport')->findUserSportsByUser($userSlug, $userConnected
                , $this->container->getParameter('SportChecked.sports_limit'), $lastSportStart, $lastSport);

        return $this->render('SportBundle:Default:sportLoad.html.twig', array('sports' => $sports));
    }

    public function userFollowSportAction($sportId) {
        if (!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {

            return $this->redirect($this->generateUrl('login'));
        }

        $em = $this->getDoctrine()->getManager();
        $sport = $em->getRepository('SportBundle:Sport')->findOneBy(array('id' => $sportId));
        $user = $this->get('security.context')->getToken()->getUser();
        $userSport = $em->getRepository('SportBundle:UserSport')->findOneBy(array(
            'sport' => $sportId, 'user' => $user->getId()));

        if (!$userSport) {
            $userSport = new UserSport();

            $userSport->setSport($sport);
            $userSport->setUser($user);
            $sport->increaseNFollowers();
            $user->increaseNSports();

            $em->persist($userSport);
            $em->persist($sport);
            $em->persist($user);
            $em->flush();
        }

        $return = array("id" => $sport->getId(), "followers" => $sport->getNFollowers());
        $return = json_encode($return); //jscon encode the array

        return new Response($return, 200, array('Content-Type' => 'application/json')); //make sure it has the correct content type
    }

    public function userUnfollowSportAction($sportId) {
        if (!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {

            return $this->redirect($this->generateUrl('login'));
        }

        $em = $this->getDoctrine()->getManager();
        $sport = $em->getRepository('SportBundle:Sport')->findOneBy(array('id' => $sportId));
        $user = $this->get('security.context')->getToken()->getUser();
        $userSport = $em->getRepository('SportBundle:UserSport')->findOneBy(array(
            'sport' => $sportId, 'user' => $user->getId()));

        if ($userSport) {

            $sport->getFollowers()->remove($userSport->getId());
            $userSport->setSport(null);
            $sport->decreaseNFollowers();
            $user->decreaseNSports();

            $em->persist($userSport);
            $em->persist($sport);
            $em->persist($user);
            $em->flush();
        }

        $return = array("id" => $sport->getId(), "followers" => $sport->getNFollowers());
        $return = json_encode($return); //jscon encode the array

        return new Response($return, 200, array('Content-Type' => 'application/json')); //make sure it has the correct content type
    }

    public function userFollowersAction($userSlug) {

        $em = $this->getDoctrine()->getManager();
        $userConnected = $this->get('security.context')->getToken()->getUser();
        $followers = $em->getRepository('FollowerBundle:Follower')->findFollowersByUser($userSlug, $userConnected
                , $this->container->getParameter('SportChecked.users_limit'));

        $user = $em->getRepository('UserBundle:User')->findUserBySlug($userConnected, $userSlug);

        if ($user) {
            return $this->render('UserBundle:Default:userFollowers.html.twig', array('user' => $user, 'followers' => $followers));
        } else {
            return $this->redirect($this->generateUrl('static_page', array('page' => 'notFound')));
        }
    }

    public function userFollowersLoadAction($userSlug, $lastElement) {

        if (strrpos($lastElement, "_") === false) {
            $lastFollowed = $lastElement;
            $lastFollow = null;
        } else {
            $parameters = explode("_", $lastElement);
            $lastFollowed = $parameters[0];
            $lastFollow = $parameters[1];
        }

        $user = $this->get('security.context')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $followers = $em->getRepository('FollowerBundle:Follower')->findFollowersByUser($userSlug, $user
                , $this->container->getParameter('SportChecked.users_limit'), $lastFollow, $lastFollowed);


        return $this->render('FollowerBundle:Default:followerLoad.html.twig', array('followers' => $followers));
    }

    public function userFollowingAction($userSlug) {

        $em = $this->getDoctrine()->getManager();
        $userConnected = $this->get('security.context')->getToken()->getUser();
        $following = $em->getRepository('ContactBundle:Contact')->findContactsByUser($userSlug
                , $userConnected, $this->container->getParameter('SportChecked.users_limit'));

        $user = $em->getRepository('UserBundle:User')->findUserBySlug($userConnected, $userSlug);

        return $this->render('UserBundle:Default:userFollowing.html.twig', array('user' => $user, 'following' => $following));
    }

    public function userFollowingLoadAction($userSlug, $lastElement) {

        if (strrpos($lastElement, "_") === false) {
            $lastContact = $lastElement;
            $lastFollowing = null;
        } else {
            $parameters = explode("_", $lastElement);
            $lastContact = $parameters[0];
            $lastFollowing = $parameters[1];
        }

        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $following = $em->getRepository('ContactBundle:Contact')->findContactsByUser($userSlug, $user
                , $this->container->getParameter('SportChecked.users_limit'), $lastFollowing, $lastContact);

        return $this->render('ContactBundle:Default:contactLoad.html.twig', array('following' => $following));
    }

    public function userListsAction($userSlug) {
        $session = $this->getRequest()->getSession();

        if ($session->has('lastList')) {
            $session->remove('lastList');
        }

        $em = $this->getDoctrine()->getManager();
        $lists = $em->getRepository('ContactBundle:Contact')->findListsByUser($userSlug, $this->container->getParameter('SportChecked.lists_limit'));

        if ($lists) {
            $session->set('lastList', end($lists)->getId());
        } 
        
        $userConnected = $this->get('security.context')->getToken()->getUser();
        $user = $em->getRepository('UserBundle:User')->findUserBySlug($userConnected, $userSlug);

        return $this->render('UserBundle:Default:userLists.html.twig', array('user' => $user, 'lists' => $lists));
    }

    public function userListsLoadAction($userSlug, $lastElement) {

        $em = $this->getDoctrine()->getManager();
        $lists = $em->getRepository('ContactBundle:Contact')->findListsByUser($userSlug, $this->container->getParameter('SportChecked.lists_limit')
                , $lastElement);

        return $this->render('ContactBundle:Default:listLoad.html.twig', array('lists' => $lists));
    }

    public function userListMembersAction($userSlug, $listId) {

        $em = $this->getDoctrine()->getManager();
        $members = $em->getRepository('ContactBundle:ListMember')->findListMembersByList($listId, $this->container->getParameter('SportChecked.users_limit'));

        $list = $em->getRepository('ContactBundle:ContactList')->findOneBy(array('id' => $listId));

        $userConnected = $this->get('security.context')->getToken()->getUser();
        $user = $em->getRepository('UserBundle:User')->findUserBySlug($userConnected, $userSlug);

        return $this->render('UserBundle:Default:userListMembers.html.twig', array('user' => $user, 'list' => $list, 'members' => $members));
    }

    public function userListMembersLoadAction($userSlug, $listId, $lastElement) {
        if (strrpos($lastElement, "_") === false) {
            $lastMember = $lastElement;
            $lastListed = null;
        } else {
            $parameters = explode("_", $lastElement);
            $lastMember = $parameters[0];
            $lastListed = $parameters[1];
        }

        $em = $this->getDoctrine()->getManager();
        $members = $em->getRepository('ContactBundle:ListMember')->findListMembersByList($listId, $this->container->getParameter('SportChecked.users_limit')
                , $lastListed, $lastMember);

        return $this->render('ContactBundle:Default:listMemberLoad.html.twig', array('members' => $members));
    }

    public function userModalContactListsAction($userSlug, $contactSlug) {
        $em = $this->getDoctrine()->getManager();
        $contact = $em->getRepository('ContactBundle:Contact')->findUserContact($userSlug, $contactSlug);
        $lists = $em->getRepository('ContactBundle:Contact')->findListMembersByUser($userSlug, $contact->getId());

        if ($lists) {
            $user = $lists[0]->getUser();
        } else {
            $user = $em->getRepository('UserBundle:User')->findOneBy(array('slug' => $userSlug));
        }

        return $this->render('UserBundle:Default:userModalContactLists.html.twig', array('user' => $user, 'lists' => $lists, 'contact' => $contact));
    }

    public function userFollowContactAction($userId) {
        if (!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {

            return $this->redirect($this->generateUrl('login'));
        }

        $em = $this->getDoctrine()->getManager();
        $follow = $em->getRepository('UserBundle:User')->findOneBy(array('id' => $userId));
        $user = $this->get('security.context')->getToken()->getUser();
        $contact = $em->getRepository('ContactBundle:Contact')->findOneBy(array(
            'contact' => $userId, 'user' => $user->getId()));

        if (!$contact) {

            $contact = new Contact();
            $contact->setContact($follow);
            $contact->setUser($user);

            $follower = new Follower();
            $follower->setFollower($user);
            $follower->setUser($follow);

            $follow->increaseNFollowers();

            $user->increaseNFollowing();

            $em->persist($contact);
            $em->persist($follower);
            $em->persist($follow);
            $em->persist($user);

            $em->flush();
        }

        $return = array("id" => $follow->getId(), "followers" => $follow->getNFollowers());
        $return = json_encode($return); //jscon encode the array

        return new Response($return, 200, array('Content-Type' => 'application/json')); //make sure it has the correct content type
    }

    public function userUnfollowContactAction($userId) {
        if (!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {

            return $this->redirect($this->generateUrl('login'));
        }

        $em = $this->getDoctrine()->getManager();
        $unfollow = $em->getRepository('UserBundle:User')->findOneBy(array('id' => $userId));
        $user = $this->get('security.context')->getToken()->getUser();
        $follower = $em->getRepository('FollowerBundle:Follower')->findOneBy(array(
            'follower' => $user->getId(), 'user' => $userId));
        $contact = $em->getRepository('ContactBundle:Contact')->findOneBy(array(
            'contact' => $userId, 'user' => $user->getId()));

        if ($contact) {

            $unfollow->getFollowers()->remove($user->getId());
            $unfollow->decreaseNFollowers();

            $follower->setFollower(null);

            $user->getContacts()->remove($contact->getId());
            $contact->setContact(null);
            $user->decreaseNFollowing();

            $em->persist($follower);
            $em->persist($contact);
            $em->persist($unfollow);
            $em->persist($user);
            $em->flush();
        }

        $return = array("id" => $unfollow->getId(), "followers" => $unfollow->getNFollowers());
        $return = json_encode($return); //jscon encode the array

        return new Response($return, 200, array('Content-Type' => 'application/json')); //make sure it has the correct content type
    }

    public function userSearchFormAction() {

        $request = $this->getRequest();

        $form = $this->createForm(new UserSearchType());

        if ($request->getMethod() == 'POST') {

            $form->handleRequest($request);

            if ($form->isValid()) {

                $formData = $request->get($form->getName());

                $text = $formData['text'];

                $textQuery = '%' . $text . '%';

                return $this->redirect($this->generateUrl('user_search'));
            }
        }
        return $this->render('UserBundle:Default:userSearchForm.html.twig', array('form' => $form->createView()));
    }

    public function userSearchAction() {

        $session = $this->getRequest()->getSession();

        if ($session->has('userQuery')) {
            $session->remove('userQuery');
        }

        if ($session->has('searchLastUser')) {
            $session->remove('searchLastUser');
        }

        $request = $this->getRequest();

        $form = $this->createForm(new UserSearchType());

        $formData = $request->get($form->getName());

        $text = $formData['text'];

        $session->set('userQuery', $text);

        return $this->redirect($this->generateUrl('user_modal_onload'));
    }

    public function userModalSearchAction() {
        $request = $this->getRequest();

        $form = $this->createForm(new UserSearchType());

        if ($request->getMethod() == 'POST') {

            $form->handleRequest($request);

            if ($form->isValid()) {

                $formData = $request->get($form->getName());

                $text = $formData['text'];

                $em = $this->getDoctrine()->getManager();
            }
        }

        return $this->render('UserBundle:Default:userModalSearch.html.twig', array('form' => $form->createView(), 'text' => null));
    }

    public function userModalSearchReloadAction() {
        $session = $this->getRequest()->getSession();

        $text = $session->get('textQuery');

        $userQuery = '%' . $text . '%';

        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $users = $em->getRepository('UserBundle:User')->findUsersByText($user, $userQuery, $this->container->getParameter('SportChecked.users_limit'));

        return $this->render('UserBundle:Default:userSearch.html.twig', array('users' => $users, 'text' => $text));
    }

    public function userSearchButtonAction() {
        $session = $this->getRequest()->getSession();

        $text = $session->get('textQuery');

        $textQuery = '%' . $text . '%';

        $session->set('searchType', 'users');

        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $users = $em->getRepository('UserBundle:User')->findUsersByText($user, $textQuery, $this->container->getParameter('SportChecked.users_limit'));

        return $this->render('UserBundle:Default:userSearch.html.twig', array('users' => $users, 'text' => $text));
    }

    public function userSearchLoadAction($lastElement) {
        $session = $this->getRequest()->getSession();

        $text = $session->get('textQuery');

        $textQuery = '%' . $text . '%';

        $session->set('searchType', 'users');

        if (strrpos($lastElement, "_") === false) {
            $lastUser = $lastElement;
        } else {
            $parameters = explode("_", $lastElement);
            $lastUser = $parameters[0];
        }

        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $users = $em->getRepository('UserBundle:User')->findUsersByText($user, $textQuery
                , $this->container->getParameter('SportChecked.users_limit'), $lastUser);

        return $this->render('UserBundle:Default:userSearchLoad.html.twig', array('users' => $users));
    }

    public function userModalOnloadAction() {
        return $this->render('UserBundle:Default:userModalOnload.html.twig');
    }

}

