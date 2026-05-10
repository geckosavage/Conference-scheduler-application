<?php

namespace App\Controller;

use App\Entity\Conference;
use App\Entity\Room;
use App\Entity\Session;
use App\Entity\Speaker;
use App\Form\ConferenceType;
use App\Form\RoomType;
use App\Form\SessionType;
use App\Form\SpeakerType;
use App\Repository\ConferenceRepository;
use App\Repository\RegistrationRepository;
use App\Repository\RoomRepository;
use App\Repository\SessionRepository;
use App\Repository\SpeakerRepository;
use Doctrine\Common\Collections\Expr\Value;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends AbstractController
{
    public function dashboard(
        ConferenceRepository $conferenceRepository,
        SessionRepository $sessionRepository,
        SpeakerRepository $speakerRepository,
        RegistrationRepository $registrationRepository
    ): Response {
        return $this->render('admin/dashboard.html.twig', [
            'conferenceCount' => count($conferenceRepository->findAll()),
            'sessionCount' => count($sessionRepository->findAll()),
            'speakerCount' => count($speakerRepository->findAll()),
            'registrationCount' => count($registrationRepository->findAll()),
            'recentSessions' => $sessionRepository->findBy([], ['startTime' => 'DESC'], 5),
        ]);
    }

    public function conferenceIndex(ConferenceRepository $conferenceRepository): Response
    {
        return $this->render('admin/conference/index.html.twig', [
            'conferences' => $conferenceRepository->findBy([], ['startDate' => 'DESC']),
        ]);
    }

    public function conferenceNew(Request $request, EntityManagerInterface $entityManager): Response
    {
        $conference = new Conference();
        $form = $this->createForm(ConferenceType::class, $conference);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($conference);
            $entityManager->flush();

            $this->addFlash('success', 'Conference created successfully.');

            return $this->redirectToRoute('admin_conference_index');
        }

        return $this->render('admin/conference/form.html.twig', [
            'title' => 'Create Conference',
            'conferenceForm' => $form->createView(),
        ]);
    }

    public function conferenceEdit(int $id, Request $request, ConferenceRepository $conferenceRepository, EntityManagerInterface $entityManager): Response
    {
        $conference = $conferenceRepository->find($id);
        if (!$conference) {
            throw $this->createNotFoundException('Conference not found.');
        }

        $oldDescription = $conference->getDescription();

        $form = $this->createForm(ConferenceType::class, $conference);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $description = $conference->getDescription();

        if (strlen($description) <= 100) {
            $this->addFlash('danger', 'Description must be more than 100 characters.');
        }
        elseif ($description === $oldDescription) {
            $this->addFlash('danger', 'No changes made to the description.');
        }

        elseif ($form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Conference updated successfully.');
            
            return $this->redirectToRoute('admin_conference_index');
        }
    }
    return $this->render('admin/conference/form.html.twig', [
        'title' => 'Edit Conference',
        'conferenceForm' => $form->createView(),
        'oldDescription' => $oldDescription,
    ]);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();
        $this->addFlash('success', 'Conference updated successfully.');

        return $this->redirectToRoute('admin_conference_index');
    }
    }

    public function conferenceDelete(
        int $id,
        Request $request,
        ConferenceRepository $conferenceRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $conference = $conferenceRepository->find($id);
        if (!$conference) {
            throw $this->createNotFoundException('Conference not found.');
        }


        if ($this->isCsrfTokenValid('delete_conference_'.$conference->getId(), (string) $request->request->get('_token'))) {
            if ($conference->getSessions()->count() > 0) {
                $this->addFlash('danger', 'Delete all sessions in this conference before deleting the conference itself.');
            } else {
                $entityManager->remove($conference);
                $entityManager->flush();
                $this->addFlash('success', 'Conference deleted successfully.');
            }
        }

        return $this->redirectToRoute('admin_conference_index');
    }

    public function sessionIndex(SessionRepository $sessionRepository): Response
    {
        return $this->render('admin/session/index.html.twig', [
            'sessions' => $sessionRepository->findBy([], ['startTime' => 'DESC']),
        ]);
    }

    public function sessionNew(
        Request $request,
        EntityManagerInterface $entityManager,
        SessionRepository $sessionRepository
    ): Response {
        $session = new Session();
        $form = $this->createForm(SessionType::class, $session);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($sessionRepository->hasConflict($session)) {
                $this->addFlash('danger', 'The selected room or one of the selected speakers already has another session during this time.');
            } else {
                $entityManager->persist($session);
                $entityManager->flush();
                $this->addFlash('success', 'Session created successfully.');

                return $this->redirectToRoute('admin_session_index');
            }
        }

        return $this->render('admin/session/form.html.twig', [
            'title' => 'Create Session',
            'sessionForm' => $form->createView(),
        ]);
    }

    public function sessionEdit(
        int $id,
        Request $request,
        SessionRepository $sessionRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $session = $sessionRepository->find($id);
        if (!$session) {
            throw $this->createNotFoundException('Session not found.');
        }

        $form = $this->createForm(SessionType::class, $session);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($sessionRepository->hasConflict($session)) {
                $this->addFlash('danger', 'The selected room or one of the selected speakers already has another session during this time.');
            } else {
                $entityManager->flush();
                $this->addFlash('success', 'Session updated successfully.');

                return $this->redirectToRoute('admin_session_index');
            }
        }

        return $this->render('admin/session/form.html.twig', [
            'title' => 'Edit Session',
            'sessionForm' => $form->createView(),
        ]);
    }

    public function sessionDelete(
        int $id,
        Request $request,
        SessionRepository $sessionRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $session = $sessionRepository->find($id);
        if (!$session) {
            throw $this->createNotFoundException('Session not found.');
        }

        if ($this->isCsrfTokenValid('delete_session_'.$session->getId(), (string) $request->request->get('_token'))) {
            foreach ($session->getRegistrations() as $registration) {
                $entityManager->remove($registration);
            }
            $entityManager->remove($session);
            $entityManager->flush();
            $this->addFlash('success', 'Session deleted successfully.');
        }

        return $this->redirectToRoute('admin_session_index');
    }

    public function speakerIndex(SpeakerRepository $speakerRepository): Response
    {
        return $this->render('admin/speaker/index.html.twig', [
            'speakers' => $speakerRepository->findBy([], ['fullName' => 'ASC']),
        ]);
    }

    public function speakerNew(Request $request, EntityManagerInterface $entityManager): Response
    {
        $speaker = new Speaker();
        $form = $this->createForm(SpeakerType::class, $speaker);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($speaker);
            $entityManager->flush();
            $this->addFlash('success', 'Speaker created successfully.');

            return $this->redirectToRoute('admin_speaker_index');
        }

        return $this->render('admin/speaker/form.html.twig', [
            'title' => 'Create Speaker',
            'speakerForm' => $form->createView(),
        ]);
    }

    public function speakerEdit(int $id, Request $request, SpeakerRepository $speakerRepository, EntityManagerInterface $entityManager): Response
    {
        $speaker = $speakerRepository->find($id);
        if (!$speaker) {
            throw $this->createNotFoundException('Speaker not found.');
        }

        $form = $this->createForm(SpeakerType::class, $speaker);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Speaker updated successfully.');

            return $this->redirectToRoute('admin_speaker_index');
        }

        return $this->render('admin/speaker/form.html.twig', [
            'title' => 'Edit Speaker',
            'speakerForm' => $form->createView(),
        ]);
    }

    public function speakerDelete(
        int $id,
        Request $request,
        SpeakerRepository $speakerRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $speaker = $speakerRepository->find($id);
        if (!$speaker) {
            throw $this->createNotFoundException('Speaker not found.');
        }

        if ($this->isCsrfTokenValid('delete_speaker_'.$speaker->getId(), (string) $request->request->get('_token'))) {
            if ($speaker->getSessions()->count() > 0) {
                $this->addFlash('danger', 'Remove this speaker from all sessions before deleting the profile.');
            } else {
                $entityManager->remove($speaker);
                $entityManager->flush();
                $this->addFlash('success', 'Speaker deleted successfully.');
            }
        }

        return $this->redirectToRoute('admin_speaker_index');
    }

    public function roomIndex(RoomRepository $roomRepository): Response
    {
        return $this->render('admin/room/index.html.twig', [
            'rooms' => $roomRepository->findBy([], ['name' => 'ASC']),
        ]);
    }

    public function roomNew(Request $request, EntityManagerInterface $entityManager): Response
    {
        $room = new Room();
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($room);
            $entityManager->flush();
            $this->addFlash('success', 'Room created successfully.');

            return $this->redirectToRoute('admin_room_index');
        }

        return $this->render('admin/room/form.html.twig', [
            'title' => 'Create Room',
            'roomForm' => $form->createView(),
        ]);
    }

    public function roomEdit(int $id, Request $request, RoomRepository $roomRepository, EntityManagerInterface $entityManager): Response
    {
        $room = $roomRepository->find($id);
        if (!$room) {
            throw $this->createNotFoundException('Room not found.');
        }

        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Room updated successfully.');

            return $this->redirectToRoute('admin_room_index');
        }

        return $this->render('admin/room/form.html.twig', [
            'title' => 'Edit Room',
            'roomForm' => $form->createView(),
        ]);
    }

    public function roomDelete(
        int $id,
        Request $request,
        RoomRepository $roomRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $room = $roomRepository->find($id);
        if (!$room) {
            throw $this->createNotFoundException('Room not found.');
        }

        if ($this->isCsrfTokenValid('delete_room_'.$room->getId(), (string) $request->request->get('_token'))) {
            if ($room->getSessions()->count() > 0) {
                $this->addFlash('danger', 'Delete or reassign sessions in this room before deleting it.');
            } else {
                $entityManager->remove($room);
                $entityManager->flush();
                $this->addFlash('success', 'Room deleted successfully.');
            }
        }

        return $this->redirectToRoute('admin_room_index');
    }
}
