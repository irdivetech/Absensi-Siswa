<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'responder_id',
        'message',
        'is_auto_reply',
    ];

    protected $casts = [
        'is_auto_reply' => 'boolean',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function responder()
    {
        return $this->belongsTo(User::class, 'responder_id', 'id_user');
    }
}
