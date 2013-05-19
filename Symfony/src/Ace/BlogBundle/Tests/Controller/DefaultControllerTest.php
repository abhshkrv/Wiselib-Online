<?php

namespace Ace\BlogBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testBlog()   // Test wether each page has 5 posts or less.
    {
//        $client = static::createClient();
//
//		$crawler = $client->request('GET', '/blog');
//			$pages = $crawler->filter('.pagination')->children()->children()->count();
//			//echo $pages;
//
//		for($i = 1;$i <= $pages-2;$i++){
//        $crawler = $client->request('GET', '/blog/'.$i);
//		$this->assertLessThanOrEqual(5, ($crawler->filter('#posts')->children()->count() - 1)); }
		$this->assertTrue(false);
    }

	public function testPostLink() // Test post title link
	{
//		$client = static::createClient();
//		$crawler = $client->request('GET', '/blog');
//
//		$link = $crawler->filter('#posts')->children()->children()->filter('div > a')->link();
//		$crawler = $client->click($link);
//
//		$matcher = array('id' => 'post');
//		$this->assertTag($matcher, $client->getResponse());
//		$this->assertCount(1,$crawler->filter('#post'));
		$this->assertTrue(false);
	}


	public function testRss() // Test rss feed
	{
//		$client = static::createClient();
//
//		$crawler = $client->request('GET', '/misc/blog/rss');
//		$this->assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/rss+xml'));
//
//		$crawler = $client->request('GET', '/blog/rss');
//		$this->assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/rss+xml'));
		$this->assertTrue(false);
	}

	public function testNewPost()   // Test new blog post
	{
//		$client = static::createClient();
//		$crawler = $client->request('GET', '/');
//
//		$client->followRedirects();
//
//		$link = $crawler->selectLink('Log In')->link();
//		$crawler = $client->click($link);
//
//		$this->assertGreaterThan(0, $crawler->filter('html:contains("Username")')->count());
//
//		$buttonCrawlerNode = $crawler->selectButton('Login');
//
//		$form = $buttonCrawlerNode->form();
//
//
//		$form['_username'] = 'DiMmiOuS';
//		$form['_password'] = 'Dimakopoulos87';
//
//		print_r($form->getValues());
//		$crawler = $client->submit($form);
//
//		$this->assertGreaterThan(0, $crawler->filter('html:contains("Hello DiMmiOuS!")')->count());
//
//		$link = $crawler->selectLink('Blog')->link();
//		$crawler = $client->click($link);
//
//		//$this->assertGreaterThan(0, $crawler->filter('html:contains("Text Editing Tools")')->count());  probable problem with iframe
//
//		$link = $crawler->selectLink('New Post!')->link();
//		$crawler = $client->click($link);
//
//		$buttonCrawlerNode = $crawler->selectButton('New Post!');
//
//		$form = $buttonCrawlerNode->form();
//
//		$form['title'] = (string)mt_rand();
//		$form['msgpost'] = 'unit testing sucks...';
//
//		print_r($form->getValues());
//
//		$client->submit($form);
//
//		$matcher = array('tag'   => 'h3','content' => $form['title']->getValue());
//		$this->assertTag($matcher, $client->getResponse()->getContent());
//
		$this->assertTrue(false);
	}

	
}
