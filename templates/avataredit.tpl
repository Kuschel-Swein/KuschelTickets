{include file="header.tpl" title="Avatar-Verwaltung"}
<form class="ui form{if $errors['avatar'] !== false || $errors['upload'] !== false || $errors['token'] !== false} error{/if} {if $success !== false} success{/if}" action="{link url="avataredit"}" method="post" enctype="multipart/form-data">
    <div class="ui grid">
        <div class="two column row">
            <div class="column middle aligned">
                <div class="field">
                    <div class="ui radio checkbox">
                        <input type="radio" name="avatar" value="none" tabindex="0" class="hidden" onchange="toggleElement('#avatarupload', document.getElementById('avatar_upload_selection'))"{if $type == "none"} checked="checked"{/if}>
                        <label>Keinen Avatar verwenden</label>
                        <small class="helper">Bereits hochgeladene Avatare werden bei Auswahl dieser Option gelöscht.</small>
                    </div>
                </div>
            </div>
            <div class="column floated right"></div>
        </div>
    </div>
    <div class="ui grid">
        <div class="two column row">
            <div class="column middle aligned">
                <div class="field">
                    <div class="ui radio checkbox">
                        <input id="avatar_upload_selection" type="radio" name="avatar" value="upload" tabindex="0" class="hidden" onchange="toggleElement('#avatarupload', this)"{if $type == "upload"} checked="checked"{/if}>
                        <label>Eigenen Avatar hochladen</label>
                        <small class="helper">Eigene Avatare dürfen die Dateiendungen {", "|implode:$__KT['avatarextensions']} und maximal eine Dateigröße von {$__KT['avatarsize']|formatbytes} besitzen.</small>
                    </div>
                </div>
                <div class="field{if $errors['upload'] !== false} error{/if}" id="avatarupload"{if $type !== "upload"} style="display: none"{/if}>
                    <label>Hochladen</label>
                    <div class="ui labeled input">
                        <div class="ui icon label">
                            <i class="upload icon margin-0"></i>
                        </div>
                        <input type="text" placeholder="Klicke um eine Datei zu wählen..." readonly="">
                        <input type="file" accept="image/*" name="upload">
                    </div>
                </div>
            </div>
            <div class="column floated right">
                <div class="float-right">
                    <img draggable="false" class="useravatar ui small circular image" src="{if $type !== "gravatar"}{$__KT['user']->getAvatar()}{else}{$__KT['mainurl']}data/avatars/default.png{/if}">
                </div>
            </div>
        </div>
    </div>
    {if $__KT['gravatar']}
    <div class="ui grid">
        <div class="two column row">
            <div class="column middle aligned">
                <div class="field">
                    <div class="ui radio checkbox">
                        <input type="radio" name="avatar" value="gravatar" tabindex="0" class="hidden" onchange="toggleElement('#avatarupload', document.getElementById('avatar_upload_selection'))"{if $type == "gravatar"} checked="checked"{/if}>
                        <label>Gravatar verwenden</label>
                        <small class="helper">Bei einem Gravatar handelt es sich um einen global verfügbaren Avatar (Global Recognized Avatar), welcher mit deiner E-Mail-Adresse („{$__KT['user']->email}“) verknüpft ist. Auf der folgenden Website kannst du einen Gravatar anlegen: <a href="https://www.gravatar.com" target="_blank" data-no-favicon="true" data-titlechanged="true">www.gravatar.com</a><br>Bereits hochgeladene Avatare werden bei Auswahl dieser Option gelöscht.</small>
                    </div>
                </div>
            </div>
            <div class="column floated right">
                <div class="float-right">
                    <img draggable="false" class="useravatar ui small circular image" src="{$gravatarurl}">
                </div>
            </div>
        </div>
    </div>
    <br>
    {$recaptcha}
    <button type="submit" name="submit" class="ui blue submit button">Absenden</button>
    <input type="hidden" name="CRSF" value="{$__KT['CRSF']}">
    {if $errors['avatar'] !== false || $errors['token'] !== false || $errors['upload'] !== false}
        <div class="ui error message">
            <ul class="list">
                {if $errors['avatar'] !== false}
                    <li>{$errors['avatar']}</li>
                {/if}
                {if $errors['upload'] !== false}
                    <li>{$errors['upload']}</li>
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
{/if}
<script>
$('.ui.radio.checkbox').checkbox();
$("input:text").click(function() {
    $(this).parent().find("input:file").click();
});
    
$('input:file', '.ui.labeled.input').on('change', function(e) {
    if(e.target.files[0] !== undefined) {
        var name = e.target.files[0].name;
        $('input:text', $(e.target).parent()).val(name);
    } else {
        $('input:text', $(e.target).parent()).val("");
    }
});
</script>
{include file="footer.tpl"}