<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Guid\Guid;

class Item extends Model
{
    use HasFactory;

    /**
     * Database table name
     *
     * @var string
     */
    protected $table = 'Items';

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
    protected $primaryKey = 'Codigo_Item';

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
     * BelongsTo Guide
     *
     * @return BelongsTo
     */
    public function guide(): BelongsTo
    {
        return $this->belongsTo(Guide::class, 'Numero_Guia_Prestador');
    }
}
