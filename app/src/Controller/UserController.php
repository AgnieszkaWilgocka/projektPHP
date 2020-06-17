<?php

/**
 * User controller
 */
namespace App\Controller;

use App\Entity\User;
use App\Entity\UserData;
use App\Form\RegistrationType;
use App\Repository\UserDataRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     *     "/index",
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
     * @param UserData $userData
     * @param UserRepository $userRepository
     * @param UserDataRepository $userDataRepository
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     *
     * @Route(
     *     "/{id}/{id_data}/edit",
     *     methods={"GET", "PUT"},
     *     name="user_edit",
     *     requirements={"id": "[1-9]\d*", "id_data": "[1-9]\d*"},
     * )
     *
     */
    public function edit(Request $request, User $user, UserData $userData, UserRepository $userRepository, UserDataRepository $userDataRepository): Response
    {
        $form = $this->createForm(RegistrationType::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user);
            $userDataRepository->save($userData);


            $this->addFlash('success', 'data updated successfully');

            return $this->redirectToRoute('user_index');
        }

        return $this->render(
            'user/edit.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,

            ]

        );
    }






}