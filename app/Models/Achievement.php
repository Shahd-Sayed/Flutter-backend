<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use App\Helpers\Classes\FileHelpers;
use Illuminate\Database\Eloquent\SoftDeletes;

class Achievement extends Model {
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'date',
        'location',
        'members',
        'rank',
        'image'
    ];

    public static function boot()
    {
        parent::boot();
    
        static::saving(function ($achievement) {
            if (request()->hasFile('image')) {
                $randomString = Str::random(10);
                $achievement->image = FileHelpers::uploadImage(request()->file('image'), "public/achievements/$randomString.png", oldPath: $achievement->getOriginal('image'));
            }           
        });
    
        static::forceDeleted(function ($achievement) {
          if ($achievement->image) {
            FileHelpers::deleteFile($achievement->image); 
          }
        });
    }
    public function getImageUrlAttribute(): ?string {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
