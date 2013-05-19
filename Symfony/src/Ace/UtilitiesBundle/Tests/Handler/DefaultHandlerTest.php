<?php

namespace Ace\UtilitiesBundle\Tests\Handler;

use Ace\UtilitiesBundle\Handler\DefaultHandler;

class DefaultHandlerTest extends \PHPUnit_Framework_TestCase
{
	public function testGet_data()
	{
		$handler = new DefaultHandler();

		//Check for wrong URL
		$result = $handler->get_data("http://codebender.cc\\/","", "");
		$this->assertNotEmpty($result);
		$this->assertStringMatchesFormat('%a400 Bad Request%a', $result);

		//Check for No Data
		$result = $handler->get_data("http://codebender.cc/","", "");
		$this->assertNotEmpty($result);
		$this->assertStringMatchesFormat('%a<html>%a</html>%a', $result);

		//Check for POST Data
		$result = $handler->get_data("http://www.htmlcodetutorial.com/cgi-bin/mycgi.pl","data", "test");
		$this->assertNotEmpty($result);
		$this->assertStringMatchesFormat('%a<TR VALIGN=TOP><TH ROWSPAN=1>data</TH><TD><PRE>test</PRE></TD></TR>%a', $result);
	}

	public function testGet()
	{
		$handler = new DefaultHandler();

		//Check for wrong URL
		$result = $handler->get("http://codebender.cc\\/");
		$this->assertNotEmpty($result);
		$this->assertStringMatchesFormat('%a400 Bad Request%a', $result);

		//Check for No Data
		$result = $handler->get("http://codebender.cc/");
		$this->assertNotEmpty($result);
		$this->assertStringMatchesFormat('%a<html>%a</html>%a', $result);
	}

	public function testDefault_text()
	{
		$handler = new DefaultHandler();

		//Check for wrong URL
		$result = $handler->default_text();
		$this->assertStringMatchesFormat('%asetup()%aloop()%a', $result);
	}

	public function testGet_gravatar()
	{
		$handler = new DefaultHandler();

		//Check for wrong URL
		$result = $handler->get_gravatar("tzikis@gmail.com");
		$this->assertEquals($result, 'http://www.gravatar.com/avatar/1a6a5289ac4473b5731fa9d9a3032828?s=80&d=mm&r=g');

		$result = $handler->get_gravatar("tzikis@gmail.com", 120);
		$this->assertEquals($result, 'http://www.gravatar.com/avatar/1a6a5289ac4473b5731fa9d9a3032828?s=120&d=mm&r=g');
	}
}


