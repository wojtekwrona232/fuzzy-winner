<html>

<?php session_start();
?>

    <head>

        <title>Przelew - Diamond Holdings</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- responsywnosc -->

        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css"> <!-- ikony -->
        <link rel="stylesheet" href="css/mdb.css"> <!-- bootstrap -->
        <link rel="stylesheet" href="css/bootstrap.min.css"> <!-- bootstrap -->
        <link rel="stylesheet" href="css/bootstrapLux.min.css"> <!-- bootstrap -->
        <link rel="stylesheet" type="text/css" href="css/style.css" /> <!-- style -->

    </head>
    <body>

    <?php @include('komponenty/navbar_przelew.php');?>

    <div class="container">
        <div class="row">

            <div class="col-lg-2"> </div>

            <div class="col-lg-8" style="height: 110%; width:100%; margin-top: 2%; border: solid black 1px; padding: 4px; background-color: white; text-align: center;">

                <div class="row" style="margin:auto; width:90%; text-align: center;">

                <h1 style ="font-size: 42; display: inline-block; font-weight: 150;">Przelew</h1>
                <br/><br/><br/>

                    <form id="form" action="po_przelewie.php" method="POST">

                        <div class="form-group row needs-validation" style="margin-bottom:1px;"> <!-- wybor konta -->

                            <label style = "font-size:18px;">Z konta</label>
                            <select class="form-select" aria-label="Default select example" id="wyborKonta" required onchange="fillSaldo()">
                            </select>
                            <p id="saldoK" style="text-align: left;"></p>

                        </div>

                        <div class="form-group row needs-validation">  <!-- odbiorca-->

                                <label for="odbiorca" style = "font-size:18px;">Odbiorca</label>
                                <input type="text" class="form-control" id="odbiorca"  placeholder="Imię nazwisko" value="" required>

                                <div id="form-messagee"></div>

                        </div>

                        <div class="form-group row">  <!-- adres-->

                                <label for="adres" style = "font-size:18px;">Adres (opcjonalnie)</label>
                                <input type="text" class="form-control" id="adres"  placeholder="Adres">

                        </div>

                        <div class="form-group row">  <!-- miejscowosc-->

                                <label for="miejscowosc" style = "font-size:18px;">Miejscowość (opcjonalnie)</label>
                                <input type="text" class="form-control" id="miejscowosc"  placeholder="Miejscowość">

                        </div>

                        <div class="form-group row">  <!-- kod pocztowy-->

                                <label for="kod" style = "font-size:18px;">Kod pocztowy (opcjonalnie)</label>
                                <input type="text" class="form-control" id="kod" placeholder="00-000">

                                <div id="form-message3"></div>

                        </div>

                        <div class="form-group row needs-validation"> <!-- konto -->

                                <label for="nrkonta" style = "font-size:18px;" >Numer konta odbiorcy</label>
                                <input type="text" class="form-control" id="nrkonta" placeholder="00 0000 0000 0000 0000 0000 0000" value="" required>

                                <div id="form-message1"></div>

                        </div>

                        <div class="form-group row needs-validation">  <!-- tytul-->

                                <label for="tytul" style = "font-size:18px;">Tytuł przelewu</label>
                                <input type="text" class="form-control" id="tytul"  placeholder="Przelew środków" value="" required>

                        </div>

                        <div class="form-group row needs-validation">  <!-- kwota-->

                                <label for="kwota" style = "font-size:18px;">Kwota</label>
                                <input type="text" class="form-control" id="kwota"  placeholder="0,00" value="" required>

                                <div id="form-message2"></div>

                        </div>

                        <div class="form-check"> <!-- przelew ekspresowy lub zwykly -->
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="standard" checked>
                            <label class="form-check-label" for="standard" style = "font-size:18px;">
                                Standardowy
                            </label>

                        <br/>

                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="ekspres">
                            <label class="form-check-label" for="ekspres" style = "font-size:18px;">
                                Ekspresowy
                            </label>
                        </div>

                        <br/>

                        <button id="przelew" type="submit" class="btn btn-default btn-dark" style="width:50%;">Przelej</button> <!-- button przelewu -->

                    </form>

                </div>

            </div>
            <div class="col-lg-2"> </div>

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

            $.post("api/diamond/transfer", params, function (result) {

                if(result == 0){
                    $(location).attr('href', 'po_przelewie_blad.php');
                }else{
                    $(location).attr('href', 'po_przelewie_sukces.php');
                }

            });

        }

        function fillSaldo(){
            var selektor = document.getElementById("wyborKonta");
            var konto = client.accounts[selektor.selectedIndex];
            $("#saldoK").text("Saldo: " + konto.saldo.saldo);
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
            $.post("api/diamond/getAccounts", "id=" +<?php echo($kd); ?> , function (result) {
                client = JSON.parse(result);

                fillSelectorAccounts();

                fillSaldo();

            });

        });

    </script>



    <!-- walidacja -->
    <script>

            const form = document.getElementById("form");
            const odbiorca = document.getElementById("odbiorca");
            const kod = document.getElementById("kod");
            const nrkonta = document.getElementById("nrkonta");
            const kwota = document.getElementById("kwota");
            const formMessage = document.getElementById("form-messagee");
            const formMessage1 = document.getElementById("form-message1");
            const formMessage2 = document.getElementById("form-message2");
            const formMessage3 = document.getElementById("form-message3");
            const submit = document.getElementById("przelew");

            form.addEventListener("submit", e => {
                        e.preventDefault();
                        let formErrors = [];
                        let formErrors1 = [];
                        let formErrors2 = [];
                        let formErrors3 = [];

                        const regx = /[a-zA-Z]+\s\w+/;
                        if(!regx.test(odbiorca.value)){
                            formErrors.push("Nie podano poprawnego imienia bądź nazwiska.");
                        }


                        const regx1 = /(\d{2}[ ]\d{4}[ ]\d{4}[ ]\d{4}[ ]\d{4}[ ]\d{4}[ ]\d{4})|(\d{26})/;
                        if(!regx1.test(nrkonta.value)){
                            formErrors1.push("Niepoprawny format numeru konta.");
                        }

                        const regx2 = /^[^+-0]\d*[\.]{0,1}\d{0,2}$/;
                        if(!regx2.test(kwota.value)){
                            formErrors2.push("Niepoprawny format kwoty.");
                        }

                        if(kod.value != ""){
                            const regx3 = /[0-9]{2}-[0-9]{3}/;
                            if(!regx3.test(kod.value)){
                                formErrors3.push("Niepoprawny format kodu pocztowego.");
                            }
                        }

                        if (!formErrors.length && !formErrors1.length && !formErrors2.length && !formErrors3.length) {

                            sendTransfer();

                        }

                        else {
                        formMessage.innerHTML = `
                            <ul class="form-error-list">
                                ${formErrors.map(el => `<p>${el}</p>`).join("")}
                            </ul>
                        `;
                        formMessage1.innerHTML = `
                            <ul class="form-error-list">
                                ${formErrors1.map(el => `<p>${el}</p>`).join("")}
                            </ul>
                        `;
                        formMessage2.innerHTML = `
                            <ul class="form-error-list">
                                ${formErrors2.map(el => `<p>${el}</p>`).join("")}
                            </ul>
                        `;
                        formMessage3.innerHTML = `
                            <ul class="form-error-list">
                                ${formErrors3.map(el => `<p>${el}</p>`).join("")}
                            </ul>
                        `;

                        return false;
                    }

            });

    </script>

    </body>

</html>
