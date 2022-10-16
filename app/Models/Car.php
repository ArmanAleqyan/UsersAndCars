<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;
    protected $guarded =[];

    public function UserCar(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function CarPhoto(){
        return $this->Hasmany(CarPhoto::class,'car_id');
    }
}
