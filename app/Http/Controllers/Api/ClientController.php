<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
   
    public function index()
    {
        return response()->json(Client::all());
    }

    public function show($id)
    {
        $cliente = Client::findOrFail($id);
        return response()->json($cliente);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'   => 'required|string|max:255',
            'email'    => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:255',
            'direccion'=> 'nullable|string|max:255',
            'logo'     => 'nullable|image|mimes:png|max:1024', // 1MB max
        ]);

      
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $cliente = Client::create($data);

        return response()->json($cliente, 201);
    }

    public function update(Request $request, $id)
    {
        $cliente = Client::findOrFail($id);

        $data = $request->validate([
            'nombre'   => 'sometimes|required|string|max:255',
            'email'    => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:255',
            'direccion'=> 'nullable|string|max:255',
            'logo'     => 'nullable|image|mimes:png|max:1024',
        ]);

        if ($request->hasFile('logo')) {
            if ($cliente->logo) {
                Storage::disk('public')->delete($cliente->logo);
            }
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $cliente->update($data);

        return response()->json($cliente);
    }

    // Delete cliente
    public function destroy($id)
    {
        $cliente = Client::findOrFail($id);

        // Delete logo file if exists
        if ($cliente->logo) {
            Storage::disk('public')->delete($cliente->logo);
        }

        $cliente->delete();

        return response()->json(['message' => 'Cliente deleted']);
    }
}
