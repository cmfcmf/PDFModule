{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="help" size="small"}
    <h3>{gt text='Help'}</h3>
</div>
{if $themeInstalled}
    <div class="z-informationmsg">{gt text='The PDF theme is installed. Good job!'}</div>
{else}
    <div class="z-warningmsg">{gt text='Please consider installing the PDF theme for all features!'}</div>
{/if}
{$help}
{adminfooter}
