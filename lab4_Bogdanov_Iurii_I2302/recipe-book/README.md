# Лабораторная работа №4. Обработка и валидация форм

## Цель работы

Освоить основные принципы работы с HTML-формами в PHP, включая отправку данных на сервер и их обработку, включая валидацию данных.

Данная работа станет основой для дальнейшего изучения разработки веб-приложений. Дальнейшие лабораторные работы будут основываться на данной.

## Условие

Студенты должны выбрать тему проекта для лабораторной работы, которая будет развиваться на протяжении курса.

**Например**:

- ToDo-лист;
- Блог;
- Система управления задачами;
- другие.

Для данной лабораторной работы в качестве примера используется проект **"Каталог рецептов"**, можно выбрать данную тему.

---

## Задания

### Задание 1. Создание проекта

1. Создайте корневую директорию проекта (например, `recipe-book`).
2. Организуйте файловую структуру проекта.

### Задание 2. Создание формы добавления рецепта

1. Создайте HTML-форму для добавления рецепта.
2. Форма должна содержать следующие поля:
   - Название рецепта (`<input type="text">`);
   - Категория рецепта (`<select>`);
   - Ингредиенты (`<textarea>`);
   - Описание рецепта (`<textarea>`);
   - Тэги (выпадающий список с возможностью выбора нескольких значений, `<select multiple>`).
3. Добавьте поле для **шагов приготовления рецепта**. Реализуйте один из двух вариантов:
   - **Простой вариант**: `<textarea>`, где каждый шаг начинается с новой строки.
   - **Расширенный вариант (на более высокую оценку)**: динамическое добавление шагов с помощью JavaScript (кнопка "Добавить шаг"), где каждый шаг — отдельное поле ввода.
4. Добавьте кнопку **"Отправить"** для отправки формы.

### Задание 3. Обработка формы

1. Создайте в директории `handlers` файл, который будет обрабатывать данные формы.
2. **В обработчике реализуйте**:
   - Фильрацию данных;
   - Валидацию данных;
   - Сохранение данных в файл `storage/recipes.txt` в формате JSON.
3. Чтобы избежать дублирования кода и улучшить его читаемость, рекомендуется вынести повторяющиеся операции в отдельные вспомогательные функции, разместив их в файле `src/helpers.php`.
4. После успешного сохранения данных выполните перенаправление пользователя на главную страницу.
5. Если валидация не пройдена, отобразите соответствующие ошибки на странице добавления рецепта под соответствующими полями.

Для сохранения данных в файл можно использовать разные подходы. Один из вариантов — сохранять данные в текстовый файл, где каждая строка представляет собой отдельный JSON-объект:

```php
$formData = // данные формы;

// валидация данных

file_put_contents('<filename>', json_encode($formData) . PHP_EOL, FILE_APPEND);
```

### Задание 4. Отображение рецептов

1. В файле `public/index.php` отобразите 2 последних рецепта из `storage/recipes.txt`:

   ```php
   // Читаем данные из файла
   $recipes = file('<filename>', FILE_IGNORE_NEW_LINES);

   // Преобразуем строки JSON в массив
   $recipes = array_map('json_decode', $recipes);

   // Получаем два последних рецепта
   $latestRecipes = array_slice($recipes, -2);
   ```

2. В файле `public/recipe/index.php` отобразите все рецепты из файла `storage/recipes.txt`.

### Дополнительное задание

1. Реализуйте пагинацию (постраничный вывод) списка рецептов.
2. На странице `public/recipe/index.php` отображайте по 5 рецептов на страницу.
3. Для этого используйте GET-параметр page, например:
   - `/recipe/index.php?page=2` — отобразить 2 страницу рецептов.
   - `/recipe/index.php?page=3` — отобразить 3 страницу рецептов.
   - Если страница не указана, отобразите первую страницу.

## Выполнение

### Структура проекта

