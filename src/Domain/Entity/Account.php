<?php


namespace App\Domain\Entity;

use JsonSerializable;

class Account implements JsonSerializable
{
    /**
     * @var
     */
    private $id;

    /**
     * @var
     */
    private $balance;

    public function __construct(?int $id, float $balance)
    {
        $this->id = $id;
        $this->balance = $balance;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getBalance()
    {
        return $this->balance;
    }

    public function deposit(float $value)
    {
        $this->balance += $value;
    }

    public function withdraw(float $value)
    {
        $this->balance -= $value;
    }

    public function transfer(float $value, Account $receiverAccount)
    {
        $this->withdraw($value);
        $receiverAccount->deposit($value);

        return $receiverAccount;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            "id"      => $this->id,
            "balance" => $this->balance
        ];
    }
}