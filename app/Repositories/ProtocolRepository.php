<?php

namespace App\Repositories;

use App\Contracts\Repositories\AbstractRepository;
use App\Models\Protocol;

class ProtocolRepository implements AbstractRepository
{

    /**
     * Constructor method
     *
     * @param GuideRepository $guideRepository
     */
    public function __construct(
        public GuideRepository $guideRepository
    )
    {
    }

    /**
     * Find a protocol by id
     *
     * @param string $id
     * @return array | null
     */
    public function find(string $id): array | null
    {
        return optional(Protocol::find($id))->toArray();
    }

    /**
     * Save a protocol into the database
     *
     * @param string $data
     * @param string $id
     * @return array
     */
    public function save(array $data, string $id = null): array
    {
        if ($id && $this->find($id)) {
            Protocol::where('Numero_Protocolo', $id)->update(
                collect($data)->except('Guias')->toArray()
            );
            $protocol = $this->find($id);
        } else {
            $protocol = Protocol::create(
                collect($data)->except('Guias')->toArray()
            )->toArray();
        }

        if (optional($data)['Guias']) {
            foreach ($data['Guias'] as $guide) {
                $guide['Numero_Protocolo'] = $protocol['Numero_Protocolo'];
                $this->guideRepository->save($guide, $guide['Numero_Guia_Prestador']);
            }
        }

        return $protocol;
    }
}
