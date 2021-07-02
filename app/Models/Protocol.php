<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Protocol extends Model
{
    use HasFactory;

    /**
     * Database table name
     *
     * @var string
     */
    protected $table = 'Protocolos';

    /**
     * Guarded properties
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Primary Key Column
     *
     * @var string
     */
    protected $primaryKey = 'Numero_Protocolo';

    /**
     * Disable incrementing for primary key
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Disable model timestamps
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * HasMany Guide
     *
     * @return HasMany
     */
    public function guides(): HasMany
    {
        return $this->hasMany(Guide::class, 'Numero_Protocolo');
    }
}
