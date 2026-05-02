<?php

namespace App\Models;

use App\Models\NewsView;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'news';

    protected $fillable = [
        'title',
        'content',
        'image',
        'published_at',
        'status',
    ];


    public function views()
    {
        return $this->hasMany(NewsView::class);
    }
}
