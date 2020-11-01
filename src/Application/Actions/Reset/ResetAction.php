<?php


namespace App\Application\Actions\Reset;


use App\Application\Actions\Action;
use App\Domain\DomainException\DomainRecordNotFoundException;
use App\Domain\Entity\Factory\AccountEntityFactory;
use App\Domain\Persister\AccountPersister;
use App\Infrastructure\Persistence\CachePersistence;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class ResetAction extends Action
{
    /**
     * @var CachePersistence
     */
    private $persistence;

    /**
     * @var AccountEntityFactory
     */
    private $accountEntityFactory;

    /**
     * @var AccountPersister
     */
    private $accountPersister;

    /**
     * ResetAction constructor.
     *
     * @param LoggerInterface $logger
     * @param CachePersistence $persistence
     * @param AccountEntityFactory $accountEntityFactory
     * @param AccountPersister $accountPersister
     */
    public function __construct(
        LoggerInterface $logger,
        CachePersistence $persistence,
        AccountEntityFactory $accountEntityFactory,
        AccountPersister $accountPersister
    )
    {
        parent::__construct($logger);

        $this->persistence          = $persistence;
        $this->accountEntityFactory = $accountEntityFactory;
        $this->accountPersister     = $accountPersister;
    }

    /**
     * @return Response
     */
    protected function action(): Response
    {
        $this->persistence->clearAll();

        $account = $this->accountEntityFactory->createAccount("300", 0);
        $this->accountPersister->persist($account);

        return $this->respondOk();
    }
}