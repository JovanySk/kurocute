<?php


namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClienteController extends Controller
{
    // Mostrar listado de clientes
public function index()
{
    $clientes = Cliente::paginate(5); // 5 registros por página
    return view('clientes.index', compact('clientes'));
}

    // Mostrar formulario de creación
    public function create()
    {
        return view('clientes.crear');
    }

    // Guardar nuevo cliente
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'rfc' => 'required|string|max:20|unique:clientes,rfc',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'email' => 'required|email|unique:clientes,email',
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('clientes', 'public');
        } else {
            $data['imagen'] = null;
        }

        Cliente::create($data);

        return redirect()->route('clientes.index')->with('success', 'Cliente creado correctamente.');
    }

    // Mostrar formulario de edición
    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('clientes.editar', compact('cliente'));
    }

    // Actualizar cliente
 public function update(Request $request, $id)
{
    $cliente = Cliente::findOrFail($id);
    
    $validated = $request->validate([
        'nombre' => 'required|string|max:255',
        'rfc' => 'required|string|max:20',
        'direccion' => 'required|string|max:255',
        'telefono' => 'required|string|max:20',
        'email' => 'required|email|unique:clientes,email,'.$id,
        'descripcion' => 'nullable|string',
        'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    if($request->hasFile('imagen')) {
        // Eliminar imagen anterior si existe
        if($cliente->imagen && Storage::exists($cliente->imagen)) {
            Storage::delete($cliente->imagen);
        }
        
        $path = $request->file('imagen')->store('public/clientes');
        $validated['imagen'] = str_replace('public/', '', $path);
    }

    $cliente->update($validated);

    return redirect()->route('clientes.index')
        ->with('success', 'Cliente actualizado correctamente');
}
    // Eliminar cliente
    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        if ($cliente->imagen) {
            Storage::disk('public')->delete($cliente->imagen);
        }
        $cliente->delete();

        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado correctamente.');
    }
}