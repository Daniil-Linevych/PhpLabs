<?php

namespace App\Controller;

use App\Entity\Visitor;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Doctrine\ORM\QueryBuilder;
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
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $qb = $this->entityManager->getRepository(Visitor::class)->createQueryBuilder('e');

        $this->applyFilters($qb, [
            'name' => $request->query->get('name'),
            'email' => $request->query->get('email'),
            'phone' => $request->query->get('phone'),
            'date' => $request->query->get('date'),
        ]);

        $adapter = new QueryAdapter($qb);
        $pager = new Pagerfanta($adapter);

        $pager->setMaxPerPage($request->query->get('perPage', 3));
        $pager->setCurrentPage($request->query->get('page', 1));

        return $this->render('visitors/index.html.twig', [
            'pager'=>$pager
        ]);
    }

    #[Route('/create', name:'create', methods:['GET', 'POST'])]
    public function create(Request $request): Response {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');
    
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

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('visitors/show.html.twig', [
            'visitor' => $visitor,
            'tickets' => $ticketRepository->findBy(['visitor'=>$visitor])
        ]);
    }

    #[Route('/{id}/update', name:'update', methods:['GET', 'POST'])]
    public function update(Request $request, Visitor $visitor):Response {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

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

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $submittedToken = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete'.$visitor->getId(), $submittedToken)) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        $this->entityManager->remove($visitor);
        $this->entityManager->flush();

        $this->addFlash('success', 'Visitor deleted successfully!');

        return $this->redirectToRoute('visitors_index');
    }
    private function applyFilters(QueryBuilder $qb, array $filters): void
    {
        foreach ($filters as $field => $value) {
            if (empty($value)) {
                continue;
            }

            $allowedFields = ['name', 'author', 'creationYear', 'exhibition']; 
            if (!in_array($field, $allowedFields)) {
                continue;
            }

            switch ($field) {
                case 'date':
                    $this->applyDateFilter($qb, $value);
                    
                default:
                    $qb->andWhere($qb->expr()->like("e.$field", ":$field"))
                       ->setParameter($field, '%'.$value.'%');
            }
        }
    }

    private function applyDateFilter(QueryBuilder $qb, $value): void
    {
        try {
            if (str_contains($value, '..')) {
                [$startDate, $endDate] = explode('..', $value, 2);
                
                $start = new \DateTime(trim($startDate));
                $end = new \DateTime(trim($endDate));
                
                $qb->andWhere('e.registration_date BETWEEN :startDate AND :endDate')
                ->setParameter('startDate', $start->format('Y-m-d 00:00:00'))
                ->setParameter('endDate', $end->format('Y-m-d 23:59:59'));
                return;
            }

            if (preg_match('/^(>|<|>=|<=)\s*(.*)/', $value, $matches)) {
                $operator = $matches[1];
                $dateValue = new \DateTime(trim($matches[2]));
                
                $qb->andWhere("e.registration_date {$operator} :date")
                ->setParameter('date', $dateValue->format('Y-m-d H:i:s'));
                return;
            }

            $date = new \DateTime($value);
            $qb->andWhere('e.date BETWEEN :dateStart AND :dateEnd')
            ->setParameter('dateStart', $date->format('Y-m-d 00:00:00'))
            ->setParameter('dateEnd', $date->format('Y-m-d 23:59:59'));

        } catch (\Exception $e) {
            return; 
        }
    }
}