```sh
RECIPE-BOOK/
├── README.md
├── public/
│   ├── index.php
│   ├── handlers/
│   │   └── save_recipe.php
│   └── recipe/
│       ├── create.php
│       └── index.php
├── src/
│   └── helpers.php
└── storage/
    └── recipes.txt
```

## Описание файлов проекта

### 1. `public/index.php` – Главная страница

Выводит два последних добавленных рецепта. Используются функции `file()`, `array_map()`, `json_decode()` и `array_slice()`.

```php
$filepath = __DIR__ . '/../storage/recipes.txt';
$recipes = file_exists($filepath)
    ? array_reverse(array_map('json_decode', file($filepath)))
    : [];
$latest = array_slice($recipes, 0, 2);
```

```php
<?php foreach ($latest as $recipe): ?>
    <div>
        <h3><?= htmlspecialchars($recipe->title) ?></h3>
        <p><strong>Категория:</strong> <?= htmlspecialchars($recipe->category) ?></p>
        <p><strong>Описание:</strong> <?= nl2br(htmlspecialchars($recipe->description)) ?></p>
        <hr>
    </div>
<?php endforeach; ?>
```

**Краткое пояснение:**

- Читает все строки из `recipes.txt` и декодирует их из JSON в объекты.
- Сначала берутся последние записи (`array_reverse()`), затем 2 из них (`array_slice()`)
- Результат отображается на главной странице с защитой от XSS через `htmlspecialchars()`

---


### 2. `public/recipe/create.php` – Форма добавления рецепта

HTML-форма для создания нового рецепта. Данные формы отправляются методом POST в `save_recipe.php`.

```php
<form action="/handlers/save_recipe.php" method="post">
    <input type="text" name="title" required>
    <select name="category">
        <option value="Супы">Супы</option>
        <option value="Салаты">Салаты</option>
    </select>
    <textarea name="ingredients" required></textarea>
    <textarea name="description" required></textarea>
    <select name="tags[]" multiple>
        <option value="Быстрый">Быстрый</option>
        <option value="Веган">Веган</option>
    </select>
```

```js
function addStep() {
    document.getElementById('steps')
        .insertAdjacentHTML('beforeend', '<input type="text" name="steps[]" required><br>');
}
document.getElementById('add-step').onclick = addStep;
```

**Краткое пояснение:**

- При ошибках данные сохраняются в сессии (`$_SESSION['errors']`, `$_SESSION['old']`).
- Используется JavaScript для динамического добавления полей шагов.
- Все поля обязательны для заполнения и валидируются на сервере.

---

### 3. `public/recipe/index.php` – Все рецепты с пагинацией

Реализована постраничная навигация. На каждой странице отображается по 5 рецептов.

```php
$page = $_GET['page'] ?? 1;
$perPage = 5;
$offset = ($page - 1) * $perPage;
$currentRecipes = array_slice($recipes, $offset, $perPage);
```

**Краткое пояснение:**

- `$_GET['page']` получает номер текущей страницы.
- `array_slice()` выбирает часть рецептов на текущую страницу, согласно смещению.
- HTML-навигация позволяет перемещаться между страницами без перезагрузки данных.
- Каждому рецепту соответствует отдельный HTML-блок с тегами, шагами и метками времени.

---

### 4. `public/handlers/save_recipe.php` – Обработка формы

Обрабатывает отправку данных формы методом POST. Сохраняет корректные данные в `recipes.txt`, а при ошибках возвращает на форму.

```php
$formData = [
  'title' => $title,
  'category' => $category,
  'ingredients' => $ingredients,
  'description' => $description,
  'tags' => $tags,
  'steps' => $steps,
  'created_at' => date('Y-m-d H:i:s')
];
file_put_contents($filepath, json_encode($formData, JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND);
```

**Краткое пояснение:**

- Применяет функции фильтрации и валидации к полученным данным.
- В случае ошибок — сохраняет сообщения в `$_SESSION` и выполняет редирект.
- При успешной валидации — сериализует массив `formData` в JSON и добавляет в файл.
- После сохранения выполняется редирект на главную страницу.

