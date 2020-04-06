<script src="{$__KT['mainurl']}/assets/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
{if $templates == null}
  {assign var="templates" value="true"}
{/if}
<script>
tinymce.init({
    selector: "{$selector}",
    height: 300,
    language: "de",
    plugins: [
      "advlist autolink link image lists charmap preview hr anchor{if $__KT['user']->hasPermission("general.editor.templates") && $templates == "true"} template{/if}",
      "searchreplace wordcount visualblocks code fullscreen media",
      "table paste help tabfocus toc"
    ],
    templates: KT.userTemplates,
    default_link_target: "_blank"
});
</script>