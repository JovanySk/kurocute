@php
    $editando = $factura->exists;
    $archivoActual = fn (?string $ruta) => $ruta ? ['nombre' => basename($ruta), 'peso' => 'Archivo actual', 'url' => Storage::disk('public')->url($ruta)] : null;
@endphp
<x-app-layout :titulo="$editando ? 'Editar '.$factura->numero : 'Nueva factura'">
    <x-slot name="header">
        <div>
            <a href="{{ $editando ? route('facturas.show', $factura) : route('facturas.index') }}" class="inline-flex items-center gap-1.5 text-sm text-ink-3 hover:text-ink"><x-icono nombre="izquierda" clase="size-4" /> Volver</a>
            <h1 class="mt-3 font-display text-3xl font-semibold tracking-tight">{{ $editando ? 'Editar factura' : 'Nueva factura' }}</h1>
            <p class="mt-1.5 text-ink-2">{{ $editando ? 'Los cambios no reenvian el correo.' : 'Al guardar, el cliente recibe la factura en PDF por correo.' }}</p>
        </div>
    </x-slot>

    <form method="POST" enctype="multipart/form-data" action="{{ $editando ? route('facturas.update', $factura) : route('facturas.store') }}"
        class="grid gap-4 xl:grid-cols-[1fr_22rem]" x-data="importes(@js(old('subtotal', $factura->subtotal)))">
        @csrf
        @if ($editando) @method('PUT') @endif

        <div class="space-y-4">
            <section class="shell">
                <div class="core p-6 sm:p-8">
                    <h2 class="font-semibold">Datos generales</h2>
                    <div class="mt-6 grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="numero" class="label">Folio</label>
                            <input id="numero" name="numero" value="{{ old('numero', $factura->numero) }}" required class="field num">
                            <x-input-error :messages="$errors->get('numero')" />
                        </div>
                        <div>
                            <label for="cliente_id" class="label">Cliente</label>
                            <select id="cliente_id" name="cliente_id" required class="field">
                                <option value="">Selecciona un cliente</option>
                                @foreach ($clientes as $c)
                                    <option value="{{ $c->id }}" @selected(old('cliente_id', $factura->cliente_id) == $c->id)>{{ $c->nombre }}{{ $c->rfc ? ' ('.$c->rfc.')' : '' }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('cliente_id')" />
                        </div>
                        <div>
                            <label for="fecha_emision" class="label">Fecha de emisión</label>
                            <input id="fecha_emision" type="date" name="fecha_emision" value="{{ old('fecha_emision', $factura->fecha_emision?->format('Y-m-d')) }}" required class="field">
                            <x-input-error :messages="$errors->get('fecha_emision')" />
                        </div>
                        <div>
                            <label for="fecha_vencimiento" class="label">Fecha de vencimiento</label>
                            <input id="fecha_vencimiento" type="date" name="fecha_vencimiento" value="{{ old('fecha_vencimiento', $factura->fecha_vencimiento?->format('Y-m-d')) }}" class="field">
                            <x-input-error :messages="$errors->get('fecha_vencimiento')" />
                        </div>
                        <div>
                            <label for="forma_pago_id" class="label">Forma de pago</label>
                            <select id="forma_pago_id" name="forma_pago_id" required class="field">
                                @foreach ($formasPago as $fp)
                                    <option value="{{ $fp->id }}" @selected(old('forma_pago_id', $factura->forma_pago_id) == $fp->id)>{{ $fp->nombre }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('forma_pago_id')" />
                        </div>
                        <fieldset>
                            <legend class="label">Estado</legend>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach ($estados as $e)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="estado_id" value="{{ $e->id }}" class="peer sr-only" @checked(old('estado_id', $factura->estado_id) == $e->id)>
                                        <span class="flex items-center justify-center rounded-xl px-3 py-2 text-sm font-medium text-ink-2 ring-1 ring-line-strong transition peer-checked:bg-ink peer-checked:text-bg peer-checked:ring-ink peer-focus-visible:ring-2 peer-focus-visible:ring-accent">{{ $e->nombre }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <x-input-error :messages="$errors->get('estado_id')" />
                        </fieldset>
                        <div class="sm:col-span-2">
                            <label for="detalles" class="label">Concepto o detalles</label>
                            <textarea id="detalles" name="detalles" rows="3" class="field" placeholder="Ej. Surtido de peluches y papelería para reventa">{{ old('detalles', $factura->detalles) }}</textarea>
                            <x-input-error :messages="$errors->get('detalles')" />
                        </div>
                    </div>
                </div>
            </section>

            <section class="shell">
                <div class="core p-6 sm:p-8">
                    <h2 class="font-semibold">Comprobantes</h2>
                    <p class="mt-1 text-sm text-ink-2">Opcional. Adjunta el PDF y el XML timbrados por tu proveedor de facturación.</p>
                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        @foreach ([['archivo_pdf', 'PDF del comprobante', 'application/pdf,.pdf', 'pdf', 'Solo PDF, máx. 10 MB'], ['archivo_xml', 'XML timbrado', '.xml,application/xml,text/xml', 'xml', 'Solo XML, máx. 10 MB']] as [$campo, $titulo, $acepta, $icono, $ayuda])
                            <div x-data="vistaArchivo(@js($archivoActual($factura->{$campo})))">
                                <label for="{{ $campo }}" class="group flex cursor-pointer items-center gap-4 rounded-2xl border-2 border-dashed border-line-strong bg-surface-2 p-4 transition hover:border-brand/60">
                                    <span class="inline-flex size-12 shrink-0 items-center justify-center rounded-2xl bg-brand-soft text-brand transition duration-500 group-hover:scale-105"><x-icono :nombre="$icono" clase="size-6" /></span>
                                    <span class="min-w-0">
                                        <span class="block text-sm font-semibold">{{ $titulo }}</span>
                                        <span class="block truncate text-xs text-ink-3" x-text="archivo ? archivo.nombre + ' · ' + archivo.peso : '{{ $ayuda }}'"></span>
                                    </span>
                                    <span x-show="archivo?.nuevo" x-cloak class="badge badge-ok ml-auto">Listo</span>
                                </label>
                                <input id="{{ $campo }}" name="{{ $campo }}" type="file" accept="{{ $acepta }}" class="sr-only" @change="elegir($event)">
                                <template x-if="archivo?.url && !archivo?.nuevo"><a :href="archivo.url" target="_blank" class="link mt-2 inline-block text-xs">Ver archivo actual</a></template>
                                <template x-if="pdfUrl">
                                    <iframe :src="pdfUrl" class="mt-3 h-64 w-full rounded-2xl ring-1 ring-line" title="Vista previa del PDF"></iframe>
                                </template>
                                <x-input-error :messages="$errors->get($campo)" />
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        </div>

        <aside class="space-y-4">
            <section class="panel p-6 xl:sticky xl:top-28">
                <h2 class="font-semibold">Importe</h2>
                <label for="subtotal" class="label mt-5">Subtotal (sin IVA)</label>
                <div class="relative">
                    <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-ink-3">$</span>
                    <input id="subtotal" name="subtotal" type="number" step="0.01" min="0.01" required x-model.number="subtotal" class="field num pl-8 text-lg font-semibold">
                </div>
                <x-input-error :messages="$errors->get('subtotal')" />
                <dl class="mt-6 space-y-3 text-sm">
                    <div class="flex justify-between"><dt class="text-ink-2">Subtotal</dt><dd class="num" x-text="dinero(subtotal)"></dd></div>
                    <div class="flex justify-between"><dt class="text-ink-2">IVA 16%</dt><dd class="num" x-text="dinero(iva)"></dd></div>
                    <div class="flex items-baseline justify-between border-t border-line pt-3"><dt class="font-semibold">Total</dt><dd class="num font-display text-2xl font-semibold" x-text="dinero(total)"></dd></div>
                </dl>
                <button type="submit" class="btn btn-primary mt-6 w-full justify-between py-3.5 pl-6 pr-3">
                    {{ $editando ? 'Guardar cambios' : 'Crear y enviar' }} <span class="btn-orb"><x-icono nombre="{{ $editando ? 'check' : 'enviar' }}" clase="size-4" /></span>
                </button>
            </section>
        </aside>
    </form>
</x-app-layout>
