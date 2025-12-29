<?php

namespace App\Rules;

use App\Models\Category;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CategoryNameUnique implements ValidationRule
{
    protected $ignoreId;

    public function __construct($ignoreId = null)
    {
        $this->ignoreId = $ignoreId;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $query = Category::whereNull('deleted_at')
            ->where('category_name', $value);

        if ($this->ignoreId) {
            $query->where('category_id', '!=', $this->ignoreId);
        }

        if ($query->exists()) {
            $fail('Nama kategori sudah digunakan',null);
        }
    }
}
