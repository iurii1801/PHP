# Лабораторная работа №5. Работа с базой данных

## Цель работы

Освоить архитектуру с единой точкой входа, подключение шаблонов для визуализации страниц, а также переход от хранения данных в файле к использованию базы данных (MySQL).

## Условия

Продолжите разработку проекта, начатого в предыдущей лабораторной работе, необходимо:

- Реализовать архитектуру с единой точкой входа (`index.php`), обрабатывающей все входящие HTTP-запросы.
- Настроить базовую систему шаблонов с использованием файла `layout.php` и отдельных представлений для разных страниц.
- Перенести логику работы с рецептами из файловой системы в базу данных (например, `MySQL`).

## Используемая среда

- СУБД: **MySQL** (через интерфейс **phpMyAdmin**, установленный в составе XAMPP)
- Веб-сервер: **Apache** (через **XAMPP**)
- Язык: **PHP 8.2**
- Интерфейс управления базой: **phpMyAdmin** (localhost)

![image](https://i.imgur.com/3oZP27d.png)

> [!NOTE]
> **Примечание**: хотя phpMyAdmin по умолчанию работает с сервером MariaDB, полностью совместимым с MySQL, в рамках отчёта мы будем использовать термин **MySQL**, как указано в задании.

## Настройка окружения через `XAMPP`

Для локальной разработки использован программный комплекс `XAMPP`, включающий в себя:

1. Apache — для запуска PHP-приложения на локальном сервере;

2. MySQL — сервер баз данных, работающий совместимо с MariaDB;

3. phpMyAdmin — визуальный интерфейс для администрирования базы данных.

  На панели управления `XAMP` были запущены модули:

![image](https://i.imgur.com/AqTfahO.png)

- Благодаря `XAMPP`, разработка происходила в локальной среде по адресу [http://localhost/recipe-book/public/index.php](http://localhost/recipe-book/public/)

## Ход работы

### Задание 1. Подготовка среды

#### **Создана база данных `recipe_book` со следующими таблицами**

![image](https://i.imgur.com/qdRF79z.png)

#### Таблица `categories`

```sql
CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

![image](https://i.imgur.com/aTfml50.png)

**Объяснение:**
Таблица содержит список категорий рецептов (например, Завтрак, Обед). Поле `id` — первичный ключ. Поле `created_at` автоматически заполняется при создании записи.

#### Таблица `recipes`

```sql
CREATE TABLE recipes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  category INT NOT NULL,
  ingredients TEXT,
  description TEXT,
  tags TEXT,
  steps TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (category) REFERENCES categories(id) ON DELETE CASCADE
);
```

![image](https://i.imgur.com/dFyKRNC.png)

**Объяснение:**
Основная таблица с рецептами. В ней хранится название, категория (в виде внешнего ключа), ингредиенты, описание, теги, шаги и дата создания. Все текстовые поля допускают длинный ввод. `FOREIGN KEY` обеспечивает связь с таблицей `categories`. Указан `ON DELETE CASCADE`, что позволяет автоматически удалять рецепты при удалении категории.

#### Таблица `steps`

```sql
CREATE TABLE steps (
  id INT AUTO_INCREMENT PRIMARY KEY,
  recipe_id INT NOT NULL,
  step_number INT NOT NULL,
  description TEXT NOT NULL,
  FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE
);
```

![image](https://i.imgur.com/S5jh6xR.png)

**Объяснение:**
Каждый рецепт состоит из шагов. Эта таблица хранит список шагов, связанных с рецептом. Для каждого шага указывается его номер и описание. Используется внешний ключ `recipe_id` для связи с рецептом.

#### Таблица `tags`

```sql
CREATE TABLE tags (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL UNIQUE
);
```

![image](https://i.imgur.com/V8UxqzK.png)

**Объяснение:**
Таблица с уникальными тегами (например, "веган", "быстро"). Используется в связке с `recipe_tag` для создания связи многие-ко-многим.

#### Таблица связи `recipe_tag`

```sql
CREATE TABLE recipe_tag (
  recipe_id INT NOT NULL,
  tag_id INT NOT NULL,
  PRIMARY KEY (recipe_id, tag_id),
  FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE,
  FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
);
```

![image](https://i.imgur.com/xJfLUu4.png)

**Объяснение:**
Промежуточная таблица для связи рецептов с тегами. Поддерживает связь многие-ко-многим: один рецепт может иметь несколько тегов и наоборот. Используется составной первичный ключ `recipe_id, tag_id`.

> [!NOTE]
> **Таблицы `steps`, `tags` и `recipe_tag` реализованы в рамках дополнительного задания для поддержки шагов приготовления и тегов, связанных с рецептами.**

### Задание 2. Архитектура и шаблонизация

#### Структура проекта

```sh
recipe-book/
├── README.md                          # Файл с описанием проекта и отчётом
├── config/
│   └── db.php                         # Параметры подключения к базе данных
├── public/
│   ├── index.php                      # Главная точка входа (роутинг)
│   ├── styles.css                     # Общие стили для всех страниц
│   └── recipe/
│       ├── create.php                # Отображение формы добавления рецепта
│       ├── delete.php                # Удаление рецепта по POST
│       ├── edit.php                  # Отображение формы редактирования рецепта
│       ├── index.php                 # Альтернативный список рецептов (не основной)
│       ├── save.php                  # (Ранее использовался для сохранения рецепта)
│       └── show.php                  # Детальный просмотр рецепта
├── src/
│   ├── db.php                         # Функция подключения к базе данных через PDO
│   ├── helpers.php                    # Утилиты, фильтрация, валидация
│   └── handlers/
│       └── recipe/
│           └── edit.php              # Обработка логики редактирования рецепта
└── templates/
    ├── index.php                      # Шаблон отображения всех рецептов
    ├── layout.php                     # Базовый шаблон для всех страниц
    └── recipe/
        ├── create.php                # Шаблон формы добавления рецепта
        ├── edit.php                  # Шаблон формы редактирования рецепта
        └── show.php                  # Шаблон детального отображения рецепта
```

**`public/index.php`**

```php
<?php
/**
 * Главная точка входа.
 * Загружает рецепты из базы данных с пагинацией и подключает шаблон отображения.
 *
 * @package RecipeBook
 */

require_once __DIR__ . '/../src/db.php';

// Включение отображения ошибок (на этапе отладки)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$pdo = getPDO();

// Количество рецептов на странице
$perPage = 5;

/**
 * Номер текущей страницы, полученный из GET-запроса (?page=2 и т.д.)
 *
 * @var int $page
 */
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

/**
 * Сдвиг для SQL-запроса (OFFSET)
 *
 * @var int $offset
 */
$offset = ($page - 1) * $perPage;

/**
 * Получение общего количества рецептов
 */
$totalRecipes = $pdo->query("SELECT COUNT(*) FROM recipes")->fetchColumn();
$totalPages = ceil($totalRecipes / $perPage);

/**
 * Получение рецептов с пагинацией
 *
 * @var array $recipes
 */
$stmt = $pdo->prepare("
    SELECT r.*, c.name AS category_name
    FROM recipes r
    JOIN categories c ON r.category = c.id
    ORDER BY r.created_at DESC
    LIMIT :limit OFFSET :offset
");

$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$recipes = $stmt->fetchAll();

/**
 * Сделать переменные глобальными для шаблона layout
 */
$GLOBALS['page'] = $page;
$GLOBALS['totalPages'] = $totalPages;

/**
 * Подключение шаблона
 */
$template = __DIR__ . '/../templates/index.php';
include __DIR__ . '/../templates/layout.php';
```

- Файл `index.php` выполняет роль маршрутизатора и контроллера. Это основная точка входа в веб-приложение. Сначала подключается функция `getPDO()` из `src/db.php`, чтобы получить доступ к базе данных через PDO. Далее реализована логика пагинации: определяется текущая страница, вычисляется количество рецептов, общее число страниц и смещение (`OFFSET`) для SQL-запроса.
Рецепты выбираются с помощью подготовленного SQL-запроса, который объединяет таблицы `recipes` и `categories`, чтобы сразу получить имя категории. Это повышает читаемость кода и производительность.
Затем список рецептов сохраняется в переменную `$recipes`, которая передаётся в шаблон `templates/index.php` через общий шаблон `layout.php`. Таким образом, файл обеспечивает вывод данных и визуальное оформление через шаблоны.

**`templates/layout.php`**

```php
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Каталог рецептов</title>
    <link rel="stylesheet" href="/styles.css">
</head>
<body>
    <nav>
        <a href="/index.php">Главная</a>
        <a href="/public/recipe/create.php">Добавить рецепт</a>
    </nav>

    <main>
        <?php include $template; ?>
    </main>
</body>
</html>
```

- Файл `layout.php` является базовым шаблоном, используемым для всех страниц проекта. Он содержит стандартную HTML-разметку, подключение CSS и навигационное меню. Главная особенность шаблона — использование переменной `$template`, в которую подставляется нужный контент (например, список рецептов, форма или отдельный рецепт).
Таким образом, `layout.php` обеспечивает единый стиль и структуру страниц, а также изолирует разметку от логики — это повышает масштабируемость проекта и удобство поддержки.

**`templates/index.php (фрагмент)`**

```php
/**
 * Шаблон вывода всех рецептов с пагинацией.
 *
 * @var array $recipes Массив рецептов
 * @global int $page Текущая страница
 * @global int $totalPages Общее количество страниц
 */

<?php foreach ($recipes as $recipe): ?>
    <li>
        <strong><?= htmlspecialchars($recipe['title']) ?></strong><br>
        Категория: <?= htmlspecialchars($recipe['category_name']) ?><br>
        Добавлен: <?= $recipe['created_at'] ?><br>

        <div class="actions">
            <a class="details-button" href="/public/recipe/show.php?id=<?= $recipe['id'] ?>">Подробнее</a>

            <form method="POST" action="/public/recipe/delete.php" class="inline-form" onsubmit="return confirm('Удалить рецепт?');">
                <input type="hidden" name="id" value="<?= $recipe['id'] ?>">
                <button class="delete-button" type="submit">Удалить</button>
            </form>
        </div>
    </li>
<?php endforeach; ?>
```

- Этот шаблон выводит список всех рецептов, полученных из базы данных. Каждая запись выводится в виде карточки с заголовком, категорией и датой добавления. Для каждого рецепта реализованы две основные функции:

  1. Кнопка "Подробнее" открывает страницу `show.php`, где отображаются все детали рецепта;

  2. Кнопка "Удалить" реализована как HTML-форма с методом `POST`, что соответствует REST-принципам безопасности. Кнопка дополнительно защищена `confirm()` - проверкой, чтобы избежать случайного удаления.

Кроме того, все поля проходят обработку через `htmlspecialchars()` для защиты от XSS-атак. Этот шаблон — часть MVC-архитектуры и отвечает за представление (View).

**`templates/recipe/create.php (фрагмент)`**

```php
/**
 * Шаблон формы добавления рецепта.
 * Загружает категории из базы данных, отображает ошибки валидации,
 * сохраняет старые значения из сессии.
 *
 * @package RecipeBook
 */

<form method="POST" action="/src/handlers/recipe/create.php" class="main-form">
    <label for="title">Название:</label>
    <input type="text" name="title" id="title" required>

    <label for="category">Категория:</label>
    <select name="category" id="category">
        <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
        <?php endforeach; ?>
    </select>

    <label for="ingredients">Ингредиенты:</label>
    <textarea name="ingredients"></textarea>

    <label for="tags">Теги (через запятую):</label>
    <input type="text" name="tags">

    <label>Шаги приготовления:</label>
    <textarea name="steps[]"></textarea>

    <button type="submit">Сохранить</button>
</form>
```

- Файл `create.php` реализует форму для добавления нового рецепта. Все поля формы соответствуют структуре таблицы `recipes` в базе данных. Категории динамически подгружаются из БД, чтобы пользователь мог выбрать существующую.
Поля `ingredients`, `tags`, `steps` вводятся вручную. Теги — в одну строку, шаги — в виде массива `steps[]`, чтобы можно было передать несколько шагов.
После отправки формы данные передаются в обработчик create.php в папке `src/handlers/recipe`. Также предусмотрена возможность повторного отображения введённых данных при ошибках через `$_SESSION['old']`.

**`templates/recipe/show.php`**

```php

/**
 * Шаблон отображения одного рецепта.
 *
 * @var array $recipe Основная информация о рецепте
 */

<h1><?= htmlspecialchars($recipe['title']) ?></h1>

<p><strong>Категория:</strong> <?= htmlspecialchars($recipe['category_name']) ?></p>
<p><strong>Дата добавления:</strong> <?= $recipe['created_at'] ?></p>

<?php if ($recipe['description']): ?>
    <h3>Описание</h3>
    <p><?= nl2br(htmlspecialchars($recipe['description'])) ?></p>
<?php endif; ?>

<?php if ($recipe['ingredients']): ?>
    <h3>Ингредиенты</h3>
    <pre><?= htmlspecialchars($recipe['ingredients']) ?></pre>
<?php endif; ?>

<?php if (!empty($stepList)): ?>
    <h3>Шаги приготовления</h3>
    <ol>
        <?php foreach ($stepList as $step): ?>
            <li><?= nl2br(htmlspecialchars($step['description'])) ?></li>
        <?php endforeach; ?>
    </ol>
<?php endif; ?>

<?php if (!empty($tagList)): ?>
    <h3>Теги</h3>
    <p><?= implode(', ', array_map('htmlspecialchars', $tagList)) ?></p>
<?php endif; ?>

<a href="../index.php" class="back-link">Назад к списку</a>
```

- Файл `show.php` — шаблон для отображения полного рецепта. Сюда передаётся массив $recipe и связанные шаги и теги. Если поля заполнены — они выводятся в виде секций с заголовками.
Описание и шаги проходят через `nl2br()` и `htmlspecialchars()` — так сохраняется разметка и обеспечивается безопасность.
Формат шагов и тегов предполагает, что они хранятся в отдельных таблицах и извлекаются через JOIN-запросы в контроллере. Кнопка "Назад" ведёт на главную.


**`src/db.php`**

```php
<?php
/**
 * Подключение к базе данных через PDO.
 *
 * @return PDO
 */
function getPDO(): PDO {
    $config = require __DIR__ . '/../config/db.php';

    $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4";
    return new PDO($dsn, $config['user'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
}
```

- Функция `getPDO()` — это единая точка подключения к базе данных. Она использует настройки из файла config/db.php, включая хост, логин, пароль и имя БД.
Используется `mysql:...` DSN с кодировкой `utf8mb4`. PDO настроен на:

  - выброс исключений при ошибках (`ERRMODE_EXCEPTION`);

  - получение результатов в виде ассоциативных массивов (`FETCH_ASSOC`).

Это упрощает работу с базой и делает доступ к данным безопасным. Такой подход инкапсулирует подключение и исключает дублирование кода.

### Задание 3. Подключение к базе данных

>
1. В файле `src/db.php` реализуйте функцию подключения к базе данных (_Рекомендуется использовать PDO_).
2. Храните параметры подключения `config/db.php`.
   1. _Доп. задание_: используйте файл `.env` для хранения подключения к базе данных.
3. Функция подключения должна возвращать экземпляр `PDO`, настроенный на выбрасывание исключений (`PDO::ERRMODE_EXCEPTION`).
   1. _Примечание_: при желании можно реализовать класс `Database` с методами `query()`, `insert()`, `find()` и т.д. для инкапсуляции работы с базой данных.

**Файл `src/db.php` — реализует подключение через PDO**

```php
<?php
/**
 * Возвращает объект PDO для подключения к базе данных MySQL.
 *
 * @return PDO
 */
function getPDO(): PDO {
    $config = require __DIR__ . '/../config/db.php';

    $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4";
    return new PDO($dsn, $config['user'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
}
```

- Эта функция централизует подключение к базе данных через PDO. Она:

- использует параметры подключения из файла config/db.php;

- настраивает режим выброса исключений (`PDO::ERRMODE_EXCEPTION`) — это обязательно по заданию;

- устанавливает `FETCH_ASSOC` — данные из базы приходят в виде ассоциативных массивов.

**Файл `config/db.php` — содержит параметры подключения**

```php
<?php
return [
    'host' => 'localhost',
    'dbname' => 'recipe_book',
    'user' => 'root',
    'password' => '', // по умолчанию в XAMPP
];
```

- Это конфигурационный файл, который позволяет удобно менять параметры БД в одном месте. Он подключается в `db.php` и не содержит логики.

### Задание 3. Реализация CRUD-функциональности

>
Реализуйте обработчики следующих операций:

  Добавление рецепта (`handlers/recipe/create.php`);
  Редактирование рецепта (`handlers/recipe/edit.php`);
  Удаление рецепта (`handlers/recipe/delete.php`).

Все данные должны сохраняться и извлекаться из базы данных.
Обязательно реализуйте проверку и валидацию данных на стороне сервера.

**`src/handlers/recipe/create.php`**

```php
<?php
/**
 * Обработчик формы добавления рецепта.
 * Валидирует данные и сохраняет их в БД.
 *
 * @package RecipeBook
 */

session_start();
require_once __DIR__ . '/../../../src/db.php';

$pdo = getPDO();

$title = trim($_POST['title'] ?? '');
$category = (int)($_POST['category'] ?? 0);
$ingredients = trim($_POST['ingredients'] ?? '');
$description = trim($_POST['description'] ?? '');
$tags = trim($_POST['tags'] ?? '');
$steps = $_POST['steps'] ?? [];

$errors = [];

// Валидация
if (!$title) $errors[] = 'Название обязательно';
if (!$category) $errors[] = 'Категория обязательна';

if ($errors) {
    $_SESSION['errors'] = $errors;
    $_SESSION['old'] = $_POST;
    header('Location: /public/recipe/create.php');
    exit;
}

// Вставка рецепта
$stmt = $pdo->prepare("INSERT INTO recipes (title, category, ingredients, description) VALUES (?, ?, ?, ?)");
$stmt->execute([$title, $category, $ingredients, $description]);
$recipeId = $pdo->lastInsertId();

// Обработка тегов
$tagList = array_map('trim', explode(',', $tags));
foreach ($tagList as $tag) {
    if ($tag === '') continue;

    // Добавляем тег, если его ещё нет
    $tagStmt = $pdo->prepare("INSERT IGNORE INTO tags (name) VALUES (?)");
    $tagStmt->execute([$tag]);

    $tagId = $pdo->lastInsertId() ?: $pdo->query("SELECT id FROM tags WHERE name = " . $pdo->quote($tag))->fetchColumn();

    // Привязываем тег к рецепту
    $linkStmt = $pdo->prepare("INSERT INTO recipe_tag (recipe_id, tag_id) VALUES (?, ?)");
    $linkStmt->execute([$recipeId, $tagId]);
}

// Вставка шагов
foreach ($steps as $i => $step) {
    if (trim($step) === '') continue;

    $stepStmt = $pdo->prepare("INSERT INTO steps (recipe_id, step_number, description) VALUES (?, ?, ?)");
    $stepStmt->execute([$recipeId, $i + 1, trim($step)]);
}

header('Location: /index.php');
```

- Обработчик формы добавления рецепта. 
Сначала происходит фильтрация и валидация входных данных. Если поля пустые — сохраняются ошибки в сессии и пользователь возвращается на форму.
Если ошибок нет — рецепт добавляется в таблицу `recipes`, после чего `lastInsertId()` используется для привязки шагов и тегов.
Теги сначала проверяются и, если их нет — вставляются. После этого связываются с рецептом через таблицу `recipe_tag`.
Шаги приготовления нумеруются и вставляются в таблицу `steps`.
Все SQL-запросы безопасны и используют подготовленные выражения.

**`public/recipe/delete.php`**

```php
<?php
/**
 * Удаляет рецепт по ID.
 * Также автоматически удаляются шаги и теги благодаря ON DELETE CASCADE.
 *
 * @package RecipeBook
 */

require_once __DIR__ . '/../../src/db.php';

$pdo = getPDO();

// Проверка метода запроса
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);

    if ($id > 0) {
        $stmt = $pdo->prepare("DELETE FROM recipes WHERE id = ?");
        $stmt->execute([$id]);
    }
}

header('Location: /index.php');
exit;
```

- Этот обработчик реализует удаление рецепта по его `id`, переданному методом `POST`. Это важно для соблюдения безопасности и REST-принципов.
Удаление происходит только если `id` валидный. Благодаря внешним ключам с `ON DELETE CASCADE`, автоматически удаляются все связанные данные — шаги (`steps`) и связи с тегами (`recipe_tag`).
После удаления пользователь перенаправляется на главную страницу.
Такой подход предотвращает SQL-инъекции и позволяет централизованно удалять рецепты.

**`templates/recipe/edit.php (шаблон формы)`**

```php
<?php
/**
 * Шаблон формы редактирования рецепта.
 * Загружает старые значения и категории из базы данных.
 *
 * @package RecipeBook
 */
?>
<h1>Редактировать рецепт</h1>

<form method="POST" action="/src/handlers/recipe/edit.php" class="main-form">
    <input type="hidden" name="id" value="<?= $recipe['id'] ?>">

    <label for="title">Название:</label>
    <input type="text" name="title" id="title" value="<?= htmlspecialchars($recipe['title']) ?>">

    <label for="category">Категория:</label>
    <select name="category" id="category">
        <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $recipe['category'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="ingredients">Ингредиенты:</label>
    <textarea name="ingredients"><?= htmlspecialchars($recipe['ingredients']) ?></textarea>

    <label for="description">Описание:</label>
    <textarea name="description"><?= htmlspecialchars($recipe['description']) ?></textarea>

    <button type="submit">Сохранить изменения</button>
</form>
```

- Этот шаблон отображает форму редактирования рецепта. В отличие от формы создания, все поля здесь уже предзаполнены данными, загруженными из базы.
Для удобства пользователя автоматически выбран текущий пункт категории. После редактирования данные отправляются в файл `handlers/recipe/edit.php`, который обрабатывает обновление рецепта в базе.

**`src/handlers/recipe/edit.php`**

```php
<?php
/**
 * Обработчик редактирования рецепта.
 * Обновляет существующую запись в базе данных.
 *
 * @package RecipeBook
 */

require_once __DIR__ . '/../../../src/db.php';

$pdo = getPDO();

$id = (int)($_POST['id'] ?? 0);
$title = trim($_POST['title'] ?? '');
$category = (int)($_POST['category'] ?? 0);
$ingredients = trim($_POST['ingredients'] ?? '');
$description = trim($_POST['description'] ?? '');

$errors = [];

if (!$id || !$title || !$category) {
    $errors[] = 'Все поля обязательны';
}

if ($errors) {
    // Тут можно сохранить в сессию ошибки, если надо
    header("Location: /public/recipe/edit.php?id=$id");
    exit;
}

$stmt = $pdo->prepare("
    UPDATE recipes
    SET title = ?, category = ?, ingredients = ?, description = ?
    WHERE id = ?
");

$stmt->execute([$title, $category, $ingredients, $description, $id]);

header('Location: /index.php');
```

- Файл обрабатывает данные, полученные с формы редактирования рецепта. Выполняется простая валидация обязательных полей (`id`, `title`, `category`).
Если всё корректно — обновляется запись в таблице `recipes` с помощью подготовленного SQL-запроса (`UPDATE ... WHERE id = ?`).
Этот подход безопасен, так как исключает SQL-инъекции. После успешного редактирования пользователь перенаправляется на главную страницу.

### Задание 4. Защита от SQL-инъекций

> Используйте подготовленные выражения для выполнения SQL-запросов.

Проверьте, что все входные данные корректно экранируются и валидируются перед выполнением запросов к базе данных.

Продемонстрируйте пример SQL-инъекции в вашем приложении и объясните, как вы её предотвратили.

#### Используемая защита в проекте

**Все SQL-запросы выполняются через подготовленные выражения (prepared statements):**

***Пример: добавление рецепта***

```php
$stmt = $pdo->prepare("INSERT INTO recipes (title, category, ingredients, description) VALUES (?, ?, ?, ?)");
$stmt->execute([$title, $category, $ingredients, $description]);
```

***Пример: выборка по ID***

```php
$stmt = $pdo->prepare("SELECT * FROM recipes WHERE id = ?");
$stmt->execute([$id]);
```

***Пример: удаление по ID***

```php
$stmt = $pdo->prepare("DELETE FROM recipes WHERE id = ?");
$stmt->execute([$id]);
```

- Подготовленные выражения в PDO работают так, что структура SQL-запроса передаётся отдельно от пользовательских данных. Это предотвращает внедрение вредоносного кода, потому что данные интерпретируются строго как значения, а не как часть SQL-команды.

#### Валидация и фильтрация данных

Все входные значения:

- очищаются с помощью `trim()` для удаления лишних пробелов;

- преобразуются в `int` при необходимости (например, ID и category);

- текстовые поля экранируются при выводе с помощью `htmlspecialchars()` для защиты от XSS.

#### Пример SQL-инъекции без защиты

Если бы код выглядел так:

```php
// Плохо: данные напрямую подставляются в запрос
$id = $_GET['id'];
$result = $pdo->query("SELECT * FROM recipes WHERE id = $id");
```

Пользователь может ввести:

```bash
?id=1 OR 1=1
```

Результат: SQL-запрос превращается в:

```sql
SELECT * FROM recipes WHERE id = 1 OR 1=1
```

- Это приведёт к выводу всех рецептов, независимо от их ID, и потенциально — к раскрытию конфиденциальных данных.

#### Как мы это предотвращаем

```php
$id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM recipes WHERE id = ?");
$stmt->execute([$id]);
```

Даже если пользователь передаст `1 OR 1=1`, всё будет трактоваться как строка, а не SQL-команда.
PDO автоматически экранирует значения, и SQL-инъекция становится невозможной.

### Задание 5. Дополнительное задание: Пагинация

> **Реализуйте пагинацию с использованием SQL-запросов `LIMIT` и `OFFSET`, отображая по 5 рецептов на странице (`page=2`, `page=3` и т.д.).**

**`Пример кода (`из public/index.php`):`**

```php
$perPage = 5;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $perPage;

$totalRecipes = $pdo->query("SELECT COUNT(*) FROM recipes")->fetchColumn();
$totalPages = ceil($totalRecipes / $perPage);

$stmt = $pdo->prepare("
    SELECT r.*, c.name AS category_name
    FROM recipes r
    JOIN categories c ON r.category = c.id
    ORDER BY r.created_at DESC
    LIMIT :limit OFFSET :offset
");

$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$recipes = $stmt->fetchAll();
```

**Вывод пагинации (`в templates/index.php`)**

```php
<nav class="pagination">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <?php if ($i === $page): ?>
            <strong>[<?= $i ?>]</strong>
        <?php else: ?>
            <a href="/index.php?page=<?= $i ?>">[<?= $i ?>]</a>
        <?php endif; ?>
    <?php endfor; ?>
</nav>
```

- Пагинация позволяет делить список рецептов на части — по 5 рецептов на страницу. Это улучшает производительность и удобство.
В SQL-запрос добавляется `LIMIT` и `OFFSET`, которые позволяют получать нужный фрагмент записей. Количество страниц (`$totalPages`) рассчитывается на основе общего числа записей.
На странице выводятся ссылки на каждую страницу, а текущая страница отображается жирным шрифтом.

## Ответы на вопросы

### 1. Какие преимущества даёт использование единой точки входа в веб-приложении?

Единая точка входа (файл `index.php` в папке `public/`) позволяет централизованно обрабатывать все входящие HTTP-запросы. Это упрощает маршрутизацию, облегчает поддержку проекта, позволяет реализовать общую обработку ошибок, сессий, авторизации и повысить безопасность (например, с помощью CSRF-защиты и фильтрации данных). Такой подход обеспечивает удобное масштабирование и унификацию всех маршрутов внутри приложения.

### 2. Какие преимущества даёт использование шаблонов?

Шаблонизация (использование `layout.php` и подстраниц `templates/…`) позволяет отделить логику приложения от представления. Это делает код более читаемым, уменьшает дублирование и упрощает поддержку дизайна. Все страницы подключаются через базовый макет `layout.php`, где содержатся общие элементы (например, заголовок, подключение CSS), а контент подставляется динамически. Такой подход облегчает повторное использование кода и соблюдение принципов MVC.

### 3. Какие преимущества даёт хранение данных в базе по сравнению с хранением в файлах?

Хранение данных в MySQL обеспечивает надёжность, безопасность и масштабируемость. В отличие от хранения в файлах, база данных позволяет выполнять гибкие выборки с помощью SQL-запросов, использовать связи между таблицами (например, `recipes` и `categories`), реализовывать индексацию и сортировку, а также централизованно управлять целостностью данных. Это особенно важно при увеличении количества пользователей и записей. Также база данных поддерживает транзакции, резервное копирование и разграничение доступа.

### 4. Что такое SQL-инъекция? Придумайте пример SQL-инъекции и объясните, как её предотвратить.

SQL-инъекция — это тип уязвимости, при котором злоумышленник внедряет произвольный SQL-код через пользовательский ввод, чтобы изменить поведение запроса. Например, если в коде не используется подготовленное выражение:

```php
$pdo->query("SELECT * FROM recipes WHERE id = $id");
```

и пользователь передаст `id=1 OR 1=1`, то запрос вернёт все записи. Это может привести к утечке данных.
Способ защиты: всегда использовать подготовленные выражения (prepared statements) с параметрами:

```php
$stmt = $pdo->prepare("SELECT * FROM recipes WHERE id = ?");
$stmt->execute([$id]);
```

Это надёжно экранирует ввод и предотвращает SQL-инъекции.

## Библиография

1. [PHP Manual](https://www.php.net/manual/ru/)
2. [Работа с PDO](https://www.php.net/manual/ru/book.pdo.php)
3. [Описание XAMPP](https://www.apachefriends.org/ru/index.html)
4. [Документация по SQL и MySQL](https://dev.mysql.com/doc/)
5. [Шаблонизация в PHP](https://www.php.net/manual/ru/language.basic-syntax.phpmode.php)

