<?php

/**
 * User controller
 */
namespace App\Controller;

use App\Entity\User;
use App\Entity\UserData;
use App\Form\ChangeDataType;
use App\Form\PasswordType;
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
     * @param \Symfony\Component\HttpFoundation\Request $request        HTTP Request
     * @param \App\Repository\UserRepository            $userRepository User repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator      Paginator
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
                'pagination' => $pagination
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
     *     "/{id}/change_password",
     *     methods={"GET", "PUT"},
     *     name="change_password",
     *     requirements={"id" : "[1-9]\d*"},
     * )
     */
    public function changePassword(Request $request, User $user, UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(PasswordType::class, $user, ['method' => 'PUT']);
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

            return $this->redirectToRoute('user_index');
        }

        return $this->render(
            'user/change_password.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }


    /**
     * @param Request $request
     * @param User $user
     * @param UserData $userData
     * @param UserRepository $userRepository
     * @param UserDataRepository $userDataRepository
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/{id_data}/change_data",
     *     methods={"GET", "PUT"},
     *     name="change_data",
     *     requirements={"id": "[1-9]\d*", "id_data": "[1-9]\d*"}
     * )
     */
    public function changeData(Request $request, User $user, UserData $userData, UserRepository $userRepository, UserDataRepository $userDataRepository): Response
    {
        $form = $this->createForm(ChangeDataType::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user);
            $userDataRepository->save($userData);


            $this->addFlash('success', 'data updated successfully');

            return $this->redirectToRoute('category_index');
        }

        return $this->render(
            'user/change_data.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }
}
