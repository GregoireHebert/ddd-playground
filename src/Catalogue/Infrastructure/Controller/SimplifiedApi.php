<?php

declare(strict_types=1);

namespace App\Catalogue\Infrastructure\Controller;

use App\Catalogue\Application\SimplifiedEntityService;
use App\Catalogue\Infrastructure\Persistence\Repository\SimplifiedEntityRepository;
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
class SimplifiedApi
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly QueryBus $queryBus,
        private readonly SimplifiedEntityRepository $repository,
    )
    {
    }

    #[Route("/classic-service-cqs/simplified", name: 'create_entity', methods: ["POST"])]
    public function newSmartphone(Request $request): Response
    {
        $entityId = $this->repository->getNext();

        $this->commandBus->sendWithRouting(
            SimplifiedEntityService::ADD_ENTITY_TO_CATALOGUE,
            // Pretend it's a nice shiny validated DTO coming from API Platform Resource
            $request->request->all()  + ["id" => $entityId]
        );

        $domainEntity = $this->queryBus->sendWithRouting(
            SimplifiedEntityService::VIEW_ENTITY_FROM_CATALOGUE,
            ["id" => $entityId]
        );

        // Pretend it's still a nice shiny DTO coming from API Platform Resource
        return new JsonResponse($domainEntity, status: 201);
    }

    #[Route("/classic-service-cqs/simplified/{id}", name: 'read_entity', methods: ["GET"])]
    public function viewEntity(string $id): Response
    {
        $domainEntity = $this->queryBus->sendWithRouting(
            SimplifiedEntityService::VIEW_ENTITY_FROM_CATALOGUE,
            ["id" => UuidV7::fromString($id)]
        );

        // Pretend it's still a nice shiny DTO coming from API Platform Resource
        return new JsonResponse($domainEntity, status: 200);
    }

    #[Route("/classic-service-cqs/smartphones/{id}/rpc-toggle", name: 'toggle_smartphone', methods: ["POST"])]
    public function toggleSmartphone(string $id): Response
    {
        $this->commandBus->sendWithRouting(
            SimplifiedEntityService::TOGGLE_ENTITY,
            ["id" => $id]
        );

        $domainEntity = $this->queryBus->sendWithRouting(
            SimplifiedEntityService::VIEW_ENTITY_FROM_CATALOGUE,
            ["id" => $id]
        );

        // Pretend it's still a nice shiny DTO coming from API Platform Resource
        return new JsonResponse($domainEntity, status: 200);
    }
}
