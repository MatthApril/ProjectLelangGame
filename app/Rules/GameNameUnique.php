<?php

namespace App\Rules;

use App\Models\Game;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class GameNameUnique implements ValidationRule
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
         $query = Game::whereNull('deleted_at')->where('game_name', $value);

        if ($this->ignoreId) {
            $query->where('game_id', '!=', $this->ignoreId);
        }

        if ($query->exists()) {
            $fail('Nama game sudah digunakan',null);
        }
    }
}
