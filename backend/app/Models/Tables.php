<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tables extends Model
{
    /** @use HasFactory<\Database\Factories\TablesFactory> */
    use HasFactory;

    protected $fillable = [ 'identification', 'places' ];

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
