<?php
namespace App\Repositories;

interface ClientRepository
{
    public function findAll();
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function findById($id);
}
