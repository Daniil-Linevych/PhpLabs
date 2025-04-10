<?php

namespace App\Controller;

use App\Entity\Staff;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\StaffType;
use App\Repository\ExhibitionRepository;

#[Route('/staff', name: 'staff_')]
final class StaffController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'index', methods:['GET'])]
    public function index(): Response
    {
        $staff = $this->entityManager->getRepository(Staff::class)->findAll();

        return $this->render('staff/index.html.twig', [
            'staff' => $staff,
        ]);
    }

    #[Route('/create', name:'create', methods:['GET', 'POST'])]
    public function create(Request $request, ExhibitionRepository $exhibitionRepository): Response
    {
        //dd($exhibitionRepository->findAll());
        $staff_member = new Staff();
        $form = $this->createForm(StaffType::class, $staff_member, [
            'exhibitions' => $exhibitionRepository->findAll()
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $this->entityManager->persist($staff_member);
            $this->entityManager->flush();

            $this->addFlash('success', 'Staff member created successfully!');
            
            return $this->redirectToRoute('staff_index');
        }

        return $this->render('staff/create.html.twig', [
            'form' => $form->createView()
        ]);
    }   

    #[Route('/{id}', name:'show', methods:['GET'])]
    public function show(Staff $staff_member): Response{

        return $this->render('staff/show.html.twig', [
            'staff_member' => $staff_member,
        ]);
    }

    #[Route('/{id}/update', name:'update', methods:['GET', 'POST'])]
    public function update(Request $request, Staff $staff, ExhibitionRepository $exhibitionRepository):Response {

        $form = $this->createForm(StaffType::class, $staff, [
            'exhibitions' => $exhibitionRepository->findAll()
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $this->entityManager->persist($staff);
            $this->entityManager->flush();

            $this->addFlash('success', 'Staff member updated successfully!');
            
            return $this->redirectToRoute('staff_index');
        }

        return $this->render('staff/update.html.twig', [
            'form' => $form->createView(),
            'staff' => $staff,
        ]);
    }

    #[Route('/{id}/delete', name:'delete', methods:['POST'])]
    public function delete(Request $request, Staff $staff_member):Response {

        $submittedToken = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete'.$staff_member->getId(), $submittedToken)) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        $this->entityManager->remove($staff_member);
        $this->entityManager->flush();

        $this->addFlash('success', 'Staff member deleted successfully!');

        return $this->redirectToRoute('staff_index');
    }
}
