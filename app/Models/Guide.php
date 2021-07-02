<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guide extends Model
{
    use HasFactory;

    /**
     * Database table name
     *
     * @var string
     */
    protected $table = 'Guias';

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
    protected $primaryKey = 'Numero_Guia_Prestador';

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
     * BelongsTo Protocol
     *
     * @return BelongsTo
     */
    public function protocol(): BelongsTo
    {
        return $this->belongsTo(Protocol::class, 'Numero_Protocolo');
    }

    /**
     * HasMany Item
     *
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(Item::class, 'Numero_Guia_Prestador');
    }
}
