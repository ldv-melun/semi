function showLogin()
{
    var label = $('div#login-register-label');
    var help = $('div#login-register-help');
    var switchLabel = $('div#login-register-switchLabel');
    var form = $('div#login-register-form');

    label.animate({ opacity: 0 }, 500, function() { label.html("<h2>Connexion</h2>"); label.animate({ opacity: 1}, 500); });
    help.animate({ opacity: 0 }, 500, function() { help.html(""); help.animate({ opacity: 1}, 500); });
    switchLabel.animate({ opacity: 0 }, 500, function() { switchLabel.html('<p class="connexion" onClick="showRegister();">... Ou cliquez ici pour vous enregistrer !</p>'); switchLabel.animate({ opacity: 1}, 500); });
    form.animate({ opacity: 0 }, 500, function() { form.html('<form action="login_check" method="post">'
                                                        +   '<div class="input-group">'
                                                        +   '<span class="input-group-addon" id="basic-addon1">></span>'
                                                        +   '<input type="text" name="_username" class="form-control" placeholder="E-Mail" aria-describedby="basic-addon1"><br>'
                                                        +   '</div>'
                                                        +   '<div class="input-group">'
                                                        +   '<span class="input-group-addon" id="basic-addon">></span>'
                                                        +   '<input type="password" name="_password" class="form-control" placeholder="Mot de passe" aria-describedby="basic-addon2">'
                                                        +   '</div>'
                                                        +   '<input type="submit" class="btn btn-sm btn-success" style="width:100%;" value="Se connecter">'
                                                        +   '</form>'); 
                                                    form.animate({ opacity: 1}, 500); });
    
}

function showRegister()
{
    var label = $('div#login-register-label');
    var help = $('div#login-register-help');
    var switchLabel = $('div#login-register-switchLabel');
    var form = $('div#login-register-form');
    
    label.animate({ opacity: 0 }, 500, function() { label.html("<h2>Inscription</h2>"); label.animate({ opacity: 1}, 500); });
    help.animate({ opacity: 0 }, 500, function() { help.html('<p class="help">* Une clé d\'inscription à un séminaire est nécessaire pour s\'enregistrer.<br></p>'); help.animate({ opacity: 1}, 500); });
    switchLabel.animate({ opacity: 0 }, 500, function() { switchLabel.html('<p class="connexion" onClick="showLogin();">... Ou cliquez ici pour vous connecter !</p>'); switchLabel.animate({ opacity: 1}, 500); });
    form.animate({ opacity: 0 }, 500, function() { form.html('<form action="register" method="post">'
                                                        +   '<div class="input-group">'
                                                        +   '<span class="input-group-addon" id="basic-addon1">></span>'
                                                        +   '<input type="text" name="mail" class="form-control" placeholder="E-Mail" aria-describedby="basic-addon1"><br>'
                                                        +   '</div>'
                                                        +   '<div class="input-group">'
                                                        +   '<span class="input-group-addon" id="basic-addon">></span>'
                                                        +   '<input type="text" name="pass" class="form-control" placeholder="Clé d\'inscription" aria-describedby="basic-addon2">'
                                                        +   '</div>'
                                                        +   '<input type="submit" class="btn btn-sm btn-success" style="width:100%;" value="S\'enregistrer">'
                                                        +   '</form>'); 
                                                    form.animate({ opacity: 1}, 500); });
}

function overMenu(id) 
{
    $(id).attr('class', 'list-group-item active');
}

function outMenu(id) 
{
    $(id).attr('class', 'list-group-item');
}

function addParam()
{
    init++;
    
    if($('div#optParam').length) 
    {
        // Exist.
        var WriteCouple = '<div id="optParam" class="input-group">'
                        + '<span class="input-group-addon" id="basic-addon1">Clé</span>'
                        + '<input name="clefOpt'+init+'" type="text" class="form-control" placeholder="Clé" aria-describedby="basic-addon1">'
                        + '<span class="input-group-addon" id="basic-addon1">Valeur</span>'
                        + '<input name="valueOpt'+init+'" type="text" class="form-control" placeholder="Valeur" aria-describedby="basic-addon1">'
                        + '</div>';
        $('div#optParamContainer').append(WriteCouple);
    }
    else 
    {
        // Does not exist.
        $('div#noOptParam').remove();
        var WriteCouple = '<div id="optParam" class="input-group">'
                        + '<span class="input-group-addon" id="basic-addon1">Clé</span>'
                        + '<input name="clefOpt'+init+'" type="text" class="form-control" placeholder="Clé" aria-describedby="basic-addon1">'
                        + '<span class="input-group-addon" id="basic-addon1">Valeur</span>'
                        + '<input name="valueOpt'+init+'" type="text" class="form-control" placeholder="Valeur" aria-describedby="basic-addon1">'
                        + '</div>';
        $('div#optParamContainer').append(WriteCouple);
    }
}

