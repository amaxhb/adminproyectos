
<button id="BtnCrearPDF" class="btn">Descargar PDF</button>

<script src="../html2pdf.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const BtnCrearPDF = document.getElementById('BtnCrearPDF');
        const elementoParaConvertir = document.forms[0];
        BtnCrearPDF.addEventListener('click', () => {
            html2pdf()
                .set({
                    margin: 1,
                    filename: 'test.pdf',
                    image: {
                        type: 'jpeg',
                        quality: 0.98
                    },
                    html2canvas: {
                        scale: 2,
                        letterRendering: true,
                    },
                    jsPDF: {
                        unit: "in",
                        format: "a2",
                        orientation: 'portrait'
                    }
                })
                .from(elementoParaConvertir)
                .save()
                .catch(err => console.log(err));
                
        });
    });

</script>