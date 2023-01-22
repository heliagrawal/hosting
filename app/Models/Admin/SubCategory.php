<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
  use HasFactory;
  protected $fillable = [
    'category_id', 'en_SubCategory_Name', 'en_SubCategory_Slug', 'Status', 'en_Description', 'SubCategory_Icon', 'fr_SubCategory_Name', 'fr_SubCategory_Slug', 'fr_Description'
  ];
  public function category()
  {
    return $this->belongsTo(Category::class, 'category_id');
  }
  public function products()
  {
    return $this->hasMany(Product::class, 'SubCategory_Id');
  }
}
