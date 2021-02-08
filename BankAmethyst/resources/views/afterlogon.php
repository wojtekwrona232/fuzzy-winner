<!DOCTYPE html>
<html lang="en">

<?php session_start();
if(isset($_REQUEST["kod"])){
    $kd = $_REQUEST["kod"];
    $_SESSION["kodKlienta"] = $kd;
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Strona Główna</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css">
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Material Design Bootstrap -->
    <link rel="stylesheet" href="css/mdb.css">
    <!-- Style -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@1,600&display=swap" rel="stylesheet">
    <style>

        html,body{
            min-width: 500px;
        }
        .picker__box .picker__table .picker__day.picker__day--today {
            color: #6f00ea;
        }


        .picker__box .picker__table .picker__day--selected, .picker__box .picker__table .picker__day--selected:hover, .picker__box .picker__table .picker--focused {
            background-color: #783fc4 !important;
            color: white !important;
        }


        .picker__box .picker__header .picker__date-display {
            background-color: #833de3 !important;
        }

        .picker__box .picker__footer .picker__button--today:before {
            top: -.05em;
            width: 0;
            border-top: .66em solid #6f00ea;
            border-left: .66em solid transparent;
        }

        .form-check-label {
            color: #495057;

        }

        .form-check-input[type="radio"]:not(:checked) + label:before, .form-check-input[type="radio"]:not(:checked) + label:after, label.btn input[type="radio"]:not(:checked) + label:before, label.btn input[type="radio"]:not(:checked) + label:after {
            border: 2px solid #cdcdcd !important;
        }

        .form-check-input[type="radio"]:checked + label:after {
            background-color: #965ce7 !important;
            border: 2px solid #965ce7 !important;
        }

        .special_button {
            padding: .6rem 1.6rem;
            font-size: .84rem;
        }

        .labelel {
            color: #2E2E2E;
            font-weight: 300;
            font-size: 1.3rem;
            margin-bottom: 15px;
        }

        .smoll_labelel {
            color: #2E2E2E;
            font-weight: 300;
            font-size: 1.1rem;
            margin-bottom: 15px;
        }

        p {
            margin-bottom: 0;
        }

        .welcome {
            color: #2E2E2E;
            font-weight: 400;
            font-size: 1.30rem;
            margin-bottom: 15px;
        }

        #saldo {
            color: #2E2E2E;
            font-weight: 700;
            font-size: 2.0rem;
            margin-bottom: 15px;
        }

        #debet {
            color: #2E2E2E;
            font-weight: 500;
            font-size: 1.2rem;
            margin-bottom: 15px;
        }

        #witaj {
            color: #2E2E2E;
            font-weight: 700;
            font-size: 1.65rem;
            margin-bottom: 15px;
        }
        input.select-dropdown.form-control{
             color: white!important;
              margin: 0px!important;
         }
          li{
              width:300px;
          }
        .select-wrapper span.caret {
            color:white!important;
        }
        @media only screen and (max-width: 800px) {
           td{

               font-size: 0.9rem!important;
               margin-bottom: 0px!important;
           }
            .card-body{
                padding: 15px!important;

            }
            .welcome {
                font-size: 0.90rem;

            }

            #saldo {
                font-size: 1.4rem;
            }

            #debet {
                font-size: 0.8rem;
            }

            #witaj {
                font-size: 1.3rem;
            }
            .aaa{
                margin-top: 5px!important;
            }
        }

        @media (max-width: 768px) {
            .navbar:not(.top-nav-collapse) {
                background: #945ae2!important;
            }
            li{
                width: 97%!important;
            }
        }

    </style>
</head>
<body>

