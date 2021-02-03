import datetime
import json
from orm import *
from flask import jsonify


def make_json_transfers(transfer, sender, recipient):
    timestamp = datetime.datetime.fromisoformat(str(transfer.time)).timestamp()
    return {
        "Details": {
            "Amount": transfer.money,
            "Status": transfer.status.name,
            "Timestamp": str(datetime.datetime.fromtimestamp(int(timestamp))),
            "Title": transfer.title,
            "Verified": transfer.verified
        },
        "Recipient": {
            "Account": recipient.account_number,
            "Address": recipient.address,
            "BankName": recipient.bank.name,
            "Name": recipient.name,
            "Town": recipient.city,
            "ZIP": recipient.zip_code
        },
        "Sender": {
            "Account": sender.account_number,
            "Address": sender.address,
            "BankName": sender.bank.name,
            "Name": sender.name,
            "Town": sender.city,
            "ZIP": sender.zip_code
        }
    }


def find_transfers_for_bank(bank):
    db = DBMethods()
    transfers = db.get_all(Transfers)
    transfers_list = []
    for transfer in transfers:
        recipient = db.get_query(Client).filter_by(id=transfer.id_receiver).first()
        sender = db.get_query(Client).filter_by(id=transfer.id_sender).first()
        re_bank = db.get_query(Banks).filter_by(name=recipient.bank.name).first()
        if bank == re_bank.name:
            if transfer.status.name == 'VERIFIED' or transfer.status.name == 'REJECTED' \
                    or transfer.status.name == 'EXECUTED_TRUE' or transfer.status.name == 'EXECUTED_FALSE':
                transfers_list.append(make_json_transfers(transfer, sender, recipient))
    return transfers_list


def add_transfer_to_db(obj):
    db = DBMethods()
    for i in obj:
        bank = DBMethods().get_query(Banks).filter_by(name=i['Sender']['BankName']).first()
        check_if_sender_exists = db.get_query(Client).filter_by(name=(i['Sender']['Name']),
                                                                address=(i['Sender']['Address']),
                                                                zip_code=(i['Sender']['ZIP']),
                                                                account_number=(i['Sender']['Account']),
                                                                city=(i['Sender']['Town']),
                                                                bank_id=bank.id).first()
        if check_if_sender_exists is None:
            client = Client(name=i['Sender']['Name'], address=i['Sender']['Address'], zip_code=i['Sender']['ZIP'],
                            account_number=i['Sender']['Account'], city=i['Sender']['Town'], bank_id=bank.id)
            db.add(client)
            sender = db.get_query(Client).filter_by(name=(i['Sender']['Name']),
                                                    address=(i['Sender']['Address']),
                                                    zip_code=(i['Sender']['ZIP']),
                                                    account_number=(i['Sender']['Account']),
                                                    city=(i['Sender']['Town']),
                                                    bank_id=bank.id).first()
        else:
            sender = check_if_sender_exists

        bankRe = DBMethods().get_query(Banks).filter_by(name=i['Recipient']['BankName']).first()
        check_if_recipient_exists = db.get_query(Client).filter_by(name=(i['Recipient']['Name']),
                                                                   address=(i['Recipient']['Address']),
                                                                   zip_code=(i['Recipient']['ZIP']),
                                                                   account_number=(i['Recipient']['Account']),
                                                                   city=(i['Recipient']['Town']),
                                                                   bank_id=bankRe.id).first()
        if check_if_recipient_exists is None:
            client2 = Client(name=i['Recipient']['Name'], address=i['Recipient']['Address'], zip_code=i['Recipient']['ZIP'],
                             account_number=i['Recipient']['Account'], city=i['Recipient']['Town'], bank_id=bankRe.id)
            db.add(client2)
            recipient = db.get_query(Client).filter_by(name=(i['Recipient']['Name']),
                                                       address=(i['Recipient']['Address']),
                                                       zip_code=(i['Recipient']['ZIP']),
                                                       account_number=(i['Recipient']['Account']),
                                                       city=(i['Recipient']['Town']),
                                                       bank_id=bankRe.id).first()
        else:
            recipient = check_if_recipient_exists

        transfer = Transfers(money=i['Details']['Amount'], id_sender=sender.id, id_receiver=recipient.id,
                             time=datetime.datetime.fromtimestamp(int(i['Details']['Timestamp'])), status=i['Details']['Status'],
                             verified=i['Details']['Verified'], title=i['Details']['Title'])
        db.add(transfer)


