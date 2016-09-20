
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
        var stat = data.statMeeting[i].free + " / " + data.statMeeting[i].maxSeats;
        var actual = $('#' + data.statMeeting[i].id).text().trim();
        var free = 1 * data.statMeeting[i].free;
        // console.log(data.statMeeting[i].free);
        //console.log("["+stat+"]["+actual+"]"+ " free=["+free+"]");
        if (stat != actual) {
          $('#' + data.statMeeting[i].id).css("background-color", "lightyellow");
        } else {          
          $('#' + data.statMeeting[i].id).css("background-color", "");
        }
        if (free == 0){          
          $('#' + data.statMeeting[i].id).css("background-color", "red");
        } else if (free <= 10){          
          $('#' + data.statMeeting[i].id).css("background-color", "coral");
        }
        $('#' + data.statMeeting[i].id).html(stat);
      }

      // see meetings.html.twig           
      $('#statNbSeanceInscr').html(data.statCurUser);
    }
  });
}  


function stateInscrSeance() {
  $.ajax({
    type: "POST",
    url: "ajax/stateregistration",
    error: function (msg) {
      // alert("Error : " + msg);
    },
    success: function (data) {
      // refresh stats : see meetings.html.twig 
      for (var i = 0; i < data.statMeeting.length; i++) {
        var stat = data.statMeeting[i].free + " / " + data.statMeeting[i].maxSeats;
        var actual = $('#' + data.statMeeting[i].id).text().trim();
        var free = 1 * data.statMeeting[i].free;
        // console.log(data.statMeeting[i].free);
        //console.log("["+stat+"]["+actual+"]"+ " free=["+free+"]");
        if (stat != actual) {
          $('#' + data.statMeeting[i].id).css("background-color", "lightyellow");
        } else {          
          $('#' + data.statMeeting[i].id).css("background-color", "");
        }
        if (free == 0){          
          $('#' + data.statMeeting[i].id).css("background-color", "red");
        } else if (free <= 10){          
          $('#' + data.statMeeting[i].id).css("background-color", "coral");
        }
        $('#' + data.statMeeting[i].id).html(stat);
      }
    }
  });
}  
