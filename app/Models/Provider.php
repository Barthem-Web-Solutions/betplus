<?php

namespace App\Models;

use App\Helpers\Core as Helper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Provider extends Model
{
    use HasFactory;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'providers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
        'rtp',
        'status',
        'distribution',
        'views',
    ];

    /**
     * Fivers Game
     * @return HasMany
     */
    public function games(): HasMany
    {
        return $this->hasMany(Game::class, 'provider_id', 'id')
            ->orderBy('views', 'desc')
            ->where('show_home', 1)
            ->where('status', 1)
            ;
    }

}
