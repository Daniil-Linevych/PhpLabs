<?php

namespace App\Controller;

use App\Entity\Exhibition;
use App\Form\ExhibitionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\StaffRepository;
use App\Repository\ExhibitRepository;
use App\Repository\TicketRepository;

#[Route('/exhibitions', name: 'exhibitions_')]
final class ExhibitionsController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'index', methods:['GET'])]
    public function index(): Response
    {
        $exhibitions = $this->entityManager->getRepository(Exhibition::class)->findAll();

        return $this->render('exhibitions/index.html.twig', [
            'exhibitions' => $exhibitions,
        ]);
    }

    #[Route('/create', name:'create', methods:['GET', 'POST'])]
    public function create(Request $request, StaffRepository $staffRepository): Response{
        
        $exhibition = new Exhibition();
        $form = $this->createForm(ExhibitionType::class, $exhibition, [
            'staff_members' => $staffRepository->findAll()
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($exhibition);
            $this->entityManager->flush();

            $this->addFlash('success', 'Exhibition created successfully!');

            return $this->redirectToRoute('exhibitions_index');
        }

        return $this->render('exhibitions/create.html.twig', [
            'form' => $form->createView(),
            'exhibition' => $exhibition,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Exhibition $exhibition, ExhibitRepository $exhibitRepository, TicketRepository $ticketRepository): Response
    {
        return $this->render('exhibitions/show.html.twig', [
            'exhibition' => $exhibition,
            'exhibits' => $exhibitRepository->findBy(['exhibition' => $exhibition]),
            'tickets' => $ticketRepository->findBy(['exhibition' => $exhibition])
        ]);
    }


    #[Route('/{id}/update', name:'update', methods:['GET', 'POST'])]
    public function update(Request $request, Exhibition $exhibition, StaffRepository $staffRepository):Response {

        $form = $this->createForm(ExhibitionType::class, $exhibition, [
            'staff_members' => $staffRepository->findAll()
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Exhibition updated successfully!');

            return $this->redirectToRoute('exhibitions_index');
        }

        return $this->render('exhibitions/create.html.twig', [
            'form' => $form,
            'exhibition' => $exhibition,
        ]);
            
        
    }

    #[Route('/{id}/delete', name:'delete', methods:['POST'])]
    public function delete(Request $request, Exhibition $exhibition):Response {

        $submittedToken = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete'.$exhibition->getId(), $submittedToken)) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        $this->entityManager->remove($exhibition);
        $this->entityManager->flush();

        $this->addFlash('success', 'Exhibition deleted successfully!');

        return $this->redirectToRoute('exhibitions_index');
    }
}
