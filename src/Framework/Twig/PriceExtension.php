<?php
namespace App\Framework\Twig;

class PriceExtension extends \Twig_Extension
{

    /**
     * @var string
     */
    private $currency;

    public function __construct(string $currency = '€')
    {
        $this->currency = $currency;
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('price_format', [$this, 'priceFormat'])
        ];
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('taxed_price', [$this, 'taxedPrice']),
            new \Twig_SimpleFunction('tax_amount', [$this, 'taxAmount'])
        ];
    }

    public function priceFormat(float $price, ?string $currency = null)
    {
        return number_format($price, 2, ',', ' ') . ' ' . ($currency ?: $this->currency);
    }

    public function taxedPrice(float $price, ?float $rate): float
    {
        return $price + $this->taxAmount($price, $rate);
    }

    public function taxAmount(float $price, ?float $rate): float
    {
        if ($rate === null) {
            return 0;
        }
        return ($price * $rate) / 100;
    }
}
