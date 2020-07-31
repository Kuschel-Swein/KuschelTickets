{include file="header.tpl" title="Passwort zurücksetzen"}
<div class="ui grid container">
  <div class="column centered">
    {if !$token}
    <form class="ui form{if $errors['email'] !== false || $errors['recaptcha'] !== false} error{/if}{if $result !== false} success{/if}" action="{link url="passwordreset"}" method="post">
      <div class="field required{if $errors['email'] !== false} error{/if}">
        <label>E-Mail Adresse</label>
        <div class="ui input">
          <input type="email" name="email" value="{if isset($tpl['post']['email']) && $result == false}{$tpl['post']['email']}{/if}">
        </div>
      </div>
      {$recaptcha}
      <button type="submit" name="submit" class="ui blue submit button">Absenden</button>
      {if $errors['email'] !== false || $errors['recaptcha'] !== false}
        <div class="ui error message">
          <ul class="list">
            {if $errors['email'] !== false}
              <li>{$errors['email']}</li>
            {/if}
            {if $errors['recaptcha'] !== false}
              <li>{$errors['recaptcha']}</li>
            {/if}
          </ul>
        </div>
      {/if}
      {if $result !== false}
        <div class="ui success message">
          <ul class="list">
              <li>{$result}</li>
          </ul>
        </div>
      {/if}
    </form>
    {else}
      {if $errors['token'] == false}
        <form class="ui form{if $errors['password'] !== false || $errors['password_confirm'] !== false || $errors['recaptcha'] !== false} error{/if}{if $result !== false} success{/if}" action="{link url="passwordreset/token-{$tokenkey}"}" method="post">
          <div class="field required{if $errors['password'] !== false} error{/if}">
            <label>neues Passwort</label>
            <div class="ui input">
              <input type="password" name="password">
            </div>
          </div>
          <div class="field required{if $errors['password_confirm'] !== false} error{/if}">
            <label>neues Passwort bestätigen</label>
            <div class="ui input">
              <input type="password" name="password_confirm">
            </div>
          </div>
          {$recaptcha}
          <button type="submit" name="submit" class="ui blue submit button">Absenden</button>
          {if $errors['password'] !== false || $errors['password_confirm'] !== false || $errors['recaptcha'] !== false}
            <div class="ui error message">
              <ul class="list">
                  {if $errors['password'] !== false}
                    <li>{$errors['password']}</li>
                  {/if}
                  {if $errors['password_confirm'] !== false}
                    <li>{$errors['password_confirm']}</li>
                  {/if}
                  {if $errors['recaptcha'] !== false}
                    <li>{$errors['recaptcha']}</li>
                  {/if}
              </ul>
            </div>
          {/if}
          {if $result !== false}
            <div class="ui success message">
              <ul class="list">
                  <li>{$result}</li>
              </ul>
            </div>
          {/if}
        </form>
      {else}
        <div class="ui error message">
          <ul class="list">
            <li>{$errors['token']}</li>
          </ul>
        </div>
      {/if}
    {/if}
    <div class="ui center aligned basic segment">
      <div class="ui horizontal divider">
        Oder
      </div>
      <div class="ui horizontal bulleted list">
        <a class="item" href="{link url="register"}">Registrieren</a>
        <a class="item" href="{link url="login"}">Einloggen</a>
      </div>
      {include file="__oauth_providers.tpl"}
    </div>
  </div>
</div>
{include file="footer.tpl"}