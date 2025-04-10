<?php

namespace App\Controller;

use App\Entity\Exhibit;
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
    public function index(): Response
    {
        $exhibits = $this->entityManager->getRepository(Exhibit::class)->findAll();

        return $this->render('exhibits/index.html.twig', [
            'exhibits' => $exhibits,
        ]);
    }

    #[Route('/create', name:'create', methods:['GET', 'POST'])]
    public function create(Request $request,  ExhibitionRepository $exhibitionRepository): Response
    {
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

        return $this->render('exhibits/show.html.twig', [
            'exhibit' => $exhibit,
        ]);
    }

    #[Route('/{id}/update', name:'update', methods:['GET', 'POST'])]
    public function update(Request $request, Exhibit $exhibit, ExhibitionRepository $exhibitionRepository):Response {

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

        $submittedToken = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete'.$exhibit->getId(), $submittedToken)) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        $this->entityManager->remove($exhibit);
        $this->entityManager->flush();

        $this->addFlash('success', 'Exhibit deleted successfully!');

        return $this->redirectToRoute('exhibits_index');
    }
}
