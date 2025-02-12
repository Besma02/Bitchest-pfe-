<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

<<<<<<< HEAD
class Cryptocurrency extends Model {
    use HasFactory;

    protected $fillable = ['name', 'logo', 'currentPrice'];

    public function priceHistory() {
        return $this->hasMany(PriceHistory::class);
    }
=======
class Cryptocurrency extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'logo', 'current_price'];
>>>>>>> bilel
}
