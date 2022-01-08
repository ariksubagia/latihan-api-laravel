<?php

namespace App\Http\Resources;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $article = parent::toArray($request);

        $summary = substr(strip_tags($article['content']), 0, 150) . ' ...';

        $article['summary'] = $summary;
        $article['image'] = Storage::disk('public')->url($article['image']);

        return $article;
    }
}
