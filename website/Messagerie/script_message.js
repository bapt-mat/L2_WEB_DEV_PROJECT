//SCRIPT ENVOI MESSAGE QUAND TOUCHE ENTREE
var form = document.querySelector('form');
        
function verif_entree (e){
    // Si la touche entrée est pressée
    if (e.keyCode == 13 && !e.shiftKey){
        e.preventDefault();
        form.submit();
    }
}
// gestionnaire d'événements de frappe de touche au champ de saisie de message
form.querySelector('textarea').addEventListener('keydown', verif_entree);



//SCRIPT MISE A JOUR EN DIRECT DES MESSAGES

//on modifie l'id de la div messages pour pouvoir l'utiliser avec js
var messages = document.getElementById('messages-js-only');
messages.id = 'messages';

//defilement vers le bas des messages au chargement ou quand un nouveau msg arrive
function scrollToBottom(){
    var messagesDiv = $('#messages');
    messagesDiv.scrollTop(messagesDiv.prop("scrollHeight"));
}

//on récupère l'id de la session et id interlocuteur 
var id_session = messages.getAttribute('data-id_session');
var id_interlocuteur = messages.getAttribute('data-id_interlocuteur');

var messagesPrecedents = [];
//fonction de récupération des messages

function updateMessages(){
    $.ajax({
        url: "recup_message.php?id="+id_interlocuteur,
        dataType: "json",
        success: function(data){
            for (var i=0; i<data.length; i++){
                var message = data[i];
                
                //verif ds la console
                console.log(message);

                //on vérifie si le message n'est pas déjà affiché
                if(!messagesPrecedents.includes(message.id)){
                    //on ajoute le message à la liste des messages affichés
                    messagesPrecedents.push(message.id);
                    //on affiche le message
                    if (message.id_expediteur == id_session){
                        $('#messages').append('<p class="sent">Vous : ' + message.mess + '</p>');
                    }   
                    else {
                        //on récupère le pseudo de l'interlocuteur
                        var pseudoUser = messages.getAttribute('data-pseudo_interlocuteur');
                        $('#messages').append('<p class="received">' + pseudoUser + ' : ' + message.mess + '</p>');
                    }
                    scrollToBottom();
                }
            }
        }
    });
}

$(document).ready(function(){
    updateMessages();
    scrollToBottom();
});

//on met à jour les messages toutes les secondes
setInterval(function(){
    updateMessages();
}, 1000);