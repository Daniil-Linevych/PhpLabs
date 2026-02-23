<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/product')]
final class ProductController extends AbstractController
{
    private static array $products = [];
    private static int $nextId = 1;

    public function __construct()
    {
        if (empty(self::$products)) {
            self::$products = [
                1 => [
                    'id' => 1,
                    'name' => 'Laptop',
                    'price' => 999.99,
                    'description' => 'High-performance laptop'
                ],
                2 => [
                    'id' => 2,
                    'name' => 'Smartphone',
                    'price' => 599.99,
                    'description' => 'Latest model'
                ],
                3 => [
                    'id' => 3,
                    'name' => 'Headphones',
                    'price' => 199.99,
                    'description' => 'Wireless headphones'
                ],
            ];
            self::$nextId = 4;
        }
    }

    #[Route('/', name: 'product_index', methods:['GET'])]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => self::$products,
        ]);
    }

    #[Route('/new', name:'product_new', methods:['GET', 'POST'])]
    public function new(Request $request): Response
    {
        if ($request->isMethod('POST')){
            $data = $request->request->all();

            $newProduct = [
                'id' => self::$nextId,
                'name' => $data['name'],
                'price' => (float)$data['price'],
                'description' => $data['description']
            ];

            self::$products[self::$nextId] = $newProduct;
            self::$nextId++;
            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/new.html.twig', [
            'product' => null,
        ]);
        

    }

    #[Route('/{id}', name: 'product_show', methods:['GET'])]
    public function show(int $id): Response{

        if (!isset(self::$products[$id])){
            throw $this->createNotFoundException('Product not found!');
        }

        return $this->render('product/show.html.twig', [
            'product' => self::$products[$id],
        ]);
    }

    #[Route('/{id}/edit', name: 'product_edit', methods:['GET','POST'])]
    public function edit(Request $request, int $id): Response {

        if (!isset(self::$products[$id])){
            throw $this->createNotFoundException('Product not found!');
        }

        if ($request->isMethod('POST')){
            $data = $request->request->all();

            $newProduct = [
                'id' => self::$nextId,
                'name' => $data['name'],
                'price' => (float)$data['price'],
                'description' => $data['description']
            ];

            self::$products[self::$nextId] = $newProduct;
            self::$nextId++;

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/edit.html.twig', [
            'product' => self::$products[$id],
        ]);
    }

    #[Route('/{id}/delete', name:'product_delete', methods:['POST'])]
    public function delete(int $id): Response {
        
        if (!isset(self::$products[$id])) {
            throw $this->createNotFoundException('Product not found');
        }

        unset(self::$products[$id]);

        return $this->redirectToRoute('product_index');
    }
}
