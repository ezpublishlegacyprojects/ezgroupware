{if count($info_tags) > 0}
<strong>Information Tags:</strong><br />
<table class="method-tags" cellspacing="0" cellpadding="0">
{section name=tag loop=$info_tags}
  <tr>
    <td class="indent">{$info_tags[tag].keyword|capitalize}:&nbsp;&nbsp;</td><td>{$info_tags[tag].data}</td>
  </tr>
{/section}
</table>
{/if}

{if count($api_tags) > 0}
<strong>API Tags:</strong><br />
<table class="method-tags" cellspacing="0" cellpadding="0">
{section name=tag loop=$api_tags}
  <tr>
    <td class="indent">{$api_tags[tag].keyword|capitalize}:&nbsp;&nbsp;</td><td>{$api_tags[tag].data}</td>
  </tr>
{/section}
</table>
<br />
{/if}
