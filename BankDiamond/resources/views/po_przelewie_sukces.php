<html>

    <head>

        <title>Informacja o realizacji przelewu - Diamond Holdings</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- responsywnosc -->

        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css"> <!-- ikony -->
        <link rel="stylesheet" href="css/bootstrap.min.css"> <!-- bootstrap -->
        <link rel="stylesheet" href="css/bootstrapLux.min.css"> <!-- bootstrap -->
        <link rel="stylesheet" type="text/css" href="css/style.css" /> <!-- style -->

    </head>
    <body>

    <?php @include('komponenty/navbar.php');?>

    <div class="container" style = "margin-top:3%;">
        <div class="row">

            <div class="col-lg-1"> </div>
            <div class="col-lg-10">
                <div class="card bg-light mb-3" style="width:100%;">

                    <div class="card-header" style="font-size:19px;">Stan realizacji przelewu</div>
                    <div class="card-body" style="text-align:center;">
                        <br/>
                        <h4 class="card-title">Operacja przelewu została przyjęta do realizacji.</h4>
                        <br/>
                        <a href="przelew.php" class="btn btn-default btn-dark" role="button" style="width:20%">Ok</a>
                    </div>

                </div>

            </div>
            <div class="col-lg-1"> </div>

        </div>
    </div>



    <?php @include('komponenty/stopka.php');?>

    <script type="text/javascript" src="js/jquery.min.js"></script> <!-- jquery -->
    <script type="text/javascript" src="js/popper.min.js"></script> <!-- popper -->
    <script type="text/javascript" src="js/bootstrap.min.js"></script> <!-- bootstrap -->
    <script type="text/javascript" src="js/mdb.min.js"></script> <!-- bootstrap -->

    </body>

</html>
