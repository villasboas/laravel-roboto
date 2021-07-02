<?php

namespace App\Contracts\Repositories;

interface AbstractRepository
{
    /**
     * Find a record by id
     *
     * @param string $id
     * @return array | null
     */
    public function find(string $id): array | null;

    /**
     * Save a record into the database
     *
     * @param array $data
     * @param string $id
     * @return array
     */
    public function save(array $data, string $id = null): array;
}
