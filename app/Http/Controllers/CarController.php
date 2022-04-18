<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Repositories\CarRepository;
use Illuminate\Http\Request;

class CarController extends Controller
{

    public function __construct(Car $car) {
        $this->car = $car;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $cars = array();

        $carRepository = new CarRepository($this->car);
        
        if($request->has('modelo_atributos')){
            $modelo_atributos = 'modelo:id,'.$request->modelo_atributos;
            $carRepository->getSelectedAttributes($modelo_atributos);
        } else {
            $carRepository->getSelectedAttributes('modelo');
        }

        if($request->has('filter')) {
            $carRepository->filter($request->filter);
        }

        if ($request->has('atributos')) {
            $carRepository->selectAttributes($request->atributos);
        }

        return response()->json($carRepository->getResult(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCarRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->car->rules());
        
        $car = $this->car->create([
            'modelo_id' => $request->modelo_id,
            'plate' => $request->plate,
            'available' => $request->available,
            'km' => $request->km,
        ]);

        return response()->json($car, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $car = $this->car->with('modelo')->find($id);

        if ($car === null) {
            return response()->json(['erro' => 'Carro pesquisado não existe!'], 404);
        }
        
        return response()->json($car, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCarRequest  $request
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) 
    {
        $car = $this->car->find($id);

        if ($car === null) {
            return response()->json(['erro' => 'Carro solicitado para atualização não existe!'], 404);
        }

        // ** VALIDATION
        if ($request->method() === 'PATCH'){
            $dynamicRules = array();

            foreach($car->rules() as $input => $rule) {
                if (array_key_exists($input, $request->all())) {
                    $dynamicRules[$input] = $rule;
                }
            }
            
            $request->validate($dynamicRules);

        } else {
            $request->validate($car->rules());
        }
        
        // UPDATING
        $car->fill($request->all());       
        $car->save();

        return response()->json($car, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $car = $this->car->find($id);

        if ($car === null) {
            return response()->json(['erro' => 'Carro solicitado para exclusão não existe!'], 404);
        }

        $car->delete();
        return ['msg' => 'Carro removido com sucesso'];
    }
}
