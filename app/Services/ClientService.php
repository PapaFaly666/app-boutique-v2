<?php
namespace App\Services;

interface ClientService{
    public function findAllClient();
    public function findClientById($id);
    public function createClient(array $data);
    public function updateClient($id,array $data);
    public function deleteClient($id);
}