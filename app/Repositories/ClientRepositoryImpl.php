<?php

namespace App\Repositories;
use App\Models\Client;
class ClientRepositoryImpl implements ClientRepository{
    public function findAll(){
        return Client::all();
    }

    public function findById($id){
        return Client::find($id);
    }

    public function create(array $data){
        return Client::create($data);
    }

    public function update($id, array $data){
        $client = Client::find($id);
        return $client->update($data);
    }

    public function delete($id){
        $client = Client::find($id);
        return $client->delete();
    }
}