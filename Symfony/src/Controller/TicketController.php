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
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

#[Route('/tickets', name: 'tickets_')]
final class TicketController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'index', methods:['GET'])]
    public function index(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $tickets = $this->entityManager->getRepository(Ticket::class)->findAll();

        $adapter = new ArrayAdapter($tickets);
        $pager = new Pagerfanta($adapter);

        $pager->setMaxPerPage($request->query->get('perPage', 3));
        $pager->setCurrentPage($request->query->get('page', 1));

        return $this->render('tickets/index.html.twig', [
            'tickets' => $tickets,
            'pager'=>$pager,
        ]);
    }

    #[Route('/create', name:'create', methods:['GET', 'POST'])]
    public function create(Request $request, ExhibitionRepository $exhibitionRepository): Response{

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

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

        $this->denyAccessUnlessGranted('ROLE_USER');

        return $this->render('tickets/show.html.twig', [
            'ticket' => $ticket,
        ]);
    }

    #[Route('/{id}/update', name:'update', methods:['GET', 'POST'])]
    public function update(Request $request, Ticket $ticket, ExhibitionRepository $exhibitionRepository):Response {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

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

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

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

        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();
        $visitor_by_user = $this->entityManager->getRepository(Visitor::class)->findOneBy(['user'=>$user]);

        $id_visitor = $visitor_by_user->getId();
        $visitor = $this->entityManager->getRepository(Visitor::class)->find($id_visitor);

        $ticket->setVisitor($visitor);
    
        $this->entityManager->flush();

        $this->addFlash('success', 'Ticket bought successfully!');

        return $this->redirectToRoute('tickets_index');
    }

    #[Route('/{id}/sell', name:'sell', methods:['GET', 'POST'])]
    public function sell(Ticket $ticket):Response {

        $this->denyAccessUnlessGranted('ROLE_USER');

        $id = $ticket->getVisitor()->getId();
        $ticket->setVisitor(null);
    
        $this->entityManager->flush();

        $this->addFlash('success', 'Ticket sold successfully!');

        return $this->redirectToRoute('app_profile', [
            'id'=> $id
        ]);
    }
}
