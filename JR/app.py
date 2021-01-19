import datetime, time, json, xmltodict
from flask import Blueprint, request, Flask, jsonify, render_template, redirect, url_for
from orm import *
from verification import *
import xml.etree.ElementTree as ET

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


@app.route("/temp", methods=['GET'])
def temp(listt):
    return render_template("potwierdz_przelew.html", obj=listt)


@app.route("/", methods=['POST'])
def incoming():
    obj = xmltodict.parse(request.data)

    obj_json = obj

    transfers = obj_json['Data']['Transfer']
    bank = obj_json['Data']['BankData']

    verification_get_data(transfers, bank)

    return jsonify(transfers, bank)


if __name__ == '__main__':
    app.run()
