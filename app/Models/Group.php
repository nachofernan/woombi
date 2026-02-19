<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = ['name', 'owner_id', 'invite_code'];

    public function owner() { return $this->belongsTo(User::class, 'owner_id'); }
    public function users() { return $this->belongsToMany(User::class)->withPivot('total_points'); }
}
