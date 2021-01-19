import json
from orm import *
from flask import render_template, url_for


def add_transfer_to_db(obj, bank):
    db = DBMethods()
    for i in obj:
        client = Client(name=i['Sender']['Name'], address=i['Sender']['Address'], zip_code=i['Sender']['ZIP'],
                        account_number=i['Sender']['Account'], city=i['Sender']['Town'])
        db.add(client)
        sender = db.get_query(Client).filter_by(name= i['Sender']['Name'],
                                                address=(i['Sender']['Address']),
                                                zip_code=(i['Sender']['ZIP']),
                                                account_number=(i['Sender']['Account']),
                                                city=(i['Sender']['Town'])).first()

        client1 = Client(name=i['Recipient']['Name'], address=i['Recipient']['Address'],
                         zip_code=i['Recipient']['ZIP'], account_number=i['Recipient']['Account'],
                         city=i['Recipient']['Town'])
        db.add(client1)
        receiver = db.get_query(Client).filter_by(name=(i['Recipient']['Name']),
                                                  address=(i['Recipient']['Address']),
                                                  zip_code=(i['Recipient']['ZIP']),
                                                  account_number=(i['Recipient']['Account']),
                                                  city=(i['Recipient']['Town'])).first()

        transfer = Transfers(money=i['Details']['Amount'], id_sender=sender, id_receiver=receiver,
                             time=i['Details']['Timestamp'], status=i['Details']['Status'],
                             verified=i['Details']['Verified'])
        db.add(transfer)


def manual_verification(listt, bank):
    amount_sum = 0.0
    for i in listt:
        i['Details']['Verified'] = False
        amount_sum += float(i['Details']['Amount'])
        print(i['Details']['Amount'])
    # return listt, amount_sum
    return render_template(url_for('temp', listt=listt))


def auto_verification(listt, bank):
    amount_sum = 0.0
    for i in listt:
        i['Details']['Status'] = TransferStatusEnum.VERIFIED.name
        i['Details']['Verified'] = True
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


def verification_get_data(obj, bank):
    db = DBMethods()
    current_bank = db.get_query(Banks).filter_by(account_number=bank['AccountNumber']).first()
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
            # add_transfer_to_db(list1, bank)
            # add_transfer_to_db(list1, bank)

            list2, amount_sum2 = manual_verification(to_be_verified_manually, bank)
            print("Manual auth:\t%.2f" % amount_sum2)
            # add_transfer_to_db(list2, bank)
            # add_transfer_to_db(list2, bank)

            sum1 = amount_sum1+amount_sum2
            print('Total sum of the transfers:\t%.2f' % sum1)
            l = []
            for i in to_be_verified_manually:
                l.append(i)
            for j in to_be_verified_automatically:
                l.append(j)
            js = {
                "BankData": {
                    "AccountNumber": bank['AccountNumber'],
                    "Amount": bank['Amount']
                },
                "Transfers": l
            }
            return json.dumps(js, ensure_ascii=False, indent=4).encode('utf8')
    else:
        for j in obj:
            j['Details']['Status'] = TransferStatusEnum.UNVERIFIED.name
        print('Transactions can not be executed, sum of transfers in not equal to stated sum in the file')
        js = {
            "error": 'Transactions can not be executed, sum of transfers in not equal to stated sum in the file'
        }
        return json.dumps(js, ensure_ascii=False, indent=4).encode('utf8')

