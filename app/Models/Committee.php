<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use App\Helpers\Classes\FileHelpers;
use App\Models\AboutUs;
use App\Models\Category;
use App\Models\Course;


class Committee extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'description',
        'image'
    ];

    public function aboutUs()
    {
        return $this->hasOne(AboutUs::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public static function boot()
    {
        parent::boot();
    
        static::saving(function ($course) {
            if (request()->hasFile('image')) {
                $randomString = Str::random(10);
                $course->image = FileHelpers::uploadImage(request()->file('image'), "public/courses/$randomString.png", oldPath: $course->getOriginal('image'));
            }           
        });
    
        static::forceDeleted(function ($course) {
          if ($course->image) {
            FileHelpers::deleteFile($course->image); 
          }
        });
    }
    public function getImageUrlAttribute(): ?string {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
