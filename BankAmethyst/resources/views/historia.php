<!DOCTYPE html>
<html lang="en">
<?php session_start();
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Historia transakcji</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css"> <!-- ikony -->
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Material Design Bootstrap -->
    <link rel="stylesheet" href="css/mdb.css">
    <!-- Style -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@1,600&display=swap" rel="stylesheet">
    <style>

        html {
            min-width: 600px;
        }
        .my-custom-scrollbar {
            position: relative;
            height:450px;     <!--- WYSOKOŚC TABLICY -->
            overflow: auto;
        }

        html {
            min-width: 600px;
        }


        .intro-3 {
            background: url("https://u.cubeupload.com/xda321/1474624.jpg") no-repeat center center;
            background-size: cover;
        }


        .table-wrapper-scroll-y {
            display: block;
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

        table th {
            font-size: 1rem;
            font-weight: 600;
        }

        table td {
            font-size: 1rem;
            font-weight: 400;
        }
        li{
            width:300px;
        }

        input.select-dropdown.form-control{
            color: white!important;
            margin: 0px!important;
        }

        .select-wrapper span.caret {
            color:white!important;
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


            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent-7"
                    aria-controls="navbarSupportedContent-7" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>

            </button>

        </div>
    </nav>


    <!--Intro Section-->
    <section class="view intro-3">
        <div class="mask mbackground h-100 d-flex justify-content-center align-items-center">
            <div class="container">
                <div class="row">

                    <div class="col-12">
                        <!--Form with header-->
                        <div class="card wow fadeIn" data-wow-delay="0.3s">
                            <div class="card-body">
                                <div class="form-header mbutton_long" style="margin-bottom: 20px;">
                                    <h3><i class="fas fa-file-invoice-dollar mt-2"></i>&nbsp&nbspHistoria transakcji
                                    </h3>
                                </div>

                                <!--Header-->
                                <div class='table-responsive table-wrapper-scroll-y my-custom-scrollbar mt-4'>
                                    <!--Table-->
                                    <table id="tablePreview" class="table table-hover">
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
                                        <tbody id="historia">

                                        </tbody>
                                        <!--Table body-->
                                    </table>
                                    <!--Table-->
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

<script>
    var client = null;
    var aktualneKonto = 0;
    var currNrKonta;

    function swapIndex(iteracja){
        aktualneKonto = iteracja;
        fillActHistory();
    }


    function fillActHistory(){
        currNrKonta = client.accounts[aktualneKonto].numer.numer;

        $.post("api/amethyst/transferHistory", "account_number="+client.accounts[aktualneKonto].numer.numer, function(result) {

            var items = [];
            var lp = 1;
            $.each(result, function(i,item){
                var tr = '<tr>\n' +
                    '                            <th scope="row">'+ lp++ +'</th>\n' +
                    '                            <td>'+ item.nadawca+'</td>\n' +
                    '                            <td>'+ item.odbiorca+'</td>\n' +
                    '                            <td>'+ item.tytul +'</td>\n' +
                    '                            <td>'+ item.created_at +'</td>\n' +
                    '                            <td>'+ ((item.nadawca == currNrKonta) ? '-' : '+')+ item.kwota +'zł </td>\n' +
                    '                            </tr>';
                items.push(tr);
            })
            $("#historia").html(items.join(''));

        })


    }

    $(document).ready(function() {
        <?php $kd = $_SESSION['kodKlienta'] ?>
        $.post("api/amethyst/getAccounts", "id=" +<?php echo($kd); ?> , function (result) {
            client = JSON.parse(result);
            fillActHistory();

        });

    });

</script>



<!-- Your custom scripts (optional) -->
<script>
    new WOW().init();
    // Data Picker Initialization
    $('.datepicker').pickadate();
    $(document).ready(function () {
        $('.mdb-select').materialSelect();
    });
    $(document).ready(function()
    {
        $("tr:odd").css("color", "#370064");
    });
</script>
</body>
</html>
