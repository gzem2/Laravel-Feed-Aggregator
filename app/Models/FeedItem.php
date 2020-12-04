<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedItem extends Model
{
    use HasFactory;

    public $timestamps = false;
    const CREATED_AT = 'pubDate';

    protected $fillable = [
        'feed_id',
        'title',
        'link',
        'description',
        'pubDate',
        'source',
        'source_url'
    ];
}
