<script src="{$__KT['mainurl']}/assets/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
{if !isset($templates)}
  {assign var="templates" value="true"}
{else}
  {assign var="templates" value="false"}
{/if}
<script>
{include file="__wysiwygInit.tpl" selector=$selector templates=$templates}
</script>