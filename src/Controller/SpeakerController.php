<?php

namespace App\Controller;

use App\Repository\SpeakerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class SpeakerController extends AbstractController
{
    public function index(SpeakerRepository $speakerRepository): Response
    {
        return $this->render('speaker/index.html.twig', [
            'speakers' => $speakerRepository->findBy([], ['fullName' => 'ASC']),
        ]);
    }

    public function show(int $id, SpeakerRepository $speakerRepository): Response
    {
        $speaker = $speakerRepository->find($id);
        if (!$speaker) {
            throw $this->createNotFoundException('Speaker not found.');
        }

        return $this->render('speaker/show.html.twig', [
            'speaker' => $speaker,
        ]);
    }
}
