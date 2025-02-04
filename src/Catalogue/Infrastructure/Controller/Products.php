<?php

declare(strict_types=1);

namespace App\Catalogue\Infrastructure\Controller;

use App\Catalogue\Infrastructure\Persistence\Repository\ProductsRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/products', methods: ['GET'])]
class Products
{
    public function __construct(private readonly ProductsRepository $productsRepository)
    {
    }

    public function __invoke()
    {
        $this->productsRepository->pretendItsCreatedCorrectly();

        $products = $this->productsRepository->findBy([], null, 10);
        dd($products);

        return new JsonResponse($products);
    }
}
