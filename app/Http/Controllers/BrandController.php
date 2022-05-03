<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Repositories\BrandRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    
    public function __construct(Brand $brand) {
        $this->brand = $brand;
    }

    
    public function index(Request $request)
    {
        $brands = array();

        $brandRepository = new BrandRepository($this->brand);
        
        if($request->has('modelos_atributos')){
            $modelos_atributos = 'modelos:id,'.$request->modelos_atributos;
            $brandRepository->getSelectedAttributes($modelos_atributos);
        } else {
            $brandRepository->getSelectedAttributes('modelos');
        }

        if($request->has('filter')) {
            $brandRepository->filter($request->filter);
        }

        if ($request->has('atributos')) {
            $brandRepository->selectAttributes($request->atributos);
        }

        return response()->json($brandRepository->getPaginatedResult(9), 200);
    }

    public function getAll(Request $request) {
        $brands = array();

        $brandRepository = new BrandRepository($this->brand);
        
        if($request->has('modelos_atributos')){
            $modelos_atributos = 'modelos:id,'.$request->modelos_atributos;
            $brandRepository->getSelectedAttributes($modelos_atributos);
        } else {
            $brandRepository->getSelectedAttributes('modelos');
        }

        if($request->has('filter')) {
            $brandRepository->filter($request->filter);
        }

        if ($request->has('atributos')) {
            $brandRepository->selectAttributes($request->atributos);
        }

        return response()->json($brandRepository->getResult(), 200);
    }



    public function store(Request $request)
    {
        $request->validate($this->brand->rules(), $this->brand->feedback());

        $image = $request->file('image');
        $image_urn = $image->store('images', 'public');

        $brand = $this->brand->create([
            'name' => $request->name,
            'image' => $image_urn
        ]);

        return response()->json($brand, 201);
    }

    
    public function show($id)
    {
        $brand = $this->brand->with('modelos')->find($id);

        if ($brand === null) {
            return response()->json(['erro' => 'Marca pesquisada não existe!'], 404);
        }
        
        return response()->json($brand, 200);
    }

    
    public function update(Request $request, $id) 
    {
        $brand = $this->brand->find($id);

        if ($brand === null) {
            return response()->json(['erro' => 'Marca solicitada para atualização não existe!'], 404);
        }

        // ** VALIDATION
        if ($request->method() === 'PATCH'){
            $dynamicRules = array();

            foreach($brand->rules() as $input => $rule) {
                if (array_key_exists($input, $request->all())) {
                    $dynamicRules[$input] = $rule;
                }
            }
            
            $request->validate($dynamicRules, $brand->feedback());

        } else {
            $request->validate($brand->rules(), $brand->feedback());
        }
        
        // UPDATING
        $brand->fill($request->all());

             // ** GETTING NEW IMAGE FILE
            if( $request->file('image') ) {
                // ** REMOVING OLD IMAGE FILE
                Storage::disk('public')->delete($brand->image);

                $image = $request->file('image');
                $image_urn = $image->store('images', 'public');
                $brand->image = $image_urn;
            }
       
        $brand->save();

        return response()->json($brand, 200);
    }

    
    public function destroy($id)
    {
        $brand = $this->brand->find($id);

        if ($brand === null) {
            return response()->json(['erro' => 'Marca solicitada para exclusão não existe!'], 404);
        }

        // ** REMOVING IMAGE FILE
        Storage::disk('public')->delete($brand->image);

        $brand->delete();
        return ['msg' => 'Marca removida com sucesso'];
    }
}
