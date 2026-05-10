<?php

namespace App\Controller;

use App\Repository\ConferenceRepository;
use App\Repository\SessionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ConferenceController extends AbstractController
{
    public function index(ConferenceRepository $conferenceRepository): Response
    {
        return $this->render('conference/index.html.twig', [
            'conferences' => $conferenceRepository->findBy([], ['startDate' => 'ASC']),
        ]);
    }

    public function show(int $id, ConferenceRepository $conferenceRepository): Response
    {
        $conference = $conferenceRepository->find($id);
        if (!$conference) {
            throw $this->createNotFoundException('Conference not found.');
        }

        return $this->render('conference/show.html.twig', [
            'conference' => $conference,
        ]);
    }

    public function schedule(int $id, ConferenceRepository $conferenceRepository, SessionRepository $sessionRepository): Response
    {
        $conference = $conferenceRepository->find($id);
        if (!$conference) {
            throw $this->createNotFoundException('Conference not found.');
        }

        return $this->render('conference/schedule.html.twig', [
            'conference' => $conference,
            'groupedSessions' => $sessionRepository->findByConferenceGroupedByDay($id),
        ]);
    }
}
