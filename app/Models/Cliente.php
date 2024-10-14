<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['id','nombre', 'email', 'telefono', 'direccion'];

    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }
// Relación con el modelo Historial
public function historial()
{
    return $this->hasMany(Historial::class);
}

    
}
