<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentSignature extends Model
{
    use HasFactory;

    protected $fillable = [
        'guest_id',
        'dosen_id',
        'document_path',
        'signed_document_path',
        'original_filename',
        'status',
        'notes',
        'signed_at',
        'qr_page',
        'qr_x',
        'qr_y',
        'qr_canvas_width',
        'qr_canvas_height'
    ];

    protected $casts = [
        'signed_at' => 'datetime',
    ];

    public function guest()
    {
        return $this->belongsTo(User::class, 'guest_id');
    }

    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }
} 