$(document).ready(function () {
    $(".ui.toggle.button").click(function () {
        $(".mobile.only.grid .ui.vertical.menu").toggle(100);
    });
      
    $(".ui.dropdown.navigation").dropdown({
            on: "click"
    });
    var elems = document.querySelectorAll("a.menuLink[href]");
    for(var i = 0; i < elems.length; i++) {
        elems[i].addEventListener("click", function(e) {
            window.location.href = this.href;
        });
    }
    elems = document.querySelectorAll(".ui.dropdown.child .menu > .item.visualactive");
    for(var i = 0; i < elems.length; i++) {
        var elem = elems[i].parentElement.parentElement;
        while(elem.classList.contains("child") && !elem.classList.contains("visualactive")) {
            elem.classList.add("visualactive");
            elem = elem.parentElement.parentElement;
        }
    }
    elems = document.querySelectorAll(".ui.dropdown.child .menu > .item.active");
    for(var i = 0; i < elems.length; i++) {
        var elem = elems[i].parentElement.parentElement;
        while(elem.classList.contains("child") && !elem.classList.contains("visualactive")) {
            elem.classList.add("visualactive");
            elem = elem.parentElement.parentElement;
        }
    }
    externalpage.init();
    $('table').addClass("sortable");
    var elems = document.querySelectorAll('table > thead > tr > th');
    for(var i = 0; i < elems.length; i++) {
        if(elems[i].innerHTML == "Aktion") {
            elems[i].classList.add("no-sort");
        }
    }
    $('table').tablesort();
    notifications.init();
});

const ajax = {
    call: function (value, object) {
        var xmlHttp = null;
        xmlHttp = new XMLHttpRequest();
        xmlHttp.open("GET", link("ajax-" + value + "/object-" + object), false);
        xmlHttp.send(null);
        return JSON.parse(xmlHttp.responseText);
    },
    fetch: function (value, object) {
        fetch(link("ajax-" + value + "/object-" + object)).then(function(response) {
            return JSON.parse(response);
        });
    },
    post: function (value, object, parameters) {
        var xmlHttp = null;
        xmlHttp = new XMLHttpRequest();
        xmlHttp.open("POST", link("ajax-" + value + "/object-" + object), false);
        xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlHttp.send(parameters);
        return JSON.parse(xmlHttp.responseText);
    }
};

const modal = {
    captcha: function(recaptchaCallback) {
        var captchaExecute = function(recaptchaLoaded = false) {
            if(KT.captchaType == "2") {
                var modalID = modal.modal("reCaptcha", '<div id="recaptchaModal"></div>', "captchaModal");
                grecaptcha.render('recaptchaModal', {
                    'sitekey': KT.captchaPublicKey,
                    'callback': function(token) {
                        return new Promise(function(resolve, reject) { 
                            recaptchaCallback(token);
                            resolve();
                            $("#modal" + modalID).modal('hide');
                        });
                    }
                });
            } else if(KT.captchaType == "3") {
                var recaptchaReady = function() {
                    grecaptcha.execute(KT.captchaPublicKey, {action: "homepage"}).then(function(token) {
                        recaptchaCallback(token);
                    });
                };
                if(recaptchaLoaded) {
                    grecaptcha.ready(reCaptchaReady);
                } else {
                    recaptchaReady();
                }
            }
        };
        if(typeof grecaptcha === "undefined") {
            utils.loadScript(KT.recaptchaTypes[KT.captchaType], function() {
                captchaExecute(true);
            });
        } else {
            captchaExecute();
        }
    },
    confirm: function (text, callback) {
        var id = Math.round(Math.random() * 100);
        var div = document.createElement("div");
        div.innerHTML = '<div class="ui modal" id="confirmmodal' + id + '"><i class="close icon"></i><div class="header">Bestätigung erforderlich</div><div class="content"><p>' + text + '</p></div><div class="actions"><button class="ui negative button">Abbruch</button><button class="ui positive button" autofocus>Bestätigen</button></div></div>';
        document.body.appendChild(div);
        $('#confirmmodal' + id).modal({
            onApprove: function () {
                callback();
            },
            centered: false,
            onHidden: function () {
                document.getElementById("confirmmodal" + id).parentElement.remove();
            }
        }).modal('show');
    },
    modal: function (title, text, customClass = "") {
        if(customClass !== "") {
            customClass = " " + customClass;
        }
        var id = Math.round(Math.random() * 100);
        var div = document.createElement("div");
        div.innerHTML = '<div class="ui modal' + customClass + '" id="modal' + id + '"><i class="close icon"></i><div class="header">' + title + '</div><div class="content">' + text + '</div></div>';
        document.body.appendChild(div);
        $('#modal' + id).modal({
            centered: false,
            onHidden: function () {
                document.getElementById("modal" + id).parentElement.remove();
            }
        }).modal('show');
        return id;
    }
}
const utils = {
    loadScript: function(src, callback) {
        var script = document.createElement('script');
        script.src = src;
        script.onload = callback
        script.async = false;
        document.body.appendChild(script);
    },
    toASCI: function(string) {
        return string.replace(/./g, function(c) {
            return ('00' + c.charCodeAt(0)).slice(-3);
        });
    },
    copy: function(text) {
        var textarea = document.createElement("textarea");
        textarea.value = text;
        document.body.appendChild(textarea);
        textarea.select();
        textarea.setSelectionRange(0, 9999999999);
        document.execCommand("copy");
        textarea.remove();
    },
    search: function (table, input, type, colspan) {
        var filter, tr, td, i, txtValue, result, helper;
        helper = table.getElementsByClassName("search_info");
        if (helper[0] !== undefined) {
            helper[0].remove();
        }
        filter = input.value.toUpperCase();
        tr = table.getElementsByTagName("tr");
        result = 0;
        for (i = 0; i < tr.length; i++) {
            var td = tr[i].getElementsByTagName("td");
            for (j = 0; j < td.length; j++) {
                td[j].classList.remove("warning");
            }
        }
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[type.value];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                    if (input.value !== "") {
                        td.classList.add("warning");
                    }
                } else {
                    tr[i].setAttribute("style", "display: none!important;");
                }
            }
        }
        for (i = 0; i < tr.length; i++) {
            if (tr[i].style.display !== "none") {
                result++;
            }
        }
        if (result == 0) {
            table.innerHTML += "<tr class='search_info'><td colspan='" + colspan + "'><div class='ui info message'><ul class='list'><li>Zu dieser Suche wurde kein Ergebnis gefunden.</li></ul></div></td></tr>";
        }
    },
    setSearch: function(dropdownelemid, searchelemid, index, value, colspan) {
        $('#' + dropdownelemid).parent().dropdown('set selected', index);
        $('#' + searchelemid).val(value);
        utils.search(document.querySelector('#search_list'), document.querySelector('#' + searchelemid), document.querySelector('#' + dropdownelemid), colspan);
    },
    previewText: function(elem, defaultvalue, previewclass = "preview") {
        var value = elem.value;
        if(value == "") {
            value = defaultvalue;
        }
        value = value.replace(/</, "&lt;");
        value = value.replace(/>/, "&gt;");
        var elems = document.getElementsByClassName(previewclass);
        for(var i = 0; i < elems.length; i++) {
            elems[i].innerHTML = value;
        }
    }
};

