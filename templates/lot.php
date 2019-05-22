
<main>
    <nav class="nav">
        <ul class="nav__list container">
            <?php
            while ($index < $num) : ?>
                <li class="nav__item">
                    <a href="pages/all-lots.html"><? $catigories[$index]; ?></a>
                </li>
                <?php $index++; ?>
            <?php endwhile; ?>
        </ul>
    </nav>
    <section class="lot-item container">
        <?php foreach ($advert as $key => $item): ?>
        <h2><? $item["name"]; ?></h2>
        <div class="lot-item__content">
            <div class="lot-item__left">
                <div class="lot-item__image">
                    <img src="<?=$item["gif"]; ?>" width="730" height="548" alt="Сноуборд">
                </div>
                <p class="lot-item__category">Категория: <span><?=$item["category"];?></span></p>
                <p class="lot-item__description">Легкий маневренный сноуборд, готовый дать жару в любом парке, растопив
                    снег
                    мощным щелчкоми четкими дугами. Стекловолокно Bi-Ax, уложенное в двух направлениях, наделяет этот
                    снаряд
                    отличной гибкостью и отзывчивостью, а симметричная геометрия в сочетании с классическим прогибом
                    кэмбер
                    позволит уверенно держать высокие скорости. А если к концу катального дня сил совсем не останется,
                    просто
                    посмотрите на Вашу доску и улыбнитесь, крутая графика от Шона Кливера еще никого не оставляла
                    равнодушным.</p>
            </div>
            <div class="lot-item__right">
                <div class="lot-item__state">
                    <div class="lot-item__timer timer <?=show_date()[1];?>">
                        <?=show_date()[0];?>
                    </div>
                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span class="lot-item__cost">10 999</span>
                        </div>
                        <div class="lot-item__min-cost">
                            Мин. ставка <span><?=format_price($item["price"]);?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </section>
</main>
