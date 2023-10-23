<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Transcribe extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'file_name',
        'file_type',
        'file_lang',
        'file_duration',
        'file_size',
        'file_transcribe_text',
        'file_translation_lang',
        'file_translate_text',
        'file_realname',
    ];
}
