<html>

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

    <?php include('komponenty/navbar_pracownik.php');?>

    <div class="container" style = "margin:0; width:100%; margin-top:10px;">
        <div class="row">

            <div class="col-lg-1"> </div>
            <div class="col-lg-10">

                <div class="row" style = "margin:2px;  width:70rem;"> <!-- historia -->

                <h3>Operacje do weryfikacji</h3>

                    <table class="table">
                        <thead class="thead-light">
                        <tr>
                            <th scope="col"></th>
                            <th scope="col">Nadawca</th>
                            <th scope="col">Odbiorca</th>
                            <th scope="col">Tytul</th>
                            <th scope="col">Data</th>
                            <th scope="col">Kwota</th>
                            <th scope="col"">Potwierdzenie</th>
                        </tr>
                        </thead>
                        <tbody id="potwierdzenia">  <!-- tabela -->


                        </tbody>
                    </table> <!-- koniec tabeli -->

            <div class="col-lg-1"> </div>

        </div>
    </div>

    <script type="text/javascript" src="js/jquery.min.js"></script> <!-- jquery -->
    <script type="text/javascript" src="js/popper.min.js"></script> <!-- popper -->
    <script type="text/javascript" src="js/bootstrap.min.js"></script> <!-- bootstrap -->

            <script>

                function zatwierdz(id){
                    $.post("api/diamond/verifyTransfer", "id="+ id +"&ver=1", function(result) {
                        fillVerification();
                    });
                }

                function odrzuc(id){
                    $.post("api/diamond/verifyTransfer", "id="+ id +"&ver=0", function(result) {
                        fillVerification();
                    });
                }

                function fillVerification(){
                    $.post("api/diamond/getTransferToVerification", function(result) {
                        verList = JSON.parse(result);
                        var items = [];
                        var lp = 1;
                        $.each(verList, function(i,item){
                            var tr = '<tr>\n' +
                                '                            <th scope="row">'+ lp++ +'</th>\n' +
                                '                            <td>'+ item.nadawca+'</td>\n' +
                                '                            <td>'+ item.odbiorca+'</td>\n' +
                                '                            <td>'+ item.tytul +'</td>\n' +
                                '                            <td>'+ item.created_at +'</td>\n' +
                                '                            <td>'+ item.kwota +'zł </td>\n' +
                                '                            <td><button type="button" class="btn btn-default btn-dark" onclick="zatwierdz('+item.id+')" style="width:90%;" >Tak</button>\n' +
                                '                            <button type="button"  class="btn btn-default btn-dark" onclick="odrzuc('+item.id+')" style="width:90%;" >Nie</button>\n' +
                                '                            </td>\n' +
                                '                            </tr>';
                            items.push(tr);
                        })
                        $("#potwierdzenia").html(items.join(''));

                    })
                }


                $(document).ready(function() {

                        fillVerification();

                });

            </script>

    </body>

</html>
