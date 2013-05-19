<?php

namespace Ace\SearchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


class DefaultController extends Controller
{
    public function findAction()
    {
        if ($this->getRequest()->getMethod() === 'GET') {
            
            $query = $this->getRequest()->query->get('query');

			$usercontroller = $this->get('ace_user.usercontroller');
			$users = json_decode($usercontroller->searchAction($query)->getContent(), true);

			$projectmanager = $this->get('ace_project.sketchmanager');
			$projects = json_decode($projectmanager->searchAction($query)->getContent(), true);

	        return $this->render('AceSearchBundle:Default:find.html.twig', array('query'=>$query, 'users'=>$users, 'projects'=>$projects));
        }
    }
}
