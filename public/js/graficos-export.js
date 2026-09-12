// Junta todos los <canvas> de gráficos de la pantalla actual (los que arrancan con id "grafico")
// y los convierte en imágenes PNG en base64, junto con el título de su card.
function capturarGraficos() {
    const canvases = document.querySelectorAll('canvas[id^="grafico"]');
    const graficos = [];
    canvases.forEach(canvas => {
        const card = canvas.closest('.card');
        const titulo = card ? card.querySelector('.card-header')?.innerText.trim() : canvas.id;
        graficos.push({
            titulo: titulo || canvas.id,
            imagen: canvas.toDataURL('image/png', 1.0)
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
