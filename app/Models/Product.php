<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'price',
        'description',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    
    public function scopeSearch($query, $search)
    {
        if($search){
            return $query->where('name', 'like', "%$search%")
                        ->orwhere('code', 'like', $search)
                        ->orwhere('price', 'like', "%$search%")
                        ->orwhere('description', 'like', "%$search%");
        }
    }

}
