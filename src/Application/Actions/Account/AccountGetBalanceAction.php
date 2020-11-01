<?php


namespace App\Application\Actions\Account;


use App\Application\Actions\Action;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class AccountGetBalanceAction extends AccountAction
{

    /**
     * @return Response
     */
    protected function action(): Response
    {
        $id = $this->request->getQueryParams()["account_id"];

        $account = $this->accountRepository->find($id);

        if ($account == null) {
            return $this->respondNotFound();
        }

        return $this->respondOk($account->getBalance());
    }
}