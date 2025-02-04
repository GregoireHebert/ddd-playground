<?php

declare(strict_types=1);

namespace App\Catalogue\Infrastructure\Controller;

use App\Catalogue\Infrastructure\Persistence\Repository\ProductsContextDifferentRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/products-context-different', methods: ['GET'])]
class ProductsContextDifferent
{
    public function __construct(private readonly ProductsContextDifferentRepository $productsContextDifferentRepository)
    {
    }

    public function __invoke()
    {
        $this->productsContextDifferentRepository->pretendItsCreatedCorrectly();

        $products = $this->productsContextDifferentRepository->findBy([], null, 10);
        dd($products);

        return new JsonResponse($products);
    }
}
