<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'writer_user_id',
        'editor_user_id',
        'company_id',
        'image',
        'path',
        'title',
        'link',
        'date',
        'content',
        'status'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'string',
        'date' => 'datetime',
    ];

    /**
     * Get the user writer associated with the article.
     */
    public function writer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'writer_user_id');
    }

    /**
     * Get the user editor associated with the article.
     */
    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'editor_user_id');
    }

    /**
     * Get the company associated with the article.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Scope a query to only search users.
     */
    public function scopeSearch($query, $request): void
    {
        $query->where('title', 'LIKE', '%'.$request->search.'%')
            ->orWhere('status', 'LIKE', '%'.$request->search.'%')
            ->orWhere('content', 'LIKE', '%'.$request->search.'%')
            ->orWhereHas('company', function($query) use($request){
                $query->where('name', 'LIKE', '%'.$request->search.'%');
            })
            ->orWhereHas('writer', function($query) use($request){
                $query->where('firstname', 'LIKE', '%'.$request->search.'%')
                    ->orWhere('lastname', 'LIKE', '%'.$request->search.'%');
            })
            ->orWhereHas('editor', function($query) use($request){
                $query->where('firstname', 'LIKE', '%'.$request->search.'%')
                    ->orWhere('lastname', 'LIKE', '%'.$request->search.'%');
            });
    }
}
