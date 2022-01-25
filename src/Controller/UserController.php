<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\User;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * POST /users // add a new user
 *
 * @Route("/users", name="users", defaults={"_format"="json"})
*/

class UserController extends AbstractController
{
    /**
     * @Route("", name="create", methods="POST")
     */
    public function create(Request $request, SerializerInterface $serializer): Response // AKA index
    {
        $newUserInfo = $request->getContent();

        /** var User */
        $user = $serializer->deserialize($newUserInfo, User::class, "json");

        // TODO : Insert in database
        dump($user);

        return $this->json($user, 201);
    }

    /**
     * @Route("/{id}", name="update", methods="PUT")
     * 
     * @return void
     */
    public function update($id, Request $request, SerializerInterface $serializer)
    {
        $newUserInfo = $request->getContent();

        $myUser = new User('Chafik', 'El idrissi', 'chafik.elidrissi@outlook.fr', 'MachinBidule');
        /** var User */
        $user = $serializer->deserialize($newUserInfo, User::class, "json", [AbstractNormalizer::OBJECT_TO_POPULATE => $myUser]);


        // TODO : Insert in database
        dump($user);

        return $this->json($user, 200);
    }
}
