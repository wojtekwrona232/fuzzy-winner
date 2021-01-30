from sqlalchemy import engine, Column, FLOAT, String, Enum, DATETIME, ForeignKey, Integer, Boolean
from sqlalchemy.engine import create_engine
from sqlalchemy.ext.declarative import declarative_base
from sqlalchemy.orm import Session, sessionmaker, relationship
import datetime, time, enum

Base = declarative_base()


class TransferStatusEnum(enum.Enum):
    UNVERIFIED = 0
    AWAITS_MANUAL_VERIFICATION = 1
    VERIFIED = 2
    REJECTED = 3


class Banks(Base):
    __tablename__ = 'banki'
    id = Column('id', Integer, primary_key=True, nullable=False, autoincrement=True)
    name = Column('nazwa', String, nullable=False)
    account_number = Column('nr_konta', String, nullable=False)
    balance = Column('balans', FLOAT, nullable=False)


class Client(Base):
    __tablename__ = 'klienci'
    id = Column('id', Integer, primary_key=True, nullable=False, autoincrement=True)
    name = Column('imie_nazwisko', String, nullable=False)
    address = Column('adres', String, nullable=False)
    zip_code = Column('kod_pocztowy', String, nullable=False)
    city = Column('miejscowosc', String, nullable=False)
    account_number = Column('nr_konta', String, nullable=False)
    bank_id = Column('id_bank', Integer, ForeignKey('banki.id'), nullable=False)
    bank = relationship("Banks", lazy='subquery')


class Transfers(Base):
    __tablename__ = 'przelewy'
    id = Column('id', Integer, primary_key=True, nullable=False, autoincrement=True)
    money = Column('kwota', FLOAT, nullable=False)
    id_sender = Column('id_nadawcy', Integer, ForeignKey('klienci.id'), nullable=False)
    id_receiver = Column('id_odbiorcy', Integer, ForeignKey('klienci.id'), nullable=False)
    time = Column('czas', DATETIME, nullable=False)
    verified = Column('zweryfikowany', Boolean, nullable=False)
    status = Column('status', Enum(TransferStatusEnum), nullable=False)
    title = Column('tytul', String, nullable=False)
    sender = relationship("Client", lazy='subquery', foreign_keys=[id_sender])
    receiver = relationship("Client", lazy='subquery', foreign_keys=[id_receiver])


# class for orm connection to the database
class SQLUtil:
    __engine__ = engine
    __session__ = Session
    __connection__ = engine
    __transaction__ = engine

    # creates new engine for orm
    def create_engine(self):
        string = 'mysql+pymysql://root:@127.0.0.1/jr'
        self.__engine__ = create_engine(string)

    # return current engine for orm
    def get_engine(self):
        return self.__engine__

    # creates new session for orm
    def open_session(self):
        session = sessionmaker()
        session.configure(bind=self.__engine__)
        self.__session__ = session()

    def get_session(self):
        return self.__session__

    def close_session(self):
        self.__session__.close()

    def open_connection(self):
        self.__connection__ = self.get_engine().connect()

    def get_connection(self):
        return self.__connection__

    def close_connection(self):
        return self.__connection__.close()

    def transaction(self):
        self.__transaction__ = self.__connection__.begin()

    def get_transaction(self):
        return self.__transaction__

    def transaction_rollback(self):
        return self.__transaction__.rollback()

    def session_rollback(self):
        return self.__session__.rollback()


class DBMethods:

    def __init__(self):
        self.util = SQLUtil()
        self.util.create_engine()
        self.util.open_session()

    def get_all(self, entity):
        try:
            return self.util.get_session().query(entity).all()
        except Exception:
            self.util.session_rollback()
            raise Exception

    def get(self, entity, id):
        try:
            return self.util.get_session().query(entity).get(id)
        except Exception:
            self.util.session_rollback()
            raise Exception

    def get_query(self, entity):
        try:
            return self.util.get_session().query(entity)
        except Exception:
            self.util.session_rollback()
            raise Exception

    def add(self, entity):
        try:
            self.util.get_session().add(entity)
            self.util.get_session().commit()
            return True
        except Exception:
            self.util.session_rollback()
            raise Exception

    def delete_id(self, entity, e_id):
        try:
            a = self.util.get_session().query(entity).get(e_id)
            self.util.get_session().delete(a)
            self.util.get_session().commit()
        except Exception:
            self.util.session_rollback()
            raise Exception

    def update_banks(self, e_id, new_entity):
        try:
            bank = self.util.get_session().query(Banks).get(e_id)
            bank.name = new_entity.name
            bank.account_number = new_entity.account_number
            bank.balance = new_entity.balance
            self.util.get_session().commit()
        except Exception:
            self.util.session_rollback()
            raise Exception

    def update_client(self, e_id, new_entity):
        try:
            sel = self.util.get_session().query(Client).get(e_id)
            sel.name = new_entity.__getitem__(0)
            sel.address = new_entity.__getitem__(1)
            sel.zip_code = new_entity.__getitem__(2)
            sel.city = new_entity.__getitem__(3)
            sel.contact = new_entity.__getitem__(5)
            sel.account_number = new_entity.__getitem__(6)
            sel.bank_id = new_entity.__getitem__(7)
            self.util.get_session().commit()
        except Exception:
            self.util.session_rollback()
            raise Exception

    def update_transfer(self, e_id, new_entity):
        try:
            sel = self.util.get_session().query(Transfers).get(e_id)
            sel.money = new_entity.money
            sel.id_sender = new_entity.id_sender
            sel.id_receiver = new_entity.id_receiver
            sel.time = new_entity.time
            sel.verified = new_entity.verified
            sel.status = new_entity.status
            sel.title = new_entity.title
            self.util.get_session().commit()
        except Exception:
            self.util.session_rollback()
            raise Exception
