<?php

namespace App\Interfaces;

interface BookInterface
{
    /**
     * Get Paginated Data
     *
     * @param int   Page No
     * @return array Paginated Data
     */
    public function getPaginatedData(int $perPage);

    /**
     * Create New Item
     *
     * @param array $data
     * @return object Created Book
     */
    public function create(array $data);

    /**
     * Delete Item By Id
     *
     * @param int $id
     * @return object Deleted Book
     */
    public function delete(int $id);

    /**
     * Get Item Details By ID
     *
     * @param int $id
     * @return object Get Book
     */
    public function getByID(int $id);

    /**
     * Update Book By Id and Data
     *
     * @param int $id
     * @param array $data
     * @return object Updated Book Information
     */
    public function update(int $id, array $data);
}