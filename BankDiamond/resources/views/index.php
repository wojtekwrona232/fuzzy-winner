<html>

    <head>

        <title>Logowanie - Diamond Holdings</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- responsywnosc -->

        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css"> <!-- ikony -->
        <link rel="stylesheet" href="css/bootstrap.min.css"> <!-- bootstrap -->
        <link rel="stylesheet" href="css/bootstrapLux.min.css"> <!-- bootstrap -->
        <link rel="stylesheet" type="text/css" href="css/style.css" /> <!-- style -->

    </head>
    <body>

    <div class="container">

        <div class="row">

        <div class="col-lg-3"></div>
        <div class="col-lg-6" style="height: 110%; margin-top: 4%; border: solid black 1px; padding: 10px; background-color: white; text-align: center;">

            <div class="row" style="margin:auto; width:90%; text-align: center;">

                <h1 style ="font-size: 45; display: inline-block; font-weight: 150;">Diamond Holdings</h1> <i class="far fa-gem fa-2x" style="color:black;"></i> <br/><br/>

                <form id="form" action="strona_glowna.php" method="GET">


                    <div class="form-group row needs-validation">

                            <label for="login" style = "font-size:17px;">Login</label>
                            <input type="text" class="form-control" name="login" id="login" required> <!-- input loginu -->

                            <div id="form-message1"></div>

                    </div>
                    <div class="form-group row needs-validation">

                            <label for="haslo" style = "font-size:17px;" >Hasło</label>
                            <input type="password" class="form-control" name="password" id="password" required> <!-- input hasla -->

                            <div id="logError"></div>

                    </div>

                    <br/>

                    <input type="hidden" id="kod" name="kod" value="">
                    <input type="hidden" id="csrf_field" name="csrf_field" >

                    <button id="wyslij" type="button" class="btn btn-default btn-dark" style="width:60%">Zaloguj</button> <!-- button zalogowania -->

                </form>

            </div>

        </div>
        <div class="col-lg-3"></div>
    </div>

    <?php @include('komponenty/stopka.php');?>

    <script type="text/javascript" src="js/jquery.min.js"></script> <!-- jquery -->

    <!-- sprawdzanie poprawnosci loginu i hasla i przekazanie wartosci id zalogowanej osoby -->
    <script>
    $("#wyslij").click(function(){
        var login = $("#login").val();
        var password = $("#password").val();
        $.post("/api/diamond/login", "login="+login+"&password="+password, function(result){
            var obj = JSON.parse(result);

            if(obj.ID == -1){

                $("#logError").html('<br/><h5>Błąd logowania, sprawdź poprawność loginu lub hasła.</h5>');

            }else if(obj.jestPracownikiem == 0){

                $(location).attr('href','strona_glowna.php?kod='+obj.ID);

            }else{

                $("#kod").val(obj.ID);
                $(location).attr('href','pracownik.php?kod='+obj.ID);

            }
        });
    });
    </script>

    <script type="text/javascript" src="js/popper.min.js"></script> <!-- popper -->
    <script type="text/javascript" src="js/bootstrap.min.js"></script> <!-- bootstrap -->

    </body>

</html>
