<?php

namespace Ace\GenericBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Validator\Constraints\Regex;
use Ace\UtilitiesBundle\Handler\DefaultHandler;


class DefaultController extends Controller
{
	
	public function indexAction()
	{
		if ($this->get('security.context')->isGranted('ROLE_USER'))
		{
			// Load user content here
			$user = json_decode($this->get('ace_user.usercontroller')->getCurrentUserAction()->getContent(), true);
			{
				$popular_users = json_decode($this->get('ace_user.usercontroller')->getTopUsersAction(5)->getContent(), true);
				if($popular_users["success"] == true)
					$popular_users = $popular_users["list"];
				else
					unset($popular_users);

				return $this->render('AceGenericBundle:Index:list.html.twig', array('user' => $user, "popular_users" => $popular_users));
			}
		}

		return $this->render('AceGenericBundle:Index:index.html.twig');
	}
	
	public function userAction($user)
	{
		$user = json_decode($this->get('ace_user.usercontroller')->getUserAction($user)->getContent(), true);

		if ($user["success"] === false)
		{
			return $this->render('AceGenericBundle:Default:minor_error.html.twig', array('error'=> "There is no such user."));
		}

		$projectmanager = $this->get('ace_project.sketchmanager');
		$projects = $projectmanager->listAction($user["id"])->getContent();
		$projects = json_decode($projects, true);

		$result=@file_get_contents("http://api.twitter.com/1/statuses/user_timeline/".$user["twitter"].".json");
		if ( $result != false ) {
			$tweet=json_decode($result); // get tweets and decode them into a variable
			$lastTweet = $tweet[0]->text; // show latest tweet
		} else {
			$lastTweet=0;
		}
		$utilities = $this->get('ace_utilities.handler');
		$image = $utilities->get_gravatar($user["email"],120);
		return $this->render('AceGenericBundle:Default:user.html.twig', array( 'user' => $user, 'projects' => $projects, 'lastTweet'=>$lastTweet, 'image'=>$image ));
	}

	public function projectAction($id, $embed = false)
	{

		$projectmanager = $this->get('ace_project.sketchmanager');
		$projects = NULL;
		
		$project = json_decode($projectmanager->checkExistsAction($id)->getContent(), true);
		if($project["success"] === false)
		{
			return $this->render('AceGenericBundle:Default:minor_error.html.twig', array('error'=> "There is no such project!"));
		}

		$owner = $projectmanager->getOwnerAction($id)->getContent();
		$owner = json_decode($owner, true);
		$owner = $owner["response"];

		if (!$embed && $this->get('security.context')->isGranted('ROLE_USER'))
		{
			$user = json_decode($this->get('ace_user.usercontroller')->getCurrentUserAction()->getContent(), true);

			if($owner["id"] == $user["id"])
			{
				return $this->forward('AceGenericBundle:Editor:edit', array("id"=> $id));
			}
		}

		$name = $projectmanager->getNameAction($id)->getContent();
		$name = json_decode($name, true);
		$name = $name["response"];

		$parent = $projectmanager->getParentAction($id)->getContent();
		$parent = json_decode($parent, true);
		if($parent["success"])
		{
			$parent = $parent["response"];
		}
		else
			$parent = NULL;

		$files = $projectmanager->listFilesAction($id)->getContent();
		$files = json_decode($files, true);
		$files = $files["list"];
		foreach($files as $key=>$file)
		{
			$files[$key]["code"] = htmlspecialchars($file["code"]);
		}

		$json = array("project" => array("name" => $name, "url" => $this->get('router')->generate('AceGenericBundle_project',array("id" => $id), true)),"user"=>array("name"=>$owner["username"], "url" => $this->get('router')->generate('AceGenericBundle_user',array('user' => $owner['username']), true )), "clone_url" => $this->get('router')->generate('AceUtilitiesBundle_clone', array('id' => $id), true) , "download_url" => $this->get('router')->generate('AceUtilitiesBundle_download',array('id'=> $id), true), "files" => $files);
		$json = json_encode($json);

		if($embed)
			return $this->render('AceGenericBundle:Default:project_embeddable.html.twig', array("json" => $json));
		return $this->render('AceGenericBundle:Default:project.html.twig', array('project_name'=>$name, 'owner' => $owner, 'files' => $files, "project_id" => $id, "parent" => $parent, "json" => $json));
	}

	public function projectfilesAction()
	{
		header('Access-Control-Allow-Origin: *');

		$id = $this->getRequest()->request->get('project_id');

		$projectmanager = $this->get('ace_project.sketchmanager');
		$projects = NULL;

		$project = json_decode($projectmanager->checkExistsAction($id)->getContent(), true);
		if ($project["success"] === false)
		{
			return new Response("Project Not Found",404);
		}

		$files = $projectmanager->listFilesAction($id)->getContent();
		$files = json_decode($files, true);
		$files = $files["list"];

		$files_hashmap = array();
		foreach ($files as $file)
		{
			$files_hashmap[$file["filename"]] = htmlspecialchars($file["code"]);
		}
		return new Response(json_encode($files_hashmap));
	}

	public function librariesAction()
	{
		$utilities = $this->get('ace_utilities.handler');

		$libraries = json_decode($utilities->get($this->container->getParameter('library')), true);
		$categories = $libraries["categories"];

		return $this->render('AceGenericBundle:Default:libraries.html.twig', array('categories' => $categories));
	}

	public function exampleAction($library, $example, $url)
	{
		$utilities = $this->get('ace_utilities.handler');
		$data = htmlspecialchars($utilities->get($url));
		$file = array("filename" => $example.".ino", "code" => $data);
		$files = array($file);
		return $this->render('AceGenericBundle:Default:example.html.twig', array('library' => $library, 'example' => $example, 'files' => $files));
	}

	public function boardsAction()
	{
		$boardcontroller = $this->get('ace_utilities.boardcontroller');
		$boards = json_decode($boardcontroller->listAction()->getContent(), true);
		return $this->render('AceGenericBundle:Default:boards.html.twig', array('boards' => $boards));
	}
	public function boardslistAction()
	{
		header('Access-Control-Allow-Origin: *');

		$boardcontroller = $this->get('ace_utilities.boardcontroller');
		$boards = $boardcontroller->listAction()->getContent();
		return new Response(json_encode($boards));
	}
    
}
