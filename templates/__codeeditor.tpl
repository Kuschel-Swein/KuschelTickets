<link rel="stylesheet" href="{$__KT['mainurl']}assets/codemirror/codemirror.css">
<style>
.CodeMirror {
  border: 1px solid #ddd;
}
</style>
<script src="{$__KT['mainurl']}assets/codemirror/codemirror.js" type="text/javascript"></script>
{if $type == "html"}
<script src="{$__KT['mainurl']}assets/codemirror/xml.js" type="text/javascript"></script>
{elseif $type == "smarty"}
<script src="{$__KT['mainurl']}assets/codemirror/smarty.js" type="text/javascript"></script>
{else}
<script src="{$__KT['mainurl']}assets/codemirror/{$type}.js" type="text/javascript"></script>
{/if}
<script>
var editor = CodeMirror.fromTextArea(document.querySelector("{$selector}"), {
  lineNumbers: true,
  mode: {if $type == "html"}"text/html",{elseif $type == "smarty"}{ name: "smarty", baseMode: "text/html", version: 3 },{else}{if isset($modeOptions)}{$modeOptions}{/if}{/if}
  indentWithTabs: true,
});
</script>