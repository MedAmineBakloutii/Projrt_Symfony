<?php

namespace App\Controller;

use App\Entity\Claim;
use App\Form\ClaimType;
use App\Repository\ClaimRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/claim')]
class ClaimController extends AbstractController
{
    #[Route('/', name: 'app_claim_index', methods: ['GET'])]
    public function index(ClaimRepository $claimRepository): Response
    {
        return $this->render('claim/index.html.twig', [
            'claims' => $claimRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_claim_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $claim = new Claim();
        $form = $this->createForm(ClaimType::class, $claim);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($claim);
            $entityManager->flush();

            return $this->redirectToRoute('app_claim_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('claim/new.html.twig', [
            'claim' => $claim,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_claim_show', methods: ['GET'])]
    public function show(Claim $claim): Response
    {
        return $this->render('claim/show.html.twig', [
            'claim' => $claim,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_claim_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Claim $claim, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ClaimType::class, $claim);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_claim_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('claim/edit.html.twig', [
            'claim' => $claim,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_claim_delete', methods: ['POST'])]
    public function delete(Request $request, Claim $claim, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$claim->getId(), $request->request->get('_token'))) {
            $entityManager->remove($claim);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_claim_index', [], Response::HTTP_SEE_OTHER);
    }
}
