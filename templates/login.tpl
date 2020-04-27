{include file="header.tpl" title="Login"}
<div class="ui grid container">
  <div class="column centered">
    <form class="ui form{if $errors['username'] !== false || $errors['password'] !== false || $errors['token'] !== false} error{/if}" action="{link url="login"}" method="post">
      <div class="field required{if $errors['username'] !== false} error{/if}">
        <label>Benutzername</label>
        <div class="ui left input">
          <input type="text" name="username" value="{if isset($tpl['post']['username'])}{$tpl['post']['username']}{/if}">
        </div>
      </div>
      <div class="field required{if $errors['password'] !== false} error{/if}">
        <label>Passwort</label>
        <div class="ui left input">
          <input type="password" name="password">
        </div>
      </div>
      {$recaptcha}
      <button type="submit" name="submit" class="ui blue submit button">Absenden</button>
      {if $errors['username'] !== false || $errors['password'] !== false || $errors['token'] !== false}
        <div class="ui error message">
          <ul class="list">
            {if $errors['username'] !== false}
              <li>{$errors['username']}</li>
            {/if}
            {if $errors['password'] !== false}
              <li>{$errors['password']}</li>
            {/if}
            {if $errors['token'] !== false}
              <li>{$errors['token']}</li>
            {/if}
          </ul>
        </div>
      {/if}
    </form>
    <div class="ui center aligned basic segment">
      <div class="ui horizontal divider">
        Oder
      </div>
      <div class="ui horizontal bulleted list">
        <a class="item" href="{link url="register"}">Registrieren</a>
        <a class="item" href="{link url="passwordreset"}">Passwort vergessen</a>
      </div>
      {include file="oauth_providers.tpl"}
    </div>
  </div>
</div>
{include file="footer.tpl"}