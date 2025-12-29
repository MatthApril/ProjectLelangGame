<?php

namespace App\Rules;

use App\Models\Shop;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ShopNameExist implements ValidationRule
{
    protected $ignoreShopId;
    public function __construct($ignoreShopId = null)
    {
        $this->ignoreShopId = $ignoreShopId;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $query = Shop::whereNull('deleted_at')->where('shop_name', $value);
        if ($this->ignoreShopId) {
            $query->where('shop_id', '!=', $this->ignoreShopId);
        }

        if ($query->exists()) {
            $fail('Nama toko sudah digunakan',null);
        }
    }
}
