<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrationLog extends Model
{
    protected $fillable = ['registration_id', 'user_id', 'status', 'title', 'note', 'is_public'];

    protected $casts = ['is_public' => 'boolean'];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
