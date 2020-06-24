<?php

/**
 * UserData Controller
 */
namespace App\Controller;

use App\Entity\UserData;
use App\Form\UserDataType;
use App\Repository\UserDataRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserDataController
 * @Route("/user-data")
 */
class UserDataController extends AbstractController
{

    /**
     * @param Request            $request
     * @param UserData           $userData
     * @param UserDataRepository $userDataRepository
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     name="user_data_edit",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function edit(Request $request, UserData $userData, UserDataRepository $userDataRepository): Response
    {
        $form = $this->createForm(UserDataType::class, $userData, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userDataRepository->save($userData);

            $this->addFlash('success', 'data updated successfully');

            return $this->redirectToRoute('category_index');
        }

        return $this->render(
            'userData/edit.html.twig',
            [
                    'form' => $form->createView(),
                    'userData' => $userData,
                ]
        );
    }
}
