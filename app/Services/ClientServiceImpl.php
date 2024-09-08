<?php 
namespace App\Services;

use App\Facades\ClientRepositoryFacade as ClientRepository;
use App\Models\Client;

class ClientServiceImpl implements ClientService{

    public function findAllClient(){
        return ClientRepository::findAll();
    }
    
    public function findClientById($id){
        return ClientRepository::findById($id);
    }

    public function createClient(array $data)
    {
        return ClientRepository::create($data);
    }

    public function updateClient($id, array $data){
        return ClientRepository::update($id, $data);
    }

    public function deleteClient($id){
        return ClientRepository::delete($id);
    }
    
}