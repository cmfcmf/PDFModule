<div class="z-formrow">
    <label for="PDF_QRCode_code">{gt text='Code'}:</label>
    <input type="text" id="PDF_QRCode_code" name="code" maxlength="9999" value="{$code|safetext}" />
</div>
<div class="z-formrow">
    <label for="PDF_QRCode_dimension">{gt text='1D / 2D'}:</label>
    <input type="number" id="PDF_QRCode_dimension" name="dimension" maxlength="1" min="1" max="2" value="{$dimension|safetext}" />
</div>
<div class="z-formrow">
    <label for="PDF_QRCode_type">{gt text='Type'}:</label>
    <input type="text" id="PDF_QRCode_type" name="codetype" maxlength="20" value="{$type|safetext}" />
</div>
<div class="z-formrow">
    <label for="PDF_QRCode_color">{gt text='Color'}:</label>
    <input type="color" id="PDF_QRCode_color" name="color" maxlength="20" value="{$color|safetext}" />
    <em class="z-sub z-formnote">{gt text='By the way, this is using your browser\'s native color picker! If you just see a text field, upgrade your browser or enter a valid html color like "#fb0" or "cornflowerblue".'}</em>
</div>
<div class="z-formrow">
    <label for="PDF_QRCode_format">{gt text='Format'}:</label>
    <select id="PDF_QRCode_format" name="format">
        <option value="png"{if $format == 'png'} selected="selected"{/if}>{gt text='PNG image'}</option>
        <option value="svg"{if $format == 'svg'} selected="selected"{/if}>{gt text='SVG image'}</option>
    </select>
</div>
<div class="z-formrow">
    <label for="PDF_QRCode_width">{gt text='Width of a single bar / pixel'}:</label>
    <input type="number" id="PDF_QRCode_width" name="width" maxlength="20" min="1" value="{$width|safetext}" />
    <em class="z-sub z-formnote">{gt text='Leave empty for a reasonable default value.'}</em>
</div>
<div class="z-formrow">
    <label for="PDF_QRCode_height">{gt text='Height of a single bar / pixel'}:</label>
    <input type="number" id="PDF_QRCode_height" name="height" maxlength="20" min="1" value="{$height|safetext}" />
    <em class="z-sub z-formnote">{gt text='Leave empty for a reasonable default value.'}</em>
</div>
