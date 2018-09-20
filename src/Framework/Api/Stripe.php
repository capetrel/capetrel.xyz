<?php
namespace App\Framework\Api;

use Stripe\Card;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Token;

class Stripe
{

    /**
     * Stripe constructor.
     * @param string $token
     */
    public function __construct(string $token)
    {
        \Stripe\Stripe::setApiKey($token);
    }

    public function getCardFromToken(string $token)
    {
        return Token::retrieve($token)->card;
    }

    public function getCustomer($customerId): Customer
    {
        return Customer::retrieve($customerId);
    }

    public function createCustomer(array $params): Customer
    {
        return Customer::create($params);
    }

    public function createCardForCustomer(Customer $customer, string $token): Card
    {
        return $customer->sources->create(['source' => $token]);
    }

    public function createCharge(array $params): Charge
    {
        return Charge::create($params);
    }
}
