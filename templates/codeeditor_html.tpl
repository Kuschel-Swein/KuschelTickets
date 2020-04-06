<link rel="stylesheet" href="{$__KT['mainurl']}assets/codemirror/codemirror.css">
<style>
.CodeMirror {
  border: 1px solid #ddd;
}
</style>
<script src="{$__KT['mainurl']}assets/codemirror/codemirror.js" type="text/javascript"></script>
<script src="{$__KT['mainurl']}assets/codemirror/xml.js" type="text/javascript"></script>
<script>
var editor = CodeMirror.fromTextArea(document.querySelector("{$selector}"), {
  lineNumbers: true,
  mode: "text/html",
  indentWithTabs: true,
});
</script>