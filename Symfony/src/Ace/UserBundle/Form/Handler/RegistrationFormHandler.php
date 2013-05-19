<?php

namespace Ace\UserBundle\Form\Handler;

use FOS\UserBundle\Form\Handler\RegistrationFormHandler as BaseHandler;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Mailer\MailerInterface;
use Ace\UserBundle\Entity\User;
use Ace\UserBundle\Controller\DefaultController as UserController;
use Ace\ProjectBundle\Controller\SketchController as ProjectManager;
use Ace\UtilitiesBundle\Controller\ReferralCodeController;
use MCAPI;


class RegistrationFormHandler extends BaseHandler
{
	private $usercontroller;
	private $projectmanager;
	private $referralcontroller;
	private $listapi;
	private $listid;

    public function __construct(Form $form, Request $request, UserManagerInterface $userManager, MailerInterface $mailer, UserController $usercontroller, ProjectManager $projectmanager, ReferralCodeController $referralcontroller, $listapi, $listid)
    {
		parent::__construct($form, $request, $userManager, $mailer);
		$this->usercontroller = $usercontroller;
		$this->projectmanager = $projectmanager;
	    $this->referralcontroller = $referralcontroller;
		$this->listapi = $listapi;
		$this->listid = $listid;
    }

	public function generateReferrals()
	{
		$user = new User();

		$user->setReferrerUsername($this->request->query->get('referrer'));
		$user->setReferralCode($this->request->query->get('referral_code'));
		$this->form->setData($user);
		return $this->form;
	}

    protected function onSuccess(UserInterface $user, $confirmation)
    {

        parent::onSuccess($user, $confirmation);
		
		$first_code =
"/*
	Blink
	Turns on an LED on for one second, then off for one second, repeatedly.

	This example code is in the public domain.
*/

void setup()
{
	// initialize the digital pin as an output.
	// Pin 13 has an LED connected on most Arduino boards:
	pinMode(13, OUTPUT);
}

void loop()
{
	digitalWrite(13, HIGH); // set the LED on
	delay(1000); // wait for a second
	digitalWrite(13, LOW); // set the LED off
	delay(1000); // wait for a second
}
";

		$second_code =
"/*
	Prints an incremental number the serial monitor
*/
int number = 0;

void setup()
{
	Serial.begin(9600);
}

void loop()
{
	Serial.println(number++);
	delay(500);
}
";
		//create new projects
		$username = $user->getUsernameCanonical();
	    $user = json_decode($this->usercontroller->getUserAction($username)->getContent(), true);
		$response = $this->projectmanager->createprojectAction($user["id"], "Blink Example", $first_code)->getContent();
		$response = $this->projectmanager->createprojectAction($user["id"], "Serial Comm Example", $second_code)->getContent();

	    if($user["referrer_username"])
	        $referrer = json_decode($this->usercontroller->getUserAction($user["referrer_username"])->getContent(), true);

	    if($user["referral_code"])
		    $referral_code = json_decode($this->referralcontroller->useCodeAction($user["referral_code"])->getContent(), true);

	    if($user["referrer_username"] && $referrer["success"])
	    {
		    $response = $this->usercontroller->setReferrerAction($username, $user["referrer_username"])->getContent();
		    $response = $this->usercontroller->setKarmaAction($username, 50)->getContent();
		    $response = $this->usercontroller->setPointsAction($username, 50)->getContent();
	    }
	    else if ($user["referral_code"] && $referral_code["success"])
	    {
		    $response = $this->usercontroller->setKarmaAction($username, 50)->getContent();
		    $response = $this->usercontroller->setPointsAction($username, $referral_code["points"])->getContent();
	    }

	    // Mailchimp Integration
       	$api = new MCAPI($this->listapi);
       	$merge_vars = array('EMAIL'=> $user["email"], 'UNAME'=> $user["username"] );
       	$double_optin=false;
		$send_welcome=false;
       	$api->listSubscribe( $this->listid, $user["email"], $merge_vars, $double_optin, $send_welcome);        	
                
    }
}
