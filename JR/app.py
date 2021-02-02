import xmltodict
from flask import request, Flask, render_template
from verification import *

app = Flask(__name__)


def make_json(obj):
    return {
        'nadawca': {
            'imie_nazwisko': obj.sender.name,
            'adres': obj.sender.address,
            'kod_pocztowy': obj.sender.zip_code,
            'miejscowosc': obj.sender.city,
            'numer_konta': obj.sender.account_number,
            'nazwa_banku': obj.sender.bank.name
        },
        'odbiorca': {
            'imie_nazwisko': obj.receiver.name,
            'adres': obj.receiver.address,
            'kod_pocztowy': obj.receiver.zip_code,
            'miejscowosc': obj.receiver.city,
            'numer_konta': obj.receiver.account_number,
            'nazwa_banku': obj.receiver.bank.name
        },
        'kwota': obj.money,
        'typ': obj.type,
        'czas': obj.time,
        'zweryfikowany': obj.verified,
        'status': obj.status
    }


@app.route("/manual_verification", methods=['GET', 'POST'])
def manual_verification():
    transfers = DBMethods().get_query(Transfers).filter_by(status=TransferStatusEnum.AWAITS_MANUAL_VERIFICATION.name)

    if request.method == 'POST':
        checkedBoxes = request.form.getlist('verify')
        checkedBoxesIds = request.form.getlist('verifyId')
        for i, j in zip(checkedBoxesIds, checkedBoxes):
            transfer = DBMethods().get(Transfers, int(i))
            if j == '1':
                update_transfer = Transfers(money=transfer.money,
                                            id_sender=transfer.id_sender,
                                            id_receiver=transfer.id_receiver,
                                            time=transfer.time,
                                            verified=True,
                                            status=TransferStatusEnum.VERIFIED.name,
                                            title=transfer.title)
                DBMethods().update_transfer(int(i), update_transfer)
            if j == '2':
                update_transfer = Transfers(money=transfer.money,
                                            id_sender=transfer.id_sender,
                                            id_receiver=transfer.id_receiver,
                                            time=transfer.time,
                                            verified=True,
                                            status=TransferStatusEnum.REJECTED.name,
                                            title=transfer.title)
                DBMethods().update_transfer(int(i), update_transfer)

        print(checkedBoxes)
        print(checkedBoxesIds)
        post_transfers = DBMethods().get_query(Transfers).filter_by(status=TransferStatusEnum.AWAITS_MANUAL_VERIFICATION.name)
        after_manual_verification_rejected(checkedBoxesIds)
        return render_template("potwierdz_przelew.html", obj=post_transfers)

    return render_template("potwierdz_przelew.html", obj=transfers)


@app.route("/", methods=['POST'])
def incoming():
    obj = xmltodict.parse(request.data)

    obj_json = obj

    transfers = obj_json['Data']['Transfer']
    bank = obj_json['Data']['BankData']
    return_transfers = obj_json['Data']['ReturnTransfer']

    json_return = verification_get_data(transfers, bank, return_transfers)

    return json_return


if __name__ == '__main__':
    app.run()
