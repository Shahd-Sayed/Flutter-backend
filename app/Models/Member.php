<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use App\Helpers\Classes\FileHelpers;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model {

    use HasFactory , SoftDeletes;
    protected $fillable = [
        'name',
        'role',
        'description',
        'link1',
        'link2',
        'link3',
        'image'
    ];

    public static function boot()
    {
        parent::boot();
    
        static::saving(function ($member) {
            if (request()->hasFile('image')) {
                $randomString = Str::random(10);
                $member->image = FileHelpers::uploadImage(request()->file('image'), "public/members/$randomString.png", oldPath: $member->getOriginal('image'));
            }           
        });
    
        static::forceDeleted(function ($member) {
          if ($member->image) {
            FileHelpers::deleteFile($member->image); 
          }
        });
    }
    public function getImageUrlAttribute(): ?string {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
