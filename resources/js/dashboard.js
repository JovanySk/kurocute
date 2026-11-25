import {
    ArcElement,
    BarController,
    BarElement,
    CategoryScale,
    Chart,
    DoughnutController,
    Legend,
    LinearScale,
    Tooltip,
} from 'chart.js';

Chart.register(BarController, BarElement, CategoryScale, LinearScale, Tooltip, Legend, DoughnutController, ArcElement);

const css = (nombre) => getComputedStyle(document.documentElement).getPropertyValue(nombre).trim();
const dinero = (v) => new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN', maximumFractionDigits: 0 }).format(v);
const reducido = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

const graficas = [];

function tooltipBase() {
    return {
        backgroundColor: css('--surface'),
        titleColor: css('--ink'),
        bodyColor: css('--ink-2'),
        borderColor: css('--line-strong'),
        borderWidth: 1,
        padding: 12,
        cornerRadius: 12,
        boxPadding: 4,
        usePointStyle: true,
        titleFont: { family: css('--kc-body') || 'sans-serif', weight: '600' },
        bodyFont: { family: css('--kc-body') || 'sans-serif' },
    };
}

function ingresos(el) {
    const serie = JSON.parse(el.dataset.serie);
    const superficie = css('--surface');

    return new Chart(el, {
        type: 'bar',
        data: {
            labels: serie.map((m) => m.etiqueta),
            datasets: [
                { label: 'Cobrado', data: serie.map((m) => m.cobrado), backgroundColor: css('--chart-1') },
                { label: 'Por cobrar', data: serie.map((m) => m.pendiente), backgroundColor: css('--chart-2') },
            ].map((d) => ({
                ...d,
                stack: 'ingresos',
                borderColor: superficie,
                borderWidth: { top: 2, bottom: 0, left: 0, right: 0 },
                borderSkipped: false,
                borderRadius: 4,
                maxBarThickness: 26,
                hoverBackgroundColor: d.backgroundColor,
            })),
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: reducido ? false : { duration: 900, easing: 'easeOutQuart' },
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    ...tooltipBase(),
                    callbacks: {
                        label: (ctx) => ` ${ctx.dataset.label}: ${dinero(ctx.parsed.y)}`,
                        footer: (items) => `Total: ${dinero(items.reduce((s, i) => s + i.parsed.y, 0))}`,
                    },
                    footerColor: css('--ink'),
                },
            },
            scales: {
                x: {
                    stacked: true,
                    grid: { display: false },
                    border: { display: false },
                    ticks: { color: css('--ink-3'), font: { size: 11 } },
                },
                y: {
                    stacked: true,
                    border: { display: false },
                    grid: { color: css('--line'), drawTicks: false },
                    ticks: { color: css('--ink-3'), padding: 8, font: { size: 11 }, maxTicksLimit: 5, callback: (v) => dinero(v) },
                },
            },
        },
    });
}

function estados(el) {
    const datos = JSON.parse(el.dataset.estados);
    const colores = { pagada: '--ok', pendiente: '--warn', vencida: '--danger', cancelada: '--ink-3' };

    return new Chart(el, {
        type: 'doughnut',
        data: {
            labels: datos.map((d) => d.nombre),
            datasets: [
                {
                    data: datos.map((d) => d.cantidad),
                    backgroundColor: datos.map((d) => css(colores[d.clave] || '--ink-3')),
                    borderColor: css('--surface'),
                    borderWidth: 3,
                    hoverOffset: 6,
                    borderRadius: 6,
                },
            ],
        },
        options: {
            cutout: '72%',
            responsive: true,
            maintainAspectRatio: false,
            animation: reducido ? false : { duration: 900, easing: 'easeOutQuart' },
            plugins: {
                legend: { display: false },
                tooltip: {
                    ...tooltipBase(),
                    callbacks: {
                        label: (ctx) => ` ${ctx.label}: ${ctx.parsed} facturas (${dinero(datos[ctx.dataIndex].monto)})`,
                    },
                },
            },
        },
    });
}

function dibujar() {
    graficas.splice(0).forEach((g) => g.destroy());
    document.querySelectorAll('[data-grafica="ingresos"]').forEach((el) => graficas.push(ingresos(el)));
    document.querySelectorAll('[data-grafica="estados"]').forEach((el) => graficas.push(estados(el)));
}

dibujar();
window.addEventListener('kc:tema', dibujar);
