<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cryptocurrency extends Model {
    use HasFactory;

    // Fillable fields for mass assignment
    protected $fillable = ['name', 'logo', 'currentPrice']; // Use 'logo' and 'current_price' consistently

    // Relationship with price history
    public function priceHistory()
    {
        return $this->hasMany(PriceHistory::class); // Assuming there's a 'PriceHistory' model
    }
}
