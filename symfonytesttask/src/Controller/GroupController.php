<?php

namespace App\Controller;

use App\Entity\Group;
use App\Entity\User;
use App\Form\GroupType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'api_')]
class GroupController extends BaseApiController
{
    #[Route('/groups', name: 'groups_list', methods: ['get'])]
    /**
     * Get list of all groups
     */
    public function indexAction(): Response
    {
        $groups = $this->em->getRepository(Group::class)->findAll();
        return $this->response($groups);
    }

    /**
     * Creates a new group with given user input
     */
    #[Route('/groups', name: 'group_create', methods: ['post'])]
    public function createAction(Request $request): Response
    {
        $form = $this->buildForm(GroupType::class);
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()){
            return $this->json($form, Response::HTTP_BAD_REQUEST);
        }

        /* @var Group $group */
        $group = $form->getData();

        $this->em->persist($group);
        $this->em->flush();

        return $this->response($group, Response::HTTP_CREATED);
    }

    /**
     * Shows single group by Id
     */
    #[Route('/groups/{id}', name: 'group_show', methods: ['get'])]
    public function showAction(int $id): Response
    {
        /* @var Group $group */
        $group = $this->findEntity($id);

        $data = [
            'id' => $group->getId(),
            'group' => $group->getName(),
            'users' => $group->getUsers()->map(function(User $user){
                return [
                    'id' => $user->getId(),
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                ];
            })
        ];

        return $this->response($data);
    }

    /**
     * Updates a group by Id with given user input
     */
    #[Route('/groups/{id}', name: 'group_update', methods: ['put', 'patch'])]
    public function updateAction(Request $request, int $id): Response
    {
        /* @var Group $group */
        $group = $this->findEntity($id);

        $form = $this->buildForm(GroupType::class, $group, [
            'method' => $request->getMethod()
        ]);

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()){
            return $this->response($form, Response::HTTP_BAD_REQUEST);
        }

        $group = $form->getData();
        $this->em->persist($group);
        $this->em->flush();

        return $this->response($group);
    }

    /**
     * Deletes a group by Id
     */
    #[Route('/groups/{id}', name: 'group_delete', methods: ['delete'])]
    public function deleteAction(int $id): Response
    {
        /* @var Group $group */
        $group = $this->findEntity($id);

        $this->em->remove($group);
        $this->em->flush();

        return $this->response(null, Response::HTTP_NO_CONTENT);
    }

    protected function findEntity(int $id)
    {
        /* @var Group $group */
        $group = $this->em->getRepository(Group::class)->find($id);
        if ($group) {
            return $group;
        } else {
            throw $this->createNotFoundException('Group with ID = '.$id.' not found');
        }
    }
}