---

### 5. `src/helpers.php` – Вспомогательные функции

Содержит две ключевые функции: `sanitize()` и `validate()`

```php
function sanitize(string $data): string {
  return htmlspecialchars(strip_tags(trim($data)));
}

function validate(array $data): array {
  $errors = [];
  if (empty($data['title'])) $errors['title'] = 'Введите название рецепта';
  if (empty($data['category'])) $errors['category'] = 'Выберите категорию';
  if (empty($data['steps']) || count(array_filter($data['steps'])) === 0) {
    $errors['steps'] = 'Добавьте хотя бы один шаг приготовления';
  }
  return $errors;
}
```

**Краткое пояснение:**

- `sanitize()` — очищает строки от пробелов, HTML и потенциально опасного ввода.
- `validate()` — проверяет, что все обязательные поля заполнены, особенно `steps`.
- Возвращает массив ошибок, который затем отображается пользователю.

---

### 6. `storage/recipes.txt` – Хранилище рецептов

Файл, в котором каждый рецепт сохраняется как JSON-строка. Используется файловая система вместо базы данных.

Пример строки:

```json
{"title":"Салат","category":"Салаты","ingredients":"Огурцы, помидоры","description":"Лёгкий","tags":["Быстрый"],"steps":["Порезать","Смешать"],"created_at":"2025-03-29 14:00:00"}
```

**Краткое пояснение:**

- Каждая строка — отдельный JSON-объект.
- Используется `file()` и `json_decode()` для чтения данных.
- Простое и надёжное решение для локального хранения рецептов.

---

## Как запустить проект

```bash
php -S localhost:8000 -t public
```

Перейти в браузере по адресу: [http://localhost:8000](http://localhost:8000)

---

## Результат

- **Основная страница**

![image](https://i.imgur.com/mnjG4om.png)

- **Страница добавления рецепта**

![image](https://i.imgur.com/1Rlf0t9.png)

- **Страница со всеми рецептами**
![image](https://i.imgur.com/wRBSPJU.png)

## Контрольные вопросы

**1. Какие методы HTTP применяются для отправки данных формы?**

- POST (в проекте используется для отправки формы)

**2. Что такое валидация и чем она отличается от фильтрации?**  

- Валидация — проверка корректности данных (непустые поля, правильные значения).  
- Фильтрация — очистка данных от HTML-тегов, пробелов, XSS-уязвимостей.

**3. Какие функции PHP используются для фильтрации данных?**  

- `trim()` — удаляет пробелы, `strip_tags()` — удаляет HTML, `htmlspecialchars()` — экранирует спецсимволы.

---

## Вывод

В ходе лабораторной работы №4 был реализован веб-проект «Каталог рецептов», позволяющий добавлять рецепты с ингредиентами, тегами и шагами приготовления. Освоены принципы обработки HTML-форм в PHP, включая фильтрацию, валидацию и защиту от XSS. Реализована динамическая форма с добавлением шагов, сохранение данных в JSON-файл и вывод рецептов с пагинацией. Работа закрепила ключевые навыки серверной разработки и структурирования проекта.

---

## Библиография

1. Официальная документация PHP: [https://www.php.net/manual/ru/](https://www.php.net/manual/ru/)
2. Работа с массивами в PHP: [https://www.php.net/manual/ru/language.types.array.php](https://www.php.net/manual/ru/language.types.array.php)
3. Работа с файлами в PHP: [https://www.php.net/manual/ru/function.file.php](https://www.php.net/manual/ru/function.file.php)
4. Работа с JSON в PHP: [https://www.php.net/manual/ru/function.json-decode.php](https://www.php.net/manual/ru/function.json-decode.php)
5. Работа с формами и сессиями: [https://www.php.net/manual/ru/reserved.variables.session.php](https://www.php.net/manual/ru/reserved.variables.session.php)
