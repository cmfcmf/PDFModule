{formsetinitialfocus inputId='code'}
<div class="z-formrow">
    {formlabel for='code' __text='Code' mandatorysym=true}
    {formtextinput id='code' maxLength='9999' group='data' mandatory=true}
    <em class="z-sub z-formnote">{gt text='In it\'s simplest form, just insert an url here. But there are many other possibilities: %s. There also is an open issue to improve your user experience: %s' tag1='<a href="https://github.com/zxing/zxing/wiki/Barcode-Contents">https://github.com/zxing/zxing/wiki/Barcode-Contents</a>' tag2='<a href="https://github.com/cmfcmf/PDFModule/issues/2">https://github.com/cmfcmf/PDFModule/issues/2</a>'}</em>
</div>
<div class="z-formrow">
    {formlabel for='dimension' __text='1D / 2D' mandatorysym=true}
    {formintinput id='dimension' min=1 max=2 group='data' mandatory=true}
</div>
<div class="z-formrow">
    {formlabel for='codeType' __text='Type' mandatorysym=true}
    {formtextinput id='codeType' maxLength='10' group='data' mandatory=true value=$codeType}
</div>
<div class="z-formrow">
    {formlabel for='color' __text='Color' mandatorysym=true}
    {formtextinput id='color' maxLength=30 group='data' mandatory=true}
    <!-- em class="z-sub z-formnote">{gt text='By the way, this is using your browser\'s native color picker! If you just see a text field, upgrade your browser or enter a valid html color like "#fb0" or "cornflowerblue".'}</em -->
</div>
<div class="z-formrow">
    {formlabel for='qrFormat' __text='Format' mandatorysym=true}
    {php}
        $dom = ZLanguage::getModuleDomain('PDF');
        $items = array (
            array ('text' => __('PNG image', $dom), 'value' => 'png'),
            array ('text' => __('SVG image', $dom), 'value' => 'svg'),
        );
        $this->assign('items', $items);
    {/php}
    {formdropdownlist id='qrFormat' items=$items group='data'}
</div>
<div class="z-formrow">
    {formlabel for='width' __text='Width of a single bar / pixel'}
    {formtextinput id='width' maxLength=3 group='data'}
    <em class="z-sub z-formnote">{gt text='Leave empty for a reasonable default value.'}</em>
</div>
<div class="z-formrow">
    {formlabel for='height' __text='Height of a single bar / pixel'}
    {formtextinput id='height' maxLength=3 group='data'}
    <em class="z-sub z-formnote">{gt text='Leave empty for a reasonable default value.'}</em>
</div>
