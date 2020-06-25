<?php
/**
 * Registration controller.
 *
 */
namespace App\Controller;

use App\Form\RegistrationType;
use App\Entity\User;
use App\Entity\UserData;
use App\Repository\UserDataRepository;
use App\Repository\UserRepository;
use App\Security\LoginFormAuthenticator;
use App\Service\UserDataService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

/**
 * Class RegistrationController
 */
class RegistrationController extends AbstractController
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var UserDataService
     */
    private $userDataService;

    /**
     * RegistrationController constructor.
     *
     * @param UserService     $userService
     * @param UserDataService $userDataService
     */
    public function __construct(UserService $userService, UserDataService $userDataService)
    {
        $this->userService = $userService;
        $this->userDataService = $userDataService;
    }

    /**

     * @param Request                      $request
     * @param UserPasswordEncoderInterface $passwordEncoder
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
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $authenticatorHandler, LoginFormAuthenticator $formAuthenticator): Response
    {
        $user = new User();
        $userData = new UserData();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $user->getPassword()
                )
            );
            $user->setUserData($userData);
            $user->setRoles([User::ROLE_USER]);
            $this->userService->save($user);
            $this->userDataService->save($userData);


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
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }
}
