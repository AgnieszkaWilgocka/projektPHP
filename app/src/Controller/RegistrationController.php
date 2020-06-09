<?php
/**
 * Registration controller.
 *
 */
namespace App\Controller;

use App\Form\RegistrationType;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
    /**
     * @param Request                      $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param UserRepository               $userRepository
     * @param GuardAuthenticatorHandler    $authenticatorHandler
     * @param LoginFormAuthenticator       $formAuthenticator
     *
     * @return \Symfony\Component\HttpFoundation\Response $response HTTP Response
     *
     * @Route(
     *     "/register",
     *     methods={"GET", "POST"},
     *     name="app_register",
     * )
      */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, UserRepository $userRepository, GuardAuthenticatorHandler $authenticatorHandler, LoginFormAuthenticator $formAuthenticator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $user->getPassword()
                )
            );
            $user->setRoles([User::ROLE_USER]);
            $userRepository->save($user);
            $this->addFlash('success', 'Your account has been created successfully');

            return $authenticatorHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $formAuthenticator,
                'main'
            );
        }

        return $this->render(
            'registration/register.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }








}
