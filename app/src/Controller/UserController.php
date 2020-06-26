<?php

/**
 * User controller
 */
namespace App\Controller;

use App\Entity\User;
use App\Form\UpgradePasswordType;
use App\Service\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserController
 * @Route(
 *     "/user"
 * )
 */
class UserController extends AbstractController
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * UserController constructor.
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param User $user
     *
     * @return Response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="user_show",
     *     requirements={"id": "[1-9]\d*"}
     * )
     * @IsGranted(
     *     "USER_VIEW",
     *     subject="user",
     * )
     */
    public function show(User $user): Response
    {
        return $this->render(
            'user/show.html.twig',
            ['user' => $user]
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/",
     *     methods={"GET", "POST"},
     *     name="user_index"
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', '1');
        $pagination = $this->userService->createPaginatedList($page);

        return $this->render(
            'user/index.html.twig',
            [
                'pagination' => $pagination,
            ]
        );
    }


    /**
     * @param Request                      $request
     * @param User                         $user
     * @param UserPasswordEncoderInterface $passwordEncoder
     *
     * @return Response
     *
     * @Route(
     *     "/change_password/{id}",
     *     methods={"GET", "PUT"},
     *     name="change_password",
     *     requirements={"id" : "[1-9]\d*"},
     * )
     * @IsGranted(
     *     "USER_EDIT",
     *     subject="user",
     * )
     */
    public function changePassword(Request $request, User $user, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(UpgradePasswordType::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $this->userService->save($user);
            $this->addFlash('success', 'password changed successfully');

            return $this->redirectToRoute('category_index');
        }

        return $this->render(
            'user/change_password.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }
}
