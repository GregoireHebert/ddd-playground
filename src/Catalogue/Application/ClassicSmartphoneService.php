<?php

declare(strict_types=1);

namespace App\Catalogue\Application;

use App\Catalogue\Domain\Command\AddSmartphone;
use App\Catalogue\Domain\Query\ViewSmartphone;
use App\Catalogue\Domain\Models\Smartphone;
use App\Catalogue\Infrastructure\Persistence\Repository\SmartphoneRepository;
use Ecotone\Modelling\Attribute\CommandHandler;
use Ecotone\Modelling\Attribute\QueryHandler;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class ClassicSmartphoneService
{
    const ADD_SMARTPHONE_TO_CATALOGUE = "catalogue.addSmartphone";
    const VIEW_SMARTPHONE_FROM_CATALOGUE = "catalogue.getSmartphone";
    const TOGGLE_SMARTPHONE = "catalogue.toggleSmartphone";

    public function __construct(private SmartphoneRepository $repository)
    {
    }

    #[CommandHandler(self::ADD_SMARTPHONE_TO_CATALOGUE)]
    public function addToCatalogue(AddSmartphone $command): void
    {
        $smartphone = Smartphone::fromClassicApplication(
            $command->id ?: Uuid::uuid4()->toString(),
            $command->label
        );

        $this->repository->save($smartphone);
    }

    #[QueryHandler(self::VIEW_SMARTPHONE_FROM_CATALOGUE)]
    public function viewSmartphone(ViewSmartphone $query): Smartphone
    {
        if (null === $smartphone = $this->repository->get($query->id)) {
            // pretend it's handled nicely
            throw new NotFoundHttpException();
        }

        return $smartphone;
    }

    #[CommandHandler(self::TOGGLE_SMARTPHONE)]
    public function toggleSmartphone(ViewSmartphone $query): Smartphone
    {
        if (null === $smartphone = $this->repository->get($query->id)) {
            // pretend it's handled nicely
            throw new NotFoundHttpException();
        }

        $smartphone->toggle();
        $this->repository->flush();

        return $smartphone;
    }
}
