<link rel="stylesheet" href="{$__KT['mainurl']}assets/codemirror/codemirror.css">
<style>
.CodeMirror {
  border: 1px solid #ddd;
}
</style>
<script src="{$__KT['mainurl']}assets/codemirror/codemirror.js" type="text/javascript"></script>
<script src="{$__KT['mainurl']}assets/codemirror/smarty.js" type="text/javascript"></script>
<script>
var editor = CodeMirror.fromTextArea(document.querySelector("{$selector}"), {
  lineNumbers: true,
  mode: {
    name: "smarty",
    baseMode: "text/html",
    version: 3
  },
  indentWithTabs: true,
});
</script>