<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudenDtetails extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'subject',
        'mark',
        'is_delete',
        'updated_by'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
