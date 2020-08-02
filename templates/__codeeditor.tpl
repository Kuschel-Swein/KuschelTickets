<link rel="stylesheet" href="{$__KT['mainurl']}assets/codemirror/codemirror.css">
<style>
.CodeMirror {
  border: 1px solid #ddd;
}
</style>
<script src="{$__KT['mainurl']}assets/codemirror/codemirror.js" type="text/javascript"></script>
{if $type == "html" || $type == "smarty"}
<script src="{$__KT['mainurl']}assets/codemirror/xml.js" type="text/javascript"></script>
<script src="{$__KT['mainurl']}assets/codemirror/htmlmixed.js" type="text/javascript"></script>
<script src="{$__KT['mainurl']}assets/codemirror/css.js" type="text/javascript"></script>
<script src="{$__KT['mainurl']}assets/codemirror/javascript.js" type="text/javascript"></script>
{if $type == "smarty"}
<script src="{$__KT['mainurl']}assets/codemirror/smarty.js" type="text/javascript"></script>
{/if}
{else}
<script src="{$__KT['mainurl']}assets/codemirror/{$type}.js" type="text/javascript"></script>
{/if}
<script>
var editor = CodeMirror.fromTextArea(document.querySelector("{$selector}"), {
  lineNumbers: true,
  mode: {if $type == "html"}"text/html",{elseif $type == "smarty"}{ name: "smarty", baseMode: "text/html" },{else}{if isset($modeOptions)}{$modeOptions}{/if}{/if}
  indentWithTabs: true,
  matchBrackets: true
});
</script>