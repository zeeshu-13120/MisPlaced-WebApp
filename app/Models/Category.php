<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'icon',
        'parent_id',

    ];
    public function subcategories()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
    public function form()
    {
        return $this->hasOne(Form::class, 'category_id', 'id');
    }

}
