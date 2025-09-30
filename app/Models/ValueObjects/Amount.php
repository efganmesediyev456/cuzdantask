<?php

namespace App\Models\ValueObjects;

class Amount
{
    public float $value;
    public string $currency;

    public function __construct(float $value, string $currency = 'EUR')
    {
        if (!is_numeric($value)) {
            throw new \InvalidArgumentException("Invalid amount");
        }
        $this->value = (float)$value;
        $this->currency = $currency;
    }

    public function withValue(float $v): self
    {
        return new self($v, $this->currency);
    }

    public function rounded(int $decimals = 2): self
    {
        return $this->withValue((float) round($this->value, $decimals));
    }
}
