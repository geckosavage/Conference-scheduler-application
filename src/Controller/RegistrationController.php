<?php

namespace App\Controller;

use App\Entity\Registration;
use App\Repository\RegistrationRepository;
use App\Repository\SessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class RegistrationController extends AbstractController
{
    public function create(
        int $id,
        SessionRepository $sessionRepository,
        RegistrationRepository $registrationRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $session = $sessionRepository->find($id);
        if (!$session) {
            throw $this->createNotFoundException('Session not found.');
        }

        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $existing = $registrationRepository->findOneBy([
            'user' => $user,
            'session' => $session,
        ]);

        if ($existing) {
            $this->addFlash('warning', 'You have already registered for this session.');

            return $this->redirectToRoute('registration_schedule');
        }

        if ($session->getRegistrations()->count() >= $session->getRoom()->getCapacity()) {
            $this->addFlash('danger', 'This session is already full.');

            return $this->redirectToRoute('session_show', ['id' => $session->getId()]);
        }

        $registration = new Registration();
        $registration->setUser($user);
        $registration->setSession($session);
        $registration->setRegisteredAt(new \DateTime());
        $registration->setStatus('confirmed');

        $entityManager->persist($registration);
        $entityManager->flush();

        $this->addFlash('success', 'Session added to your personal schedule.');

        return $this->redirectToRoute('registration_schedule');
    }

    public function cancel(
        int $id,
        RegistrationRepository $registrationRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $registration = $registrationRepository->find($id);
        if (!$registration) {
            throw $this->createNotFoundException('Registration not found.');
        }

        if ($registration->getUser() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        $entityManager->remove($registration);
        $entityManager->flush();

        $this->addFlash('success', 'Registration removed successfully.');

        return $this->redirectToRoute('registration_schedule');
    }

    public function mySchedule(RegistrationRepository $registrationRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/my_schedule.html.twig', [
            'registrations' => $registrationRepository->findForUser($user->getId()),
        ]);
    }
}
