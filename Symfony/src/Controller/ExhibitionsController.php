<?php

namespace App\Controller;

use App\Entity\Exhibition;
use App\Entity\Staff;
use App\Form\ExhibitionType;
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

        if ($this->isGranted('ROLE_WORKER')){
            $user = $this->getUser();

            $staff = $this->entityManager->getRepository(Staff::class)->findOneBy(['user'=>$user]);
            $exhibitions = $staff->getExhibitions()->toArray();
        } else {
            $exhibitions = $this->entityManager->getRepository(Exhibition::class)->findAll();
        } 

        $adapter = new ArrayAdapter($exhibitions);
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
}
