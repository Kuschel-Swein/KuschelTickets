{include file="header.tpl" title="Account Verwaltung"}
<form class="ui form{if $errors['password'] !== false || $errors['username'] !== false || $errors['email'] !== false || $errors['password_new'] !== false || $errors['password_new_confirm'] !== false} error{/if}{if $success['username'] !== false || $success['email'] !== false || $success['password'] !== false} success{/if}" action="{link url="accountmanagement"}" method="post">
    <div class="field required{if $errors['password'] !== false} error{/if}">
        <label>Passwort</label>
        <div class="ui input">
            <input type="password" name="password">
        </div>
        <small class="helper">gib hier dein aktuelles Passwort zur Bestätigung der Anfrage ein.</small>
    </div>
    <div class="ui divider"></div>
    <div class="field required{if $errors['username'] !== false} error{/if}">
        <label>Benutzername</label>
        <div class="ui input">
            <input type="text" name="username" value="{$__KT['user']->username}">
        </div>
    </div>
    <div class="field{if $errors['email'] !== false} error{/if}">
        <label>E-Mail Adresse</label>
        <div class="ui input">
            <input type="email" name="email" placeholder="{$__KT['user']->email}">
        </div>
    </div>
    <div class="ui divider"></div>
    <div class="field{if $errors['password_new'] !== false} error{/if}">
        <label>neues Passwort</label>
        <div class="ui input">
            <input type="password" name="password_new">
        </div>
    </div>
    <div class="field{if $errors['password_new_confirm'] !== false} error{/if}">
        <label>neues Passwort bestätigen</label>
        <div class="ui input">
            <input type="password" name="password_new_confirm">
        </div>
    </div>
    {$recaptcha}
    <button type="submit" name="submit" class="ui blue submit button">Absenden</button>
    <input type="hidden" name="CRSF" value="{$__KT['CRSF']}">
    {if $errors['password'] !== false || $errors['username'] !== false || $errors['email'] !== false || $errors['password_new'] !== false || $errors['password_new_confirm'] !== false || $errors['token'] !== false}
        <div class="ui error message">
          <ul class="list">
            {if $errors['password'] !== false}
              <li>{$errors['password']}</li>
            {/if}
            {if $errors['username'] !== false}
              <li>{$errors['username']}</li>
            {/if}
            {if $errors['email'] !== false}
              <li>{$errors['email']}</li>
            {/if}
            {if $errors['password_new'] !== false}
              <li>{$errors['password_new']}</li>
            {/if}
            {if $errors['password_new_confirm'] !== false}
              <li>{$errors['password_new_confirm']}</li>
            {/if}
            {if $errors['token'] !== false}
              <li>{$errors['token']}</li>
            {/if}
          </ul>
        </div>
    {/if}
    {if $success['username'] !== false || $success['email'] !== false || $success['password'] !== false}
        <div class="ui success message">
            <ul class="list">
                {if $success['username'] !== false}
                    <li>{$success['username']}</li>
                {/if}
                {if $success['email'] !== false}
                    <li>{$success['email']}</li>
                {/if}
                {if $success['password'] !== false}
                    <li>{$success['password']}</li>
                {/if}
            </ul>
        </div>
    {/if}
</form>
{include file="footer.tpl"}