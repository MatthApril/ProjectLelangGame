<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckStock implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    protected int $stock;

    public function __construct(int $stock)
    {
        $this->stock = $stock;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value < 1) {
            $fail('Jumlah harus minimal 1.', null);
        }

        if ($value > $this->stock) {
            $fail('Jumlah melebihi stok yang tersedia.', null);
        }
    }
}
