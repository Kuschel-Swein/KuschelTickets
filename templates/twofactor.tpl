{include file="header.tpl" title="2-Faktor Authentisierung"}
<form class="ui form{if $errors['code'] !== false || $errors['token'] !== false} error{/if}{if $success !== false} success{/if}" action="{link url="accountmanagement"}" method="post">
    <div class="field required{if $errors['code'] !== false} error{/if}">
        <label>Sicherheitscode</label>
        <div class="ui input">
            <input type="text" name="code" autocomplete="onetimecode">
        </div>
        <small class="helper">
            Gib den Sicherheitscode zur Bestätigung ein.<br>
            Wenn du das Gerät zur Authentisierung verloren hast, kannst du alternativ einen deiner Backup-Codes eingeben.
        </small>
    </div>
    {$recaptcha}
    <button type="submit" name="submit" class="ui blue submit button">Absenden</button>
    <input type="hidden" name="CRSF" value="{$__KT['CRSF']}">
    {if $errors['code'] !== false || $errors['token'] !== false}
          <ul class="list">
            {if $errors['code'] !== false}
              <li>{$errors['code']}</li>
            {/if}
            {if $errors['token'] !== false}
              <li>{$errors['token']}</li>
            {/if}
          </ul>
        </div>
    {/if}
    {if $success !== false}
        <div class="ui success message">
            <ul class="list">
                <li>{$success}</li>
            </ul>
        </div>
    {/if}
</form>
{include file="footer.tpl"}