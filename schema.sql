create database yeticave
default character set utf8
default collate utf8_general_ci;

use yeticave;

create table catigories (
    id int auto_increment primary key,
    name_category char not null,
    simvol_code char(50) not null unique
);

create table lots (
    id int auto_increment primary key,
    date_create timestamp not null ,
    name_lot char not null ,
    discription text not null ,
    picture char,
    price int not null  unique ,
    last_data timestamp not null ,
    step tinyint not null
);

create table wager (
    id int auto_increment primary key,
    date timestamp,
    sum int not null unique
);

create table users(
    id int auto_increment primary key,
    date timestamp,
    email char(68) not null unique ,
    user_name char not null ,
    password char not null unique ,
    avatar char,
    connect char not null
);

create index name_category on catigories(name_category);
create index name_lot on lots(name_lot);
create index summa on wager(sum);
create index user_name on users(user_name);

create unique index simvol_code on catigories(simvol_code);
create unique index price on lots(price);
create unique index sum on wager(sum);
create unique index email on users(email);

