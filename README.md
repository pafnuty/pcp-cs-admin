# pcp-cs-admin

Независимая админка для [pcp-cs](https://github.com/Mofsy/pcp-cs)

## На данный момент работает
- Адаптивность админки (куда ж без этого...)
- Авторизация (подходит таблица от DLE)
- Вывод списка лицензий
- Вывод списка методов
- Постраничка на ajax
- Добавление нового метода на ajax
- Создание лицензионных ключей вручную
- Cмена статуса лицензии
- Просмотр логов
- Фильтрация ключей по методам
- Редактирование методов
- Защита от подбора пароля


## Что нужно реализовать:
- Мелочи и удобности.

## Установка
1. Заливаем содержимое папки upload на сервер
2. Выполняем содержимое файла sql.sql
3. Правим под себя три файла:
    * `admin/config/db_config.php` - Конфиг БД
    * `admin/config/config.php` - Конфиг адмнки
    * `api/config.php` - Конфиг pcp-cs

Для авторизации использовать логин и пароль `admin`, Так же есть ещё `user`, был заведён для проверки прав доступа групп.

## Сторонние библиотеки, используемые в проекте
- [шаблонизатор Fenom](https://github.com/bzick/fenom) - наболее подходящее решение для поставленных целей!
- [SafeMySQL](https://github.com/colshrapnel/safemysql) - удобный класс для работы с БД, есть всё, что нужно для быстрой и безопасной работы.
- [Pager](https://github.com/flexocms/flexo1.source/blob/master/cms/helpers/Pager.php) - Доработанный под нужды проекта класс постраничной навигации.
- [Ladda](https://github.com/hakimel/Ladda)
- [MagnificPopup](https://github.com/dimsemenov/Magnific-Popup)
- [selectize.js](https://github.com/brianreavis/selectize.js)
- [form](https://github.com/malsup/form) - для ajax форм
- [SweetAlert](https://github.com/Mikhus/jsurl)
- [jsurl](https://github.com/t4t5/sweetalert)
