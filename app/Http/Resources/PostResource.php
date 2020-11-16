<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'image' => $this->thumbnail ? url(config('blog.uploads.webpath') . '/' . $this->thumbnail) : '',
            'content' => $this->content,
            'author' => 'Athellow',
            'published_at' => $this->published_at,
            'views' => $this->visited,
            'votes' => mt_rand(1, 1000)
        ];
    }
}
