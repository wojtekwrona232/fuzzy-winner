<html>

<!-- uzyskanie id osoby zalogowanej -->
<?php session_start();
if(isset($_REQUEST["kod"])){
    $kd = $_REQUEST["kod"];
    $_SESSION["kodKlienta"] = $kd;
}
?>

    <head>

        <title>Strona glowna - Diamond Holdings</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- responsywnosc -->

        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css"> <!-- ikony -->
        <link rel="stylesheet" href="css/bootstrap.min.css"> <!-- bootstrap -->
        <link rel="stylesheet" href="css/bootstrapLux.min.css"> <!-- bootstrap -->
        <link rel="stylesheet" type="text/css" href="css/style.css" /> <!-- style -->

    </head>
    <body>

    <script type="text/javascript" src="js/jquery.min.js"></script> <!-- jquery -->

    <?php @include('komponenty/navbar.php');?>

    <div class="container.fluid" style = "margin:0; width:100%; margin-top:2%;">
        <div class="row" style = "width:100%;">

            <div class="col-lg-2 col-xl-1"> </div>
            <div class="col-lg-8 col-xl-10">
            <div class="card bg-light mb-3">

                <div class="card-body"> <!-- karta pierwsza -->
                    <div class = "row">
                        <div class= "col-lg-5">
                                <h4 class="card-title" id="imie">
                                Witaj
                                </h4>
                                <br/>
                                <h5 class="card-title">Konto bieżące</h5>
                                <p style="font-size: 16px;" class="card-text" id="kontoGlowne"></p>
                        </div>
                        <div class= "col-lg-2 col-xl-2"></div>
                        <div class= "col-xl-5 col-lg-5 col-md-6 col-sm-5 col-5">
                                <br/>
                                <br/>
                                <h3 class="card-title" id="saldo"></h3>
                                <p class="card-text">w tym debet: 0,00zł</p>
                                <a class="btn btn-default btn-dark" href="historia.php" role="button" style="width:49%">Historia</a>
                                <a class="btn btn-default btn-dark" href="przelew.php" role="button" style="width:49%">Przelew</a>

                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>

                <div class="row" style = "width:100%;"> <!-- historia -->
                    <div class="col-lg-2 col-xl-1"> </div>
                    <div class="col-lg-8 col-xl-10">
                <h4>Ostatnie operacje</h4>

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
                        <tbody id="najnowszePrzelewy">  <!-- tabela z 5 najnowszymi przelewami -->

                        </tbody>
                    </table> <!-- koniec tabeli -->

            <div class="col-lg-2"> </div>

            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 col-4"></div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-4"><a class="btn btn-default btn-dark" href="historia.php" role="button" style="width:100%">Więcej</a></div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-4"></div>
            </div>

            <br/><br/><br/><br/><br/><br/><br/><br/>


            </div>
                </div>
    </div>


    <?php @include('komponenty/stopka.php');?>

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
            $.post("api/diamond/newestTransfers", "account_number="+client.accounts[aktualneKonto].numer.numer, function(result) {
                var items = [];
                var lp = 1;
                $("#najnowszePrzelewy").html('');
                $.each(result, function(i,item){
                    var tr = '<tr '+ ((item.nadawca == currNrKonta) ? 'class="table-active"' : 'class="table-success"') +'>\n' +
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

        //funkcja do uzupelniania dropsdownu z numerami kont klienta
        function dropdownAccounts(){
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
        }

        //pozyskanie id klienta i wywolanie poszczegolnych funkcji
        $(document).ready(function(){
            <?php $kd = $_SESSION['kodKlienta'] ?>
            $.post("api/diamond/getAccounts", "id="+<?php echo($kd); ?> , function(result){
                client = JSON.parse(result);

                //imie i nazwisko
                $("#imie").html("Witaj "+client.nazwisko);

                fillAccount();

                dropdownAccounts();
            });
        });
    </script>

    <script type="text/javascript" src="js/popper.min.js"></script> <!-- popper -->
    <script type="text/javascript" src="js/bootstrap.min.js"></script> <!-- bootstrap -->



    </body>

</html>
