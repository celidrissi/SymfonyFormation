<?php

namespace App\Controller;

use App\Repository\InMemoryRestaurantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/** 
 * restaurants : 
 * GET /restaurants/{id} //show
 * GET /restaurants //listing
 * POST /restaurants
 * PATCH /restaurants/{id}/upvote|downvote
 * 
 * user : 
 * POST /users //add new user
 * POST /login 
 */

class RestaurantController extends AbstractController
{
    private InMemoryRestaurantRepository $restaurantRepository;

    public function __construct(InMemoryRestaurantRepository $restaurantRepository)
    {
        $this->restaurantRepository = $restaurantRepository;
    }

    /**
     * @Route("/restaurants", name="restaurants")
     */
    public function getRestaurants(): Response // AKA List
    {
        $restaurants = $this->restaurantRepository->findAll();
        //$restaurants = $this->getRestaurants();

        $context = ["action" => ["list"]];

        $context[AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER] = function($object, $format, $context) {
            return $object->getId();
        };

        return $this->json($restaurants, 200, [], $context);
    }

    /**
     * @Route("/restaurants/{id}", name="restaurant", requirements={"id":"\d+"}, methods="GET")
     */
    public function getRestaurant(int $id): Response // AKA Show
    {
        $restaurant = $this->restaurantRepository->findOneById($id);

        $context = [];

        $context[AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER] = function($object, $format, $context) {
            return $object->getId();
        };

        return $this->json($restaurant, 200, [], $context);
        //return new Response(json_encode($restaurant), 200, ['content-type' => 'application/json']);
    }
}
