<?php

namespace App\Controller;

use App\Entity\Decision;
use App\Entity\User;
use App\Entity\Opinion;
use App\Form\DecisionType;
use App\Form\OpinionType;
use App\Repository\OpinionRepository;
use App\Repository\DecisionRepository;
use App\Service\DecisionDateService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use PHPStan\Symfony\Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FullEmailService;
use Symfony\Component\Security\Core\Security;

#[Route('/decision')]
class DecisionController extends AbstractController
{
    private DecisionDateService $decisionDateService;

    public function __construct(DecisionDateService $decisionDateService)
    {
        $this->decisionDateService = $decisionDateService;
    }

    #[Route('/', name: 'app_decision_index', methods: ['GET'])]
    public function index(DecisionRepository $decisionRepository): Response
    {
        //Filtre les décisions par odre d'arriver
        $decisions = $decisionRepository->findAllOrderedByDeadlineDecisionDesc();
        $actualDate = new DateTimeImmutable();
        foreach ($decisions as $decision) {
            $user = $decision->getOwner();

            if ($user === null) {
                // Gère le cas où l'utilisateur est null (peut-être un problème dans la fixture)
                // Tu peux ignorer cette décision ou effectuer une action spécifique
            } else {
            }
        }
        return $this->render('decision/index.html.twig', [
            'decisions' => $decisions,
            'actualDate' => $actualDate
        ]);
    }

