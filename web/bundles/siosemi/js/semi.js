
// TODO à placer dans un twig pour permettre le paramètrage de l'url ?

function inscrSeance(idSeance, heureDeb, description, checked) {   
    $.ajax({
        type: "POST",
        url: "ajax/register",
        data: "idSeance=" + idSeance + "&inscrire=" + checked + "&dateHeureDebut=" + heureDeb,
        error: function (msg) {
            alert("Error : " + msg);
        },
        success: function (data) {             
           // console.log(data.statMeeting[0].free);  
           // refresh stats : see meetings.html.twig 
           for (var i = 0; i < data.statMeeting.length; i++) {
              $('#' + data.statMeeting[i].id).html(data.statMeeting[i].free + " / "+ data.statMeeting[i].maxSeats);
           }
            
           // see meetings.html.twig           
           $('#statNbSeanceInscr').html(data.statCurUser);
        }
    });
}  