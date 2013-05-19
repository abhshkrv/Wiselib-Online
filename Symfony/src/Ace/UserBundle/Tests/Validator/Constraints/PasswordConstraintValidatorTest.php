<?php

namespace Ace\UserBundle\Tests\Validator\Constraints;
 
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Ace\UserBundle\Validator\Constraints\PasswordConstraint;
use Ace\UserBundle\Validator\Constraints\PasswordConstraintValidator;
 
class PasswordConstraintValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers PasswordConstraintValidator::isValidPasswordConstraint
     * @covers PasswordConstraintValidator::isValid
     * @covers PasswordConstraint
     */

	protected $validator;
	
	protected function setUp()
	{
		$this->validator = new PasswordConstraintValidator();
	}
	
	protected function tearDown()
	{
		$this->validator = null;
	}
	
	/**
     * @dataProvider validPasswords
     */
    public function testValidPasswords($pass)
    {
 		/* Test valid passwords & if error message is set to null */
        $this->assertTrue($this->validator->isValid($pass, new PasswordConstraint()));
        $this->assertNull($this->validator->getMessageTemplate());
        $this->assertNull($this->validator->getMessageParameters());					
	}
	
	/* Generate valid passwords */
	public function validPasswords()
    {
        return array(
            array('M5%/*gF'),
            array('aAr0nn'),
            array('m4!kt/'),
            array('<val1d>'),
            array('[Djfdk\']'),
            array('*rm-rf/*'),
            array('te$tingWord'),
            array('noMorePass!'),
            array('one1isenough'),
            array('Superpass'),
            array('super`pass'),
            array('super~pass'),
            array('super~pass'),
            array('super!pass'),
            array('super#pass'),
            array('super$pass'),
            array('super%pass'),
            array('super^pass'),
            array('super&pass'),
            array('super*pass'),
            array('super(pass'),
            array('super)pass'),
            array('super-pass'),
            array('super_pass'),
            array('super+pass'),
            array('super=pass'),
            array('super{pass'),
            array('super}pass'),
            array('super[pass'),
            array('super]pass'),
            array('super|pass'),
            array('super:pass'),
            array('super;pass'),
            array('super,pass'),
            array('super<pass'),
            array('super>pass'),
            array('super?pass'),
            array('super/pass'),
            array('super//pass'),
            array('super\'pass'),
            array('super"pass'),
            array('Sup3r/p@ss'),
        );
    }
	
	/**
     * @dataProvider invalidPasswords
     */
    public function testInvalidPasswords($pass)
    {
		/* Test invalid passwords & if error is set correctly*/
		$this->assertFalse($this->validator->isValid($pass, new PasswordConstraint()));
		$this->assertEquals( array('{{ pass }}' => $pass ), $this->validator->getMessageParameters());
    }
    
    /* Generate invalid passwords */
    public function invalidPasswords()
    {
        return array(
            array('123456'),
            array('password'),
            array('SHALLNOTPASS'),
            array('!@#$}-+@#$<?/{[>'),
            array('this\isnotvalid'),
            array('no space'),
            array(''),
        );
    }
    
    /* Failure Test to check if error message is set correctly */
    public function testMessageIsSet()
    {
    	$constraint = new PasswordConstraint();
		$this->assertFalse($this->validator->isValid(null, new PasswordConstraint()));
		$this->assertEquals($constraint->message, $this->validator->getMessageTemplate()); 
    }
  
}
