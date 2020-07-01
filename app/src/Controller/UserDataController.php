<?php

/**
 * UserData Controller
 */
namespace App\Controller;

use App\Entity\UserData;
use App\Form\UserDataType;
use App\Service\UserDataService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
     * User data service
     *
     * @var UserDataService
     */
    private $userDataService;

    /**
     * UserDataController constructor.
     *
     * @param UserDataService $userDataService
     */
    public function __construct(UserDataService $userDataService)
    {
        $this->userDataService = $userDataService;
    }

    /**
     * Edit user data
     *
     * @param Request  $request
     * @param UserData $userData
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
     * );
     * @IsGranted(
     *     "USER_DATA_EDIT",
     *     subject="userData"
     * )
     */
    public function edit(Request $request, UserData $userData): Response
    {
        $form = $this->createForm(UserDataType::class, $userData, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userDataService->save($userData);

            $this->addFlash('success', 'message_update');

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
