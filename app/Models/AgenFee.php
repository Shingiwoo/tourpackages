<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgenFee extends Model
{

    use HasFactory;

    protected $fillable = ['price'];

    public static function defaultFee()
    {
        // Retrieve the default fee.
        // You can adjust the logic based on your needs.
        // For example:

        // 1. Get the first record:
        $defaultFee = self::first();

        // 2. Get the fee directly (if you have a dedicated column for default fee):
        // $defaultFee = self::where('is_default', 1)->first()->price;

        return $defaultFee ? $defaultFee->price : 0;
    }

}
