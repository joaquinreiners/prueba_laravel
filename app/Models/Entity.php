<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Entity extends Model
{
    use HasFactory;
    protected $fillable = [
        'api',
        'description',
        'link',
        'category_id',
    ];
    public function Category()
    {
        return $this->belongsTo(Category::class);
    }
}
