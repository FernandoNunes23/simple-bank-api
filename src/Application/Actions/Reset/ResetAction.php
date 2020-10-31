<?php


namespace App\Application\Actions\Reset;


use App\Infrastructure\Persistence\CachePersistence;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ResetAction
{
    private $request;
    private $response;
    private $persistence;

    public function __invoke(Request $request, Response $response, $args): Response
    {
        $this->request = $request;
        $this->response = $response;

        $this->action();
    }

    private function action()
    {
        //$this->persistence->clearAll();
    }
}