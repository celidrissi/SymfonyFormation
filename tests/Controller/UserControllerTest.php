<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testRestaurantList(): void
    {
        $client = static::createClient();
        $client->request('GET', '/restaurants');

        $restaurant = json_decode($client->getResponse()->getContent(), true);
        
        $this->assertCount(3, $restaurant);
        $this->assertEquals('Hoki Sushi', $restaurant[0]["name"]);
    }

    public function testUserCreate()
    {
        $client = static::createClient();
        $client->request('POST', '/users', [], [], [], json_encode([
            "firstname" => "Virgil",
            "lastname" => "DOËRR",
            "email" => "vdoeer@inpi.fr",
            "password" => "password12345"
        ]));

        $this->assertResponseIsSuccessful();

        /** @var UserRepository */
        $userRepository = $client->getContainer()->get(UserRepository::class);
        $createdUser = $userRepository->findOneBy(["email" => "vdoeer@inpi.fr"]);
        $this->assertEquals('vdoeer@inpi.fr', $createdUser->getEmail());
    }
}
