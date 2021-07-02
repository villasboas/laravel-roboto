<?php

namespace App\Repositories;

use App\Contracts\Repositories\AbstractRepository;
use App\Models\Guide;

class GuideRepository implements AbstractRepository
{
    /**
     * Constructor method
     *
     * @param ItemRepository $itemRepository
     */
    public function __construct(
        public ItemRepository $itemRepository
    )
    {
    }

    /**
     * Find a guide by id
     *
     * @param string $id
     * @return array | null
     */
    public function find(string $id): array | null
    {
        return optional(Guide::find($id))->toArray();
    }

    /**
     * Save a guide into the database
     *
     * @param string $data
     * @param string $id
     * @return array
     */
    public function save(array $data, string $id = null): array
    {
        if ($id && $this->find($id)) {
            Guide::where('Numero_Guia_Prestador', $id)->update(
                collect($data)->except('Items')->toArray()
            );
            $guide = $this->find($id);
        } else {
            $guide = Guide::create(
                collect($data)->except('Items')->toArray()
            )->toArray();
        }

        if (optional($data)['Items']) {
            $this->itemRepository->removeAllByGuideNumber($guide['Numero_Guia_Prestador']);
            foreach ($data['Items'] as $item) {
                $item['Numero_Guia_Prestador'] = $guide['Numero_Guia_Prestador'];
                $this->itemRepository->save($item);
            }
        }

        return $guide;
    }
}
