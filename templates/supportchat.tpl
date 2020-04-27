{include file="header.tpl" title="Supportchat"}
<div class="ui container grid stackable form">
<div class="four wide column">
</div>
<div class="five wide column">
</div>
<div class="five wide column right floated">
  <br>
  {if $__KT['user']->hasPermission("mod.supportchat.create")}
    <a class="ui blue button right floated" href="javascript:chat.open()" id="openChat">Chat öffnen</a>
  {/if}
</div>
</div>
<div class="ui piled segment">
  <div class="ui relaxed divided list" id="chatList">
  </div>
  <div class="ui minimal comments" id="chatSection" style="display: none">
    <div class="ui dividing header"><h3>SupportChat <span class="float-right" data-tooltip="Chat verlassen"><i class="icon pointer close float-right" id="chatSection_close"></i></span></h3></div>
    <div id="chatSection_list"></div>
    <form class="ui reply form" action="#" id="chatSection_form">
      <div class="ui action input field" id="chatSection_answer_field">
          <input type="text" id="chatSection_answer" autocomplete="off" autofocus>
          <button class="ui icon blue button" accesskey="s" id="chatSection_answer_submit" type="submit">
              <i class="icon paper plane"></i>
          </button>
      </div>
    </form>
  </div>
</div>

<script src="{$__KT['mainurl']}assets/chat.js"></script>
<script>
chat.init();
</script>
{include file="footer.tpl"}