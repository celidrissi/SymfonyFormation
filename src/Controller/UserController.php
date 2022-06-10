<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * POST /users // add a new user
 *
 * @Route("/users", name="users", defaults={"_format"="json"})
 * 
 * user : 
 * GET /users/{id} //show
 * GET /users //listing
 * POST /users //add new user
 * POST /login 
*/

class UserController extends AbstractController
{
    private $validator;
    private $entityManager;

    public function __construct(ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    /**
     * @Route("", name="getUsers", methods="GET")
     */
    public function getUsers(UserRepository $userRepository): Response
    {
        return $this->json($userRepository->findAll(), 200, []);
    }

    /**
     * @Route("", name="create", methods="POST")
     */
    public function create(Request $request, SerializerInterface $serializer): Response
    {
        $newUserInfo = $request->getContent();

        /** var User */
        $user = $serializer->deserialize($newUserInfo, User::class, "json");

        if ($errors =  $this->runValidation($user)){
            return $errors;
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush($user);

        dump($user, $newUserInfo);

        return $this->json($user, 200);
    }

    /**
     * @Route("/{id}", name="update", methods="PUT")
     * 
     * @return void
     */
    public function update($id, Request $request, SerializerInterface $serializer, UserRepository $userRepository)
    {
        $newUserInfo = $request->getContent();

        $myUser = $userRepository->findOneById($id);

        /** var User */
        $user = $serializer->deserialize($newUserInfo, User::class, "json", [AbstractNormalizer::OBJECT_TO_POPULATE => $myUser]);

        if ($errors = $this->runValidation($user)) {
            return $errors;
        };

        dump($user, $myUser);
        
        $this->entityManager->flush($user);

        return $this->json($user, 200);
    }

    public function runValidation(object $object)
    {
        // Validate the object with doctrine annotations inside the Entity
        $errors = $this->validator->validate($object);

        if (count($errors) > 0){
            // Unprocessable Entity
            return $this->json($errors, 422);
        }
    }
}
