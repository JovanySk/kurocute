<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Factura;
use App\Models\Cliente;
use App\Models\Estadofactura; // Usar el nombre correcto del modelo
use App\Models\Formaspago;    // Usar el nombre correcto del modelo
use App\Mail\FacturaCreadaMail;
use Illuminate\Support\Facades\Mail;

class FacturaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Carga las facturas con paginación
        $facturas = Factura::paginate(10);

        // Carga las listas de modelos para los dropdowns
        // Usamos los nombres correctos de los modelos
        $clientes = Cliente::all();
        $formasPago = Formaspago::all(); // Cambiado a Formaspago::all()
        $estados = Estadofactura::all(); // Cambiado a Estadofactura::all()
        
        // Retorna la vista y pasa todas las variables necesarias
        return view('facturas.index', compact('facturas', 'clientes', 'formasPago', 'estados'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Carga las listas necesarias para el formulario
        $clientes = Cliente::all();
        $formasPago = Formaspago::all(); // Usar el nombre del modelo
        $estados = Estadofactura::all(); // Usar el nombre del modelo
        
        // Retorna la vista y pasa las variables
        return view('facturas.create', compact('clientes', 'formasPago', 'estados'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) 
    {
        // Validación de datos (recomendado)
        $request->validate([
            'numero' => 'required|string|max:255',
            'detalles' => 'nullable|string',
            'valor' => 'required|numeric',
            'cliente_id' => 'required|exists:clientes,id',
            'estado_id' => 'required|exists:estadosfacturas,id',
            'forma_pago_id' => 'required|exists:formaspago,id',
            'archivo' => 'nullable|file|mimes:pdf,xml|max:10240', // 10MB max
        ]);

        $factura = new Factura();
        $factura->numero = $request->input('numero');
        $factura->detalles = $request->input('detalles');
        $factura->valor = $request->input('valor');
        $factura->idcliente = $request->input('cliente_id'); // Asegúrate de que el nombre del campo coincida con el formulario
        $factura->idestado = $request->input('estado_id');
        $factura->idforma = $request->input('forma_pago_id');

        if ($request->hasFile('archivo')) {
            $path = $request->file('archivo')->store('facturas', 'public');
            $factura->archivo = $path;
        }

        $factura->save();

        // Obtener el correo del cliente relacionado
        $cliente = Cliente::find($factura->idcliente);
        if ($cliente && $cliente->email) {
            try {
                Mail::to($cliente->email)->send(new FacturaCreadaMail($factura));
            } catch (\Exception $e) {
                // Opcional: loguea el error o muestra un mensaje
                // \Log::error('Error al enviar correo: ' . $e->getMessage());
            }
        }

        return redirect()->route('facturas.index')->with('success', 'Factura creada correctamente y correo enviado.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $factura = Factura::findOrFail($id);
        return view('facturas.show', compact('factura'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $factura = Factura::findOrFail($id);
        // Carga todas las listas necesarias para el formulario de edición
        $clientes = Cliente::all();
        $estados = Estadofactura::all(); // Usar el nombre del modelo
        $formasPago = Formaspago::all(); // Cambiado a formasPago para consistencia
        
        // Retorna la vista y pasa las variables
        return view('facturas.editar', compact('factura', 'clientes', 'estados', 'formasPago'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validación de datos (recomendado)
        $request->validate([
            'numero' => 'required|string|max:255',
            'detalles' => 'nullable|string',
            'valor' => 'required|numeric',
            'cliente_id' => 'required|exists:clientes,id',
            'estado_id' => 'required|exists:estadosfacturas,id',
            'forma_pago_id' => 'required|exists:formaspago,id',
            'archivo' => 'nullable|file|mimes:pdf,xml|max:10240', // 10MB max
        ]);
        
        $factura = Factura::findOrFail($id);
        $factura->numero = $request->input('numero');
        $factura->detalles = $request->input('detalles');
        $factura->valor = $request->input('valor');
        $factura->idcliente = $request->input('cliente_id'); // Asegúrate de que el nombre del campo coincida con el formulario
        $factura->idestado = $request->input('estado_id');
        $factura->idforma = $request->input('forma_pago_id');

        if ($request->hasFile('archivo')) {
            // Elimina el archivo anterior si existe para evitar archivos huérfanos
            if ($factura->archivo) {
                \Storage::disk('public')->delete($factura->archivo);
            }
            $factura->archivo = $request->file('archivo')->store('facturas', 'public');
        }
        $factura->save();

        return redirect()->route('facturas.index')->with('success', 'Factura actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $factura = Factura::findOrFail($id);
        // Opcional: Elimina el archivo del storage al eliminar la factura
        if ($factura->archivo) {
            \Storage::disk('public')->delete($factura->archivo);
        }
        $factura->delete();

        return redirect()->route('facturas.index')->with('success', 'Factura eliminada exitosamente.');
    }
}