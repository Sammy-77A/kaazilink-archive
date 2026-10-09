<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderService extends Model
{
    protected $fillable = ['user_id', 'service_slug'];

    public function provider()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
