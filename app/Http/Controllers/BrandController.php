<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    
    public function __construct(Brand $brand) {
        $this->brand = $brand;
    }
    
    public function index()
    {
        $brand = $this->brand->all();
        return response()->json($brand, 200);
    }

    public function store(Request $request)
    {
        $request->validate($this->brand->rules(), $this->brand->feedback());

        // ** GETTING IMAGE FILE
        $image = $request->image;
        $image_urn = $image->store('images', 'public');
        
        $brand = $this->brand->create([
            'name' => $request->name,
            'image' => $image_urn
        ]);
        return response()->json($brand, 200);
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

        // ** REMOVING OLD IMAGE FILE
        if ($request->file('image')) {
            Storage::disk('public')->delete($brand->image);
        }

        // ** GETTING NEW IMAGE FILE
        $image = $request->image;
        $image_urn = $image->store('images', 'public');

        // UPDATING
        dd($request->name);
        $brand->update([
            'name' => $request->name,
            'image' => $image_urn
        ]);

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
