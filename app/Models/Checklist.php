<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Checklist extends Model
{
    protected $fillable = [
        'name',
        'user_id'
    ];


    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }


}