<!--Main Navigation-->
<header>

    <nav class="navbar navbar-expand-lg navbar-dark fixed-top scrolling-navbar">
        <div class="container">
            <h3><a href="/" style="text-decoration: none;color:white;"><i class="fas fa-arrow-circle-left" href=""></i> &nbsp </a><a  class="navbar-brand" href="afterlogon"><strong>Amethyst Holdings</strong></a></h3>

        </div>
    </nav>

    <!--Intro Section-->
    <section class="view intro-4">
        <div class="mask mbackground h-100 d-flex justify-content-center align-items-center">
            <div class="container">
                <div class="row">
                    <div class="col-xl-10 col-lg-11 col-md-12 col-sm-12 mx-auto mt-lg-3">
                        <!--Form with header-->
                        <div class="card wow fadeIn" data-wow-delay="0.3s">
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-6">
                                        <p <span class="welcome" id="imie"> </span></p>
                                        <p><span class="welcome">Konto główne </span></p>
                                        <p><span class="welcome" id="kontoGlowne"></span>
                                        <p></p>

                                    </div>

                                    <div class="col-6 align-bottom text-right">
                                        <p>&nbsp; </p>
                                        <p>&nbsp; </p>
                                        <p><span id="saldo"></span></p>
                                        <p><span id="debet">w tym debet w koncie: 0,00 zł</span></p>


                                    </div>
                                </div>
                                <div class="row float-right">

                                    <div class="text-center mt-1 mb-0">
                                        <a class="btn btn-outline-secondary waves-effect pl-5 pr-5" role="button" href="historia">
                                            Historia</a>
                                        </button>


                                    </div>
                                    <div class="text-center mt-1 mb-0"><a class="btn mbutton pl-5 pr-5" role="button" href="przelew"> Przelew </a>


                                    </div>
                                </div>
                            </div>
                            <!--/Form with header-->
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-10 col-lg-11 col-md-12 col-sm-12 mx-auto mt-3 mt-lg-3 aaa">
                        <!--Form with header-->
                        <div class="card wow fadeIn" data-wow-delay="0.3s">
                            <div class="card-body">
                                <span class="labelel">Ostatnie transakcje</span>
                                <!--Header-->
                                <div class='mt-0'>
                                    <!--Table-->
                                    <table class="table table-hover table-sm">
                                        <!--Table head-->
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nadawca</th>
                                            <th>Odbiorca</th>
                                            <th>Tytuł</th>
                                            <th>Data</th>
                                            <th>Kwota</th>

                                        </tr>
                                        </thead>
                                        <!--Table head-->
                                        <!--Table body-->
                                        <tbody  id="najnowszePrzelewy">

                                        </tbody>
                                        <!--Table body-->
                                    </table>
                                    <!--Table-->
                                    <div class="text-center mt-1 mb-0">
                                        <a href="historia" class="btn btn-outline-secondary" type="button" >Pokaż więcej</a>


                                    </div>
                                </div>
                            </div>
                            <!--/Form with header-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>
        </div>
    </section>

</header>
<!--Main Navigation-->


<!-- jQuery -->
<script type="text/javascript" src="js/jquery.min.js"></script>
<!-- Bootstrap tooltips -->
<script type="text/javascript" src="js/popper.min.js"></script>
<!-- Bootstrap core JavaScript -->
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<!-- MDB core JavaScript -->
<script type="text/javascript" src="js/mdb.min.js"></script>
<!-- Your custom scripts (optional) -->


<script>
    var client = null;
    var aktualneKonto = 0;

    //funkcja do uzupelniania podstawowych informacji o kliencie
    function fillAccount(){
        var numerSpacje = client.accounts[aktualneKonto].numer.numer.match(/[A-Z]{2}|(?:(?:\d{2}|\d{4})(?=(\d{4})*$))/g).join(" ");

        //konto glowne
        $("#kontoGlowne").html(numerSpacje);

        //saldo
        $("#saldo").html("Saldo: "+client.accounts[aktualneKonto].saldo.saldo+" zł");
        fillActHistory();
    }

    function swapIndex(iteracja){
        aktualneKonto = iteracja;
        fillAccount();
    }

    //funkcja do uzupelniania skroconej wersji historii
    function fillActHistory(){
        currNrKonta = client.accounts[aktualneKonto].numer.numer;
        $.post("api/amethyst/newestTransfers", "account_number="+client.accounts[aktualneKonto].numer.numer, function(result) {
            var items = [];
            var lp = 1;
            $("#najnowszePrzelewy").html('');

            $.each(result, function(i,item){
                var tr = '<tr>\n' +
                    '                            <th scope="row">'+ lp++ +'</th>\n' +
                    '                            <td>'+ item.nadawca+'</td>\n' +
                    '                            <td>'+ item.odbiorca+'</td>\n' +
                    '                            <td>'+ item.tytul +'</td>\n' +
                    '                            <td>'+ item.created_at+'</td>\n' +
                    '                            <td>'+ ((item.nadawca == currNrKonta) ? '-' : '+')+ item.kwota +'zł </td>\n' +
                    '                            </tr>';
                items.push(tr);
            })
            $("#najnowszePrzelewy").html(items.join(''));
        })

    }

    //pozyskanie id klienta i wywolanie poszczegolnych funkcji
    $(document).ready(function(){
    <?php $kd = $_SESSION['kodKlienta'] ?>
        $.post("api/amethyst/getAccounts", "id="+<?php echo($kd); ?> , function(result){
            client = JSON.parse(result);

            //imie i nazwisko
            $("#imie").html("Witaj "+client.nazwisko);

            fillAccount();

        });
    });
</script>

<script>
    new WOW().init();

    $(document).ready(function () {
        $('.mdb-select').materialSelect();
    });
    $(document).ready(function () {
        $("tr:odd").css("color", "#370064");
    });
</script>
</body>
</html>
