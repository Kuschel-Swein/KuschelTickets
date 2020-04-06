{include file="header.tpl" title="Registrierung"}
<div class="ui grid container">
  <div class="column centered">
    <form class="ui form{if $errors['username'] !== false || $errors['email'] !== false || $errors['password'] !== false || $errors['password_confirm'] !== false || $errors['legal_notice'] !== false || $errors['token'] !== false} error{/if}{if $success == true} success{/if}" action="{link url="register"}" method="post">
      <div class="field required{if $errors['username'] !== false} error{/if}">
        <label>Benutzername</label>
        <div class="ui input">
          <input type="text" name="username" value="{if isset($tpl['post']['username']) && !$success}{$tpl['post']['username']}{/if}">
        </div>
      </div>
      <div class="field required{if $errors['email'] !== false} error{/if}">
        <label>E-Mail Adresse</label>
        <div class="ui input">
          <input type="email" name="email" value="{if isset($tpl['post']['email']) && !$success}{$tpl['post']['email']}{/if}">
        </div>
      </div>
      <div class="field required{if $errors['password'] !== false} error{/if}">
        <label>Passwort</label>
        <div class="ui input">
          <input type="password" name="password">
        </div>
      </div>
      <div class="field required{if $errors['password_confirm'] !== false && !$success} error{/if}">
        <label>Passwort bestätigen</label>
        <div class="ui input">
          <input type="password" name="password_confirm">
        </div>
      </div>
      <div class="inline field required{if $errors['legal_notice'] !== false} error{/if}">
        <div class="ui checkbox">
          <input type="checkbox" name="legal_notice">
          <label>Ich akzeptiere die <a href="index.php?page/legal-notice">Datenschutzerklärung</a></label>
        </div>
      </div>
      {$recaptcha}
      <button type="submit" name="submit" class="ui blue submit button">Absenden</button>
      {if $success == true}
      <div class="ui success message">
        <ul class="list">
            <li>Dein Account wurde erfolgreich erstellt, du musst nur noch deine E-Mail Adresse bestätigen.</li>
            <li>Hierzu haben wir dir eine E-Mail mit einem Link gesendet.</li>
        </ul>
      </div>
      {/if}
      {if $errors['username'] !== false || $errors['email'] !== false || $errors['password'] !== false || $errors['password_confirm'] !== false || $errors['legal_notice'] !== false || $errors['token'] !== false}
        <div class="ui error message">
          <ul class="list">
            {if $errors['username'] !== false}
              <li>{$errors['username']}</li>
            {/if}
            {if $errors['email'] !== false}
              <li>{$errors['email']}</li>
            {/if}
            {if $errors['password'] !== false}
              <li>{$errors['password']}</li>
            {/if}
            {if $errors['password_confirm'] !== false}
              <li>{$errors['password_confirm']}</li>
            {/if}
            {if $errors['legal_notice'] !== false}
              <li>{$errors['legal_notice']}</li>
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
        <a class="item" href="{link url="passwordreset"}">Passwort vergessen</a>
        <a class="item" href="{link url="login"}">Einloggen</a>
      </div>
    </div>
  </div>
</div>
{include file="footer.tpl"}