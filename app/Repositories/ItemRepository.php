<?php

namespace App\Repositories;

use App\Contracts\Repositories\AbstractRepository;
use App\Models\Item;

class ItemRepository implements AbstractRepository
{
    /**
     * Fetch all items from database
     *
     * @return array
     */
    public function all(): array
    {
        return Item::with('guide', 'guide.protocol')->get()->map(function($item) {
            return collect(array_merge(
                $item->guide->protocol->toArray(),
                $item->guide->toArray(),
                $item->toArray(),
            ))->except('guide', 'protocol');
        })->toArray();
    }

    /**
     * Find a item by id
     *
     * @param string $id
     * @return array | null
     */
    public function find(string $id): array | null
    {
        return optional(Item::find($id))->toArray();
    }

    /**
     * Remove all items by guide number
     *
     * @param string $guideNumber
     */
    public function removeAllByGuideNumber(string $guideNumber)
    {
        return Item::where('Numero_Guia_Prestador', $guideNumber)->delete();
    }

    /**
     * Save a item into the database
     *
     * @param string $data
     * @param string $id
     * @return array
     */
    public function save(array $data, string $id = null): array
    {
        return Item::create(
            array_merge($data, [
                'Codigo_Item' => uniqid(rand() * time())
            ])
        )->toArray();
    }
}
