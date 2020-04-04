<h3>Willkommen im KuschelTickets ACP, {$__KT['user']->getUserName()}</h3>
<p>Hier kannst du KuschelTickets konfigurieren, ebenfalls kannst du hier deine Konfigurationsdatei auf einer schönen Oberfläche verwalten.</p>
<p></p>
{if $site}
<div class="ui warning message">
    <ul class="list">
        <li>Du verwendest eine veraltete Version von KuschelTickets! Die neuste Version findest du immer auf dem GitHub des Projekts.</li>
    </ul>
</div>
{/if}