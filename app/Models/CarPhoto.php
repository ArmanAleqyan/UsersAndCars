<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarPhoto extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function CarPhoto(){
        return $this->Belongsto(Car::class,'car_id');
    }
}
