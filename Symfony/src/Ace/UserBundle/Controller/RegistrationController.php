<?php

namespace Ace\UserBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\RegistrationController as BaseController;

class RegistrationController extends BaseController
{
	public function registerAction()
	{
		$form = $this->container->get('fos_user.registration.form');
		$formHandler = $this->container->get('fos_user.registration.form.handler');
		$confirmationEnabled = $this->container->getParameter('fos_user.registration.confirmation.enabled');

		$process = $formHandler->process($confirmationEnabled);
		if ($process) {
			$user = $form->getData();

			if ($confirmationEnabled) {
				$this->container->get('session')->set('fos_user_send_confirmation_email/email', $user->getEmail());
				$route = 'fos_user_registration_check_email';
			} else {
				$this->authenticateUser($user);
				$route = 'fos_user_registration_confirmed';
			}

			$this->setFlash('fos_user_success', 'registration.flash.user_created');
			$url = $this->container->get('router')->generate($route);

			return new RedirectResponse($url);
		}

		//THIS IS WHAT WE CHANGED
		$form = $formHandler->generateReferrals();
		//CHANGES END HERE

		return $this->container->get('templating')->renderResponse('FOSUserBundle:Registration:register.html.'.$this->getEngine(), array(
			'form' => $form->createView(),
			'theme' => $this->container->getParameter('fos_user.template.theme')
		));
	}
}
