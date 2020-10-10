<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    public function home(): Response
    {
        return $this->render('index.html');
    }

    public function redirectToHome(): Response
    {
        return $this->redirectToRoute('home');
    }
}