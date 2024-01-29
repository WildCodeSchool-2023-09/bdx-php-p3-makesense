<?php

namespace App\Controller;

use App\Entity\Group;
use App\Form\GroupType;
use App\Form\SearchGroupType;
use App\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/group')]
class GroupController extends AbstractController
{
    #[Route('/', name: 'app_group_index', methods: ['GET', 'POST'])]
    public function index(GroupRepository $groupRepository, Request $request, Security $security): Response
    {
        $form = $this->createForm(SearchGroupType::class);
        $form->handleRequest($request);
        $user = $security->getUser();
        // Vérifiez si l'utilisateur est connecté
        if (!$user) {
            throw $this->createAccessDeniedException('Utilisateur non connecté.');
        }

        $groups = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $search = $form->getData() ['search'];
            $groups = $groupRepository->findLikeName($search);
        } else {
            $groups = $groupRepository->findAll();
        }
        return $this->render('group/index.html.twig', [
            'groups' => $groups,// $groupRepository->findAll(),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/new', name: 'app_group_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $group = new Group();
        $form = $this->createForm(GroupType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($group);
            $entityManager->flush();

            return $this->redirectToRoute('app_group_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('group/new.html.twig', [
            'group' => $group,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_group_show', methods: ['GET'])]
    public function show(Group $group): Response
    {
        return $this->render('group/show.html.twig', [
            'group' => $group,

        ]);
    }
    #[Route('/{id}/edit', name: 'app_group_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Group $group, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GroupType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($group);
            $entityManager->flush();
            return $this->redirectToRoute('app_group_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('group/edit.html.twig', [
            'group' => $group,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_group_delete', methods: ['POST'])]
    public function delete(Request $request, Group $group, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $group->getId(), $request->request->get('_token'))) {
            $entityManager->remove($group);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_group_index', [], Response::HTTP_SEE_OTHER);
    }
}
