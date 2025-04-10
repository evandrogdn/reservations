<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    /** @use HasFactory<\Database\Factories\ReservationFactory> */
    use HasFactory;

    protected $fillable = [
        'client_name',
        'table_id',
        'phone_contact',
        'email_contact',
        'guests',
        'reservation_start',
        'reservation_end',
    ];

    public function tables() :BelongsTo
    {
        return $this->belongsTo(Tables::class);
    }
}