def return_transfers_status(obj):
    db = DBMethods()
    for i in obj:
        bank = DBMethods().get_query(Banks).filter_by(name=i['Sender']['BankName']).first()
        sender = db.get_query(Client).filter_by(name=(i['Sender']['Name']),
                                                                address=(i['Sender']['Address']),
                                                                zip_code=(i['Sender']['ZIP']),
                                                                account_number=(i['Sender']['Account']),
                                                                city=(i['Sender']['Town']),
                                                                bank_id=bank.id).first()
        bankRe = DBMethods().get_query(Banks).filter_by(name=i['Recipient']['BankName']).first()
        recipient = db.get_query(Client).filter_by(name=(i['Recipient']['Name']),
                                                                   address=(i['Recipient']['Address']),
                                                                   zip_code=(i['Recipient']['ZIP']),
                                                                   account_number=(i['Recipient']['Account']),
                                                                   city=(i['Recipient']['Town']),
                                                                   bank_id=bankRe.id).first()

        transfer_cur = db.get_query(Transfers).filter_by(money=i['Details']['Amount'],
                                                     id_sender=sender.id,
                                                     id_receiver=recipient.id,
                                                     time=datetime.datetime.fromtimestamp(int(i['Details']['Timestamp'])),
                                                     title=i['Details']['Title']).first()

        if i['Details']['Executed'] == 'True':
            transfer = Transfers(money=i['Details']['Amount'],
                                 id_sender=sender.id,
                                 id_receiver=recipient.id,
                                 time=datetime.datetime.fromtimestamp(int(i['Details']['Timestamp'])),
                                 status=TransferStatusEnum.EXECUTED_TRUE,
                                 verified=True, title=i['Details']['Title'])

            db.update_transfer(transfer_cur.id, transfer)
        else:
            transfer = Transfers(money=i['Details']['Amount'],
                                 id_sender=sender.id,
                                 id_receiver=recipient.id,
                                 time=datetime.datetime.fromtimestamp(int(i['Details']['Timestamp'])),
                                 status=TransferStatusEnum.EXECUTED_FALSE,
                                 verified=True, title=i['Details']['Title'])

            db.update_transfer(transfer_cur.id, transfer)


def manual_verification(listt):
    amount_sum = 0.0
    for i in listt:
        i['Details']['Status'] = TransferStatusEnum.AWAITS_MANUAL_VERIFICATION.name
        i['Details']['Verified'] = False
        amount_sum += float(i['Details']['Amount'])
        print(i['Details']['Amount'])
    return listt, amount_sum


def after_manual_verification_rejected(listt):
    for i in listt:
        transfer = DBMethods().get(Transfers, int(i))

        bank_sender_id = transfer.sender.bank_id
        bank_sender = DBMethods().get_query(Banks).filter_by(id=bank_sender_id).first()
        bal = float(bank_sender.balance) + float(transfer.money)
        up_ba = Banks(name=bank_sender.name, account_number=bank_sender.account_number, balance=bal)
        DBMethods().update_banks(bank_sender.id, up_ba)

        bank_receiver_id = transfer.receiver.bank_id
        bank_receiver = DBMethods().get_query(Banks).filter_by(id=bank_receiver_id).first()
        bal = float(bank_receiver.balance) - float(transfer.money)
        up_ba = Banks(name=bank_receiver.name, account_number=bank_receiver.account_number, balance=bal)
        DBMethods().update_banks(bank_receiver.id, up_ba)


