<?php

namespace App\Controller;

use App\Entity\Decision;
use App\Entity\Opinion;
use App\Form\OpinionType;
use App\Repository\OpinionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/opinion')]
class OpinionController extends AbstractController
{
    #[Route('/', name: 'app_opinion_index', methods: ['GET'])]
    public function index(OpinionRepository $opinionRepository): Response
    {
        return $this->render('opinion/index.html.twig', [
            'opinions' => $opinionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_opinion_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Decision $decision): Response
    {
        $opinion = new Opinion();
        // Vérifie si l'utilisateur est connecté
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Utilisateur non connecté.');
        }
        // Vérifiez si l'utilisateur a une décision sur laquelle il peut faire une opinion
        //$decision = $admin->getDecision();

        //if (!$decision) {
        //    throw $this->createAccessDeniedException('L\'utilisateur n\'a pas de décision autorisée.');
       // }

        // Associez la décision à l'opinion
        //$opinion->setDecision($decision);
        $form = $this->createForm(OpinionType::class, $opinion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $opinion->setAuthor($user);
            // Associer l'auteur à l'opinion
            //$opinion->setAuthor($admin);
            $entityManager->persist($opinion);
            $entityManager->flush();

            return $this->redirectToRoute('app_decision_show', ['decisionId' => $decision->getId(),
                ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('opinion/new.html.twig', [
            'opinion' => $opinion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_opinion_show', methods: ['GET'])]
    public function show(Opinion $opinion): Response
    {
        return $this->render('opinion/show.html.twig', [
            'opinion' => $opinion,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_opinion_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Opinion $opinion, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OpinionType::class, $opinion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_opinion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('opinion/edit.html.twig', [
            'opinion' => $opinion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_opinion_delete', methods: ['POST'])]
    public function delete(Request $request, Opinion $opinion, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $opinion->getId(), $request->request->get('_token'))) {
            $entityManager->remove($opinion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_opinion_index', [], Response::HTTP_SEE_OTHER);
    }
}
