<?php

namespace SportChecked\CategoryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DefaultController extends Controller {

    public function categoryCheckedAction($categoryId) {
        $parameter = $this->container->getParameter('SportChecked.all_categories');
        $session = $this->getRequest()->getSession();

        if ($categoryId == $parameter) {
            $session->remove('category');
        } else {
            $session->set('category', $categoryId);
        }

        return new RedirectResponse($this->generateUrl('index'));
    }

    public function categoryMenuElementsAction() {
        $all = $this->container->getParameter('SportChecked.all_categories');

        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('CategoryBundle:Category')->findCategoriesMenu();


        $items = count($categories) + 1;
        $columns = ceil($items / 15);
        if ($columns > 4) {
            $columns = 4;
        }
        $size = 140 * $columns;

        $session = $this->getRequest()->getSession();

        if ($session->has('category')) {
            $checked = $session->get('category');
        } else {
            $checked = $all;
        }

        return $this->render('CategoryBundle:Default:categoryMenuElements.html.twig', array('categories' => $categories,
                    'size' => $size, 'all' => $all, 'checked' => $checked));
    }

}
