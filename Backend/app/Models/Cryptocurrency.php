<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

<<<<<<< HEAD
class Cryptocurrency extends Model
{
=======
class Cryptocurrency extends Model {
>>>>>>> 2460bd3c8595dfd492a823488ccaeed92223e8b9
    use HasFactory;

    // Fillable fields for mass assignment
    protected $fillable = ['name', 'logo', 'currentPrice']; // Use 'logo' and 'current_price' consistently

    // Relationship with price history
    public function priceHistory()
    {
        return $this->hasMany(PriceHistory::class); // Assuming there's a 'PriceHistory' model
    }
}
