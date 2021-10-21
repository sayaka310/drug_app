<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Models\Comment;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'discription',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function getImagePathAttribute()
    {
        return 'posts/' . $this->attachments[0]->name;
    }

    public function getImageUrlAttribute()
    {
        if (config('filesystems.default') == 'gcs') {
            return Storage::temporaryUrl($this->image_path, now()->addMinutes(5));
        }

        return Storage::url($this->image_path);
    }

    public function getImagePathsAttribute()
    {
        
        $paths = [];
        foreach ($this->attachments as $attachment) {
            $paths[] = 'posts/' . $attachment->name;
        }
        return $paths;
    }

    /**
     * get image urls of all photos
     */
    public function getImageUrlsAttribute()
    {
        $urls = [];
        foreach ($this->image_paths as $path) {
            $urls[] = Storage::url($path);
        }
        return $urls;
    }
}
