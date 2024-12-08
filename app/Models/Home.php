<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use App\Helpers\Classes\FileHelpers;


class Home extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'description',
        'image',
    ];

    public static function boot()
    {
        parent::boot();
    
        static::saving(function ($home) {
            if (request()->hasFile('image')) {
                $randomString = Str::random(10);
                $home->image = FileHelpers::uploadImage(request()->file('image'), "public/homes/$randomString.png", oldPath: $home->getOriginal('image'));
            }           
        });
    
        static::forceDeleted(function ($home) {
          if ($home->image) {
            FileHelpers::deleteFile($home->image); 
          }
        });
    }
    public function getImageUrlAttribute(): ?string {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
