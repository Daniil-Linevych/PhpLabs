<?php

namespace App\Controller;

use App\Entity\Exhibit;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Pagerfanta;
use App\Repository\ExhibitionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\ExhibitType;

#[Route('/exhibits', name: 'exhibits_')]
final class ExhibitsController extends AbstractController
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

        $qb = $this->entityManager->getRepository(Exhibit::class)->createQueryBuilder('e');

        $this->applyFilters($qb, [
            'name' => $request->query->get('name'),
            'author' => $request->query->get('author'),
            'creationYear' => $request->query->get('creationYear'),
            'exhibition' => $request->query->get('exhibition'),
        ]);
    
        $adapter = new QueryAdapter($qb);
        
        $pager = new Pagerfanta($adapter);

        $perPage = $request->query->get('perPage', 3);
        $pager->setMaxPerPage($perPage);
        $pager->setCurrentPage($request->query->get('page', 1));

        return $this->render('exhibits/index.html.twig', [
            'pager' => $pager,
            'perPage'=>$perPage,
        ]);
    }

    #[Route('/create', name:'create', methods:['GET', 'POST'])]
    public function create(Request $request,  ExhibitionRepository $exhibitionRepository): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_WORKER')) {
            throw $this->createAccessDeniedException();
        }

        $exhibit = new Exhibit();
        $form = $this->createForm(ExhibitType::class, $exhibit, [
            'exhibitions' => $exhibitionRepository->findAll()
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $this->entityManager->persist($exhibit);
            $this->entityManager->flush();

            $this->addFlash('success', 'Exhibit created successfully!');

            return $this->redirectToRoute('exhibits_index');
        }

        return $this->render('exhibits/create.html.twig', [
            'form' => $form,
            'exhibit' => $exhibit,
        ]);
    }

    #[Route('/{id}', name:'show', methods:['GET'])]
    public function show(Exhibit $exhibit): Response{

        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_WORKER')) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('exhibits/show.html.twig', [
            'exhibit' => $exhibit,
        ]);
    }

    #[Route('/{id}/update', name:'update', methods:['GET', 'POST'])]
    public function update(Request $request, Exhibit $exhibit, ExhibitionRepository $exhibitionRepository):Response {

        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_WORKER')) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(ExhibitType::class, $exhibit, [
            'exhibitions' => $exhibitionRepository->findAll()
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Exhibit updated successfully!');

            return $this->redirectToRoute('exhibits_index');
        }

        return $this->render('exhibits/update.html.twig', [
            'exhibit' => $exhibit,
            'form' => $form,
        ]);

    }

    #[Route('/{id}/delete', name:'delete', methods:['POST'])]
    public function delete(Request $request, Exhibit $exhibit):Response {

        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_WORKER')) {
            throw $this->createAccessDeniedException();
        }

        $submittedToken = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete'.$exhibit->getId(), $submittedToken)) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        $this->entityManager->remove($exhibit);
        $this->entityManager->flush();

        $this->addFlash('success', 'Exhibit deleted successfully!');

        return $this->redirectToRoute('exhibits_index');
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
                case 'creationYear':
                    $this->applyYearFilter($qb, $value);
                    break;
                    
                case 'exhibition':
                    $this->applyExhibitionFilter($qb, $value);
                    break;
                case 'date':
                    $this->applyDateFilter($qb, $value);
                    
                default:
                    $qb->andWhere($qb->expr()->like("e.$field", ":$field"))
                       ->setParameter($field, '%'.$value.'%');
            }
        }
    }

    private function applyYearFilter(QueryBuilder $qb, string $value):void{

        $operator = '=';
        $yearValue = $value;
        
        if (preg_match('/^(>|<|>=|<=|=)?\s*(\d+)$/', $value, $matches)) {
            $operator = $matches[1] ?: '=';
            $yearValue = (int)$matches[2];
        }
        
        $qb->andWhere("e.creationYear {$operator} :creationYear")
        ->setParameter('creationYear', $yearValue);
    }

    private function applyExhibitionFilter(QueryBuilder $qb, $value): void
    {
        $qb->join('e.exhibition', 'exh')
            ->andWhere('exh.name LIKE :exhibitionName')
            ->setParameter('exhibitionName', '%'.$value.'%');
    }

    private function applyDateFilter(QueryBuilder $qb, $value): void
    {
            $date = new \DateTime($value);
            $qb->andWhere("e.date = :date")
            ->setParameter('date', $date);
    }
}
