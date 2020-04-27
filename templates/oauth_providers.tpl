{if $__KT['oauth']['google']['use'] || $__KT['oauth']['github']['use']}
    <div class="container">
        <div class="ui basic segment">
            <div class="ui{if $__KT['oauth']['google']['use'] && $__KT['oauth']['github']['use']} buttons{/if}">
            {if $__KT['oauth']['google']['use']}
                <a class="ui google plus button item" href="{$__KT['google_auth_uri']}" data-external-url-whitelist>
                    <i class="google icon"></i>
                    Login mit Google
                </a>
            {/if}
            {if $__KT['oauth']['google']['use'] && $__KT['oauth']['github']['use']}
                <div class="or" data-text="&"></div>
            {/if}
            {if $__KT['oauth']['github']['use']}
                <a class="ui black button item" href="{$__KT['github_auth_uri']}" data-external-url-whitelist>
                    <i class="github icon"></i>
                    Login mit GitHub
                </a>
            {/if}
            </div>
        </div>
    </div>
{/if}