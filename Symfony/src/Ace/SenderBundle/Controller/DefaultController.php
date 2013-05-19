<?php

namespace Ace\SenderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Ace\UtilitiesBundle\Handler\DefaultHandler;


class DefaultController extends Controller
{
	public function tftpAction()
	{
		$response = new Response('404 Not Found!', 404, array('content-type' => 'text/plain'));
		$data = $this->getRequest()->request->get('data');
		$data = json_decode($data, true);
		if(isset($data["ip"]) && isset($data["bin"]))
		{
			$ip = $data["ip"];
			$bin = $data["bin"];
			if($ip && $bin)
			{
				$data = "ERROR";

				$utilities = new DefaultHandler();
				$data = $utilities->get_data($this->container->getParameter('sender'), 'bin', $bin."&ip=".$ip);
				$response->setContent($data);
				$response->setStatusCode(200);
				$response->headers->set('Content-Type', 'text/html');
			}
			return $response;
		}
		else return new Response(json_encode(array("success" => false)));
	}

}
