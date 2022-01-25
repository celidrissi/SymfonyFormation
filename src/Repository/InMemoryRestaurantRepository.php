<?php

namespace App\Repository;

use App\Entity\Restaurant;
use App\Entity\Address;

class InMemoryRestaurantRepository
{
    public function findOneById($id): ?Restaurant
    {
        $restaurants = array_filter($this->findAll(), function(Restaurant $restaurant) use ($id) {
            return $restaurant->getId() == $id;
        });

        // Return the first restaurant only
        return reset($restaurants);
    }

    public function findAll()
    {
        $r1 = new Restaurant(1, 'Hoki Sushi', 5, 1);
        $r2 = new Restaurant(2, 'Le 5 Sens', 25, 2);
        $r3 = new Restaurant(3, '231 East Street', 12, 3);

        $addr1 = new Address(1, '2 Place de la Renaissance', '92270', 'Bois-Colombes');

        $addr2 = new Address(2, '12 Place de la Renaissance', '92270', 'Bois-Colombes');

        $addr3 = new Address(3, '2 Rue De La Pepiniere', '75008', 'Paris');
            
        $r1->setAddress($addr1);
        $r2->setAddress($addr2);
        $r3->setAddress($addr3);

        return [$r1, $r2, $r3];
    }
}