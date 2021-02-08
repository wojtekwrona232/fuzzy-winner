<html>
<?php session_start();
?>


    <head>

        <title>Historia - Diamond Holdings</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- responsywnosc -->

        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css"> <!-- ikony -->
        <link rel="stylesheet" href="css/mdb.css"> <!-- bootstrap -->
        <link rel="stylesheet" href="css/bootstrap.min.css"> <!-- bootstrap -->
        <link rel="stylesheet" href="css/bootstrapLux.min.css"> <!-- bootstrap -->
        <link rel="stylesheet" type="text/css" href="css/style.css" /> <!-- style -->

    </head>
    <body>

    <?php @include('komponenty/navbar.php');?>

    <div class="container">
        <div class="row">

            <div class="col-lg-1"> </div>

            <div class="col-lg-10" style="height: 110%; margin-top: 2%; border: solid black 1px; padding: 15px; background-color: white; text-align: center;">

                <div class="row" style="margin:auto;text-align: center;">

                    <h1 style ="font-size: 40; display: inline-block; font-weight: 150;">Historia</h1>

                    <div class="form-group col-lg-6 needs-validation" style="margin-bottom:3%; margin:auto;text-align: center;" > <!-- od kiedy -->
                        <label for="date-picker1" style = "font-size:17px;">Data początkowa</label>

                        <input placeholder="Wybierz datę" type="text" id="date-picker1" class="form-control datepicker" data-value="2021/01/14" required>
                    </div>

                </div>

            </div>

            <div class="col-lg-1"> </div>


            <div class="row" style = "margin:auto; margin-top: 3%;">

                    <table class="table">
                        <thead class="thead-light">
                        <tr>
                            <th scope="col"></th>
                            <th scope="col">Nadawca</th>
                            <th scope="col">Odbiorca</th>
                            <th scope="col">Tytul</th>
                            <th scope="col">Data</th>
                            <th scope="col">Kwota</th>
                        </tr>
                        </thead>
                        <tbody id="historia">  <!-- tabela -->


                        </tbody>
                    </table> <!-- koniec tabeli -->
            </div>

        </div>
    </div>

    <br/><br/><br/><br/>


    <?php @include('komponenty/stopka.php');?>


    <script type="text/javascript" src="js/jquery.min.js"></script> <!-- jquery -->
    <script type="text/javascript" src="js/popper.min.js"></script> <!-- popper -->
    <script type="text/javascript" src="js/bootstrap.min.js"></script> <!-- bootstrap -->
    <script type="text/javascript" src="js/mdb.min.js"></script> <!-- bootstrap -->

    <script>
        var client = null;
        var aktualneKonto = 0;
        var currNrKonta;

        function swapIndex(iteracja){
            aktualneKonto = iteracja;
            fillActHistory();
        }

        function fillHistDate(result){
            var items = [];
            var lp = 1;
            $.each(result, function(i,item){
                var tr = '<tr '+ ((item.nadawca == currNrKonta) ? 'class="table-active"' : 'class="table-success"') +'>\n' +
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
        }

        function fillActHistory(){
            currNrKonta = client.accounts[aktualneKonto].numer.numer;

            $.post("api/diamond/transferHistory", "account_number="+client.accounts[aktualneKonto].numer.numer, function(result) {

                fillHistDate(result);

        })


        }

        $(document).ready(function() {
                <?php $kd = $_SESSION['kodKlienta'] ?>
                $.post("api/diamond/getAccounts", "id=" +<?php echo($kd); ?> , function (result) {
                    client = JSON.parse(result);
                    fillActHistory();

                    var items = [];
                    var iteracja = 1;
                    $.each(client.accounts, function(i,item){

                        var numerSpacje2 = item.numer.numer.match(/[A-Z]{2}|(?:(?:\d{2}|\d{4})(?=(\d{4})*$))/g).join(" ");
                        var li;

                        if(iteracja == 1){
                            li = '<li><a class="dropdown-item" onclick="swapIndex('+ (iteracja-1) +')" style="font-size:15px;">'+ iteracja++ +'. Saldo: '+item.saldo.saldo+' zł <br/>\n' +
                                '            '+ numerSpacje2 +'\n' +
                                '            </a></li>';
                        }else {
                            li = '<li><a class="dropdown-item" onclick="swapIndex('+ (iteracja-1) +')" style="font-size:15px;border-top:1px solid black;">'+ iteracja++ +'. Saldo: '+item.saldo.saldo+' zł <br/>\n' +
                                '            '+ numerSpacje2 +'\n' +
                                '            </a></li>';
                        }
                        items.push(li);
                    })
                    $("#wszystkieKonta").append(items.join(''));

                });

        });

        </script>


    <!-- datepicker -->
    <script>
        new WOW().init();

        $('#date-picker1').pickadate({
            onSet:function(kontekst){
                var data = new Date(kontekst.select);
                $.post("api/diamond/transferHistoryDates", "account_number=" +currNrKonta+ "&from_date="+ data.toJSON() +"", function (result) {

                    fillHistDate(result);
                });
            }
        });

        $(document).ready(function () {
            $('.mdb-select').materialSelect();
        });
    </script>

    </body>

</html>
