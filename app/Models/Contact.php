<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'message'
    ];
    
    protected $hidden = [
        'password'
    ];
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    // Accessor for formatted date
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('F j, Y, g:i a');
    }
}