<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    //
    protected $fillable = ['name', 'image_path'];
    public function devices()
{
    return $this->hasMany(Devices::class);
}

}
