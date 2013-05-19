<?php

namespace Ace\UtilitiesBundle\Handler;
 
class DefaultHandler
{
	const default_file = "default_text.txt";
	const directory = "../../";
	
	public function get_data($url, $var, $value)
	{
		$ch = curl_init();
		$timeout = 10;
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);

		curl_setopt($ch,CURLOPT_POST,1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$var.'='.$value);

		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
	
	public function get($url)
	{
		$ch = curl_init();
		$timeout = 10;
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);

		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}

	public function default_text()
	{
		$file = fopen($this::directory.$this::default_file, 'r');
		$value = fread($file, filesize($this::directory.$this::default_file));
		fclose($file);
		
		return $value;
	}
	
	/**
		* Get either a Gravatar URL or complete image tag for a specified email address.
		*
		* @param string $email The email address
		* @param string $s Size in pixels, defaults to 80px [ 1 - 512 ]
		* @return String containing either just a URL or a complete image tag
		* @source http://gravatar.com/site/implement/images/php/
	*/
	public function get_gravatar( $email, $s = 80)
	{
		$d = 'mm';
		$r = 'g';

		$url = 'http://www.gravatar.com/avatar/';
		$url .= md5( strtolower( trim( $email ) ) );
		$url .= "?s=$s&d=$d&r=$r";

		return $url;
	}

}






