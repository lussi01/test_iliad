<?php

namespace App\Controller;

use Doctrine;

class TestController extends AbstractController
{
    #[Route('/orders', name: 'orders_list')]
    public function list(): Response
    {
        // ...
    }
}