<?php

namespace App\Controller;

use App\Entity\Staff;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\StaffType;
use App\Repository\ExhibitionRepository;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/staff', name: 'staff_')]
final class StaffController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'index', methods:['GET'])]
    public function index(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $staff = $this->entityManager->getRepository(Staff::class)->findAll();

        $adapter = new ArrayAdapter($staff);
        $pager = new Pagerfanta($adapter);

        $pager->setMaxPerPage($request->query->get('perPage', 3));
        $pager->setCurrentPage($request->query->get('page', 1));

        return $this->render('staff/index.html.twig', [
            'staff' => $staff,
            'pager'=>$pager,
        ]);
    }

    #[Route('/create', name:'create', methods:['GET', 'POST'])]
    public function create(Request $request, ExhibitionRepository $exhibitionRepository,   UserPasswordHasherInterface $passwordHasher,): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $staff_member = new Staff();
        $form = $this->createForm(StaffType::class, $staff_member, [
            'exhibitions' => $exhibitionRepository->findAll()
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $user = new User();
            $user->setFullName($form->get('fullName')->getData());
            
            $email = strtolower("worker".uniqid()."@gmail.com");
            if ($this->entityManager->getRepository(User::class)->findOneBy(['email' => $email])) {
                $email = strtolower("worker".uniqid()."@gmail.com");
            }
            
            $user->setEmail($email);
            $user->setPassword(
                $passwordHasher->hashPassword($user, '123123')
            );
            $user->setRoles(['ROLE_USER', 'ROLE_WORKER']);

            $this->entityManager->persist($user);

            $staff_member->setUser($user);

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

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('staff/show.html.twig', [
            'staff_member' => $staff_member,
        ]);
    }

    #[Route('/{id}/update', name:'update', methods:['GET', 'POST'])]
    public function update(Request $request, Staff $staff, ExhibitionRepository $exhibitionRepository):Response {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

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

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

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