    #[Route('/new', name: 'app_decision_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        Security $security,
        FullEmailService $emailService
    ): Response {
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

            // Récupérez les utilisateurs sélectionnés dans le formulaire
            $users = $form->get('users')->getData();
            $userExperts = $form->get('userExpert')->getData();
            $groupes = $form->get('groupes')->getData();
            // Initialiser un tableau pour stocker les adresses e-mail des membres du groupe
            $groupMembersEmails = [];
            // Parcourir les groupes pour récupérer les adresses e-mail des membres
            foreach ($groupes as $groupe) {
                $groupMembers = $groupe->getUsers();
                foreach ($groupMembers as $groupMember) {
                    if ($groupMember instanceof User) {
                        $groupMembersEmails[] = $groupMember;
                    }
                }
            }
            // Convertissez les ArrayCollection en tableaux
            $usersArray = $users->toArray();
            $userExpertsArray = $userExperts->toArray();

            // Combinez les adresses e-mail des groupes avec les autres utilisateurs
            $allUsersEmails = array_merge($usersArray, $userExpertsArray, $groupMembersEmails);

            // Envoyez un e-mail à tous les utilisateurs sélectionnés
            $subject = 'Nouvelle décision créée';
            $content = $this->renderView('mail/MailDecision.html.twig', [
                'user' => $user,
                'users' => $users,
                'userExperts' => $userExperts,
                'groupes' => $groupes,
            ]);

            $emailService->sendEmailsToUsers($allUsersEmails, $subject, $content);

            return $this->redirectToRoute('app_decision_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('decision/new.html.twig', [
            'decision' => $decision,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/my_decisions', name: 'my_decisions', methods: ['GET'])]
    public function myDecisions(DecisionRepository $decisionRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Utilisateur non connecté.');
        }

        $decisions = $decisionRepository->findDecisionsForUser($user);

        return $this->render('decision/my_decisions.html.twig', [
            'decisions' => $decisions,
        ]);
    }

    #[Route('/{id}', name: 'app_decision_show', methods: ['GET'])]
    public function show(
        Decision $decision,
        OpinionRepository $opinionRepository,
        DecisionDateService $decisionDateService
    ): Response {
        $users = $decision->getUsers();
        $usersExpert = $decision->getUserExpert();
        $groupes = $decision->getGroupes();
        //$opinions = $decision->getOpinions();
        // Charger les commentaires associés à l'épisode
        //$opinions = $opinionRepository->findBy(['decision' => $decision], ['createdAt' => 'ASC']);
        //$opinions = $opinionRepository->findAllOrderedByCreatedAtDesc();
        $step = $this->decisionDateService ->getCurrentStep($decision);
        $opinionsCurrentStep = $decisionDateService->getCurrentStepOpinions($decision, $opinionRepository);

        $previousStepOpinions = $decisionDateService->getPreviousStepOpinions($decision, $opinionRepository, $step);

        return $this->render('decision/show.html.twig', [
            'decision' => $decision,
            'users' => $users,
            'userExpert' => $usersExpert,
            'groupes' => $groupes,
            //'opinions' => $opinionRepository->findAllOrderedByCreatedAtDesc(), // Peut-être à supprimer ?
            'current_step' => $step,
            'currentStepOpinions' => $opinionsCurrentStep,
            'previousStepOpinions' => $previousStepOpinions,

        ]);
    }

    #[Route('/{id}/edit', name: 'app_decision_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Decision $decision, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DecisionType::class, $decision);
        $form->handleRequest($request);

        if (!$this->isEdit($decision)) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à effectuer cette action.');
        }

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
        if (!$this->isDelete($decision)) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à effectuer cette action.');
        }

        if ($this->isCsrfTokenValid('delete' . $decision->getId(), $request->request->get('_token'))) {
            $entityManager->remove($decision);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_decision_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}/opinion/new', name: 'new_opinion', methods: ['GET', 'POST'])]
    public function newOpinion(
        Request $request,
        Decision $decision,
        EntityManagerInterface $entityManager
    ): Response {
        $opinion = new Opinion();
        $user = $this->getUser();

        $this->checkUserAuthorization($decision, $user);
        $step = $this->decisionDateService->getCurrentStep($decision);

        $this->checkOpinionInterval($decision);

        $form = $this->createForm(OpinionType::class, $opinion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->persistOpinion($opinion, $user, $decision, $entityManager);

            $this->addFlash('success', 'Avis ajouté avec succès.');

            return $this->redirectToRoute('app_decision_show', ['id' => $decision->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('opinion/new.html.twig', [
            'decision' => $decision,
            'current_step' => $step,
            'form' => $form->createView(),
        ]);
    }

    private function checkUserAuthorization(Decision $decision, ?User $user): void
    {
        if (!$user) {
            throw $this->createAccessDeniedException('Utilisateur non connecté.');
        }

        if (
            !$decision->getUsers()->contains($user) &&
            !$decision->getUserExpert()->contains($user) &&
            !$decision->getGroupes()->isEmpty() &&
            !$this->isGranted('ROLE_ADMIN')
        ) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à ajouter
            un avis pour cette décision.');
        }
    }

    private function checkOpinionInterval(Decision $decision): void
    {
        $user = $this->getUser();
        if (!$user) {
            return;
        }
        // Vérifie si l'utilisateur est le créateur/propriétaire de la décision
        $isOwner = $decision->getOwner() === $user;

        if (!$this->isEdit($decision) && !$this->isDelete($decision)) {
            // Vérifie si l'utilisateur n'est ni l'owner/le créateur ni un administrateur
            if (
                !$isOwner && !$this->decisionDateService->isInOpinionInterval($decision, 2)
                && !$this->decisionDateService->isInOpinionInterval($decision, 4)
            ) {
                throw $this->createAccessDeniedException(
                    'Vous ne pouvez ajouter un avis que pendant les intervalles des étapes 2 et 4.'
                );
            }
        }
    }

    private function persistOpinion(
        Opinion $opinion,
        User $user,
        Decision $decision,
        EntityManagerInterface $entityManager
    ): void {
        $opinion->setAuthor($user);
        $opinion->setDecision($decision);
        $entityManager->persist($opinion);
        $entityManager->flush();
    }

    private function isEdit(Decision $decision): bool
    {
        $user = $this->getUser();

        if (!$user) {
            return false;
        }

        return $decision->getOwner() === $user;
    }

    private function isDelete(Decision $decision): bool
    {
        $user = $this->getUser();

        if (!$user) {
            return false;
        }

        return $decision->getOwner() === $user || $this->isGranted('ROLE_ADMIN');
    }
}
