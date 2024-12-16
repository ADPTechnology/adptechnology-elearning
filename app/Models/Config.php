<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;

    protected $fillable = [
        'whatsapp_number',
        'whatsapp_message',
        'email',
        'address',
        'link_group_wts',
        'link_group_telegram',
        'facebook_link',
        'linkedin_link',
        'instagram_link',
        'youtube_link',
        'tik_tok_link',
        'logo_url',
        'background_url',
        'message_hover_whatsapp',
        'text_whatsapp',
        'privacy_policies',
        'terms_conditions',
        'data_deletion'
    ];

    protected $table = 'configs';
}
