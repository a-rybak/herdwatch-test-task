<?php

namespace App\Controller;

use App\Form\UserType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;

#[Route('/api', name: 'api_')]
class UserController extends BaseApiController
{
    /**
     * Get list of all users
     */
    #[Route('/users', name: 'users_list', methods: ['get'])]
    public function indexAction(): Response
    {
        $users = $this->em->getRepository(User::class)->findAll();
        return $this->response($users);
    }

    /**
     * Creates a new user with given user input
     */
    #[Route('/users', name: 'user_create', methods: ['post'])]
    public function createAction(Request $request): Response
    {
        $user = new User();

        $form = $this->buildForm(UserType::class, $user, [
            'validation_groups' => ['new'],
        ]);
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()){
            return $this->response($form, Response::HTTP_BAD_REQUEST);
        }

        /* @var User $user */
        $user = $form->getData();

        $this->em->persist($user);
        $this->em->flush();

        $data = $this->mapResponse($user, withGroup: true);
        return $this->response($data);
    }

    /**
     * Shows user by Id
     */
    #[Route('/users/{id}', name: 'user_show', methods: ['get'])]
    public function showAction(int $id): Response
    {
        /* @var User $user */
        $user = $this->findEntity($id);
        $data = $this->mapResponse($user);
        return $this->response($data);
    }

    /**
     * Updates an existing user with given user input (partially or full)
     */
    #[Route('/users/{id}', name: 'user_update', methods: ['put', 'patch'])]
    public function updateAction(Request $request, int $id): Response
    {
        /* @var User $user */
        $user = $this->findEntity($id);

        $form = $this->buildForm(UserType::class, $user, [
            'method' => $request->getMethod(),
            'validation_groups' => ['edit'],
        ]);

        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()){
            return $this->response($form, Response::HTTP_BAD_REQUEST);
        }

        $user = $form->getData();
        $this->em->persist($user);
        $this->em->flush();

        $data = $this->mapResponse($user);
        return $this->response($data);
    }

    /**
     * Deletes a user by Id
     */
    #[Route('/users/{id}', name: 'user_delete', methods: ['delete'])]
    public function deleteAction(int $id): Response
    {
        /* @var User $user */
        $user = $this->findEntity($id);

        $this->em->remove($user);
        $this->em->flush();

        return $this->response(null, Response::HTTP_NO_CONTENT);
    }

    protected function findEntity(int $id)
    {
        /* @var User $user */
        $user = $this->em->getRepository(User::class)->find($id);
        if ($user) {
            return $user;
        } else {
            throw $this->createNotFoundException('User with ID = '.$id.' not found');
        }
    }

    protected function mapResponse(User $userEntity, bool $withGroup = false)
    {
        $userGroup = [];
        if ($withGroup && $userEntity->getGroup()){
            $userGroup['group'] = [
                'id' => $userEntity->getGroup()->getId(),
                'name' => $userEntity->getGroup()->getName()
            ];
        }

        $user = [
            'id' => $userEntity->getId(),
            'name' => $userEntity->getName(),
            'email' => $userEntity->getEmail(),
        ];

        return array_merge($user, $userGroup);
    }
}