const custominput = {
    addField: function() {
        var count = document.getElementById("custominputCounter").value;
        count++;
        var area = modal.modal("Eingabefeld hinzufügen", '' +
        '<form class="ui form" id="customInput' + count + '" method="post">' +
            '<div class="field required">' +
                '<label>Typ des Eingabefelds</label>' +
                '<div class="ui selection dropdown" id="customInput' + count + '_type_dropdown">' +
                    '<input type="hidden" id="customInput' + count + '_type">' +
                    '<i class="dropdown icon"></i>' +
                    '<div class="default text"></div>' +
                    '<div class="menu">' +
                        '<div class="item" data-value="text">Text</div>' +
                        '<div class="item" data-value="number">Zahl</div>' +
                        '<div class="item" data-value="email">E-Mail</div>' +
                        '<div class="item" data-value="checkbox">Checkbox</div>' +
                        '<div class="item" data-value="select">Auswahlmenü</div>' +
                    '</div>' +
                '</div>' +
            '</div>' + 
            '<div class="field required">' +
                '<label>Name</label>' +
                '<div class="ui input">' +
                    '<input type="text" id="customInput' + count + '_name">' +
                '</div>' +
            '</div>' +
            '<div class="field">' +
                '<label>Beschreibung</label>' +
                '<div class="ui input">' +
                    '<input type="text" id="customInput' + count + '_description">' +
                '</div>' +
            '</div>' +
            '<div class="field">' +
                '<div class="ui checkbox">' +
                    '<input type="checkbox" id="customInput' + count + '_required">' +
                    '<label>Muss dieses Feld zwingend ausgefüllt werden?</label>' +
                '</div>' +
            '</div>' +
            '<div class="categoryfields" id="categoryfields_text" style="display: none">' +
                '<div class="field required">' +
                    '<label>Mindestlänge</label>' +
                    '<div class="ui input">' +
                        '<input type="number" id="customInput' + count + '_minlength">' +
                    '</div>' +
                    '<small class="helper">Du kannst den Wert <i>-1</i> verwenden um kein Limit festzulegen.</small>' +
                '</div>' +
                '<div class="field required">' +
                    '<label>Maximallänge</label>' +
                    '<div class="ui input">' +
                        '<input type="number" id="customInput' + count + '_maxlength">' +
                    '</div>' +
                    '<small class="helper">Du kannst den Wert <i>-1</i> verwenden um kein Limit festzulegen.</small>' +
                '</div>' +
                '<div class="field">' +
                    '<label>Regex zur Validierung</label>' +
                    '<div class="ui input">' +
                        '<input type="text" id="customInput' + count + '_pattern">' +
                    '</div>' +
                '</div>' +
            '</div>' +
            '<div class="categoryfields" id="categoryfields_number" style="display: none">' +
                '<div class="field required">' +
                    '<label>Mindestzahl</label>' +
                    '<div class="ui input">' +
                        '<input type="number" id="customInput' + count + '_min">' +
                    '</div>' +
                '</div>' +
                '<div class="field required">' +
                    '<label>Maximalzahl</label>' +
                    '<div class="ui input">' +
                        '<input type="number" id="customInput' + count + '_max">' +
                    '</div>' +
                '</div>' +
            '</div>' +
            '<div class="categoryfields" id="categoryfields_checkbox" style="display: none">' +
                '<div class="ui info message display-block">' +
                    '<ul class="list">' +
                        '<li>Eine Checkbox hat keine weiteren Einstellungen</li>' +
                    '</ul>' +
                '</div>' +
            '</div>' +
            '<div class="categoryfields" id="categoryfields_select" style="display: none">' +
                '<div class="field">' +
                    '<div class="ui checkbox">' +
                        '<input type="checkbox" id="customInput' + count + '_options_multiple">' +
                        '<label>Sollen mehrere Optionen auswählbar sein?</label>' +
                    '</div>' +
                '</div>' +
                '<div class="field required">' +
                    '<label>Optionen</label>' +
                    '<textarea id="customInput' + count + '_options"></textarea>' +
                    '<small class="helper">eine Option pro Zeile, du kannst mit <i>:</i> Anzeigename und Wert ändern<br><b>bsp:</b> <code>Anzeigename:Wert</code></small>' +
                '</div>' +
            '</div>' +
            '<br>' +
            '<button type="submit" class="ui blue button">Absenden</button>' +
            '<div class="ui error message"></div>' +
        '</form>' + 
        '');
        $('#customInput' + count + '_type_dropdown').dropdown();
        $('#customInput' + count + '_type_dropdown').dropdown('setting', 'onChange', function(value, text, $choice) {
            var elems = document.getElementsByClassName("categoryfields");
            for(var i = 0; i < elems.length; i++) {
            elems[i].style.display = "none";
            }
            if(value == "email") {
                value = "text";
            }
            document.getElementById("categoryfields_" + value).style.display = "block";
            $('#customInput' + count).form('destroy');
            if(value == "text" || value == "email") {
                $('#customInput' + count).form({
                    fields: {
                        type: {
                            identifier: 'customInput' + count + '_type',
                            rules: [
                                {
                                    type: 'empty',
                                    prompt: 'Bitte wähle einen Typ'
                                }
                            ]
                        },
                        name: {
                            identifier: 'customInput' + count + '_name',
                            rules: [
                                {
                                    type: 'empty',
                                    prompt: 'Bitte gib einen Namen an.'
                                }
                            ]
                        },
                        minlength: {
                            identifier: 'customInput' + count + '_minlength',
                            rules: [
                                {
                                    type: 'empty',
                                    prompt: 'Bitte gib eine Mindestlänge an.'
                                },
                                {
                                    type: 'integer',
                                    prompt: 'Bitte gib eine valide Minestlänge an.'
                                }
                            ]
                        },
                        maxlength: {
                            identifier: 'customInput' + count + '_maxlength',
                            rules: [
                                {
                                    type: 'empty',
                                    prompt: 'Bitte gib eine Maximallänge an.'
                                },
                                {
                                    type: 'integer',
                                    prompt: 'Bitte gib eine valide Maximallänge an.'
                                }
                            ]
                        }
                    }
                });
            } else if(value == "number") {
                $('#customInput' + count).form({
                    fields: {
                        type: {
                            identifier: 'customInput' + count + '_type',
                            rules: [
                                {
                                    type: 'empty',
                                    prompt: 'Bitte wähle einen Typ'
                                }
                            ]
                        },
                        name: {
                            identifier: 'customInput' + count + '_name',
                            rules: [
                                {
                                    type: 'empty',
                                    prompt: 'Bitte gib einen Namen an.'
                                }
                            ]
                        },
                        min: {
                            identifier: 'customInput' + count + '_min',
                            rules: [
                                {
                                    type: 'empty',
                                    prompt: 'Bitte gib ein minimum an.'
                                },
                                {
                                    type: 'integer',
                                    prompt: 'Bitte gib ein valides minimum an.'
                                }
                            ]
                        },
                        max: {
                            identifier: 'customInput' + count + '_max',
                            rules: [
                                {
                                    type: 'empty',
                                    prompt: 'Bitte gib ein maximum an.'
                                },
                                {
                                    type: 'integer',
                                    prompt: 'Bitte gib ein valides maximum an.'
                                }
                            ]
                        }
                    }
                });
            } else if(value == "select") {
                $('#customInput' + count).form({
                    fields: {
                        type: {
                            identifier: 'customInput' + count + '_type',
                            rules: [
                                {
                                    type: 'empty',
                                    prompt: 'Bitte wähle einen Typ'
                                }
                            ]
                        },
                        name: {
                            identifier: 'customInput' + count + '_name',
                            rules: [
                                {
                                    type: 'empty',
                                    prompt: 'Bitte gib einen Namen an.'
                                }
                            ]
                        },
                        options: {
                            identifier: 'customInput' + count + '_options',
                            rules: [
                                {
                                    type: 'empty',
                                    prompt: 'Bitte gib Optionen an.'
                                }
                            ]
                        }
                    }
                });
            } else if(value == "checkbox") {
                $('#customInput' + count).form({
                    fields: {
                        type: {
                            identifier: 'customInput' + count + '_type',
                            rules: [
                                {
                                    type: 'empty',
                                    prompt: 'Bitte wähle einen Typ'
                                }
                            ]
                        },
                        name: {
                            identifier: 'customInput' + count + '_name',
                            rules: [
                                {
                                    type: 'empty',
                                    prompt: 'Bitte gib einen Namen an.'
                                }
                            ]
                        }
                    }
                });
            }
        });
        $('#customInput' + count).form({
            fields: {
                type: {
                    identifier: 'customInput' + count + '_type',
                    rules: [
                        {
                            type: 'empty',
                            prompt: 'Bitte wähle einen Typ'
                        }
                    ]
                },
                name: {
                    identifier: 'customInput' + count + '_name',
                    rules: [
                        {
                            type: 'empty',
                            prompt: 'Bitte gib einen Namen an.'
                        }
                    ]
                }
            }
        });
        document.getElementById("customInput" + count).addEventListener("submit", function(e) {
            if($('#customInput' + count).form('is valid')) {
                e.preventDefault();
                var type = document.getElementById('customInput' + count + '_type').value;
                var object = null;
                if(type == "text" || type == "email") {
                    object = {
                        type : type,
                        minlength : document.getElementById("customInput" + count + "_minlength").value,
                        maxlength : document.getElementById("customInput" + count + "_maxlength").value,
                        pattern : document.getElementById("customInput" + count + "_pattern").value,
                        description : clean(document.getElementById("customInput" + count + "_description").value),
                        required : document.getElementById("customInput" + count + "_required").checked,
                        title : clean(document.getElementById("customInput" + count + "_name").value)
                    };
                } else if(type == "number") {
                    object = {
                        type : type,
                        min : document.getElementById("customInput" + count + "_min").value,
                        max : document.getElementById("customInput" + count + "_max").value,
                        description : clean(document.getElementById("customInput" + count + "_description").value),
                        required : document.getElementById("customInput" + count + "_required").checked,
                        title : clean(document.getElementById("customInput" + count + "_name").value)
                    };
                } else if (type == "checkbox") {
                    object = {
                        type : type,
                        description : clean(document.getElementById("customInput" + count + "_description").value),
                        required : document.getElementById("customInput" + count + "_required").checked,
                        title : clean(document.getElementById("customInput" + count + "_name").value)
                    };
                } else if(type == "select") {
                    var options = [];
                    var content = document.getElementById("customInput" + count + "_options").value;
                    var multiple = document.getElementById("customInput" + count + "_options_multiple").checked;
                    content = clean(content);
                    content = content.split("\n");
                    content.forEach(function(c) {
                        if(c.includes(":")) {
                            var values = c.split(":");
                            if(values[0] !== undefined && values[1] !== undefined && values[0] !== "" && values[1] !== "") {
                                options.push({
                                    name: values[0],
                                    value: values[1]
                                });
                            } else if(c !== "") {
                                options.push({
                                    name: c,
                                    value: c
                                });
                            }
                        } else if(c !== "") {
                            options.push({
                                name: c,
                                value: c
                            });
                        }
                    });
                    object = {
                        type : type,
                        multiple: multiple,
                        options : options,
                        description : clean(document.getElementById("customInput" + count + "_description").value),
                        required : document.getElementById("customInput" + count + "_required").checked,
                        title : clean(document.getElementById("customInput" + count + "_name").value)
                    };
                }

                if(object !== null) {
                    var div = document.createElement("div");
                    div.classList.add("item");
                    div.classList.add("customInputListEntry");
                    div.id = "custominputentry" + count;
                    div.dataset.id = count;
                    div.innerHTML = "<span class='name'>" + clean(document.getElementById("customInput" + count + "_name").value) + "</span><span class='float-right'><a onclick='custominput.removeField(this)' data-tooltip='Löschen'><i class='icon times'></i></a><a onclick='custominput.editField(this)' data-tooltip='Bearbeiten'><i class='icon pencil'></i></a></span>";
                    var input = document.createElement("textarea");
                    input.name = "customInputData" + count;
                    input.classList.add("display-none");
                    input.innerText = JSON.stringify(object);
                    input.dataset.id = count;
                    div.appendChild(input);
                    var titleinput = document.createElement("textarea");
                    titleinput.classList.add("display-none");
                    titleinput.name = "customInputTitle" + count;
                    titleinput.dataset.id = count;
                    titleinput.innerText = clean(document.getElementById("customInput" + count + "_name").value);
                    div.appendChild(titleinput);
                    document.getElementById("customInputsContainer").appendChild(div);
                    $("#modal" + area).modal('hide');
                    document.getElementById("custominputCounter").value = count;
                }
            }
        });
    },
    removeField: function(field) {
        modal.confirm("Möchtest du dieses Eingabefeld wirklich löschen? Diese Aktion kann nicht rückgängig gemacht werden.", function() {
            var id = field.parentElement.parentElement.dataset.id;
            $("#custominputentry" + id).fadeOut(function() {
                field.parentElement.parentElement.remove();
            });
        });
    },
    editField: function(elem) {
        elem = elem.parentElement.parentElement;
        var mainid = elem.dataset.id;
        elem = elem.getElementsByTagName("textarea")[0];
        options = elem.value;
        options = JSON.parse(options);
        var area = modal.modal("Eingabefeld hinzufügen", '' +
        '<form class="ui form" id="customInput' + mainid + '" method="post">' +
            '<div class="field required">' +
                '<label>Typ des Eingabefelds</label>' +
                '<div class="ui selection dropdown" id="customInput' + mainid + '_type_dropdown">' +
                    '<input type="hidden" id="customInput' + mainid + '_type">' +
                    '<i class="dropdown icon"></i>' +
                    '<div class="default text"></div>' +
                    '<div class="menu">' +
                    '</div>' +
                '</div>' +
            '</div>' + 
            '<div class="field required">' +
                '<label>Name</label>' +
                '<div class="ui input">' +
                    '<input type="text" id="customInput' + mainid + '_name">' +
                '</div>' +
            '</div>' +
            '<div class="field">' +
                '<label>Beschreibung</label>' +
                '<div class="ui input">' +
                    '<input type="text" id="customInput' + mainid + '_description">' +
                '</div>' +
            '</div>' +
            '<div class="field">' +
                '<div class="ui checkbox">' +
                    '<input type="checkbox" id="customInput' + mainid + '_required">' +
                    '<label>Muss dieses Feld zwingend ausgefüllt werden?</label>' +
                '</div>' +
            '</div>' +
            '<div class="categoryfields" id="categoryfields_text" style="display: none">' +
                '<div class="field required">' +
                    '<label>Mindestlänge</label>' +
                    '<div class="ui input">' +
                        '<input type="number" id="customInput' + mainid + '_minlength">' +
                    '</div>' +
                    '<small class="helper">Du kannst den Wert <i>-1</i> verwenden um kein Limit festzulegen.</small>' +
                '</div>' +
                '<div class="field required">' +
                    '<label>Maximallänge</label>' +
                    '<div class="ui input">' +
                        '<input type="number" id="customInput' + mainid + '_maxlength">' +
                    '</div>' +
                    '<small class="helper">Du kannst den Wert <i>-1</i> verwenden um kein Limit festzulegen.</small>' +
                '</div>' +
                '<div class="field">' +
                    '<label>Regex zur Validierung</label>' +
                    '<div class="ui input">' +
                        '<input type="text" id="customInput' + mainid + '_pattern">' +
                    '</div>' +
                '</div>' +
            '</div>' +
            '<div class="categoryfields" id="categoryfields_number" style="display: none">' +
                '<div class="field required">' +
                    '<label>Mindestzahl</label>' +
                    '<div class="ui input">' +
                        '<input type="number" id="customInput' + mainid + '_min">' +
                    '</div>' +
                '</div>' +
                '<div class="field required">' +
                    '<label>Maximalzahl</label>' +
                    '<div class="ui input">' +
                        '<input type="number" id="customInput' + mainid + '_max">' +
                    '</div>' +
                '</div>' +
            '</div>' +
            '<div class="categoryfields" id="categoryfields_checkbox" style="display: none">' +
                '<div class="ui info message display-block">' +
                    '<ul class="list">' +
                        '<li>Eine Checkbox hat keine weiteren Einstellungen</li>' +
                    '</ul>' +
                '</div>' +
            '</div>' +
            '<div class="categoryfields" id="categoryfields_select" style="display: none">' +
                '<div class="field">' +
                    '<div class="ui checkbox">' +
                        '<input type="checkbox" id="customInput' + mainid + '_options_multiple">' +
                        '<label>Sollen mehrere Optionen auswählbar sein?</label>' +
                    '</div>' +
                '</div>' +
                '<div class="field required">' +
                    '<label>Optionen</label>' +
                    '<textarea id="customInput' + mainid + '_options"></textarea>' +
                    '<small class="helper">eine Option pro Zeile, du kannst mit <i>:</i> Anzeigename und Wert ändern<br><b>bsp:</b> <code>Anzeigename:Wert</code></small>' +
                '</div>' +
            '</div>' +
            '<br>' +
            '<button type="submit" class="ui blue button">Absenden</button>' +
            '<div class="ui error message"></div>' +
        '</form>' + 
        '');
        $('#customInput' + mainid + '_type_dropdown').dropdown({
            values: [
                {
                    selected: (options['type'] == "text") ? true : false,
                    name: "Text",
                    value: "text"
                },
                {
                    selected: (options['type'] == "number") ? true : false,
                    name: "Zahl",
                    value: "number"
                },
                {
                    selected: (options['type'] == "email") ? true : false,
                    name: "E-Mail",
                    value: "email"
                },
                {
                    selected: (options['type'] == "checkbox") ? true : false,
                    name: "Checkbox",
                    value: "checkbox"
                },
                {
                    selected: (options['type'] == "select") ? true : false,
                    name: "Auswahlmenü",
                    value: "select"
                }
            ],
        });
        document.getElementById('customInput' + mainid + '_name').value = options['title'];
        document.getElementById('customInput' + mainid + '_description').value = options['description'];
        document.getElementById("customInput" + mainid + "_required").checked = options['required'];
        var type = options['type'];
        if(type == "email") {
            type = "text";
        }
        document.getElementById("categoryfields_" + type).style.display = "block";
        if(options['type'] == "text" || options['type'] == "email") {
            document.getElementById("customInput" + mainid + "_minlength").value = options['minlength'];
            document.getElementById("customInput" + mainid + "_maxlength").value = options['maxlength'];
            document.getElementById("customInput" + mainid + "_pattern").value = options['pattern'];
        } else if(options['type'] == "number") {
            document.getElementById("customInput" + mainid + "_min").value = options['min'];
            document.getElementById("customInput" + mainid + "_max").value = options['max'];
        } else if(options['type'] == "select") {
            var selection = "";
            options['options'].forEach(function(o) {
                if(o['value'] == o['name']) {
                    selection += o['name'] + "\n";
                } else {
                    selection += o['name'] + ":" + o['value'] + "\n";
                }
            });
            document.getElementById("customInput" + mainid + "_options").value = selection;
            document.getElementById("customInput" + mainid + "_options_multiple").checked = (options['multiple'] !== undefined) ? options['multiple'] : false;
        }
        $('#customInput' + mainid + '_type_dropdown').dropdown('setting', 'onChange', function(value, text, $choice) {
            var elems = document.getElementsByClassName("categoryfields");
            for(var i = 0; i < elems.length; i++) {
            elems[i].style.display = "none";
            }
            if(value == "email") {
                value = "text";
            }
            document.getElementById("categoryfields_" + value).style.display = "block";
            $('#customInput' + mainid).form('destroy');
            if(value == "text" || value == "email") {
                $('#customInput' + mainid).form({
                    fields: {
                        type: {
                            identifier: 'customInput' + mainid + '_type',
                            rules: [
                                {
                                    type: 'empty',
                                    prompt: 'Bitte wähle einen Typ'
                                }
                            ]
                        },
                        name: {
                            identifier: 'customInput' + mainid + '_name',
                            rules: [
                                {
                                    type: 'empty',
                                    prompt: 'Bitte gib einen Namen an.'
                                }
                            ]
                        },
                        minlength: {
                            identifier: 'customInput' + mainid + '_minlength',
                            rules: [
                                {
                                    type: 'empty',
                                    prompt: 'Bitte gib eine Mindestlänge an.'
                                },
                                {
                                    type: 'integer',
                                    prompt: 'Bitte gib eine valide Minestlänge an.'
                                }
                            ]
                        },
                        maxlength: {
                            identifier: 'customInput' + mainid + '_maxlength',
                            rules: [
                                {
                                    type: 'empty',
                                    prompt: 'Bitte gib eine Maximallänge an.'
                                },
                                {
                                    type: 'integer',
                                    prompt: 'Bitte gib eine valide Maximallänge an.'
                                }
                            ]
                        }
                    }
                });
            } else if(value == "number") {
                $('#customInput' + mainid).form({
                    fields: {
                        type: {
                            identifier: 'customInput' + mainid + '_type',
                            rules: [
                                {
                                    type: 'empty',
                                    prompt: 'Bitte wähle einen Typ'
                                }
                            ]
                        },
                        name: {
                            identifier: 'customInput' + mainid + '_name',
                            rules: [
                                {
                                    type: 'empty',
                                    prompt: 'Bitte gib einen Namen an.'
                                }
                            ]
                        },
                        min: {
                            identifier: 'customInput' + mainid + '_min',
                            rules: [
                                {
                                    type: 'empty',
                                    prompt: 'Bitte gib ein minimum an.'
                                },
                                {
                                    type: 'integer',
                                    prompt: 'Bitte gib ein valides minimum an.'
                                }
                            ]
                        },
                        max: {
                            identifier: 'customInput' + mainid + '_max',
                            rules: [
                                {
                                    type: 'empty',
                                    prompt: 'Bitte gib ein maximum an.'
                                },
                                {
                                    type: 'integer',
                                    prompt: 'Bitte gib ein valides maximum an.'
                                }
                            ]
                        }
                    }
                });
            } else if(value == "select") {
                $('#customInput' + mainid).form({
                    fields: {
                        type: {
                            identifier: 'customInput' + mainid + '_type',
                            rules: [
                                {
                                    type: 'empty',
                                    prompt: 'Bitte wähle einen Typ'
                                }
                            ]
                        },
                        name: {
                            identifier: 'customInput' + mainid + '_name',
                            rules: [
                                {
                                    type: 'empty',
                                    prompt: 'Bitte gib einen Namen an.'
                                }
                            ]
                        },
                        options: {
                            identifier: 'customInput' + mainid + '_options',
                            rules: [
                                {
                                    type: 'empty',
                                    prompt: 'Bitte gib Optionen an.'
                                }
                            ]
                        }
                    }
                });
            } else if(value == "checkbox") {
                $('#customInput' + mainid).form({
                    fields: {
                        type: {
                            identifier: 'customInput' + mainid + '_type',
                            rules: [
                                {
                                    type: 'empty',
                                    prompt: 'Bitte wähle einen Typ'
                                }
                            ]
                        },
                        name: {
                            identifier: 'customInput' + mainid + '_name',
                            rules: [
                                {
                                    type: 'empty',
                                    prompt: 'Bitte gib einen Namen an.'
                                }
                            ]
                        }
                    }
                });
            }
        });
        $('#customInput' + mainid).form({
            fields: {
                type: {
                    identifier: 'customInput' + mainid + '_type',
                    rules: [
                        {
                            type: 'empty',
                            prompt: 'Bitte wähle einen Typ'
                        }
                    ]
                },
                name: {
                    identifier: 'customInput' + mainid + '_name',
                    rules: [
                        {
                            type: 'empty',
                            prompt: 'Bitte gib einen Namen an.'
                        }
                    ]
                }
            }
        });
        document.getElementById("customInput" + mainid).addEventListener("submit", function(e) {
            if($('#customInput' + mainid).form('is valid')) {
                e.preventDefault();
                var type = document.getElementById('customInput' + mainid + '_type').value;
                var object = null;
                if(type == "text" || type == "email") {
                    object = {
                        type : type,
                        minlength : document.getElementById("customInput" + mainid + "_minlength").value,
                        maxlength : document.getElementById("customInput" + mainid + "_maxlength").value,
                        pattern : document.getElementById("customInput" + mainid + "_pattern").value,
                        description : clean(document.getElementById("customInput" + mainid + "_description").value),
                        required : document.getElementById("customInput" + mainid + "_required").checked,
                        title : clean(document.getElementById("customInput" + mainid + "_name").value) 
                    };
                } else if(type == "number") {
                    object = {
                        type : type,
                        min : document.getElementById("customInput" + mainid + "_min").value,
                        max : document.getElementById("customInput" + mainid + "_max").value,
                        description : clean(document.getElementById("customInput" + mainid + "_description").value),
                        required : document.getElementById("customInput" + mainid + "_required").checked,
                        title : clean(document.getElementById("customInput" + mainid + "_name").value) 
                    };
                } else if (type == "checkbox") {
                    object = {
                        type : type,
                        description : clean(document.getElementById("customInput" + mainid + "_description").value),
                        required : document.getElementById("customInput" + mainid + "_required").checked,
                        title : clean(document.getElementById("customInput" + mainid + "_name").value) 
                    };
                } else if(type == "select") {
                    var options = [];
                    var content = document.getElementById("customInput" + mainid + "_options").value;
                    var multiple = document.getElementById("customInput" + mainid + "_options_multiple").checked;
                    content = clean(content);
                    content = content.split("\n");
                    content.forEach(function(c) {
                        if(c.includes(":")) {
                            var values = c.split(":");
                            if(values[0] !== undefined && values[1] !== undefined && values[0] !== "" && values[1] !== "") {
                                options.push({
                                    name: values[0],
                                    value: values[1]
                                });
                            } else if(c !== "") {
                                options.push({
                                    name: c,
                                    value: c
                                });
                            }
                        } else if(c !== "") {
                            options.push({
                                name: c,
                                value: c
                            });
                        }
                    });
                    object = {
                        type : type,
                        multiple: multiple,
                        options : options,
                        description : clean(document.getElementById("customInput" + mainid + "_description").value),
                        required : document.getElementById("customInput" + mainid + "_required").checked,
                        title : clean(document.getElementById("customInput" + mainid + "_name").value)
                    };
                }

                if(object !== null) {
                    document.getElementById("customInputsContainer").querySelector("#custominputentry" + mainid).getElementsByTagName("textarea")[0].remove();
                    document.getElementById("customInputsContainer").querySelector("#custominputentry" + mainid).getElementsByTagName("span")[0].innerHTML = clean(document.getElementById("customInput" + mainid + "_name").value);
                    var input = document.createElement("textarea");
                    input.name = "customInputData" + mainid;
                    input.classList.add("display-none");
                    input.innerText = JSON.stringify(object);
                    input.dataset.id = mainid;
                    document.getElementById("customInputsContainer").querySelector("#custominputentry" + mainid).appendChild(input);
                    var titleinput = document.createElement("textarea");
                    titleinput.classList.add("display-none");
                    titleinput.name = "customInputTitle" + mainid;
                    titleinput.dataset.id = mainid;
                    titleinput.innerText = clean(document.getElementById("customInput" + mainid + "_name").value);
                    document.getElementById("customInputsContainer").querySelector("#custominputentry" + mainid).appendChild(titleinput);
                    $("#modal" + area).modal('hide');
                }
            }
        });
    }
};

