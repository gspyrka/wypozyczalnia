<?php

namespace wypozyczalniaBundle\Controller;
use wypozyczalniaBundle\Entity\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
			$em = $this->getDoctrine()->getManager();
			$movies = $em->getRepository('wypozyczalniaBundle:Movie')->findAll();
			
			$connection = $em->getConnection();
			$statement = $connection->prepare("SELECT movieId, count(*) AS count FROM review GROUP BY movieId ORDER BY count DESC LIMIT 2");
			$statement->execute();
			$reviewedMoviesIds = $statement->fetchAll();
			$reviewedMovies = array();

			foreach ($reviewedMoviesIds as $movieId) {
				array_push($reviewedMovies, $em->getRepository('wypozyczalniaBundle:Movie')->findOneBy(array('id' => $movieId)));
			}
			
			$genders = array();
			
			foreach ($movies as $movie) {
				$flag = true;
				foreach ($genders as $gender) {
					if($gender == $movie->getGender())
					{
						$flag = false;
						break;
					}
				}
				if($flag)
				{
					array_push($genders, $movie->getGender());
				}
				
			}
        return $this->render('wypozyczalniaBundle:Default:index.html.twig', array('movies' => $movies, 'genders' => $genders, 'reviewedMovies' => $reviewedMovies ));
    }
	
    public function getMenuAction()
    {
		$session = $this->getRequest()->getSession();
		$logged =  $session->get('userName');
        return $this->render('wypozyczalniaBundle:Default:menu.html.twig', array('logged' => $logged));
    }
}
