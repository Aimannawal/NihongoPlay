<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vocabulary extends Model
{
    use HasFactory;

    protected $fillable = [
        'japanese_word',
        'romaji',
        'meaning',
        'category',
        'audio_path',
        'card_image',
        'barcode_data'
    ];
}
