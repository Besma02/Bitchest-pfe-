<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

<<<<<<< HEAD
class Cryptocurrency extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'logo', 'current_price'];
}
=======
class Cryptocurrency extends Model {
    use HasFactory;

    protected $fillable = ['name', 'image', 'currentPrice'];

    public function priceHistory() {
        return $this->hasMany(PriceHistory::class);
    }
}
>>>>>>> bc8b66ebfbcf30c4d5128882abe73845ea7a2025
