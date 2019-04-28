create database yeticave
default character set utf8
default collate utf8_general_ci;

use yeticave;

create table catigories (
    id int auto_increment primary key,
    name_category char(255) not null,
    simvol_code char(255) not null unique
);

create table lots (
    id int auto_increment primary key,
    date_create timestamp default current_timestamp,
    name_lot char(255) not null ,
    discription text default null,
    picture char(255),
    price int not null  unique ,
    last_data timestamp null,
    step tinyint default null
);

create table wager (
    id int auto_increment primary key,
    wager_date timestamp,
    sum int not null unique
);

create table users(
    id int auto_increment primary key,
    date timestamp,
    email char(255) not null unique ,
    user_name char(255) not null ,
    password char(255) not null unique ,
    avatar char(255),
    connect char(255) not null
);

create index name_category on catigories(name_category);
create index name_lot on lots(name_lot);
create index wager_date on wager(wager_date);
create index user_name on users(user_name);


