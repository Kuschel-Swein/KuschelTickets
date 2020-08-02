{if !isset($templates)}
  {assign var="templates" value="true"}
{else}
  {assign var="templates" value="false"}
{/if}
tinymce.init({
    selector: "{$selector}",
    min_height: 300,
    language: "de",
    {if isset($additionalSettings)}{$additionalSettings}{/if}
    plugins: [
      "advlist autolink link image lists charmap preview hr anchor{if $__KT['user']->hasPermission("general.editor.templates") && $templates == "true"} template{/if}",
      "searchreplace wordcount visualblocks code fullscreen",
      "table paste help tabfocus"
    ],
    menubar: 'edit insert view format table',
    menu: {
      edit: { title: 'Edit', items: 'undo redo | cut copy paste | selectall | searchreplace' },
      view: { title: 'View', items: 'code | visualaid visualchars visualblocks | preview fullscreen | help' },
      insert: { title: 'Insert', items: 'image link template codesample inserttable | charmap hr | pagebreak nonbreaking anchor' },
      format: { title: 'Format', items: 'bold italic underline strikethrough superscript subscript codeformat | formats blockformats fontformats fontsizes align | forecolor backcolor | removeformat' },
      table: { title: 'Table', items: 'inserttable | cell row column | tableprops deletetable' }
    },
    toolbar: 'undo redo | styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent',
    mobile: {
      menubar: false
    },
    setup : function(editor) {
      editor.on('init', function() {
          this.getDoc().body.style.fontFamily = 'Lato';
      });
    },
    content_style: "@import url('https://fonts.googleapis.com/css?family=Lato&display=swap'); body { font-family: 'Lato', sans-serif; } h1,h2,h3,h4,h5,h6 { font-family: 'Lato', sans-serif; }",
    font_formats: 'Standart=Lato,Andale Mono=andale mono,times; Arial=arial,helvetica,sans-serif; Arial Black=arial black,avant garde; Book Antiqua=book antiqua,palatino; Comic Sans MS=comic sans ms,sans-serif; Courier New=courier new,courier; Georgia=georgia,palatino; Helvetica=helvetica; Impact=impact,chicago; Symbol=symbol; Tahoma=tahoma,arial,helvetica,sans-serif; Terminal=terminal,monaco; Times New Roman=times new roman,times; Trebuchet MS=trebuchet ms,geneva; Verdana=verdana,geneva; Webdings=webdings; Wingdings=wingdings,zapf dingbats',
    templates: KT.userTemplates,
    default_link_target: "_blank"
});
