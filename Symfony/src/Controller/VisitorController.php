<?php

namespace App\Controller;

use App\Entity\Visitor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\VisitorType;
use App\Repository\TicketRepository;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

#[Route('/visitors', name: 'visitors_')]
final class VisitorController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'index', methods:['GET'])]
    public function index(Request $request): Response
    {
        $visitors = $this->entityManager->getRepository(Visitor::class)->findAll();

        $adapter = new ArrayAdapter($visitors);
        $pager = new Pagerfanta($adapter);

        $pager->setMaxPerPage($request->query->get('perPage', 3));
        $pager->setCurrentPage($request->query->get('page', 1));

        return $this->render('visitors/index.html.twig', [
            'visitors' => $visitors,
            'pager'=>$pager
        ]);
    }

    #[Route('/create', name:'create', methods:['GET', 'POST'])]
    public function create(Request $request): Response {
    
        $visitor = new Visitor();
        $form = $this->createForm(VisitorType::class, $visitor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())  {
            $this->entityManager->persist($visitor);
            $this->entityManager->flush();

            $this->addFlash('success', 'Visitor created successfully!');

            return $this->redirectToRoute('visitors_index');
        }

        return $this->render('visitors/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/{id}', name:'show', methods:['GET'])]
    public function show(Visitor $visitor, TicketRepository $ticketRepository): Response{

        return $this->render('visitors/show.html.twig', [
            'visitor' => $visitor,
            'tickets' => $ticketRepository->findBy(['visitor'=>$visitor])
        ]);
    }

    #[Route('/{id}/update', name:'update', methods:['GET', 'POST'])]
    public function update(Request $request, Visitor $visitor):Response {

        $form = $this->createForm(VisitorType::class, $visitor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())  {
            $this->entityManager->persist($visitor);
            $this->entityManager->flush();

            $this->addFlash('success', 'Visitor updated successfully!');

            return $this->redirectToRoute('visitors_index');
        }

        return $this->render('visitors/update.html.twig', [
            'form' => $form->createView(),
            'visitor' => $visitor,
        ]);
    }

    #[Route('/{id}/delete', name:'delete', methods:['POST'])]
    public function delete(Request $request, Visitor $visitor):Response {

        $submittedToken = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete'.$visitor->getId(), $submittedToken)) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        $this->entityManager->remove($visitor);
        $this->entityManager->flush();

        $this->addFlash('success', 'Visitor deleted successfully!');

        return $this->redirectToRoute('visitors_index');
    }
}


