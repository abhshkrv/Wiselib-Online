<?php

namespace Ace\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AceUserBundle extends Bundle
{
	public function getParent()
	{
		return 'FOSUserBundle';
	}

}
