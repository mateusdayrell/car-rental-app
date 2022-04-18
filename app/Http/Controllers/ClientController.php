<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Repositories\ClientRepository;
use Illuminate\Http\Request;

class ClientController extends Controller
{
   
    public function __construct(Client $client) {
        $this->client = $client;
    }

    
    public function index(Request $request)
    {
        $clients = array();

        $clientRepository = new ClientRepository($this->client);

        if($request->has('filter')) {
            $clientRepository->filter($request->filter);
        }

        if ($request->has('atributos')) {
            $clientRepository->selectAttributes($request->atributos);
        }

        return response()->json($clientRepository->getResult(), 200);
    }

   
    public function store(Request $request)
    {
        $request->validate($this->client->rules());
        
        $client = $this->client->create([
            'name' => $request->name,
        ]);

        return response()->json($client, 200);
    }

    
    public function show($id)
    {
        $client = $this->client->find($id);

        if ($client === null) {
            return response()->json(['erro' => 'Cliente pesquisado não existe!'], 404);
        }
        
        return response()->json($client, 200);
    }

    
    public function update(Request $request, $id) 
    {
        $client = $this->client->find($id);

        if ($client === null) {
            return response()->json(['erro' => 'Cliente solicitado para atualização não existe!'], 404);
        }

        // ** VALIDATION
        if ($request->method() === 'PATCH'){
            $dynamicRules = array();

            foreach($client->rules() as $input => $rule) {
                if (array_key_exists($input, $request->all())) {
                    $dynamicRules[$input] = $rule;
                }
            }
            
            $request->validate($dynamicRules);

        } else {
            $request->validate($client->rules());
        }
        
        // UPDATING
        $client->fill($request->all());       
        $client->save();

        return response()->json($client, 200);
    }

    
    public function destroy($id)
    {
        $client = $this->client->find($id);

        if ($client === null) {
            return response()->json(['erro' => 'Cliente solicitado para exclusão não existe!'], 404);
        }

        $client->delete();
        return ['msg' => 'Cliente removido com sucesso'];
    }
}