def auto_verification(listt, bank):
    amount_sum = 0.0
    for i in listt:
        i['Details']['Status'] = TransferStatusEnum.VERIFIED.name
        i['Details']['Verified'] = True
        d = DBMethods()
        db = d.get_query(Banks).filter_by(name=i['Recipient']['BankName']).first()
        bal = float(db.balance) - float(i['Details']['Amount'])
        up_ba = Banks(name=i['Recipient']['BankName'], account_number=db.account_number, balance=bal)
        DBMethods().update_banks(db.id, up_ba)
        amount_sum += float(i['Details']['Amount'])
        print(i['Details']['Amount'])
    db = DBMethods()
    sum_in_db = db.get_query(Banks).filter_by(account_number=bank['AccountNumber']).first()
    print(sum_in_db.balance)
    new_sum = float(sum_in_db.balance)-float(amount_sum)
    print('amount sum: ', amount_sum)
    print(new_sum)
    ba = Banks(name=sum_in_db.name, account_number=sum_in_db.account_number, balance=new_sum)
    print(ba.balance)
    db.update_banks(sum_in_db.id, ba)
    return listt, amount_sum


def get_sum(listt):
    amount_sum = 0.0
    for i in listt:
        amount_sum += float(i['Details']['Amount'])
    return amount_sum


def verification_get_data(obj, bank, return_transfers):
    db = DBMethods()
    current_bank = db.get_query(Banks).filter_by(account_number=bank['AccountNumber']).first()
    if current_bank is None:
        b = Banks(name=bank['Name'], account_number=bank['AccountNumber'], balance=10000.00)
        DBMethods().add(b)

    if return_transfers is not None:
        return_transfers_status(return_transfers)

    if obj is not None:
        to_be_verified_manually = []
        to_be_verified_automatically = []

        for i in obj:
            i['Details']['Status'] = TransferStatusEnum.UNVERIFIED.name

            if float(i['Details']['Amount']) > 1000.00:
                i['Details']['Status'] = TransferStatusEnum.AWAITS_MANUAL_VERIFICATION.name
                i['Details']['Verified'] = False
                to_be_verified_manually.append(i)
            else:
                to_be_verified_automatically.append(i)

        sum_ver_man = get_sum(to_be_verified_manually)
        sum_ver_auto = get_sum(to_be_verified_automatically)
        sum_all = round(sum_ver_auto+sum_ver_man, 2)

        print('Sum of the auto verified transactions:\t%.2f' % sum_ver_auto)
        print('Sum of the manually verified transactions:\t%.2f' % sum_ver_man)
        print('Total sum of the transfers:\t%.2f' % sum_all)
        print(float(bank['Amount']))
        if sum_all == float(bank['Amount']):
            if sum_ver_auto > float(current_bank.balance) \
                    or sum_ver_man > float(current_bank.balance)\
                    or sum_all > float(current_bank.balance):
                for j in obj:
                    j['Details']['Status'] = TransferStatusEnum.UNVERIFIED.name
                    j['Details']['Verified'] = False
                print('Transactions can not be executed, bank balance is to low')
                js = {
                    "error": 'Transactions can not be executed, bank balance is to low'
                }
                return json.dumps(js, ensure_ascii=False, indent=4).encode('utf8')
            else:
                print('Transactions can be executed, bank balance is ok')

                list1, amount_sum1 = auto_verification(to_be_verified_automatically, bank)
                print("Auto auth:\t%.2f" % amount_sum1)
                add_transfer_to_db(list1)

                list2, amount_sum2 = manual_verification(to_be_verified_manually)
                print("Manual auth:\t%.2f" % amount_sum2)
                add_transfer_to_db(list2)

                sum1 = amount_sum1+amount_sum2
                print('Total sum of the transfers:\t%.2f' % sum1)

                l = []
                for i in to_be_verified_manually:
                    l.append(i)
                for j in to_be_verified_automatically:
                    l.append(j)

                cur_bank = DBMethods().get_query(Banks).filter_by(account_number=bank['AccountNumber']).first()
                js = {
                    "BankData": {
                        "AccountNumber": bank['AccountNumber'],
                        "Balance": cur_bank.balance
                    },
                    "ReturnTransfers": l,
                    "Transfers": find_transfers_for_bank(bank['Name'])
                }
                return jsonify(js)
        else:
            for j in obj:
                j['Details']['Status'] = TransferStatusEnum.UNVERIFIED.name
            print('Transactions can not be executed, sum of transfers in not equal to stated sum in the file')
            js = {
                "error": 'Transactions can not be executed, sum of transfers in not equal to stated sum in the file'
            }
            return json.dumps(js, ensure_ascii=False, indent=4).encode('utf8')

