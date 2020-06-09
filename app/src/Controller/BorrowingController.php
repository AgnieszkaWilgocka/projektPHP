<?php

/**
 * Borrowing controller
 */
namespace App\Controller;

use App\Entity\Borrowing;
use App\Form\BorrowingType;
use App\Repository\BorrowingRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BorrowingController
 * @Route(
 *     "/borrowing"
 * )
 */
class BorrowingController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request             HTTP Request
     * @param \App\Repository\BorrowingRepository       $borrowingRepository Borrowing repository
     *
     * @return Response
     *
     * @Route(
     *     "/create",
     *     methods={"GET","POST"},
     *     name="borrowing_create",
     * )
     * @IsGranted("ROLE_USER")
     */
    public function create(Request $request, BorrowingRepository $borrowingRepository): Response
    {
        $borrowing = new Borrowing();
        $form = $this->createForm(BorrowingType::class, $borrowing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $borrowingRepository->save($borrowing);

            $this->addFlash('success', 'your borrowing has sent');

            return $this->redirectToRoute('record_index');
        }

        return $this->render(
            'borrowing/create.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }




}