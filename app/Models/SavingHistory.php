<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavingHistory extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function savings() {
        return $this->belongsTo(Savings::class);
    }
}
