<?php

declare(strict_types=1);

namespace App\Catalogue\Infrastructure\Controller;

use App\Catalogue\Infrastructure\Persistence\Repository\PhoneRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/phones', methods: ['GET'])]
class Phones
{
//    public function __construct(QueryBus $queryBus)
//    {
//        $this->queryBus = $queryBus;
//    }


    public function __construct(private readonly PhoneRepository $phoneRepository)
    {
    }

    public function __invoke()
    {
        //$this->productsRepository->pretendItsCreatedCorrectly();

        $phones = $this->phoneRepository->findBy([], null, 10000);
        dd($phones);

        return new JsonResponse($phones);
    }
}
