<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Entity\Visitor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function profile(EntityManagerInterface $entityManager): Response
{
    $this->denyAccessUnlessGranted('ROLE_USER');
    $user = $this->getUser();

    $visitor = $entityManager->getRepository(Visitor::class)->findOneBy(['user' => $user]);
    $tickets = $entityManager->getRepository(Ticket::class)->findBy(['visitor' => $visitor]);
    
    return $this->render('profile/index.html.twig', [
        'user' => $user,
        'tickets' => $tickets
    ]);
}
}