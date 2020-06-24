<?php

/**
 * User controller
 */
namespace App\Controller;

use App\Entity\User;
use App\Entity\UserData;

use App\Form\PasswordType;
use App\Form\UpgradePasswordType;
use App\Repository\UserDataRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
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
     * @param \App\Repository\UserRepository $userRepository User repository
     * @param \Knp\Component\Pager\PaginatorInterface $paginator Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/",
     *     methods={"GET", "POST"},
     *     name="user_index"
     * )
     */
    public function index(Request $request, UserRepository $userRepository, PaginatorInterface $paginator): Response
    {
        $page = $request->query->getInt('page', '1');
        $pagination = $paginator->paginate(
            $userRepository->queryAll(),
            $page,
            UserRepository::PAGINATOR_ITEM_PER_PAGE
        );

        return $this->render(
            'user/index.html.twig',
            [
                'pagination' => $pagination,
            ]
        );
    }


    /**
     * @param Request $request
     * @param User $user
     * @param UserRepository $userRepository
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
     */
    public function changePassword(Request $request, User $user, UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder): Response
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

            $userRepository->save($user);
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
