<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model {

    protected $fillable = ['user_id', 'items', 'total', 'estado'];

    protected $casts = [
        'items' => 'array'
    ];

    // Un pedido pertenece a un usuario
    public function user() {
        return $this->belongsTo(User::class);
    }
}