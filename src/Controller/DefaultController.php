<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostFormType;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 * @package App\Controller
 * @Route("/", name="default.")
 */
class DefaultController extends AbstractController {

	/**
	 * @Route("/", name="index")
	 * @param Request $request
	 *
	 * @return RedirectResponse|Response
	 */
	public function index( Request $request ) {
		$post = new Post();

		$form = $this->createForm( PostFormType::class, $post );

		$form->add( 'submit', SubmitType::class, [
			'label' => 'Create post',
			'attr'  => [
				'class' => 'btn btn-primary'
			]
		] );

		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() ) {
			$post = $form->getData();
			$post->setPostDate( DateTime::createFromFormat( 'Y-m-d H:i:s', date( 'Y-m-d H:i:s' ) ) );

			$em = $this->getDoctrine()->getManager();

			$em->persist( $post );
			$em->flush();

			$this->addFlash( 'success', 'Post is successfully created!' );

			return $this->redirectToRoute( 'default.index' );
		}

		$posts = $this->getDoctrine()->getRepository( Post::class )->findAll();

		return $this->render( 'default/index.html.twig', [
			'posts' => $posts,
			'form'  => $form->createView()
		] );
	}

	/**
	 * @Route("/delete/{id}", name="delete")
	 * @param $id
	 *
	 * @return RedirectResponse
	 */
	public function delete( $id ) {
		$post = $this->getDoctrine()->getRepository(Post::class)->find($id);

		$em = $this->getDoctrine()->getManager();

		$em->remove($post);
		$em->flush();

		return $this->redirectToRoute( 'default.index' );
	}
}
