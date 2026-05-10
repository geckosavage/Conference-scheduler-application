<?php

namespace App\Controller;

use App\Repository\SessionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SessionController extends AbstractController
{
    public function index(Request $request, SessionRepository $sessionRepository): Response
    {
        $track = $request->query->get('track');
        $criteria = [];

        if ($track) {
            $criteria['track'] = $track;
        }

        return $this->render('session/index.html.twig', [
            'sessions' => $sessionRepository->findBy($criteria, ['startTime' => 'ASC']),
            'selectedTrack' => $track,
        ]);
    }

    public function show(int $id, SessionRepository $sessionRepository): Response
    {
        $session = $sessionRepository->find($id);
        if (!$session) {
            throw $this->createNotFoundException('Session not found.');
        }

        return $this->render('session/show.html.twig', [
            'session' => $session,
        ]);
    }
}
