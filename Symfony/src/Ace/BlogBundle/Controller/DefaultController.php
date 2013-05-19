<?php

namespace Ace\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ace\BlogBundle\Entity\BlogPost;


class DefaultController extends Controller
{
    
   public function blogAction($page)
	{						
		$limit = 5;		
		$em = $this->getDoctrine()->getEntityManager();
		$qb = $em->createQueryBuilder();

		$qb->add('select', 'u')->add('from', 'AceBlogBundle:BlogPost u')->add('orderBy', 'u.date DESC');
		$allPosts = count($qb->getQuery()->getResult());		
		$q = $qb->getQuery();
		$q->setFirstResult(5*($page-1));
		$q->setMaxResults($limit);		
		$posts = $q->getResult();			
		if($allPosts % $limit == 0)
			$pages = $allPosts/$limit;
			else $pages = (($allPosts - ($allPosts % $limit))/$limit) + 1;
		
		return $this->render('AceBlogBundle:Default:blog.html.twig', array("posts" => $posts, "pages" => $pages, "page" => $page));			
	}
	
	public function rssAction()
	{	
		$em = $this->getDoctrine()->getEntityManager();
		$qb = $em->createQueryBuilder();

		$qb->add('select', 'u')->add('from', 'AceBlogBundle:BlogPost u')->add('orderBy', 'u.date DESC');		
		$posts = $qb->getQuery()->getResult();		
		
		
			$response = $this->render('AceBlogBundle:Default:blog_rss.html.twig', array("posts" => $posts));
			$response->headers->set('Content-Type', 'application/rss+xml');
			return $response;				
	}

	public function newpostAction()
	{
		if (false === $this->get('security.context')->isGranted('ROLE_ADMIN'))
		{
			throw new AccessDeniedException();
		}
		else
		{
			$title = $this->getRequest()->query->get('title');
			$text = $this->getRequest()->query->get('msgpost');
			$author = $this->container->get('security.context')->getToken()->getUser()->getUsername();
			$em = $this->getDoctrine()->getEntityManager();
			$post = new BlogPost();
			$post->setTitle($title);
			$post->setText($text);
			$post->setAuthor($author);
			$post->setDate(new \DateTime("now"));
			$em->persist($post);
			$em->flush();
			return $this->redirect($this->generateUrl('AceBlogBundle_blog'));
		}
	}
	
	public function viewpostAction($id)
	{
	
		$post = $this->getDoctrine()
        ->getRepository('AceBlogBundle:BlogPost')
        ->find($id);
		
		return $this->render('AceBlogBundle:Default:viewpost.html.twig', array("post" => $post));	
	
	}
}
