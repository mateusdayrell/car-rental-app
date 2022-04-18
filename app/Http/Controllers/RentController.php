<?php

namespace App\Http\Controllers;

use App\Models\Rent;
use App\Repositories\RentRepository;
use Illuminate\Http\Request;

class RentController extends Controller
{
    public function __construct(Rent $rent) {
        $this->rent = $rent;
    }

    
    public function index(Request $request)
    {
        $rents = array();

        $rentRepository = new RentRepository($this->rent);

        if($request->has('filter')) {
            $rentRepository->filter($request->filter);
        }

        if ($request->has('atributos')) {
            $rentRepository->selectAttributes($request->atributos);
        }

        return response()->json($rentRepository->getResult(), 200);
    }

   
    public function store(Request $request)
    {
        $request->validate($this->rent->rules());
        
        $rent = $this->rent->create([
            'client_id' => $request->client_id,
            'car_id' => $request->car_id,
            'begin_date' => $request->begin_date,
            'end_date' => $request->end_date,
            'daily_value' => $request->daily_value,
            'km_start' => $request->km_start,
            'km_end' => $request->km_end,
        ]);

        return response()->json($rent, 200);
    }

    
    public function show($id)
    {
        $rent = $this->rent->find($id);

        if ($rent === null) {
            return response()->json(['erro' => 'Locação pesquisada não existe!'], 404);
        }
        
        return response()->json($rent, 200);
    }

    
    public function update(Request $request, $id) 
    {
        $rent = $this->rent->find($id);

        if ($rent === null) {
            return response()->json(['erro' => 'Locação solicitada para atualização não existe!'], 404);
        }

        // ** VALIDATION
        if ($request->method() === 'PATCH'){
            $dynamicRules = array();

            foreach($rent->rules() as $input => $rule) {
                if (array_key_exists($input, $request->all())) {
                    $dynamicRules[$input] = $rule;
                }
            }
            
            $request->validate($dynamicRules);

        } else {
            $request->validate($rent->rules());
        }
        
        // UPDATING
        $rent->fill($request->all());       
        $rent->save();

        return response()->json($rent, 200);
    }

    
    public function destroy($id)
    {
        $rent = $this->rent->find($id);

        if ($rent === null) {
            return response()->json(['erro' => 'Locação solicitada para exclusão não existe!'], 404);
        }

        $rent->delete();
        return ['msg' => 'Locação removida com sucesso'];
    }
}
