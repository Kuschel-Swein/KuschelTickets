const chat = {
    intervalManager: {},
    closed: false,
    chatID: null,
    init: function() {
        chat.intervalManager.list = setInterval(chat.list, 5000);

        chat.list();
        document.getElementById("chatSection_form").addEventListener("submit", function(e) {
            e.preventDefault();
            if(!document.getElementById("chatSection_answer").value == "") {
                var content = utils.toASCI(document.getElementById("chatSection_answer").value);
                var data = ajax.call(27, content);
                document.getElementById("chatSection_answer").value = "";
            } else {
                document.getElementById("chatSection_answer_field").classList.add("error");
            }
        })
    },
    list: function() {
        var data = ajax.call(24, 0);
        data = data.message;
        var elem = document.getElementById("chatList");
        elem.innerHTML = "";
        data.forEach(function(chat) {
            var div = document.createElement("div");
            div.classList.add("item");
            var header = '<a class="header" href="javascript:chat.join(' + chat.chatID + ')">' + chat.creatorName + '</a>';
            if(!KT.canJoinChat) {
                header = '<span class="header">' + chat.creatorName + '</span>';
            }
            div.innerHTML = '' +
            '<i class="large chat middle aligned icon"></i>' +
            '<div class="content">' +
                header +
                '<div class="description">' + formatUnix(chat.time) + '</div>' +
            '</div>';
            elem.appendChild(div);
        });
        if(elem.innerHTML == "") {
            elem.innerHTML = '' + 
            '<div class="ui info message">' +
                '<ul class="list">' +
                    '<li>Derzeit sind keine Supporter im Chatsystem verfügbar. Versuche es später erneut.</li>' +
                '</ul>' +
            '</div>';
        }
    },
    open: function() {
        if(KT.canOpenSupportchat) {
            modal.confirm("Möchtest du wirklich einen Supportchat öffnen? Du musst diesen korrekt schließen um ihn wieder zu verlassen!", function() {
                var data = ajax.call(29, null);
                if(data.success !== undefined) {
                    toast.create(data.title, data.message, "success");
                    var id = data.chatID;
                    document.getElementById("openChat").disabled = true;
                    document.getElementById("openChat").classList.add("disabled");
                    document.getElementById("chatList").style.display = "none";
                    document.getElementById("chatSection").style.display = "block";
                    chat.load(id);
                    chat.intervalManager.inChat = setInterval(function() {
                        chat.load(id);
                    }, 1000);
                    chat.chatID = id;
                    window.onbeforeunload = function(e) {
                        ajax.fetch(28, chat.chatID);
                    }
                    document.getElementById("chatSection_close").addEventListener("click", function (e) {
                        e.preventDefault();
                        modal.confirm("Möchtest du den Chat wirklich verlassen?", function() {
                            ajax.call(28, id);
                            window.location.reload();
                        });
                    });
                }
            });
        }
    },
    join: function(id) {
        var data = ajax.call(25, id);
        if(data.success == "true") {
            toast.create(data.title, data.message, "success");
            document.getElementById("chatList").style.display = "none";
            document.getElementById("chatSection").style.display = "block";
            chat.load(id);
            chat.intervalManager.inChat = setInterval(function() {
                chat.load(id);
            }, 1000);
            chat.chatID = id;
            window.onbeforeunload = function(e) {
                ajax.fetch(28, chat.chatID);
            }
            document.getElementById("chatSection_close").addEventListener("click", function (e) {
                e.preventDefault();
                modal.confirm("Möchtest du den Chat wirklich verlassen?", function() {
                    ajax.call(28, id);
                    window.location.reload();
                });
            });
        } else {
            toast.create(data.title, data.message, "error");
        }
    },
    load: function(id) {
        var elem = document.getElementById("chatSection_list");
        var data = ajax.call(26, id);
        data = data.message;
        if(!chat.closed) {
            if(data[0] !== undefined) {
                if(data[0].state == 2) {
                    toast.create("SupportChat geschlossen.", "Dieser SupportChat wurde geschlossen", "error");
                    chat.closed = true;
                    document.getElementById("chatSection_answer").disabled = true;
                    document.getElementById("chatSection_answer_submit").disabled = true;
                    setTimeout(function() {
                        window.location.reload();
                    }, 4000);
                }
            }
            var oldContent = elem.innerHTML;
            elem.innerHTML = "";
            data.forEach(function(message) {
                var div = document.createElement("div");
                div.classList.add("comment");
                div.innerHTML = '' +
                '<div class="content">' +
                    '<span class="author">' + message.badge + " " + message.poster + '</span>' +
                    '<div class="metadata float-right">' +
                        '<span class="date">' + formatUnix(message.time, false) + '</span>' +
                    '</div>' +
                    '<div class="text">' +
                    message.content +
                    '</div>' +
                '</div>';
                elem.appendChild(div);
            });
            if(elem.innerHTML == "") {
                elem.innerHTML = '<div class="ui info message"><ul class="list"><li>Es wurden noch keine Nachrichten gesendet.</li></ul></div>';
            }
            if(oldContent !== elem.innerHTML) {
                elem.scrollTop = elem.scrollHeight;
            }
        }
    }
}