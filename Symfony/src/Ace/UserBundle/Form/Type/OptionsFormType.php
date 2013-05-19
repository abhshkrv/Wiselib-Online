<?php

namespace Ace\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\MaxLength;
use Symfony\Component\Validator\Constraints\MinLength;
use Symfony\Component\Validator\Constraints\Collection;


class OptionsFormType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
		
		/* create the form*/
        $builder
            ->add('username', 'text', array('read_only' => true,
											'attr'=> array(
													'disabled'=>true,
													'class' => 'option-form-input')))
            ->add('firstname', 'text',array('attr' => array(
													'onkeyup' => 'validation(id)',
													'class' => 'option-form-input')))
            ->add('lastname', 'text',array('attr' => array(
													'onkeyup' => 'validation(id)',
													'class' => 'option-form-input')))
            ->add('email', 'email', array('attr' => array(
													'onkeyup' => 'preCheck(id)',
													'onblur' => 'validation(id)',
													'class' => 'option-form-input'),
											))
            ->add('twitter', 'text', array('required' => false,
											'attr' => array(
													'class' => 'option-form-input')))
            ->add('currentPassword', 'password', array(
														'label' => 'Current Password',
														'required' => false,
														'attr'=> array(
																'onkeyup' => 'preCheck(id)',
																'onblur' => 'validation(id)',
																'placeholder'=> 'Type your current password',
																'class' => 'option-form-input')))
            ->add('plainPassword', 'repeated', array(
													'label' => 'New Password',
													'type' => 'password',
													'invalid_message' => 'The New Password fields must match.',
													'first_name' => 'new',
													'second_name' => 'confirm',
													'required' => false,
													'options' => array(
																'attr' => array(
																		'onkeyup' => 'validation(id)',
																		'max_length' => 255,
																		'placeholder'=> 'Type your new password',
																		'class' => 'option-form-input')),
													));
    }
    
    public function getDefaultOptions(array $options)
    {
		$constraints = new Collection(array(
			'fields' => array(
					'firstname' => array(
									new Regex( array(
													'pattern' => '/\d/',
													'match' => false,
													'message' => 'Sorry, your Firstname cannot contain a number'
													)),
									),
					'lastname' => array(
									new Regex( array(
													'pattern' => '/\d/',
													'match' => false,
													'message' => 'Sorry, your Lastname cannot contain a number'
													)),
									),
					'email' => array(
								new NotBlank(array('message' => 'Please fill in your Email address')),
								new Email(array('message' => 'Sorry, this is not a valid Email address', 'checkMX' => true)),
								),
					'plainPassword' => array(
										new MinLength(array('limit' => 6, 'message' => 'Sorry, New Password must be at least 6 characters long')),
										new MaxLength(array('limit' => 255, 'message' => 'Sorry, New Password cannot be longer than 255 characters')),
								),
					),
					'allowExtraFields' => true,
					'allowMissingFields' => true,
				));
        
        return array(
            'validation_constraint' => $constraints,
        );
    }

    public function getName()
    {
        return 'options';
    }
    
}
