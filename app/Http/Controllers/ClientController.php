<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClientResource;
use App\Services\ClientService;
use Illuminate\Http\Request;
use App\Facades\ClientServiceFacade as ClientServiceFacade;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = ClientServiceFacade::findAllClient();
        return ClientResource::collection($clients);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request)
    {
        $validatedDate = $request->validated();
        $client = ClientServiceFacade::createClient($validatedDate);
        return new ClientResource($client);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $client = ClientServiceFacade::findClientById($id);
        return new ClientResource($client); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientRequest $request, string $id)
    {
        $validatedData = $request->validated();
    
        $client = ClientServiceFacade::updateClient($id, $validatedData);
    
        return $client ? $client : null;
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         $deleted = ClientServiceFacade::deleteClient($id);

         return response(['message' =>"Supprimer avec succes"]);
    }
}
