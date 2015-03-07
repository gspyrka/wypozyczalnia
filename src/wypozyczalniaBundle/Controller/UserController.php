<?php

namespace wypozyczalniaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use wypozyczalniaBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{

	public function logoutAction()
    {
		$session = $this->getRequest()->getSession();
		$session->clear();
		$session->invalidate(1);
		return $this->redirect($this->generateUrl('wypozyczalnia_homepage'));
	}
	public function loginAction(Request $request)
    {
		$user = new User();

        $form = $this->createFormBuilder($user)
            ->add('name', 'text')
            ->add('password', 'password')
            ->add('Zaloguj', 'submit', array('label' => 'Zaloguj'))
            ->getForm();

			
		$form->handleRequest($request);

		if ($form->isValid()) {
			
			$em = $this->getDoctrine()->getManager();
			$findedUser = $em->getRepository('wypozyczalniaBundle:User')->findOneBy(
																										array('name' => $user->getName(), 'password' => $user->getPassword())
																									);
			if($findedUser)
			{
				$session = $this->getRequest()->getSession();
				$session->start();
				$session->set('userName',  $findedUser->getName());
				$session->set('userId',  $findedUser->getId());
				return $this->redirect($this->generateUrl('wypozyczalnia_homepage'));
			}
			else
			{
				return $this->render('wypozyczalniaBundle:Default:rejestracja.html.twig', array(
					'form' => $form->createView(),
					'error' => "Nie zalgowoano!"
				));
			}
		}
	
        return $this->render('wypozyczalniaBundle:Default:rejestracja.html.twig', array(
            'form' => $form->createView(),
			'error' => ""
        ));
    }
	
	
	public function registerAction(Request $request)
    {
		$user = new User();
        $user->setName("");
        $user->setPassword("");

        $form = $this->createFormBuilder($user)
            ->add('name', 'text')
            ->add('password', 'password')
            ->add('Rejestruj', 'submit', array('label' => 'Rejestruj'))
            ->getForm();

			
		$form->handleRequest($request);

		if ($form->isValid()) {
			
			$em = $this->getDoctrine()->getManager();
			$em->persist($user);
			$em->flush();
			
			return $this->redirect($this->generateUrl('wypozyczalnia_homepage'));
		}
	
        return $this->render('wypozyczalniaBundle:Default:rejestracja.html.twig', array(
            'form' => $form->createView(),
			'error' => ""
        ));
    }

}
