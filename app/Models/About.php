<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use App\Helpers\Classes\FileHelpers;

class About extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'image',
        'video',
    ];

    public static function boot()
    {
        parent::boot();
    
        static::saving(function ($about) {
            if (request()->hasFile('image')) {
                $randomString = Str::random(10);
                $about->image = FileHelpers::uploadImage(
                    request()->file('image'),
                    "public/abouts/images/$randomString.png",
                    oldPath: $about->getOriginal('image')
                );
            }
            
            if (request()->hasFile('video')) {
                $randomString = Str::random(10);
                $about->video = FileHelpers::uploadFile(
                    request()->file('video'),
                    "public/abouts/videos/$randomString." . request()->file('video')->getClientOriginalExtension(),
                    oldPath: $about->getOriginal('video')
                );
            }
        });
    
        static::forceDeleted(function ($about) {
            if ($about->image) {
                FileHelpers::deleteFile($about->image);
            }
            
            if ($about->video) {
                FileHelpers::deleteFile($about->video);
            }
        });
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    public function getVideoUrlAttribute(): ?string
    {
        return $this->video ? asset('storage/' . $this->video) : null;
    }
}
