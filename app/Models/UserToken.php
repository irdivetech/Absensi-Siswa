<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserToken extends Model
{
    protected $table = 'user_tokens';
    protected $fillable = [
        'id_siswa',
        'id_item',
        'status',
        'redemption_code',
        'id_absensi_used',
        'validated_by',
        'validated_at',
        'usage_note',
        'used_at',
        'purchased_at',
    ];

    protected $casts = [
        'used_at'      => 'datetime',
        'purchased_at' => 'datetime',
        'validated_at' => 'datetime',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    public function item()
    {
        return $this->belongsTo(FlexibilityItem::class, 'id_item');
    }

    public function absensiUsed()
    {
        return $this->belongsTo(Absensi::class, 'id_absensi_used', 'id_absensi');
    }

    public function validatedByGuru()
    {
        return $this->belongsTo(Guru::class, 'validated_by', 'id_guru');
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'AVAILABLE');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'PENDING');
    }
}
