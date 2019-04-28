/* добавляем существующий список категорий */
insert into catigories set name_category = 'Доски и лыжи', simvol_code = 'boards';
insert into catigories set name_category = 'Крепления', simvol_code = 'attachment';
insert into catigories set name_category = 'Ботинки', simvol_code = 'boots';
insert into catigories set name_category = 'Одежда', simvol_code = 'clothing';
insert into catigories set name_category = 'Инструменты', simvol_code = 'tools';
insert into catigories set name_category = 'Разное', simvol_code = 'other';

/* добавляем пользователей */
insert  into users set email = 'vasya@mai.ru', password = 'secret', user_name = 'Vasya', connect = '8 800 2000 600';
insert  into users set email = 'musya@mai.ru', password = 'musyasecret', user_name = 'Musya', connect = '8 800 00 00';
insert  into users set email = 'dusya@mai.ru', password = 'dusyasecret', user_name = 'Dusya', connect = '8 222 33 33';

/* добавляем список существующих обьявлений */
insert into lots set name_lot = '2014 Rossignol District Snowboard', picture = 'img/lot-1.jpg', price = '10999';
insert into lots set name_lot = 'DC Ply Mens 2016/2017 Snowboard', picture = 'img/lot-2.jpg', price = '159999';
insert into lots set name_lot = 'Крепления Union Contact Pro 2015 года размер L/XL', picture = 'img/lot-3.jpg', price = '8000';
insert into lots set name_lot = 'Ботинки для сноуборда DC Mutiny Charocal', picture = 'img/lot-4.jpg', price = '11999';
insert into lots set name_lot = 'Куртка для сноуборда DC Mutiny Charocal', picture = 'img/lot-5.jpg', price = '7500';
insert into lots set name_lot = 'Маска Oakley Canopy', picture = 'img/lot-6.jpg', price = '5400';

/* добавляем ставки */
insert into wager set wager_date = '2014-01-01', sum = '10999';
insert into wager set wager_date = '2015-01-01', sum = '11999';
insert into wager set wager_date = '2016-01-01', sum = '12999';

/* связь между таблицами */
select c.name_category, simvol_code from catigories c join lots l on c.name_category = l.name_lot;
select l.name_lot, price from lots l join users u on l.name_lot = u.user_name;
select w.wager_date, sum from wager w join lots l on l.name_lot = w.wager_date;

/*получить все категории*/
select * from catigories;

/*получить самые новые, открытые лоты. Каждый лот должен включать название, стартовую цену, ссылку на изображение, цену, название категории;*/
select l.name_lot, price, picture from lots l join catigories c on l.name_lot = c.name_category where last_data > now() order by date_create desc;

/*показать лот по его id. Получите также название категории, к которой принадлежит лот;*/
select l.id from lots l join catigories c where l.name_lot = c.name_category;

/*обновить название лота по его идентификатору;*/
update lots set name_lot = '2014 Rossignol District Snowboard' where id =1;

/*получить список самых свежих ставок для лота по его идентификатору.*/
select step from lots where last_data > now() order by date_create desc;
