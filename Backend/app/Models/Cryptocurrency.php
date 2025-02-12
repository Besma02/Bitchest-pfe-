<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cryptocurrency extends Model {
    use HasFactory;

    protected $fillable = ['name', 'image', 'currentPrice'];

    public function priceHistory() {
        return $this->hasMany(PriceHistory::class);
    }
}