<?php

namespace App\Repositories;

interface RepositoryInterface
{
    /**
     * Method to get all entries
     *
     * @return mixed
     */
    public function all();

    /**
     * create a new entry
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * Update an entry.
     *
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function update(array $data, $id);

    /**
     * Delete an entry.
     *
     * @param $id
     * @return mixed
     */
    public function delete($id);

    /**
     * get entry by ID.
     *
     * @param $id
     * @return mixed
     */
    public function find($id);
}
