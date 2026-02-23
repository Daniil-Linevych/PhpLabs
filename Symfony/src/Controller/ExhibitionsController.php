<?php

namespace App\Controller;

use App\Entity\Exhibition;
use App\Entity\Staff;
use App\Form\ExhibitionType;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\StaffRepository;
use App\Repository\ExhibitRepository;
use App\Repository\TicketRepository;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

#[Route('/exhibitions', name: 'exhibitions_')]
final class ExhibitionsController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'index', methods:['GET'])]
    public function index(Request $request): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_WORKER')) {
            throw $this->createAccessDeniedException();
        }

        $qb = $this->entityManager->getRepository(Exhibition::class)->createQueryBuilder('e');

        if ($this->isGranted('ROLE_WORKER')){
            $user = $this->getUser();

            $staff = $this->entityManager->getRepository(Staff::class)->findOneBy(['user'=>$user]);
            $exhibitions = $staff->getExhibitions()->toArray();
            $adapter = new ArrayAdapter($exhibitions);
        } else {
            $exhibitions = $this->entityManager->getRepository(Exhibition::class)->findAll();
            $this->applyFilters($qb, [
                'name' => $request->query->get('name'),
                'start_date' => $request->query->get('start_date'),
                'end_date' => $request->query->get('end_date'),
                'staff' => $request->query->get('staff'),
            ]);
            $adapter = new QueryAdapter($qb);
        } 

        
        $pager = new Pagerfanta($adapter);

        $pager->setMaxPerPage($request->query->get('perPage', 2));
        $pager->setCurrentPage($request->query->get('page', 1));

        return $this->render('exhibitions/index.html.twig', [
            'exhibitions' => $exhibitions,
            'pager'=>$pager,
        ]);
    }

    #[Route('/create', name:'create', methods:['GET', 'POST'])]
    public function create(Request $request, StaffRepository $staffRepository): Response{

        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_WORKER')) {
            throw $this->createAccessDeniedException();
        }

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
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_WORKER')) {
            throw $this->createAccessDeniedException();
        }

        if ($this->isGranted('ROLE_WORKER')){
            $user = $this->getUser();

            $staff = $this->entityManager->getRepository(Staff::class)->findOneBy(['user'=>$user]);
            if (!in_array($exhibition, $staff->getExhibitions()->toArray())){
                throw $this->createAccessDeniedException();
            }
        } 

        return $this->render('exhibitions/show.html.twig', [
            'exhibition' => $exhibition,
            'exhibits' => $exhibitRepository->findBy(['exhibition' => $exhibition]),
            'tickets' => $ticketRepository->findBy(['exhibition' => $exhibition])
        ]);
    }


    #[Route('/{id}/update', name:'update', methods:['GET', 'POST'])]
    public function update(Request $request, Exhibition $exhibition, StaffRepository $staffRepository):Response {

        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_WORKER')) {
            throw $this->createAccessDeniedException();
        }

        if ($this->isGranted('ROLE_WORKER')){
            $user = $this->getUser();

            $staff = $this->entityManager->getRepository(Staff::class)->findOneBy(['user'=>$user]);
            if (!in_array($exhibition, $staff->getExhibitions()->toArray())){
                throw $this->createAccessDeniedException();
            }
        } 

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

        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_WORKER')) {
            throw $this->createAccessDeniedException();
        }

        if ($this->isGranted('ROLE_WORKER')){
            $user = $this->getUser();

            $staff = $this->entityManager->getRepository(Staff::class)->findOneBy(['user'=>$user]);
            if (!in_array($exhibition, $staff->getExhibitions()->toArray())){
                throw $this->createAccessDeniedException();
            }
        } 

        $submittedToken = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete'.$exhibition->getId(), $submittedToken)) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        $this->entityManager->remove($exhibition);
        $this->entityManager->flush();

        $this->addFlash('success', 'Exhibition deleted successfully!');

        return $this->redirectToRoute('exhibitions_index');
    }

    private function applyFilters(QueryBuilder $qb, array $filters): void
{
    foreach ($filters as $field => $value) {
        if (empty($value)) {
            continue;
        }

        $allowedFields = ['name', 'start_date', 'end_date', 'staff'];
        if (!in_array($field, $allowedFields)) {
            continue;
        }

        switch ($field) {
            case 'start_date':
            case 'end_date':
                $this->applyDateFilter($qb, $field, $value);
                break;
                
            case 'staff':
                $this->applyStaffFilter($qb, $value);
                break;
                
            default:
                $qb->andWhere($qb->expr()->like("e.$field", ":$field"))
                   ->setParameter($field, '%'.$value.'%');
        }
    }
}

private function applyDateFilter(QueryBuilder $qb, string $field, $value): void
{
    try {
        if (str_contains($value, '..')) {
            [$startDate, $endDate] = explode('..', $value, 2);
            
            $start = new \DateTime(trim($startDate));
            $end = new \DateTime(trim($endDate));
            
            $qb->andWhere("e.$field BETWEEN :startDate AND :endDate")
               ->setParameter('startDate', $start->format('Y-m-d 00:00:00'))
               ->setParameter('endDate', $end->format('Y-m-d 23:59:59'));
            return;
        }

        if (preg_match('/^(>|<|>=|<=)\s*(.*)/', $value, $matches)) {
            $operator = $matches[1];
            $dateValue = new \DateTime(trim($matches[2]));
            
            $qb->andWhere("e.$field {$operator} :date")
               ->setParameter('date', $dateValue->format('Y-m-d H:i:s'));
            return;
        }

        $date = new \DateTime($value);
        $qb->andWhere("e.$field BETWEEN :dateStart AND :dateEnd")
           ->setParameter('dateStart', $date->format('Y-m-d 00:00:00'))
           ->setParameter('dateEnd', $date->format('Y-m-d 23:59:59'));

    } catch (\Exception $e) {
        return;
    }
}

private function applyStaffFilter(QueryBuilder $qb, $value): void
{
    
        $qb->join('e.staff', 's')
           ->andWhere(
               $qb->expr()->orX(
                   $qb->expr()->like('s.fullName', ':staffName'),
               )
           )
           ->setParameter('staffName', '%'.$value.'%');
    
}
}
