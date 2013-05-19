<?php

namespace Ace\UserBundle\Controller;

use Doctrine\ORM\EntityManager;
use Ace\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;


class DefaultController extends Controller
{
	protected $templating;
	protected $sc;
	protected $em;
	protected $container;

	public function existsAction($username)
	{
		$response = json_decode($this->getUserAction($username)->getContent(), true);
		if($response["success"])
			return new Response("true");
		else
			return new Response("false");
	}

	public function emailExistsAction($email)
	{
		//TODO: Fix this to use a generic function, not call the db directly
		$user = $this->em->getRepository('AceUserBundle:User')->findOneByEmail($email);
		if($user)
			return new Response("true");
		else
			return new Response("false");
	}

	public function getUserAction($username)
	{
		$response = array("success" => false);
		$user = $this->em->getRepository('AceUserBundle:User')->findOneByUsername($username);
		if ($user)
		{
			$response = array("success" => true,
			"id" => $user->getId(),
			"email" => $user->getEmail(),
			"username" => $user->getUsername(),
			"firstname" => $user->getFirstname(),
			"lastname" => $user->getLastname(),
			"twitter" => $user->getTwitter(),
			"karma" => $user->getKarma(),
			"points" => $user->getPoints(),
			"referrals" => $user->getReferrals(),
			"referrer_username" => $user->getReferrerUsername(),
			"referral_code" => $user->getReferralCode(),
			"walkthrough_status" => $user->getWalkthroughStatus()
			);
		}
		return new Response(json_encode($response));
	}

	public function getCurrentUserAction()
	{
		$response = array("success" => false);
		$current_user = $this->sc->getToken()->getUser();
		if($current_user !== "anon.")
		{
			$name = $current_user->getUsername();
			$data = json_decode($this->getUserAction($name)->getContent(), true);
			if ($data["success"] === false)
			{
				throw $this->createNotFoundException('No user found with id '.$name);
			}
			$response = $data;
		}
		return new Response(json_encode($response));

	}

	public function searchAction($token)
	{
		$results_name = json_decode($this->searchNameAction($token)->getContent(), true);
		$results_uname = json_decode($this->searchUsernameAction($token)->getContent(), true);
		$results_twit = json_decode($this->searchTwitterAction($token)->getContent(), true);
		$results = $results_name + $results_uname + $results_twit;
		return new Response(json_encode($results));
	}

	public function searchNameAction($token)
	{
		$repository = $this->em->getRepository('AceUserBundle:User');
		$users = $repository->createQueryBuilder('u')
		    ->where('u.firstname LIKE :name OR u.lastname LIKE :name')
			->setParameter('name', "%".$token."%")->getQuery()->getResult();

		$result = array();
		foreach($users as $user)
		{
			$result[] = array($user->getId() => array("firstname" => $user->getFirstname(), "lastname" => $user->getLastname(), "username" => $user->getUsername(), "karma" => $user->getKarma()));
		}
		return new Response(json_encode($result));
	}

	public function searchUsernameAction($token)
	{
		$repository = $this->em->getRepository('AceUserBundle:User');
		$users = $repository->createQueryBuilder('u')
		    ->where('u.username LIKE :name')
			->setParameter('name', "%".$token."%")->getQuery()->getResult();

		$result = array();
		foreach($users as $user)
		{
			$result[] = array($user->getId() => array("firstname" => $user->getFirstname(), "lastname" => $user->getLastname(), "username" => $user->getUsername(), "karma" => $user->getKarma()));
		}
		return new Response(json_encode($result));
	}

	public function searchTwitterAction($token)
	{
		$repository = $this->em->getRepository('AceUserBundle:User');
		$users = $repository->createQueryBuilder('u')
		    ->where('u.twitter LIKE :name')
			->setParameter('name', "%".$token."%")->getQuery()->getResult();

		$result = array();
		foreach($users as $user)
		{
			$result[] = array($user->getId() => array("firstname" => $user->getFirstname(), "lastname" => $user->getLastname(), "username" => $user->getUsername(), "karma" => $user->getKarma()));
		}
		return new Response(json_encode($result));
	}

	public function setReferrerAction($username, $referrer_username)
	{

		/** @var User $user */
		$user = $this->em->getRepository('AceUserBundle:User')->findOneByUsername($username);
		/** @var User $referrer */
		$referrer = $this->em->getRepository('AceUserBundle:User')->findOneByUsername($referrer_username);

		if($referrer != NULL)
		{
			$referrer->setReferrals($referrer->getReferrals()+1);
			$referrer->setKarma($referrer->getKarma()+20);
			$referrer->setPoints($referrer->getPoints() + 20);
			$user->setReferrer($referrer);
			$this->em->flush();
			return new Response(json_encode(array("success" => true)));
		}

		return new Response(json_encode(array("success" => false)));
	}

	public function setKarmaAction($username, $karma)
	{
		$user = $this->em->getRepository('AceUserBundle:User')->findOneByUsername($username);

		//update object - no checks atm
		$user->setKarma(intval($karma));
		$this->em->flush();

		return new Response(json_encode(array("success" => true)));
	}

	public function setPointsAction($username, $points)
	{
		$user = $this->em->getRepository('AceUserBundle:User')->findOneByUsername($username);

		//update object - no checks atm
		$user->setPoints(intval($points));
		$this->em->flush();

		return new Response(json_encode(array("success" => true)));
	}

	public function setWalkthroughStatusAction($status)
	{
		/** @var User $current_user */
		$current_user = $this->sc->getToken()->getUser();
		if ($current_user !== "anon.")
		{
			if ($current_user->getWalkthroughStatus() < $status)
			{
				if($status == 5)
					$current_user->setPoints($current_user->getPoints() + 50);
				$current_user->setWalkthroughStatus($status);
			}
		}
		$this->em->flush();
		return new Response(json_encode(array("success" => true)));
	}

	public function enabledAction()
	{
		$repository = $this->em->getRepository('AceUserBundle:User');
		$users = $repository->createQueryBuilder('u')->where('u.enabled = 1')->getQuery()->getResult();
		return new Response(count($users));

	}

	public function activeAction()
	{
		$repository = $this->em->getRepository('AceUserBundle:User');
		$users = $repository->createQueryBuilder('u')->where('u.enabled = 1')->getQuery()->getResult();
		$dayofyear = new \DateTime;
		$count = 0;
		foreach($users as $user)
		{
			if($user->getLastLogin() != null)
			{
				if($dayofyear->format("z") == $user->getLastLogin()->format("z"))
					$count++;
			}
		}
		return new Response($count);

	}

	public function inlineRegisterAction()
	{
        $form = $this->container->get('fos_user.registration.form');
	    return new Response($this->templating->render('AceUserBundle:Registration:register_inline.html.twig', array(
	            'form' => $form->createView(),
	            'theme' => $this->container->getParameter('fos_user.template.theme'),
	        )));
	}

	public function getTopUsersAction($count)
	{
		$repository = $this->em->getRepository('AceUserBundle:User');
		$users = $repository->createQueryBuilder('u')
			->orderBy('u.karma', 'DESC')
			->setMaxResults($count)
			->getQuery()->getResult();

		$users_array = array();
		foreach($users as $user)
		{
			$users_array[] = json_decode($this->getUserAction($user->getUsername())->getContent(), true);
		}

		return new Response(json_encode(array("success" => true, "list" => $users_array)));
	}

	public function __construct(EngineInterface $templating, SecurityContext $securityContext, EntityManager $entityManager, ContainerInterface $container)
	{
		$this->templating = $templating;
		$this->sc = $securityContext;
	    $this->em = $entityManager;
		$this->container = $container;
	}

}
