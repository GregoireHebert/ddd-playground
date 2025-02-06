<?php

declare(strict_types=1);

namespace App\Catalogue\Representation\Controller;

use App\Catalogue\Application\ClassicSmartphoneService;
use App\Catalogue\Infrastructure\Persistence\Repository\SmartphoneRepository;
use Ecotone\Modelling\CommandBus;
use Ecotone\Modelling\QueryBus;
use Ramsey\Uuid\Rfc4122\UuidV7;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/catalogue', name: 'catalogue_')]
class CatalogueApi
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly QueryBus $queryBus,
        private readonly SmartphoneRepository $repository,
    )
    {
    }

    #[Route("/classic-service-cqs/smartphones", name: 'create_smartphone', methods: ["POST"])]
    public function newSmartphone(Request $request): Response
    {
        $smartphoneId = $this->repository->getNext();

        $this->commandBus->sendWithRouting(
            ClassicSmartphoneService::ADD_SMARTPHONE_TO_CATALOGUE,
            // Pretend it's a nice shiny validated DTO coming from API Platform Resource
            $request->request->all()  + ["id" => $smartphoneId]
        );

        $smartPhone = $this->queryBus->sendWithRouting(
            ClassicSmartphoneService::VIEW_SMARTPHONE_FROM_CATALOGUE,
            ["id" => $smartphoneId]
        );

        // Pretend it's still a nice shiny DTO coming from API Platform Resource
        return new JsonResponse($smartPhone, status: 201);
    }

    #[Route("/classic-service-cqs/smartphones/{id}", name: 'read_smartphone', methods: ["GET"])]
    public function viewSmartphone(string $id): Response
    {
        $smartPhone = $this->queryBus->sendWithRouting(
            ClassicSmartphoneService::VIEW_SMARTPHONE_FROM_CATALOGUE,
            ["id" => UuidV7::fromString($id)]
        );

        // Pretend it's still a nice shiny DTO coming from API Platform Resource
        return new JsonResponse($smartPhone, status: 200);
    }

    #[Route("/classic-service-cqs/smartphones/{id}/rpc-toggle", name: 'toggle_smartphone', methods: ["POST"])]
    public function toggleSmartphone(string $id): Response
    {
        $this->commandBus->sendWithRouting(
            ClassicSmartphoneService::TOGGLE_SMARTPHONE,
            ["id" => $id]
        );

        $smartPhone = $this->queryBus->sendWithRouting(
            ClassicSmartphoneService::VIEW_SMARTPHONE_FROM_CATALOGUE,
            ["id" => $id]
        );

        // Pretend it's still a nice shiny DTO coming from API Platform Resource
        return new JsonResponse($smartPhone, status: 200);
    }
}
