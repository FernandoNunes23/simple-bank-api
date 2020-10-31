<?php


namespace App\Application\Actions\Account;


use App\Domain\DomainException\DomainRecordNotFoundException;
use App\Domain\Entity\Account;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class AccountDepositAction extends AccountAction
{

    protected function action(): Response
    {
        $data = $this->request->getParsedBody();

        $id = $data["destination"];
        $value = $data["amount"];

        $account = $this->accountRepository->find($id);

        //var_dump($account->getBalance());exit;

        if ($account == null) {
            $account = new Account($id, $value);
            var_dump('aqui');exit;
        } else {
            $account->deposit($value);
            //var_dump($account->getBalance());exit;
        }

        $account = $this->accountPersister->persist($account);

        return $this->respondWithData($account);
    }
}