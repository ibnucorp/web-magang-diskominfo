<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tgl_mulai',
        'tgl_selesai',
        'komentar',
        'status_pendaftaran',
        'surat_pengantar',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