const toast = {
    create: function(title, text, type) {
        var types = {
            success: {
                icon: "check",
                bgcolor: "#21ba45",
                textcolor: "#fff"
            },
            error: {
                icon: 'times',
                bgcolor: "#d01919",
                textcolor: "#fff"                
            }
        };
        $.uiAlert({
            textHead: title,
            text: text,
            bgcolor: types[type]['bgcolor'],
            textcolor: types[type]['textcolor'],
            position: "top-right",
            icon: types[type]['icon'],
            time: 3
        });
    }
};

const notifications = {
    init: function() {
        var notifications_content = ajax.call(14, KT.userID);
        if(notifications_content !== undefined && notifications_content['message'] !== "access denied") {
            if(!("Notification" in window)) {
                KT.pushNotificationsAvailable = false;
                console.log("[DEBUG] Dein Browser unterstützt keine Benachrichtigungen.");
            } else if (Notification.permission === "granted") {
                KT.pushNotificationsAvailable = true;
            } else if (Notification.permission !== "denied") {
                if(KT.useDesktopNotification) {
                    Notification.requestPermission().then(function(permission) {
                        if (permission === "granted") {
                            KT.pushNotificationsAvailable = true;
                        }
                    });
                }
            } else {
                KT.pushNotificationsAvailable = false;
                console.log("[DEBUG] Dein Browser blockiert Benachrichtigungen.");
            }

            notifications_content = notifications_content['message'];
            var result = "<i>Es gibt keine neuen Benachrichtigungen.</i><div class='ui divider'></div>";
            var ncounter = 0;
            if(notifications_content.length > 0) {
                result = "";
                notifications_content.forEach(function(notification) {
                    result += '<p id="notificationmenuentry' + notification['notificationID'] + '"><a href="' + link(notification['linkIdentifier']) + '"  data-tooltip="' + formatUnix(notification['time']) + '">' + notification['content'] + '</a><a data-tooltip="gelesen" class="pointer float-right" data-id="' + notification['notificationID'] + '" onClick="notifications.done(this)"><i class="icon check"></i></a></p><div class="ui divider"></div>';
                    ncounter++;
                    var data = ajax.call(22, notification['notificationID']);
                    if(data['success']) {
                        if(!data['message']) {
                            if(KT.pushNotificationsAvailable) {
                                notifications.desktopNotification(notification);
                            }
                            ajax.call(23, notification['notificationID']);
                        }
                    }
                });
            }
            if(ncounter > 0) {
                $(".notificationbadgehandler").html(ncounter);
                $(".notificationbadgehandler").css("display", "block");
                $("#notificationsbell").addClass("unread");
            } else {
                $(".notificationbadgehandler").css("display", "none");
                $("#notificationsbell").removeClass("unread");
            }

            $('#notificationsbell').popup({
                inline : true,
                on : 'click',
                setFluidWidth: false,
                html : '<div class="header notificationsheader">Benachrichtigungen</div><div class="ui divider"></div><span id="notificationmenulist">' + result + '</span><a href="' + notifications_link + '" class="ui blue button notificationslistbutton">alle Benachrichtigungen</a>'
            });
        }
    },
    load: function() {
        var notifications_content = ajax.call(14, KT.userID);
        if(notifications_content !== undefined && notifications_content['message'] !== "access denied") {
            notifications_content = notifications_content['message'];
            var result = "<i>Es gibt keine neuen Benachrichtigungen.</i><div class='ui divider'></div>";
            var ncounter = 0;
            
            if(notifications_content.length > 0) {
                result = "";
                notifications_content.forEach(function(notification) {
                    result += '<p id="notificationmenuentry' + notification['notificationID'] + '"><a href="' + link(notification['linkIdentifier']) + '"  data-tooltip="' + formatUnix(notification['time']) + '">' + notification['content'] + '</a><a data-tooltip="gelesen" class="pointer float-right" data-id="' + notification['notificationID'] + '" onClick="notifications.done(this)"><i class="icon check"></i></a></p><div class="ui divider"></div>';
                    ncounter++;
                    var data = ajax.call(22, notification['notificationID']);
                    if(data['success']) {
                        if(!data['message']) {
                            if(KT.pushNotificationsAvailable) {
                                notifications.desktopNotification(notification);
                            }
                            ajax.call(23, notification['notificationID']);
                        }
                    }
                });
            }

            if(ncounter > 0) {
                $(".notificationbadgehandler").html(ncounter);
                $(".notificationbadgehandler").css("display", "block");
                $("#notificationsbell").addClass("unread");
            } else {
                $(".notificationbadgehandler").css("display", "none");
                $("#notificationsbell").removeClass("unread");
            }
            document.getElementById("notificationmenulist").innerHTML = result;
        }
    },
    done: function(elem) {
        var id = elem.dataset.id;
        $("#notificationmenuentry" + id).fadeOut(function() {
            document.getElementById("notificationmenuentry" + id).remove();
            ajax.call(13, id);
            notifications.load();
        })
    },
    doneList: function(elem) {
        var id = elem.dataset.id;
        ajax.call(13, id);
        window.location.reload();
    },
    options: function(notification) {
        return {
            body: notification['content'],
            icon: KT.mainurl + "data/favicon." + KT.faviconextension
        }
    },
    desktopNotification: function(notification) {
        if(KT.useDesktopNotification) {
            if(notification['sent'] == 0) {
                var notify = new Notification(KT.pagetitle + " Benachrichtigung", notifications.options(notification));
                notify.onclick = function() {
                    window.open(notification['link'], "_blank");
                }
                ajax.call(23, notification['notificationID']);
            }
        }
    }
}

