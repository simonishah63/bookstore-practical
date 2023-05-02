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
     * @return object Created Book
     */
    public function create(array $data);

    /**
     * Delete Item By Id
     *
     * @return object Deleted Book
     */
    public function delete(int $id);

    /**
     * Get Item Details By ID
     *
     * @return object Get Book
     */
    public function getByID(int $id);

    /**
     * Update Book By Id and Data
     *
     * @return object Updated Book Information
     */
    public function update(int $id, array $data);
}
