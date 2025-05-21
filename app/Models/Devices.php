<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Devices extends Model
{
    use HasFactory;
    
    protected $fillable=[
        'name','room_id','images','is_active'
    ];
public function room()
{
    return $this->belongsTo(Room::class);
}



    public function scene(){
        return $this->belongsToMany(scene::class);
    }

}
