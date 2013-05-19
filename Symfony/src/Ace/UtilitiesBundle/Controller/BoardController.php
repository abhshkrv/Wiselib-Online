<?php

namespace Ace\UtilitiesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;

class BoardController extends Controller
{
	protected $em;

	public function listAction()
	{
		$boards = array();

		$db_boards = $this->em->getRepository('AceUtilitiesBundle:Board')->findAll();

		foreach($db_boards as $key => $board)
		{
			$boards[] = array(
				"name" => $board->getName(),
				"upload" => json_decode($board->getUpload(), true),
				"bootloader" => json_decode($board->getBootloader(), true),
				"build" => json_decode($board->getBuild(), true),
				"description" => $board->getDescription()
				);
		}
		return new Response(json_encode($boards));
	}

	public function __construct(EntityManager $entityManager)
	{
	    $this->em = $entityManager;
	}
}
