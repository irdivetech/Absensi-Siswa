<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'reporter_id',
        'operator_id',
        'subject',
        'description',
        'category',
        'priority',
        'status',
        'needs_admin',
        'first_response_at',
        'resolved_at'
    ];

    protected $casts = [
        'first_response_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id', 'id_user');
    }

    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id', 'id_user');
    }

    public function responses()
    {
        return $this->hasMany(TicketResponse::class);
    }

    public function rating()
    {
        return $this->hasOne(SatisfactionRating::class);
    }
}
