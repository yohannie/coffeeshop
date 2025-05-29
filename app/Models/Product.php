<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'category',
        'image_url',
        'image_path',
        'is_available'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean'
    ];

    protected $appends = ['image_full_url'];

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    public function getImageFullUrlAttribute()
    {
        if ($this->image_path) {
            return Storage::url($this->image_path);
        }
        return $this->image_url ?? null;
    }

    public function deleteImage()
    {
        if ($this->image_path && Storage::exists($this->image_path)) {
            Storage::delete($this->image_path);
        }
    }
}
