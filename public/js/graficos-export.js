// ============================================================
//  Tema de gráficos GamaStore (Chart.js) + exportación PDF/Excel
// ============================================================

// Colores compartidos por todos los gráficos
const GS_PALETA = ['#a855f7', '#ec4899', '#38bdf8', '#fbbf24', '#34d399', '#fb923c', '#818cf8', '#f472b6'];
const GS_FONDO  = '#14101f';   // mismo color que los paneles

if (window.Chart) {
    Chart.defaults.color        = '#a69bbd';
    Chart.defaults.borderColor  = 'rgba(255,255,255,.07)';
    Chart.defaults.font.family  = "'Inter', system-ui, sans-serif";
    Chart.defaults.plugins.legend.labels.usePointStyle = true;
}

// Toma una imagen de cada <canvas id="grafico...">.
// Los canvas son transparentes y con texto claro, así que se les pone un fondo sólido
// para que se lean bien en el PDF y en el Excel.
function capturarGraficos() {
    const canvases = document.querySelectorAll('canvas[id^="grafico"]');
    const graficos = [];

    canvases.forEach(canvas => {
        const card   = canvas.closest('.panel, .card');
        const cab    = card ? card.querySelector('.panel-head, .card-header') : null;
        const titulo = cab ? cab.innerText.trim() : canvas.id;

        const tmp = document.createElement('canvas');
        tmp.width  = canvas.width;
        tmp.height = canvas.height;
        const ctx = tmp.getContext('2d');
        ctx.fillStyle = GS_FONDO;
        ctx.fillRect(0, 0, tmp.width, tmp.height);
        ctx.drawImage(canvas, 0, 0);

        graficos.push({
            titulo: titulo || canvas.id,
            imagen: tmp.toDataURL('image/png', 1.0)
        });
    });
    return graficos;
}

// Arma un PDF (uno por página) con todos los gráficos, usando jsPDF. Todo del lado del navegador.
function exportarGraficosPDF(nombreArchivo = 'graficos') {
    const graficos = capturarGraficos();
    if (graficos.length === 0) {
        alert('No hay gráficos para exportar en esta pantalla.');
        return;
    }

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF({ orientation: 'landscape', unit: 'pt', format: 'a4' });
    const pageWidth = doc.internal.pageSize.getWidth();
    const pageHeight = doc.internal.pageSize.getHeight();

    graficos.forEach((g, i) => {
        if (i > 0) doc.addPage();
        doc.setFontSize(14);
        doc.text(g.titulo, 40, 40);

        const imgProps = doc.getImageProperties(g.imagen);
        const maxWidth = pageWidth - 80;
        const maxHeight = pageHeight - 100;
        let w = imgProps.width, h = imgProps.height;
        const ratio = Math.min(maxWidth / w, maxHeight / h);
        w *= ratio; h *= ratio;

        doc.addImage(g.imagen, 'PNG', 40, 60, w, h);
    });

    doc.save(`${nombreArchivo}.pdf`);
}

// Manda las imágenes de los gráficos al servidor, que las embebe en un Excel real (PhpSpreadsheet).
function exportarGraficosExcel(tipo, nombreArchivo = 'graficos') {
    const graficos = capturarGraficos();
    if (graficos.length === 0) {
        alert('No hay gráficos para exportar en esta pantalla.');
        return;
    }

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'index.php?action=reportes_graficos_excel';
    form.style.display = 'none';

    const inputTipo = document.createElement('input');
    inputTipo.name = 'tipo';
    inputTipo.value = tipo;
    form.appendChild(inputTipo);

    const inputGraficos = document.createElement('input');
    inputGraficos.name = 'graficos';
    inputGraficos.value = JSON.stringify(graficos);
    form.appendChild(inputGraficos);

    document.body.appendChild(form);
    form.submit();
    form.remove();
}