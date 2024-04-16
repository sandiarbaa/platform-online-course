<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    // cara pertama dalam mempersiapkan mess assignment
    protected $fillable = [
        'name',
        'slug',
        'icon'
    ];

    // cara kedua
    // user dapat memasukkan data apa saja yang membahayakan sistem
    protected $guarded = [
        'id'
    ];
}
