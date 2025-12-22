<?php

namespace App\Rules;

use App\Models\Shop;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ShopNameExist implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (Shop::where('shop_name', $value)->exists()) {
            $fail('Nama Toko sudah digunakan!', null);
        }
    }
}
