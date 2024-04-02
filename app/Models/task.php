<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'due_date', 'state'];

    public function status(){
        $STATUS = [
            "PENDING"     => "Pendiente",
            "IN_PROGRESS" => "En curso",
            "COMPLETE"    => "Finalizada",
        ];
        return $STATUS[$this->state];
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
