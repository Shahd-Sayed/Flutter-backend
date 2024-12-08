<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use App\Helpers\Classes\FileHelpers;
use App\Models\Committee;

class Course extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['committee_id', 'course_name', 'course_description','link', 'image' , 'date'];

    public function committee()
    {
        return $this->belongsTo(Committee::class);
    }

   
}
