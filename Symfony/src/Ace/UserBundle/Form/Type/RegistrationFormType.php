<?php

namespace Ace\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilder;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        parent::buildForm($builder, $options);
		
		// modify fos_user fields
		$builder->get('username')->setAttribute('attr', array(
															'placeholder' => 'Username',
															'style' => 'max-width:100%'));
		$builder->get('email')->setAttribute('attr', array(
															'placeholder' => 'Email',
															'style' => 'max-width:100%'));
		$builder->get('plainPassword')->get('first')->setAttribute('attr', array(
															'placeholder' => 'Type a Password',
															'style' => 'max-width:100%'));
		$builder->get('plainPassword')->get('second')->setAttribute('attr', array(
															'placeholder' => 'Repeat Password',
															'style' => 'max-width:100%'));
																							
        // add your custom field
		$builder
			->add('firstname', 'text', array('label' => 'user_registration_form_firstname',	'required' => false))
			->add('lastname', 'text', array('label' => 'user_registration_form_lastname', 'required' => false))
			->add('twitter', 'text', array('label' => 'user_registration_form_twitter',	'required' => false))
			->add('referrer_username', 'text', array('label' => 'user_registration_form_referrer', 'required' => false))
			->add('referral_code', 'text', array('label' => 'user_registration_form_referral_code', 'required' => false));
    }

    public function getName()
    {
        return 'ace_user_registration';
    }
}

