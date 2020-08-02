{include file="header.tpl" title="Account Verwaltung"}
<form class="ui form{if $errors['password'] !== false || $errors['username'] !== false || $errors['email'] !== false || $errors['password_new'] !== false || $errors['password_new_confirm'] !== false || $errors['twofactor'] !== false || $errors['token'] !== false} error{/if}{if $success['username'] !== false || $success['email'] !== false || $success['password'] !== false || $success['twofactor'] !== false || $success['signature'] !== false} success{/if}" action="{link url="accountmanagement"}" method="post">
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
    {if $__KT['user']->hasPermission("general.account.signature")}
    <div class="ui divider"></div>
    <div class="field">
        <label>Signatur</label>
        <div class="ui input">
            <textarea name="signature" class="wysiwygFix" id="signature">{$__KT['user']->signature}</textarea>
        </div>
    </div>
    {include file="__wysiwyg.tpl" selector="#signature" templates=false}
    {/if}
    {if $__KT['user']->hasPermission("general.account.twofactor")}
    <div class="ui divider"></div>
    <div class="inline field">
        <div class="ui checkbox">
          <input type="checkbox" name="twofactor_enabled" onchange="toggleElement('#twofactor_container', this)"{if $__KT['user']->twofactor->use !== false || $errors['twofactor'] !== false} checked="checked"{/if}>
          <label>2-Faktor Authentisierung aktivieren</label>
        </div>
    </div>
    {assign var="twofactorSecret" value=$twofactor->createSecret()}
    {assign var="twofactorQRCode" value=$twofactor->getQRCode($__KT['user']->username, $twofactorSecret, $__KT['pagetitle'])}
    <div id="twofactor_container"{if $__KT['user']->twofactor->use == false && $errors['twofactor'] == false} style="display: none"{/if}>
        <div class="ui placeholder segment">
            {if $__KT['user']->twofactor->use == false}
            <div class="ui two column stackable center aligned grid">
                <div class="ui vertical divider">
                    <i class="arrow alternate circle right icon"></i>
                </div>
                <div class="middle aligned row">
                    <div class="column">
                        <div class="ui header">
                            <img class="ui fluid image twofactorQRCode" src="{$twofactorQRCode}" draggable="false">
                        </div>
                    </div>
                <div class="column">
                    <p>
                        Scanne den QR-Code mit einer Authenticator-App deiner Wahl (z.B. Authy, Google Authenticator, etc.) ein oder gib den Code „<b>{$twofactorSecret}</b>“ manuell in der App ein.
                    </p>
                    <div class="ui icon header">
                        <div class="field{if $errors['twofactor'] !== false} error{/if}">
                            <label>Sicherheitscode</label>
                            <input type="text" name="twofactor" autocomplete="onetimecode">
                            <input type="hidden" name="twofactor_secret" value="{$twofactorSecret}">
                        </div>
                    </div>
                </div>
                </div>
            </div>
            {else}
                <div class="ui one column stackable aligned grid">
                    <div class="aligned row">
                        <div class="column">
                            <div class="ui header">
                                Backupcodes
                            </div>
                            <ul class="ui list">
                                <li>{$__KT['user']->twofactor->backupcodes[0]}</li>
                                <li>{$__KT['user']->twofactor->backupcodes[1]}</li>
                                <li>{$__KT['user']->twofactor->backupcodes[2]}</li>
                                <li>{$__KT['user']->twofactor->backupcodes[3]}</li>
                                <li>{$__KT['user']->twofactor->backupcodes[4]}</li>
                                <li>{$__KT['user']->twofactor->backupcodes[5]}</li>
                                <li>{$__KT['user']->twofactor->backupcodes[6]}</li>
                                <li>{$__KT['user']->twofactor->backupcodes[7]}</li>
                                <li>{$__KT['user']->twofactor->backupcodes[8]}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            {/if}
        </div>
        <br>
    </div>
    {/if}
    {$recaptcha}
    <button type="submit" name="submit" class="ui blue submit button">Absenden</button>
    <input type="hidden" name="CRSF" value="{$__KT['CRSF']}">
    {if $errors['password'] !== false || $errors['username'] !== false || $errors['email'] !== false || $errors['password_new'] !== false || $errors['password_new_confirm'] !== false || $errors['token'] !== false || $errors['twofactor'] !== false}
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
            {if $errors['twofactor'] !== false}
              <li>{$errors['twofactor']}</li>
            {/if}
            {if $errors['token'] !== false}
              <li>{$errors['token']}</li>
            {/if}
          </ul>
        </div>
    {/if}
    {if $success['username'] !== false || $success['email'] !== false || $success['password'] !== false || $success['twofactor'] !== false || $success['signature'] !== false}
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
                {if $success['twofactor'] !== false}
                    <li>{$success['twofactor']}</li>
                {/if}
                {if $success['signature'] !== false}
                    <li>{$success['signature']}</li>
                {/if}
            </ul>
        </div>
    {/if}
</form>
{include file="footer.tpl"}