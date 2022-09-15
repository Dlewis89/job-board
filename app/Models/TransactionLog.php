<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionLog extends Model
{
    use HasFactory;

    protected $guarded = ['id'];



    public function initiator()
    {
        return $this->morphTo("initiator");
    }
    public function owner()
    {
        return $this->morphTo("owner");
    }
}
