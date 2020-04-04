{include file="header.tpl" title="FAQ"}
<div class="ui container grid stackable">
    <div class="four wide column">
        <div class="ui styled accordion">
        {foreach from=$categorys item="category"}
        <div class="title">
            <i class="dropdown icon"></i>
            {$category['name']}
        </div>
        <div class="content">
            <p>
                <ul class="ui list">
                    {foreach from=$category['faqs'] item="faq"}
                        <a class="item" id="faq{$faq->faqID}"href="#{$faq->faqID}">{$faq->getQuestion()}<div class="answer display-none">{$faq->getAnswer()}</div></a>
                    {foreachelse}
                        <i class="item">In dieser Kategorie sind keine FAQs eingetragen.</i>
                    {/foreach}
                </ul>
            </p>
        </div>
        {/foreach}
        </div>
    </div>
    <div class="twelve wide column">
        <div class="ui segment">
            <h2 class="ui header" id="faqtitle">
                FAQ
            </h2>
            <p id="faqanswer">
                Um einen Eintrag anzusehen, nutze bitte die Navigation.
            </p>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
    $('.ui.accordion').accordion();
});

window.onhashchange = function() {
    var hash = window.location.hash.substr(1);
    var elem = document.getElementById("faq" + hash);
    if(elem) {
        if(!isNaN(hash)) {
            var answer = elem.getElementsByClassName("answer")[0].innerHTML;
            var question = elem.innerHTML;
            document.getElementById("faqtitle").innerHTML = question;
            document.getElementById("faqanswer").innerHTML = answer;
        }
    }
}
window.onhashchange();
</script>
{include file="footer.tpl"}