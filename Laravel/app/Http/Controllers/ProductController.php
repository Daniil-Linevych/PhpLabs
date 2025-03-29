<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProductController extends Controller
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

    public function index(): View
    {
        return view('products.index', ['products' => self::$products]);
    }

    public function create(): View
    {
        return view('products.create');
    }

    public function new(Request $request): RedirectResponse
    {
        $newProduct = [
            'id' => self::$nextId,
            'name' => $request->input('name'),
            'price' => (float)$request->input('price'),
            'description' => $request->input('description')
        ];

        self::$products[self::$nextId] = $newProduct;
        self::$nextId++;

        return redirect()->route('products.index');
    }

    public function show(int $id): View
    {
        if (!isset(self::$products[$id])) {
            abort(404, 'Product not found');
        }

        return view('products.show', ['product' => self::$products[$id]]);
    }

    public function edit(int $id): View
    {
        if (!isset(self::$products[$id])) {
            abort(404, 'Product not found');
        }

        return view('products.edit', ['product' => self::$products[$id]]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        if (!isset(self::$products[$id])) {
            abort(404, 'Product not found');
        }

        self::$products[$id] = [
            'id' => $id,
            'name' => $request->input('name'),
            'price' => (float)$request->input('price'),
            'description' => $request->input('description')
        ];

        return redirect()->route('products.index');
    }

    public function delete(int $id): RedirectResponse
    {
        if (!isset(self::$products[$id])) {
            abort(404, 'Product not found');
        }

        unset(self::$products[$id]);

        return redirect()->route('products.index');
    }
}