<?php
/**
 *  * Dawid Bednarz( dawid@bednarz.pro )
 * Read README.md file for more information and licence uses
 */
declare(strict_types=1);

namespace DawBed\OperationLimitBundle\Service;

use DawBed\OperationLimitBundle\Repository\OperationLimitRepository;
use DawBed\PHPOperationLimit\Model\AbstractModel;
use DawBed\PHPOperationLimit\Model\CreateModel;
use DawBed\PHPOperationLimit\Model\Criteria;
use DawBed\PHPOperationLimit\Model\UpdateModel;
use Doctrine\ORM\EntityManagerInterface;

class OperationLimitService
{
    private $entityManager;
    private $repository;
    private $entityService;

    function __construct(
        EntityManagerInterface $entityManager,
        OperationLimitRepository $repository,
        EntityService $entityService
    )
    {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
        $this->entityService = $entityService;
    }

    public function prepareModel(Criteria $criteria): AbstractModel
    {
        $existedLimit = $this->repository->findOneByName($criteria->getName());

        if (is_null($existedLimit)) {
            $limit = new $this->entityService->OperationLimit;
        } else {
            $limit = $existedLimit;
        }
        $limit->setName($criteria->getName())
            ->setAllowed($criteria->getAllowed())
            ->setForTime($criteria->getForTime())
            ->setOnTime($criteria->getOnTime())
            ->setContext($criteria->getContext());

        if (!is_null($limit->getId())) {
            return new UpdateModel($limit);
        }
        return new CreateModel($limit);
    }

    public function makeByModel(AbstractModel $model): EntityManagerInterface
    {
        $this->entityManager->persist($model->make());

        return $this->entityManager;
    }

    public function makeByCriteria(Criteria $criteria): EntityManagerInterface
    {
        return $this->makeByModel($this->prepareModel($criteria));
    }
}