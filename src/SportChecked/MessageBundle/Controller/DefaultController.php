<?php

namespace SportChecked\MessageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SportChecked\MessageBundle\Entity\Message;
use SportChecked\MessageBundle\Form\Frontend\MessageType;

class DefaultController extends Controller {

    public function messageNewAction() {
        $user = $this->get('security.context')->getToken()->getUser();

        $request = $this->getRequest();

        $message = new Message();
        $message->setUser($user);

        $form = $this->createForm(new MessageType(), $message);

        if ($request->getMethod() == 'POST') {

            $form->bind($request);

            if ($form->isValid()) {


                $em = $this->getDoctrine()->getManager();
                $em->persist($message);
                $em->flush();

                return $this->redirect($this->generateUrl('message_sent'));
            }
        }

        return $this->render('MessageBundle:Default:messageModalNew.html.twig', array('form' => $form->createView()));
    }

    public function messageSentAction() {
        return $this->render('MessageBundle:Default:messageSent.html.twig');
    }

}
