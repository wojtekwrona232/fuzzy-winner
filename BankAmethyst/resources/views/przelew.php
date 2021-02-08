<!DOCTYPE html>
<html lang="en">

<?php session_start();
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Przelew</title>
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

        .intro-1 {
            background: url("https://u.cubeupload.com/xda321/login.jpg") no-repeat center center;
            background-size: cover;
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
    </style>
</head>
<body>

<!--Main Navigation-->
<header>

    <nav class="navbar navbar-expand-lg navbar-dark fixed-top scrolling-navbar">
        <div class="container">
            <h3><a href="/" style="text-decoration: none;color:white;"><i class="fas fa-arrow-circle-left" href=""></i> &nbsp </a><a  class="navbar-brand" href="afterlogon"><strong>Amethyst Holdings</strong></a></h3>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent-7"
                    aria-controls="navbarSupportedContent-7" aria-expanded="false">
            </button>

        </div>
    </nav>

    <!--Intro Section-->
    <section class="view intro-1">
        <div class="mask mbackground h-100 d-flex justify-content-center align-items-center">
            <div class="container">
                <div class="row">
                    <div class="col-xl-5 col-lg-6 col-md-10 col-sm-12 mx-auto mt-lg-5">

                        <!--Form with header-->
                        <div class="card wow fadeIn" data-wow-delay="0.3s">
                            <div class="card-body">
                                <!--Header-->
                                <div class="form-header mbutton" style="margin-bottom: 40px;">
                                    <h3><i class="fas fa-money-check-alt mt-2"></i>&nbspPrzelew</h3>
                                </div>


                                <select class="mdb-select md-form" id="wyborKonta" required onchange="fillSaldo()"></select>
                                <label class="mdb-main-label">Z konta</label>

                                <div class="md-form">
                                    <input type="text" id="odbiorca" class="form-control"
                                           placeholder="Wpisz nazwę odbiorcy">
                                    <label for="odbiorca" >Odbiorca</label>
                                    <div id="form-messagee"></div>
                                </div>

                                <div class="md-form">
                                    <input type="text" id="adres" class="form-control"
                                           placeholder="Adres">
                                    <label for="adres" >Adres (opcjonalny)</label>
                                </div>

                                <div class="md-form">
                                    <input type="text" id="miejscowosc" class="form-control"
                                           placeholder="Miejscowosc">
                                    <label for="miejscowosc" >Miejscowosc (opcjonalna)</label>
                                </div>

                                <div class="md-form">
                                    <input type="text" id="kod" class="form-control"
                                           placeholder="00-000">
                                    <label for="kod" >Kod pocztowy (opcjonalny)</label>
                                    <div id="form-message3"></div>
                                </div>

                                <div class="md-form">
                                    <input type="text" id="nrkonta" class="form-control"
                                           placeholder="00 0000 0000 0000 0000 0000 0000">
                                    <label for="nrkonta">Numer konta</label>
                                    <div id="form-message1"></div>
                                </div>

                                <div class="md-form">
                                    <input type="text" id="tytul" class="form-control"
                                           placeholder="Przelew środków">
                                    <label for="tytul">Tytuł</label>
                                </div>

                                <div class="md-form">
                                    <input type="number" min="0" id="kwota" class="form-control"
                                           placeholder="0,00">
                                    <label for="kwota">Kwota</label>
                                    <div id="form-message2"></div>

                                </div>

                                <span style="font-size: 0.82rem;color:#495057;font-weight: 300">Typ przelewu</span>
                                <div>
                                    <!-- Material inline 1 -->
                                    <div class="form-check form-check-inline ">
                                        <input type="radio" class="form-check-input" id="standard"
                                               name="inlineMaterialRadiosExample" checked>
                                        <label class="form-check-label" for="standard">Standardowy</label>
                                    </div>

                                    <!-- Material inline 2 -->
                                    <div class="form-check form-check-inline float-right">
                                        <input type="radio" class="form-check-input" id="ekspres"
                                               name="inlineMaterialRadiosExample">
                                        <label class="form-check-label" for="ekspres">Ekspresowy</label>
                                    </div>
                                </div>

                                <div class="text-center mt-4">
                                    <button class="btn mbutton special_button " id="przelew" type="submit" onclick="sendTransfer()">Potwierdź przelew</button>


                                </div>
                            </div>
                            <!--/Form with header-->

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
    new WOW().init();
    // Data Picker Initialization
    $('.datepicker').pickadate();
    $(document).ready(function () {
        $('.mdb-select').materialSelect();
    });
</script>

<script>
    var client = null;
    var aktualneKonto = 0;

    //funkcja przesylajaca dane do przelewu - wykonujaca przelew
    function sendTransfer(){
        var nadawcaKonto = $("#wyborKonta").val();
        nadawcaKonto = nadawcaKonto.replace(/\s/g, '');
        var imie_nazwisko = $("#odbiorca").val();
        var adres = $("#adres").val();
        var miejscowosc = $("#miejscowosc").val();
        var kod = $("#kod").val();
        if(kod != null){
            kod = kod.replace(/-/g, '');
        }
        var odbiorca = $("#nrkonta").val();
        odbiorca = odbiorca.replace(/\s/g, '');
        var tytul = $("#tytul").val();
        var kwota = $("#kwota").val();
        var isStandard = $("#standard").is(':checked');

        var params = "nadawca="+nadawcaKonto+"&imie_nazwisko="+imie_nazwisko+"&adres="+adres+"&miejscowosc="+miejscowosc+"&kod="+kod+"&odbiorca="+odbiorca+"&tytul="+tytul+"&kwota="+kwota+"&standard="+isStandard;

        $.post("api/amethyst/transfer", params, function (result) {

            $(location).attr('href', 'afterlogon');

        });

    }

    //funckcja uzupelniajaca selektor znajdujacy sie w polu przelewu o dostepne konta klienta
    function fillSelectorAccounts(){
        var items = [];
        var iteracja = 0;
        $.each(client.accounts, function(i,item){

            var numerSpacje2 = item.numer.numer.match(/[A-Z]{2}|(?:(?:\d{2}|\d{4})(?=(\d{4})*$))/g).join(" ");
            var li;

            if(iteracja++ == 0){
                li = '<option selected>'+numerSpacje2+'</option>';
            }else {
                li = '<option>'+numerSpacje2+'</option>';
            }
            items.push(li);
        })
        $("#wyborKonta").html(items.join(''));
    }

    //pozyskanie id klienta i wywolanie poszczegolnych funkcji
    $(document).ready(function() {
        <?php $kd = $_SESSION['kodKlienta'] ?>
        $.post("api/amethyst/getAccounts", "id=" +<?php echo($kd); ?> , function (result) {
            client = JSON.parse(result);

            fillSelectorAccounts();

        });

    });

</script>


</body>
</html>
