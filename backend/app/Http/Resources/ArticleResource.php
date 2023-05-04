<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'image' => $this->image,
            'title' => $this->title,
            'date' => $this->date,
            'content' => $this->content,
            'status' => $this->status,
            'writer' => new UserResource($this->writer()->first()),
            'editor' => new UserResource($this->editor()->first()),
            'company' => new CompanyResource($this->company()->first()),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
