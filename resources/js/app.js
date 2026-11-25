import './bootstrap';

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import focus from '@alpinejs/focus';

Alpine.plugin(collapse);
Alpine.plugin(focus);

/* Tema claro / oscuro: se guarda en localStorage y respeta la preferencia del sistema. */
Alpine.store('tema', {
    oscuro: document.documentElement.dataset.theme === 'dark',
    alternar() {
        this.oscuro = !this.oscuro;
        document.documentElement.dataset.theme = this.oscuro ? 'dark' : 'light';
        try {
            localStorage.setItem('kc-tema', this.oscuro ? 'dark' : 'light');
        } catch (e) {
            /* almacenamiento no disponible */
        }
        window.dispatchEvent(new CustomEvent('kc:tema'));
    },
});

/* Carrito (pedido) compartido entre el boton de la barra, las tarjetas y el cajon lateral. */
Alpine.store('carrito', {
    abierto: false,
    cargando: false,
    cantidad: Number(document.body.dataset.carrito || 0),
    total_formato: '$0.00',
    partidas: [],
    aviso: null,
    async cargar() {
        const { data } = await window.axios.get('/carrito/resumen');
        this.aplicar(data);
    },
    async abrir() {
        this.abierto = true;
        await this.cargar();
    },
    async agregar(productoId, cantidad = 1) {
        this.cargando = true;
        try {
            const { data } = await window.axios.post('/carrito', { producto_id: productoId, cantidad });
            this.aplicar(data);
            this.avisar(data.mensaje);
        } catch (error) {
            this.avisar(error.response?.data?.message || 'No pudimos agregar el producto.');
        } finally {
            this.cargando = false;
        }
    },
    async cambiar(productoId, cantidad) {
        const { data } = await window.axios.patch(`/carrito/${productoId}`, { cantidad });
        this.aplicar(data);
    },
    aplicar(data) {
        this.cantidad = data.cantidad;
        this.total_formato = data.total_formato;
        this.partidas = data.partidas;
    },
    avisar(texto) {
        this.aviso = texto;
        clearTimeout(this._t);
        this._t = setTimeout(() => (this.aviso = null), 2600);
    },
});

/* Catálogo con búsqueda instantánea vía AJAX (debounce) y filtros por categoría. */
Alpine.data('catalogo', (inicial) => ({
    productos: inicial.productos,
    q: inicial.filtros.q || '',
    categoria: inicial.filtros.categoria || '',
    orden: inicial.filtros.orden || 'recientes',
    buscando: false,
    seleccionado: null,
    _peticion: 0,
    init() {
        this.$watch('q', () => this.buscar());
        this.$watch('categoria', () => this.buscar(0));
        this.$watch('orden', () => this.buscar(0));
    },
    buscar(espera = 220) {
        clearTimeout(this._t);
        this._t = setTimeout(async () => {
            const id = ++this._peticion;
            this.buscando = true;
            const params = { q: this.q || undefined, categoria: this.categoria || undefined, orden: this.orden };
            try {
                const { data } = await window.axios.get('/catalogo/buscar', { params });
                if (id === this._peticion) {
                    this.productos = data.productos;
                    const url = new URL(window.location);
                    ['q', 'categoria'].forEach((k) => (this[k] ? url.searchParams.set(k, this[k]) : url.searchParams.delete(k)));
                    window.history.replaceState({}, '', url);
                }
            } finally {
                if (id === this._peticion) this.buscando = false;
            }
        }, espera);
    },
    limpiar() {
        this.q = '';
        this.categoria = '';
    },
    abrir(producto) {
        this.seleccionado = producto;
    },
}));

/* Vista previa de imagen antes de subirla. */
Alpine.data('vistaImagen', (actual = null) => ({
    url: actual,
    nombre: null,
    elegir(evento) {
        const archivo = evento.target.files[0];
        if (!archivo) return;
        this.nombre = archivo.name;
        this.url = URL.createObjectURL(archivo);
    },
}));

/* Vista previa de PDF / XML: nombre, peso y visor embebido para PDF. */
Alpine.data('vistaArchivo', (actual = null) => ({
    archivo: actual,
    pdfUrl: null,
    elegir(evento) {
        const archivo = evento.target.files[0];
        if (!archivo) return;
        const kb = archivo.size / 1024;
        this.archivo = { nombre: archivo.name, peso: kb > 1024 ? `${(kb / 1024).toFixed(1)} MB` : `${Math.max(1, Math.round(kb))} KB`, nuevo: true };
        this.pdfUrl = archivo.type === 'application/pdf' ? URL.createObjectURL(archivo) : null;
    },
}));

/* Calculo en vivo de IVA y total en el formulario de factura. */
Alpine.data('importes', (subtotal = 0) => ({
    subtotal: Number(subtotal) || 0,
    get iva() {
        return Math.round(this.subtotal * 0.16 * 100) / 100;
    },
    get total() {
        return Math.round((this.subtotal + this.iva) * 100) / 100;
    },
    dinero(valor) {
        return new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(valor || 0);
    },
}));

window.Alpine = Alpine;
Alpine.start();

/* Animaciones de entrada al hacer scroll. */
const observador = new IntersectionObserver(
    (entradas) => {
        entradas.forEach((entrada) => {
            if (entrada.isIntersecting) {
                entrada.target.classList.add('is-visible');
                observador.unobserve(entrada.target);
            }
        });
    },
    { rootMargin: '0px 0px -8% 0px', threshold: 0.08 },
);

const observarReveal = () => document.querySelectorAll('.reveal:not(.is-visible)').forEach((el) => observador.observe(el));
observarReveal();
document.addEventListener('kc:reveal', observarReveal);
