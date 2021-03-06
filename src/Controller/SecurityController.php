<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\AppAuthenticator;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class SecurityController
 * @package App\Controller
 * @Route("/auth", name="security.")
 */
class SecurityController extends AbstractController {

	/**
	 * @Route("/login", name="app_login")
	 * @param AuthenticationUtils $authenticationUtils
	 *
	 * @return Response
	 */
	public function login( AuthenticationUtils $authenticationUtils ): Response {
		// get the login error if there is one
		$error = $authenticationUtils->getLastAuthenticationError();
		// last username entered by the user
		$lastUsername = $authenticationUtils->getLastUsername();

		return $this->render( 'security/login.html.twig', [ 'last_username' => $lastUsername, 'error' => $error ] );
	}

	/**
	 * @Route("/register", name="app_register")
	 * @param Request $request
	 * @param UserPasswordEncoderInterface $passwordEncoder
	 * @param GuardAuthenticatorHandler $guardHandler
	 * @param AppAuthenticator $authenticator
	 *
	 * @return Response
	 */
	public function register(
		Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler,
		AppAuthenticator $authenticator
	): Response {
		$user = new User();
		$form = $this->createForm( RegistrationFormType::class, $user );
		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() ) {
			// encode the plain password
			$user->setPassword(
				$passwordEncoder->encodePassword(
					$user,
					$form->get( 'plainPassword' )->getData()
				)
			);

			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist( $user );
			$entityManager->flush();

			// do anything else you need here, like send an email

			return $guardHandler->authenticateUserAndHandleSuccess(
				$user,
				$request,
				$authenticator,
				'main' // firewall name in security.yaml
			);
		}

		return $this->render( 'security/register.html.twig', [
			'registrationForm' => $form->createView(),
		] );
	}

	/**
	 * @Route("/logout", name="app_logout", methods={"GET"})
	 * @throws Exception
	 */
	public function logout() {
		throw new Exception( 'Don\'t forget to activate logout in security.yaml' );
	}
}
