<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpinRuns extends Model
{
    use HasFactory;

    protected $table = 'ggds_spin_runs';

    protected $fillable = [
        'key',
        'nonce',
        'possibilities',
        'prize'
    ];
}
