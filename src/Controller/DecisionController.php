<?php

namespace App\Controller;

use App\Entity\Decision;
use App\Entity\User;
use App\Entity\Opinion;
use App\Form\DecisionType;
use App\Form\OpinionType;
use App\Repository\OpinionRepository;
use App\Repository\DecisionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/decision')]
class DecisionController extends AbstractController
{
    #[Route('/', name: 'app_decision_index', methods: ['GET'])]
    public function index(DecisionRepository $decisionRepository): Response
    {
        $decisions = $decisionRepository->findAll();
        foreach ($decisions as $decision) {
            $user = $decision->getOwner();

            if ($user === null) {
                // Gère le cas où l'utilisateur est null (peut-être un problème dans la fixture)
                // Tu peux ignorer cette décision ou effectuer une action spécifique
            } else {
            }
        }
        return $this->render('decision/index.html.twig', [
            'decisions' => $decisionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_decision_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        $decision = new Decision();
        $user = $security->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Utilisateur non connecté.');
        }
        $decision->setOwner($user);

        $form = $this->createForm(DecisionType::class, $decision);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $decision->setStatus(true);
            $entityManager->persist($decision);
            $entityManager->flush();

            return $this->redirectToRoute('app_decision_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('decision/new.html.twig', [
            'decision' => $decision,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_decision_show', methods: ['GET'])]
    public function show(Decision $decision, OpinionRepository $opinionRepository): Response
    {
        $users = $decision->getUsers();
        $usersExpert = $decision->getUserExpert();
        $groupes = $decision->getGroupes();
        //$opinions = $decision->getOpinions();
        // Charger les commentaires associés à l'épisode
        $opinions = $opinionRepository->findBy(['decision' => $decision], ['createdAt' => 'ASC']);

        return $this->render('decision/show.html.twig', [
            'decision' => $decision,
            'users' => $users,
            'userExpert' => $usersExpert,
            'groupes' => $groupes,
            'opinions' => $opinions
        ]);
    }

    #[Route('/{id}/edit', name: 'app_decision_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Decision $decision, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DecisionType::class, $decision);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_decision_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('decision/edit.html.twig', [
            'decision' => $decision,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_decision_delete', methods: ['POST'])]
    public function delete(Request $request, Decision $decision, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $decision->getId(), $request->request->get('_token'))) {
            $entityManager->remove($decision);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_decision_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/opinion/new', name: 'new_opinion', methods: ['GET', 'POST'])]
    public function newOpinion(Request $request, Decision $decision, EntityManagerInterface $entityManager): Response
    {
        $opinion = new Opinion();
        // Vérifie si l'utilisateur est connecté
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Utilisateur non connecté.');
        }

        // Vérifie si l'utilisateur fait partie des utilisateurs concernés par la décision
        if (!$decision->getUsers()->contains($user) && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à
            ajouter un avis pour cette décision.');
        }

        $form = $this->createForm(OpinionType::class, $opinion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $opinion->setAuthor($user);
            //Associe la décision à l'opinion
            $opinion->setDecision($decision);
            $entityManager->persist($opinion);
            $entityManager->flush();

            $this->addFlash('success', 'Avis ajouté avec succès.');

            return $this->redirectToRoute('app_decision_show', [
                'id' => $decision->getId(),
            ], Response::HTTP_SEE_OTHER);
        }
        return $this->render('opinion/new.html.twig', [
            'decision' => $decision,
            'form' => $form->createView(),
        ]);
    }
}
