{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="info" size="small"}
    <h3>{gt text='Test'}</h3>
</div>
{if $themeInstalled}
    {pdfLink tag=true __text='Download this page as PDF'}
    <br />
{/if}

{barcode1D code="test"}
<br />
{barcode1D code="123abc" color='green' width='3' height='50'}
<br />
{barcode2D code="https://github.com/cmfcmf/PDFModule"}
<br />
{barcode2D code="https://github.com/cmfcmf/PDFModule" color='orange' type='DATAMATRIX'}
<br />

{adminfooter}
