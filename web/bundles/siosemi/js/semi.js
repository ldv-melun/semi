
/**
 * When user select or unselect a meeting-seance
 * @param {type} idSeance 
 * @param {type} heureDeb
 * @param {type} description of meeting
 * @param {type} checked or not...
 * @returns {undefined}
 */
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
           // see meetings.html.twig           
          $('#' + data.statMeeting[i].id).html(stat);
        } else {          
          $('#' + data.statMeeting[i].id).css("background-color", "");
        }
        if (free == 0){          
          $('#' + data.statMeeting[i].id).css("background-color", "red");
        } else if (free <= 10){          
          $('#' + data.statMeeting[i].id).css("background-color", "coral");
        }            
      }
      
      // voir meetings.html.twig
      if (data.statNbUsers > 1) {
        $('#statNbUsers').html("<b>" + data.statNbUsers + "</b> personnes inscrites.");
      } else {
        $('#statNbUsers').html("<b>" + data.statNbUsers + "</b> personne inscrite.");
      }
      
      // stat this user
      $('#statNbSeanceInscr').html(data.statCurUser);
    }
  });
}  

/**
 *  get stats of meeting for seminar in session of user (manager only)
 *  and update dom (content and css change)
 *   
 * @returns nothing
 */
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
          $('#' + data.statMeeting[i].id).html(stat);
        } else {          
          $('#' + data.statMeeting[i].id).css("background-color", "");
        }
        if (free == 0){          
          $('#' + data.statMeeting[i].id).css("background-color", "red");
        } else if (free <= 10){          
          $('#' + data.statMeeting[i].id).css("background-color", "coral");
        }        
      }
      // voir meetings.html.twig
      if (data.statNbUsers > 1) {
        $('#statNbUsers').html("<b>" + data.statNbUsers + "</b> personnes inscrites.");
      } else {
        $('#statNbUsers').html("<b>" + data.statNbUsers + "</b> personne inscrite.");
      }      
    }
  });
}  
