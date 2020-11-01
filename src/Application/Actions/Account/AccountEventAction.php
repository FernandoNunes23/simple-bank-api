<?php


namespace App\Application\Actions\Account;

use App\Domain\Entity\Factory\AccountEntityFactory;
use App\Domain\Persister\AccountPersister;
use App\Domain\Repository\AccountRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;

class AccountEventAction extends AccountAction
{

    /**
     * @var AccountEntityFactory
     */
    private $accountEntityFactory;

    public function __construct(
        LoggerInterface $logger,
        AccountRepository $accountRepository,
        AccountPersister $accountPersister,
        AccountEntityFactory $accountEntityFactory
    )
    {
        parent::__construct($logger, $accountRepository, $accountPersister);
        $this->accountEntityFactory = $accountEntityFactory;
    }

    /**
     * @return Response
     */
    protected function action(): Response
    {
        $data = $this->request->getParsedBody();

        if ($data["type"] == "deposit") {
            return $this->deposit($data);
        }

        if ($data["type"] == "withdraw") {
            return $this->withdraw($data);
        }

        if ($data["type"] == "transfer") {
            return $this->transfer($data);
        }
    }

    private function transfer(array $data): Response
    {
        $originId = $data["origin"];
        $value = $data["amount"];
        $destinationId = $data["destination"];

        $originAccount = $this->accountRepository->find($originId);
        $destinationAccount = $this->accountRepository->find($destinationId);

        if ($originAccount == null) {
            return $this->respondNotFound();
        }

        if ($destinationAccount == null) {
            return $this->respondNotFound();
        }

        $destinationAccount = $originAccount->transfer($value, $destinationAccount);

        $this->accountPersister->persist($originAccount);
        $this->accountPersister->persist($destinationAccount);

        $responseData["origin"] = $originAccount->jsonSerialize();
        $responseData["destination"] = $destinationAccount->jsonSerialize();

        return $this->respondWithData($responseData,201);
    }

    private function withdraw(array $data): Response
    {
        $id = $data["origin"];
        $value = $data["amount"];

        $account = $this->accountRepository->find($id);

        if ($account == null) {
            return $this->respondNotFound();
        }

        $account->withdraw($value);
        $account = $this->accountPersister->persist($account);

        $responseData["origin"] = $account->jsonSerialize();

        return $this->respondWithData($responseData, 201);
    }

    private function deposit(array $data): Response
    {
        $id = $data["destination"];
        $value = $data["amount"];

        $account = $this->accountRepository->find($id);

        if ($account == null) {
            $account = $this->accountEntityFactory->createAccount($id, $value);
        } else {
            $account->deposit($value);
        }

        $account = $this->accountPersister->persist($account);

        $responseData["destination"] = $account->jsonSerialize();

        return $this->respondWithData($responseData, 201);
    }
}