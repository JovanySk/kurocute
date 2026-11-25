<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ClienteController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));

        $clientes = Cliente::buscar($q ?: null)
            ->withCount('facturas')
            ->withSum('facturas', 'total')
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        return view('admin.clientes.index', compact('clientes', 'q'));
    }

    public function show(Cliente $cliente): View
    {
        $cliente->load(['facturas' => fn ($q) => $q->with('estado')->latest('fecha_emision'), 'pedidos' => fn ($q) => $q->latest()]);

        return view('admin.clientes.show', compact('cliente'));
    }

    public function create(): View
    {
        return view('admin.clientes.form', ['cliente' => new Cliente]);
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $this->validar($request);

        if ($request->hasFile('imagen')) {
            $datos['imagen'] = $request->file('imagen')->store('clientes', 'public');
        }

        $cliente = Cliente::create($datos);

        return redirect()->route('clientes.show', $cliente)->with('success', 'Cliente creado correctamente.');
    }

    public function edit(Cliente $cliente): View
    {
        return view('admin.clientes.form', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente): RedirectResponse
    {
        $datos = $this->validar($request, $cliente);

        if ($request->hasFile('imagen')) {
            if ($cliente->imagen) {
                Storage::disk('public')->delete($cliente->imagen);
            }
            $datos['imagen'] = $request->file('imagen')->store('clientes', 'public');
        } elseif ($request->boolean('quitar_imagen') && $cliente->imagen) {
            Storage::disk('public')->delete($cliente->imagen);
            $datos['imagen'] = null;
        }

        $cliente->update($datos);

        return redirect()->route('clientes.show', $cliente)->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(Cliente $cliente): RedirectResponse
    {
        if ($cliente->imagen) {
            Storage::disk('public')->delete($cliente->imagen);
        }

        $cliente->delete();

        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado correctamente.');
    }

    private function validar(Request $request, ?Cliente $cliente = null): array
    {
        $request->merge(['rfc' => mb_strtoupper(trim((string) $request->input('rfc')))]);

        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'rfc' => ['required', 'string', 'regex:/^[A-Z&Ñ]{3,4}\d{6}[A-Z0-9]{3}$/u', Rule::unique('clientes', 'rfc')->ignore($cliente)],
            'direccion' => ['required', 'string', 'max:255'],
            'telefono' => ['required', 'string', 'max:20', 'regex:/^[0-9 +()\-]{7,20}$/'],
            'email' => ['required', 'email', 'max:255', Rule::unique('clientes', 'email')->ignore($cliente)],
            'descripcion' => ['nullable', 'string', 'max:2000'],
            'imagen' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'rfc.regex' => 'El RFC no tiene un formato válido (ejemplo: GOMA850101AB1).',
            'telefono.regex' => 'Escribe un teléfono válido (solo números, espacios y +).',
        ]);

        unset($datos['imagen']);

        return $datos;
    }
}
