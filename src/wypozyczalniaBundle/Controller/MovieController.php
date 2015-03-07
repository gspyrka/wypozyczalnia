<?php

namespace wypozyczalniaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use wypozyczalniaBundle\Entity\Movie;
use wypozyczalniaBundle\Entity\Actor;
use wypozyczalniaBundle\Entity\Review;
use wypozyczalniaBundle\Entity\Orders;

class MovieController extends Controller
{
	public function showMovieAction($movieName)
    {
		$em = $this->getDoctrine()->getManager();
		$movie = $em->getRepository('wypozyczalniaBundle:Movie')->findOneBy(array('name' => $movieName));
		$actors = $em->getRepository('wypozyczalniaBundle:Actor')->findBy(array('idMovie' => $movie->getId()));
		$reviews = $em->getRepository('wypozyczalniaBundle:Review')->findBy(array('movieId' => $movie->getId()));
		$logged = false;
		if($this->getRequest()->getSession()->get('userName'))
		{
			$logged = true;
		}
		return $this->render('wypozyczalniaBundle:Default:movie.html.twig',  array('movie' => $movie, 'actors' => $actors, 'reviews' => $reviews, 'logged' => $logged));
	}
	
	public function showByGenderAction($genderName)
    {
		$em = $this->getDoctrine()->getManager();
		$movies = $em->getRepository('wypozyczalniaBundle:Movie')->findBy(array('gender' => $genderName));
		return $this->render('wypozyczalniaBundle:Default:index.html.twig', array('movies' => $movies, 'genders' => array() ));
	}
	
	public function addReviewAction($movieId, Request $request)
    {
		$review = new Review();

        $form = $this->createFormBuilder($review)
            ->add('description', 'textarea')
            ->add('Dodaj', 'submit', array('label' => 'Dodaj'))
            ->getForm();

			
		$form->handleRequest($request);

		if ($form->isValid()) {
			
			$em = $this->getDoctrine()->getManager();
			$review->setMovieId($movieId);
			$session = $this->getRequest()->getSession();
			$review->setUserName($session->get('userName'));
			$em->persist($review);
			$em->flush();
			
			$movie = $em->getRepository('wypozyczalniaBundle:Movie')->findOneBy(array('id' => $movieId));

			return $this->redirect($this->generateUrl('wypozyczalnia_film', array('movieName' => $movie->getName())));
			
		}
	
        return $this->render('wypozyczalniaBundle:Default:review.html.twig', array(
            'form' => $form->createView()
        ));
	}
	
	public function acceptAction($movieId)
    {
		if($this->getRequest()->getSession()->get('userName') !== null)
		{
			return $this->render('wypozyczalniaBundle:Default:order.html.twig',  array('movieId' => $movieId));
		}
		else
		{
			return $this->redirect($this->generateUrl('wypozyczalnia_logowanie'));
		}
	}
	
	public function orderAction($movieId)
    {
		$em = $this->getDoctrine()->getManager();
		$session = $this->getRequest()->getSession();
		
		$order = new Orders();
		$order->setIdMovie(intval($movieId));
		$order->setIdUser($session->get('userId'));
			$em->persist($order);
			$em->flush();
		
		return $this->redirect($this->generateUrl('wypozyczalnia_orders'));
	}
	
	public function showOrdersAction()
    {
		$em = $this->getDoctrine()->getManager();
		$session = $this->getRequest()->getSession();

		$orders = $em->getRepository('wypozyczalniaBundle:Orders')->findBy(array('idUser' => $session->get('userId')));
		
		$movies = array();

		foreach ($orders as $order) {
			array_push($movies, $em->getRepository('wypozyczalniaBundle:Movie')->findOneBy(array('id' => $order->getIdMovie())));
		}
			
		return $this->render('wypozyczalniaBundle:Default:orders.html.twig',  array('movies' => $movies));
	}

}