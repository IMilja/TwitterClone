<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 * @package App\Controller
 * @Route("/", name="default.")
 */
class DefaultController extends AbstractController {

	/**
	 * @Route("/", name="index")
	 */
	public function index() {
		$posts = [
			[
				'title' => 'Title 2',
				'body' => 'Body 1',
				'postDate' => date('d-m-y H:i:s')
			],
			[
				'title' => 'Title 2',
				'body' => 'Body 2',
				'postDate' => date('d-m-y H:i:s')
			],
			[
				'title' => 'Title 3',
				'body' => 'Body 3',
				'postDate' => date('d-m-y H:i:s')
			],
		];
		return $this->render( 'default/index.html.twig', [
			'posts' => $posts
		]);
	}
}
