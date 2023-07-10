<h1 align="center">Pastebin</h1>
  <p> Этот проект реализован с помощью PHP 8.2, фреймворка Laravel, СУБД PostgreSql.
 <h2>Описание:</h2>
  <p> Сервис Pastebin - Он позволяет заливать куски текста/кода и получать на них
короткую ссылку, которую можно отправить другим людям. Загружать данные
можно как анонимно, так и зарегистрировавшись.</p>
<h2>Функционал сервиса:</h2>
<ul>

- Авторизация/регистрация пользователя:
  - по логину паролю
  - с помощью ВКонтакте
- Можно указать ограничение доступа
  - public - доступна всем, видна в списках
  - unlisted - доступна только по ссылке
  - private -- доступна только отправившему
- можно выбрать срок в течение которого "паста" будет доступна по ссылке
  - 10мин, 1час, 3часа, 1день, 1неделя, 1месяц, без ограничения
  - после окончания срока получить доступ к "пасте" нельзя
- для "пасты" можно выбрать язык, тогда при выводе синтаксис выбранного
  языка должен подсвечиваться
- для загруженной пасты выдается короткая ссылка вида http://my-awesomepastebin.tld/{какой-то-рандомный-хэш}, например, http://my-awesomepastebin.tld/ab12cd34
- Возможность просмотра
  - по ссылке
  - на всех страницах блок с последними 10 public пастами
  - на всех страницах залогиненный пользователь видит доп. блок с
    последними 10 своими пастами
  - зарегистрированный пользователь имеет отдельную страницу, где видит
    список всех своих паст. Все пасты, у которых вышел срок доступности, не видны никому, в том
    числе и автору
- Возможность пользователю пожаловаться на пасту
- Администрирование
    - админка Orchid
    - возможность просмотра списка пользователей, паст и жалоб
    - возможность бана пользователя и удаления паст
- API для сторонних приложений
</ul>


    <h2>Примеры:</h2>
<img src="./example/1.png" alt="Screenshot">
<img src="./example/2.png" alt="Screenshot">
<img src="./example/3.png" alt="Screenshot">


<h2>Чтобы запустить проект, выполните:</h2>

1. Создайте контейнеры:

```docker compose build```

2. Запустите их:

```docker compose up -d```


3. Создайте таблицы:

```docker compose exec app php artisan migrate```

5. Для создания пользователя с максимальными правами:

```docker compose exec app php artisan orchid:admin admin admin@admin.com password```
