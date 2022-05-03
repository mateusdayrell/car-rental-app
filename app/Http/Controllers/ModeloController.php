<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use App\Repositories\ModeloRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModeloController extends Controller
{
    public function __construct(Modelo $modelo) {
        $this->modelo = $modelo;
    }

    
    public function index(Request $request)
    {
        // $modelos = array();

        $modeloRepository = new ModeloRepository($this->modelo);
        
        if($request->has('brand_atributos')){
            $brand_atributos = 'brand:id,'.$request->brand_atributos;
            $modeloRepository->getSelectedAttributes($brand_atributos);
        } else {
            // $modelos = $this->modelo->with('brand');
            $modeloRepository->getSelectedAttributes('brand');
        }

        if($request->has('filter')) {
            $modeloRepository->filter($request->filter);
        }

        if ($request->has('atributos')) {
            $modeloRepository->selectAttributes($request->atributos);
        }   
        
        return response()->json($modeloRepository->getResult(), 200);
    }

    
    public function store(Request $request)
    {
        $request->validate($this->modelo->rules(), $this->modelo->feedback());

        $image = $request->file('image');
        $image_urn = $image->store('images/modelos', 'public');

        $modelo = $this->modelo->create([
            'name' => $request->name,
            'image' => $image_urn,
            'brand_id' => $request->brand_id,
            'door_qt' => $request->door_qt,
            'seaters' => $request->seaters,
            'air_bag' => $request->air_bag,
            'abs' => $request->abs,
        ]);

        return response()->json($modelo, 201);
    }

    
    public function show($id)
    {
        $modelo = $this->modelo->with('brand')->find($id);

        if ($modelo === null) {
            return response()->json(['erro' => 'O modelo pesquisado não existe!'], 404);
        }
        
        return response()->json($modelo, 200);
    }

    
    public function update(Request $request, $id)
    {
        $modelo = $this->modelo->find($id);

        if ($modelo === null) {
            return response()->json(['erro' => 'O modelo solicitado para atualização não existe!'], 404);
        }

        // ** VALIDATION
        if ($request->method() === 'PATCH'){
            $dynamicRules = array();

            foreach($modelo->rules() as $input => $rule) {
                if (array_key_exists($input, $request->all())) {
                    $dynamicRules[$input] = $rule;
                }
            }
            
            $request->validate($dynamicRules, $modelo->feedback());

        } else {
            $request->validate($modelo->rules(), $modelo->feedback());
        }

        // ** REMOVING OLD IMAGE FILE
        if ($request->file('image')) {
            Storage::disk('public')->delete($modelo->image);
        }        

        // UPDATING
        $modelo->fill($request->all());

            // ** GETTING NEW IMAGE FILE
            if($request->has('image')){
                $image = $request->image;
                $image_urn = $image->store('images/modelos', 'public');
                $modelo->image = $image_urn;
            }
       
        $modelo->save();

        return response()->json($modelo, 200);
    }
    

    public function destroy($id)
    {
        $modelo = $this->modelo->find($id);

        if ($modelo === null) {
            return response()->json(['erro' => 'O modelo solicitado para exclusão não existe!'], 404);
        }

        // ** REMOVING IMAGE FILE
        Storage::disk('public')->delete($modelo->image);

        $modelo->delete();
        return ['msg' => 'Modelo removido com sucesso'];
    }
}
