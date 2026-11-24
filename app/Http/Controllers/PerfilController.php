<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perfil;
use Illuminate\Support\Facades\DB;

class PerfilController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perfiles = Perfil::paginate(5); // o el número que desees por página
        return view('perfiles.index', compact('perfiles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('perfiles.crear');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'fechaRegistro' => 'required|date',
            'estadoUsuario' => 'required|string|max:50',
            'descripcion' => 'nullable|string',
        ]);

        // Guardamos el contenido tal cual llega (con HTML)
        Perfil::create($request->all());

        return redirect()->route('perfiles.index')->with('success', 'Perfil creado correctamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $perfil = Perfil::findOrFail($id);
        return view('Perfiles.editar', compact('perfil'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'fechaRegistro' => 'required|date',
            'estadoUsuario' => 'required|string|max:50',
            'descripcion' => 'nullable|string',
        ]);

        $perfil = Perfil::findOrFail($id);

        // Actualizamos tal cual (con HTML)
        $perfil->update($request->all());

        return redirect()->route('perfiles.index')->with('success', 'Perfil actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $perfil = Perfil::findOrFail($id);
        $perfil->delete();

        return redirect()->route('perfiles.index')->with('success', 'Perfil eliminado correctamente');
    }
}
