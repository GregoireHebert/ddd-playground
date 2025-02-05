<?php

declare(strict_types=1);

namespace App\Catalogue\Application;

use App\Catalogue\Application\Command\AddEntity;
use App\Catalogue\Application\Command\ToggleEntity;
use App\Catalogue\Application\Query\ViewEntity;
use App\Catalogue\Domain\Models\SimplifiedEntity;
use App\Catalogue\Infrastructure\Persistence\Repository\SimplifiedEntityRepository;
use Ecotone\Modelling\Attribute\CommandHandler;
use Ecotone\Modelling\Attribute\QueryHandler;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class SimplifiedEntityService
{
    const ADD_ENTITY_TO_CATALOGUE = "catalogue.addSEntity";
    const VIEW_ENTITY_FROM_CATALOGUE = "catalogue.getEntity";
    const TOGGLE_ENTITY = "catalogue.toggleEntity";

    public function __construct(private SimplifiedEntityRepository $repository)
    {
    }

    #[CommandHandler(self::ADD_ENTITY_TO_CATALOGUE)]
    public function addToCatalogue(AddEntity $command): SimplifiedEntity
    {
        return SimplifiedEntity::fromClassicApplication(
            $command->id ?: Uuid::uuid4()->toString(),
            $command->label
        );
    }

    #[QueryHandler(self::VIEW_ENTITY_FROM_CATALOGUE)]
    public function viewEntity(ViewEntity $query): SimplifiedEntity
    {
        if (null === $domainEntity = $this->repository->get($query->id)) {
            // pretend it's handled nicely
            throw new NotFoundHttpException();
        }

        return $domainEntity;
    }

    #[CommandHandler(self::TOGGLE_ENTITY)]
    public function toggleEntity(ToggleEntity $command): void
    {
        if (null === $domainEntity = $this->repository->get($command->id)) {
            // pretend it's handled nicely
            throw new NotFoundHttpException();
        }

        $domainEntity->toggle();
    }
}
