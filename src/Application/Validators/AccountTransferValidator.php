<?php


namespace App\Application\Validators;

/**
 * Class AccountTransferValidator
 *
 * @package App\Application\Validators
 */
class AccountTransferValidator
{
    /**
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public function validate(array $data)
    {
        if (empty($data["origin"])) {
            throw new \Exception("Parametro 'origin' nao definido.");
        }

        if (empty($data["destination"])) {
            throw new \Exception("Parametro 'destination' nao definido.");
        }

        if (empty($data["amount"])) {
            throw new \Exception("Parametro 'amount' nao definido.");
        }

        if (!is_numeric($data["amount"])) {
            throw new \Exception("Parametro 'amount' deve ser numerico.");
        }

        if (($data["amount"] <= 0)) {
            throw new \Exception("Parametro 'amount' deve ser maior que 0.");
        }

        return true;
    }
}