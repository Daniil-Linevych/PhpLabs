<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Entity\Visitor;
use App\Repository\ExhibitionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\TicketType;

#[Route('/tickets', name: 'tickets_')]
final class TicketController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'index', methods:['GET'])]
    public function index(): Response
    {
        $tickets = $this->entityManager->getRepository(Ticket::class)->findAll();

        return $this->render('tickets/index.html.twig', [
            'tickets' => $tickets,
        ]);
    }

    #[Route('/create', name:'create', methods:['GET', 'POST'])]
    public function create(Request $request, ExhibitionRepository $exhibitionRepository): Response{
        $ticket = new Ticket();
        $form = $this->createForm(TicketType::class, $ticket, [
            'exhibitions' => $exhibitionRepository->findAll()
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($ticket);
            $this->entityManager->flush();

            $this->addFlash('success', 'Ticket created successfully!');

            return $this->redirectToRoute('tickets_index');
        }

        return $this->render('tickets/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name:'show', methods:['GET'])]
    public function show(Ticket $ticket): Response{

        return $this->render('tickets/show.html.twig', [
            'ticket' => $ticket,
        ]);
    }

    #[Route('/{id}/update', name:'update', methods:['GET', 'POST'])]
    public function update(Request $request, Ticket $ticket, ExhibitionRepository $exhibitionRepository):Response {

        $form = $this->createForm(TicketType::class, $ticket, [
            'exhibitions' => $exhibitionRepository->findAll()
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($ticket);
            $this->entityManager->flush();

            $this->addFlash('success', 'Ticket updated successfully!');

            return $this->redirectToRoute('tickets_index');
        }

        return $this->render('tickets/update.html.twig', [
            'form' => $form->createView(),
            'ticket' => $ticket,
        ]);
    }

    #[Route('/{id}/delete', name:'delete', methods:['POST'])]
    public function delete(Request $request, Ticket $ticket):Response {

        $submittedToken = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete'.$ticket->getId(), $submittedToken)) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        $this->entityManager->remove($ticket);
        $this->entityManager->flush();

        $this->addFlash('success', 'Ticket deleted successfully!');

        return $this->redirectToRoute('tickets_index');
    }

    #[Route('/{id}/buy', name:'buy', methods:['GET'])]
    public function buy(Ticket $ticket):Response {

        $id_visitor = 1;
        $visitor = $this->entityManager->getRepository(Visitor::class)->find($id_visitor);

        $ticket->setVisitor($visitor);
    
        $this->entityManager->flush();

        $this->addFlash('success', 'Ticket bought successfully!');

        return $this->redirectToRoute('tickets_index');
    }

    #[Route('/{id}/sell', name:'sell', methods:['GET', 'POST'])]
    public function sell(Ticket $ticket):Response {
        $id = $ticket->getVisitor()->getId();
        $ticket->setVisitor(null);
    
        $this->entityManager->flush();

        $this->addFlash('success', 'Ticket sold successfully!');

        return $this->redirectToRoute('visitors_show', [
            'id'=> $id
        ]);
    }
}