function getMeetings(i)
{
    showMeetings(i);
    
    // Reset all the UL & LI.
    $('ul').css("height", "0px");
    $('ul').css("visibility", "hidden");
    $('li').css("height", "0px");
    $('li').css("visibility", "hidden");
    
    // Get the new one.
    $('ul#meeting'+i).css("height", "auto");
    $('ul#meeting'+i).css("visibility", "visible");
    
    $('ul#meeting'+i+' li').css("height", "auto");
    $('ul#meeting'+i+' li').css("visibility", "visible");
    $('ul#meeting'+i).prependTo('div#contentMeetings');
    $('html,body').animate({scrollTop: ($('#meetings').offset().top - ($('nav').css("height").replace(/[^-\d\.]/g, '')) - 15)}, 'slow');
}

function showMeetings(i)
{
    if($('ul#meeting1').length)
    {
        if($('div#meetings').css("height") === "0px")
        {
            $('div#meetings').css("height", "auto");
            $('div#meetings').css("visibility", "visible");
            $('div#meetings').animate({ opacity: 1}, 500);
        }
    }
    else
    {
        alert('Une erreur est survenue. Contactez un administrateur.');
    }
}

function semiShowError(error)
{
    html = '<div id="error" class="alert alert-danger">'+error+'</div>';
    $('div#container').prepend(html);
    $('div#error').delay(3000).fadeOut(1000);
}

function semiShowWarning(warning)
{
    html = '<div id="warning" class="alert alert-warning">'+warning+'</div>';
    $('div#container').prepend(html);
    $('div#warning').delay(3000).fadeOut(1000);
}

function semiShowSuccess(success)
{
    html = '<div id="success" class="alert alert-success">'+success+'</div>';
    $('div#container').prepend(html);
    $('div#success').delay(3000).fadeOut(1000);
}

function semiAdd(item)
{
    if(item == "password")
    {
        html = '<span class="input-group-addon" id="basic-addon">></span>'
             + '<input type="password" id="password" name="password" class="form-control" placeholder="Mot de passe" aria-describedby="basic-addon1"><br>';
    }
    $('div#input-group').prepend(html);
}

function semiRedirect(url)
{
    if(url == "register")
    {
        // Case where we need a POST action.
        var url = 'register';
        var form = $('<form action="' + url + '" method="post">' +
          '<input type="text" name="mail" value="' + $('input#mail').val() + '" />' +
          '<input type="text" name="clef" value="' + $('input#clef').val() + '" />' +
          '</form>');
        $('body').append(form);
        form.submit();
    }
    else if(url == "fastRegister")
    {
        var url = 'register';
        var form = $('<form action="' + url + '" method="post">' +
          '<input type="text" name="mail" value="' + $('input#mail').val() + '" />' +
          '<input type="text" name="clef" value="' + $('input#clef').val() + '" />' +
          '<input type="password" name="password" value="' + $('input#password').val() + '" />' +
          '</form>');
        $('body').append(form);
        form.submit();
    }
    else
    {
        window.location.replace(url);
    }
}

function semiRegisterAjax()
{
    // Password is a special case.
    if($("input#password"))
    {
        var password = $("input#password").val();
    }
    else
    {
        var password = null;
    }
    
    $.ajax({
        url : "registerajax",
	type : 'POST',
	cache : false,
	dataType : 'html',
	data: { mail: $('input#mail').val(), clef: $('input#clef').val(), password: password },
	success : function(html, statut)
	{
            // Impairs = command, pairs = argument.
            var queries = html.split('|');
            var nbQueries = queries.length;
            
            for(var i = 0; i < nbQueries; i++)
            {
                if(i % 2 === 0)
                {
                    // We took only the command.
                    if(queries[i] == "error")
                    {
                        semiShowError(queries[i+1]);
                    }
                    else if(queries[i] == "warning")
                    {
                        semiShowWarning(queries[i+1]);
                    }
                    else if(queries[i] == "success")
                    {
                        semiShowSuccess(queries[i+1]);
                    }
                    else if(queries[i] == "add")
                    {
                        semiAdd(queries[i+1]);
                    }
                    else if(queries[i] == "redirect")
                    {
                        semiRedirect(queries[i+1]);
                    }
                }
            }
	}
    });
}