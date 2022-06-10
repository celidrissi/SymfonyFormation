<?php

namespace App\Repository;

interface RestaurantRepositoryInterface
{
    public function findAll();
    public function findOneById($id);
    public function findOneByName($name);
    public function findOneByAddress($address);
    public function findOneByLikes($likes);
    public function findOneByDislikes($dislikes);
}