<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ilog extends Model
{
    use HasFactory;

    // protected $dates = ['published_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'content', 'images', 'latitude', 'longitude', 'praise_num', 'is_draft', 'published_at',
    ];

    /**
     * @param $value
     * @return mixed|string
     */
    public function getImagesAttribute($value)
    {
        $images = explode(',', $value);
        $image_urls = [];
        
        foreach($images as $image) {
            $image_urls[] = url(config('blog.uploads.webpath') . '/storage/' . $image);
        }

        return $image_urls;
    }

    public function setImagesAttribute($value)
    {
        $this->attributes['images'] = implode(',', $value);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id', 'users');
    }

}
