<?php

namespace App\Controller;

use App\Repository\ConferenceRepository;
use App\Repository\SessionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    public function index(ConferenceRepository $conferenceRepository, SessionRepository $sessionRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'featuredConferences' => $conferenceRepository->findUpcoming(),
            'featuredSessions' => $sessionRepository->findBy([], ['startTime' => 'ASC'], 4),
        ]);
    }
}
