<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\FacturaCreadaMail;
use App\Models\Cliente;
use App\Models\EstadoFactura;
use App\Models\Factura;
use App\Models\FormaPago;
use App\Support\FacturaPdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class FacturaController extends Controller
{
    public function index(Request $request): View
    {
        $filtros = $request->validate([
            'q' => ['nullable', 'string', 'max:80'],
            'estado' => ['nullable', 'string', 'exists:estadosfacturas,clave'],
            'forma_pago' => ['nullable', 'integer'],
            'desde' => ['nullable', 'date'],
            'hasta' => ['nullable', 'date'],
        ]);

        $facturas = Factura::with(['cliente', 'estado', 'formaPago'])
            ->filtrar($filtros)
            ->orderByDesc('fecha_emision')
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        $conteos = EstadoFactura::withCount('facturas')->get();

        return view('admin.facturas.index', [
            'facturas' => $facturas,
            'filtros' => $filtros,
            'estados' => $conteos,
            'formasPago' => FormaPago::orderBy('nombre')->get(),
            'totalFiltrado' => (float) Factura::filtrar($filtros)->sum('total'),
        ]);
    }

    public function show(Factura $factura): View
    {
        $factura->load(['cliente', 'estado', 'formaPago', 'pedido.items']);

        return view('admin.facturas.show', [
            'factura' => $factura,
            'conceptos' => $factura->conceptos(),
        ]);
    }

    public function create(Request $request): View
    {
        $factura = new Factura([
            'numero' => Factura::siguienteNumero(),
            'fecha_emision' => now(),
            'fecha_vencimiento' => now()->addDays(15),
            'cliente_id' => $request->integer('cliente') ?: null,
            'estado_id' => EstadoFactura::idDe(EstadoFactura::PENDIENTE),
        ]);

        return view('admin.facturas.form', $this->datosFormulario($factura));
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $this->validar($request);
        $factura = new Factura($datos + Factura::importesDesdeSubtotal((float) $datos['subtotal']));
        $this->guardarArchivos($request, $factura);
        $factura->save();

        $enviado = $this->enviarCorreo($factura);

        return redirect()->route('facturas.show', $factura)->with(
            $enviado ? 'success' : 'warning',
            $enviado
                ? "Factura {$factura->numero} creada y enviada a {$factura->cliente->email}."
                : "Factura {$factura->numero} creada, pero no se pudo enviar el correo.",
        );
    }

    public function edit(Factura $factura): View
    {
        return view('admin.facturas.form', $this->datosFormulario($factura));
    }

    public function update(Request $request, Factura $factura): RedirectResponse
    {
        $datos = $this->validar($request, $factura);
        $factura->fill($datos + Factura::importesDesdeSubtotal((float) $datos['subtotal']));
        $this->guardarArchivos($request, $factura);
        $factura->save();

        return redirect()->route('facturas.show', $factura)->with('success', 'Factura actualizada correctamente.');
    }

    public function destroy(Factura $factura): RedirectResponse
    {
        Storage::disk('public')->delete(array_filter([$factura->archivo_pdf, $factura->archivo_xml]));
        $factura->delete();

        return redirect()->route('facturas.index')->with('success', 'Factura eliminada correctamente.');
    }

    /** Descarga (o vista en línea con ?ver=1) del PDF generado con dompdf. */
    public function pdf(Request $request, Factura $factura): Response
    {
        $pdf = FacturaPdf::crear($factura);
        $nombre = FacturaPdf::nombreArchivo($factura);

        return $request->boolean('ver') ? $pdf->stream($nombre) : $pdf->download($nombre);
    }

    /** Vista previa del correo que recibe el cliente. */
    public function correo(Factura $factura): FacturaCreadaMail
    {
        return new FacturaCreadaMail($factura->load(['cliente', 'estado', 'formaPago', 'pedido.items']));
    }

    public function reenviar(Factura $factura): RedirectResponse
    {
        return back()->with(
            ...($this->enviarCorreo($factura)
                ? ['success', "Correo reenviado a {$factura->cliente->email}."]
                : ['warning', 'No se pudo enviar el correo. Revisa la configuración de MAIL_*.'])
        );
    }

    private function enviarCorreo(Factura $factura): bool
    {
        $factura->load('cliente');

        try {
            Mail::to($factura->cliente->email, $factura->cliente->nombre)->send(new FacturaCreadaMail($factura));

            return true;
        } catch (Throwable $e) {
            Log::error('No se pudo enviar la factura por correo', ['factura' => $factura->id, 'error' => $e->getMessage()]);

            return false;
        }
    }

    private function datosFormulario(Factura $factura): array
    {
        return [
            'factura' => $factura,
            'clientes' => Cliente::orderBy('nombre')->get(['id', 'nombre', 'email', 'rfc']),
            'estados' => EstadoFactura::orderBy('id')->get(),
            'formasPago' => FormaPago::orderBy('nombre')->get(),
        ];
    }

    private function validar(Request $request, ?Factura $factura = null): array
    {
        $datos = $request->validate([
            'numero' => ['required', 'string', 'max:30', Rule::unique('facturas', 'numero')->ignore($factura)],
            'cliente_id' => ['required', 'exists:clientes,id'],
            'estado_id' => ['required', 'exists:estadosfacturas,id'],
            'forma_pago_id' => ['required', 'exists:formaspago,id'],
            'fecha_emision' => ['required', 'date'],
            'fecha_vencimiento' => ['nullable', 'date', 'after_or_equal:fecha_emision'],
            'subtotal' => ['required', 'numeric', 'min:0.01', 'max:9999999'],
            'detalles' => ['nullable', 'string', 'max:5000'],
            'archivo_pdf' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'archivo_xml' => ['nullable', 'file', 'mimes:xml', 'max:10240'],
        ], [
            'archivo_pdf.mimes' => 'El comprobante debe ser un archivo PDF.',
            'archivo_xml.mimes' => 'El timbre fiscal debe ser un archivo XML.',
        ]);

        unset($datos['archivo_pdf'], $datos['archivo_xml']);

        return $datos;
    }

    private function guardarArchivos(Request $request, Factura $factura): void
    {
        foreach (['archivo_pdf', 'archivo_xml'] as $campo) {
            if ($request->hasFile($campo)) {
                if ($factura->{$campo}) {
                    Storage::disk('public')->delete($factura->{$campo});
                }
                $factura->{$campo} = $request->file($campo)->store('facturas', 'public');
            }
        }
    }
}