const externalpage = {
    toASCI: function(string) {
        return string.replace(/./g, function(c) {
            return ('00' + c.charCodeAt(0)).slice(-3);
        });
    },
    init: async function() {
        if(KT.externalURLFavicons) {
            var elems = document.getElementsByTagName("a");
            for(var i = 0; i < elems.length; i++) {
                if(elems[i].href !== undefined) {
                    if(elems[i].href !== "") {
                        if(!elems[i].href.startsWith(KT.mainurl)) {
                            if(!elems[i].href.startsWith("javascript:")) {
                                if(!elems[i].classList.contains("item") && !elems[i].classList.contains("button") && elems[i].dataset.noFavicon == undefined) {
                                    if(elems[i].href.startsWith("http") || elems[i].href.startsWith("//")) {
                                        var href = elems[i].href;
                                        if(href.endsWith("/")) {
                                            href = href.substring(0, href.length - 1);
                                        }
                                        var image = "https://www.google.com/s2/favicons?domain=" + href;
                                        image = externalpage.toASCI(image);
                                        elems[i].style.backgroundImage = 'url("' + link("imageproxy/url-" + image) + '")';
                                        elems[i].style.backgroundRepeat = 'no-repeat';
                                        elems[i].style.paddingLeft = '20px';
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        if(KT.externalURLTitle) {
            var elems = document.getElementsByTagName("a");
            for(var i = 0; i < elems.length; i++) {
                if(elems[i].href !== undefined) {
                    if(elems[i].href !== "") {
                        if(!elems[i].href.startsWith(KT.mainurl)) {
                            if(!elems[i].href.startsWith("javascript:")) {
                                if(!elems[i].classList.contains('item')) {
                                    if(elems[i].href.startsWith("http") || elems[i].href.startsWith("//")) {
                                        var url = elems[i].href;
                                        if(url.includes(elems[i].innerText) && elems[i].dataset.titlechanged !== "true") {
                                            url = externalpage.toASCI(url);
                                            var data = ajax.call(20, url);
                                            if(data['success']) {
                                                var title = data['message'];
                                                if(title == null) {
                                                    title = elems[i].href;
                                                }
                                                elems[i].innerText = title;
                                                elems[i].dataset.titlechanged = true;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        if(KT.proxyAllImages) {
            var elems = document.getElementsByTagName("img");
            for(var i = 0; i < elems.length; i++) {
                if(elems[i].src !== undefined) {
                    if(elems[i].src !== "") {
                        if(!elems[i].src.startsWith(KT.mainurl)) {
                            if(elems[i].src.startsWith("http") || elems[i].src.startsWith("//")) {
                                var url = elems[i].src;
                                if(elems[i].dataset.srcchanged !== true) {
                                    url = externalpage.toASCI(url);
                                    url = link("imageproxy/url-" + url);
                                    elems[i].src = url;
                                    elems[i].dataset.srcchanged = true;
                                }
                            }
                        }
                    }
                }
            }
        }
        if(KT.externalURLWarning) {
            var elems = document.getElementsByTagName("a");
            for(var i = 0; i < elems.length; i++) {
                if(elems[i].href !== undefined) {
                    if(elems[i].href !== "") {
                        if(!elems[i].href.startsWith(KT.mainurl) && elems[i].dataset.externalUrlWhitelist == undefined) {
                            if(!elems[i].href.startsWith("javascript:")) {
                                if(elems[i].href.startsWith("http") || elems[i].href.startsWith("//")) {
                                    var callback = function (e) {
                                        e.preventDefault();
                                        var url = this.href;
                                        modal.confirm("<p>Bitte beachte, dass du nun auf eine Webseite geleitet wirst, für deren Inhalt wir nicht verantwortlich sind und auf die unsere Datenschutzbestimmungen keine Anwendung finden.</p>" + 
                                        "<p>Bitte bestätige dass du auf diese Website weitergeleitet werden möchtest.</p>", function() {
                                            window.open(url, "_blank");
                                        });
                                    };
                                    elems[i].addEventListener("click", callback);
                                    elems[i].addEventListener("contextmenu", callback);
                                    elems[i].addEventListener("mousedown", callback);
                                    elems[i].addEventListener("mouseup", callback);
                                }
                            }
                        }
                    }
                }
            }
        }
    }
};

function clean(input) {
    return input.replace(/</, "&lt;").replace(/>/, "&gt;");
}

function link(link) {
    if(KT.seourls) {
        return KT.mainurl + link + "/";
    } else {
        return KT.mainurl + "index.php?" + link + "/";
    }
}

function formatUnix(unix, addDate = true, addTime = true) {
    unix = unix*1000;
    var date = new Date(unix);
    var result = "";
    if(addDate) {
        var day = date.getDate();
        if(day < 10) {
            day = "0" + day;
        }
        var month = date.getDate();
        if(month < 10) {
            month = "0" + month;
        }
        result = day + "." + month + "." + date.getFullYear();
    }
    if(addTime) {
        var hours = date.getHours();
        if(hours < 10) {
            hours = "0" + hours;
        }
        var minutes = date.getMinutes();
        if(minutes < 10) {
            minutes = "0" + minutes;
        }
        if(result !== "") {
            result = result + ", ";
        }
        result = result + hours + ":" + minutes + " Uhr";
    }
    return result;
}

function toggleElement(querySelectorElement, checkBox) {
    var element = document.querySelector(querySelectorElement);
    if(!element) {
        throw new Error("the toggleable element you specified does not exist");
    }
    if(checkBox.checked == true) {
        element.style.display = "";
    } else {
        element.style.display = "none";
    }
}