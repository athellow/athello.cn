<?php

namespace App\Models;

use App\Services\Markdowner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    // protected $dates = ['published_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'category_id', 'title', 'subtitle', 'keyword', 'description', 'thumbnail', 'content', 'visited', 'published_at', 'is_draft',
    ];

    /**
     * @param $value
     * @return mixed|string
     */
    public function getContentAttribute($value)
    {
        if (preg_match('/.*edit/', \Request::path()) === 0) { // 编辑页面内容不需要markdown
            $markdown = new Markdowner();
            return $markdown->toHTML($value);
        }
        return $value;
    }

    public function getKeywordAttribute($value)
    {
        if (Route::currentRouteName() == 'posts.show') {
            $label_class = ['label-primary', 'label-success', 'label-info', 'label-warning', 'label-danger'];
            $keywords = explode(',', $value);
            $return = '';
            foreach ($keywords as $keyword) {
                $key = mt_rand(0, 4);
                $return .= '<span class="label '.$label_class[$key].'">'.$keyword.'</span> ';
            }
            return $return;
        }
        return $value;
    }

    /**
     * 将文章标题转化为 URL 的一部分，以利于SEO
     * @param $value
     */
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;

        if (!$this->exists) $this->setUniqueSlug(uniqid(str_random(8)), 0);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id', 'users');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tag')->select('tags.id', 'tags.name');
    }

    protected function setUniqueSlug($title, $extra)
    {
        $slug = str_slug($title . '-' . $extra);

        if (static::where('slug', $slug)->exists()) {
            $this->setUniqueSlug($title, $extra + 1);
            return ;
        }

        $this->attributes['slug'] = $slug;
    }

}
