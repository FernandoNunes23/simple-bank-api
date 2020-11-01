<?php


namespace App\Application\Actions\Account;


use App\Domain\Entity\Account;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class AccountEventAction extends AccountAction
{

    const DEPOSIT_RESPONSE_WRAPPER = "destination";
    const WITHDRAW_RESPONSE_WRAPPER = "origin";
    const TRANSFER_RESPONSE_WRAPPER = "origin";

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

        if ($data["type"] == "withdraw") {
            return $this->transfer($data);
        }
    }

    private function transfer(array $data): Response
    {
        return $this->respondWithData(null,200);
    }

    private function withdraw(array $data): Response
    {
        $id = $data["origin"];
        $value = $data["amount"];

        $account = $this->accountRepository->find($id);

        if ($account == null) {
            return $this->respondWithData(null,404);
        }

        try {
            $account->withdraw($value);
            $account = $this->accountPersister->persist($account);

            return $this->respondWithData($account, 201, AccountEventAction::WITHDRAW_RESPONSE_WRAPPER);
        } catch (\Exception $e) {
            return $this->respondWithData(null,500);
        }
    }

    private function deposit(array $data): Response
    {
        $id = $data["destination"];
        $value = $data["amount"];

        $account = $this->accountRepository->find($id);

        if ($account == null) {
            // TODO: Criar AccountFactory
            $account = new Account($id, $value);
        } else {
            $account->deposit($value);
        }

        $account = $this->accountPersister->persist($account);

        return $this->respondWithData($account, 201, AccountEventAction::DEPOSIT_RESPONSE_WRAPPER);
    }
}