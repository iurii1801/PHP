# Лабораторная работа №4. Обработка и валидация форм

## Цель работы

Освоить основные принципы работы с HTML-формами в PHP, включая отправку данных на сервер и их обработку. Особое внимание уделяется фильтрации и валидации данных, а также сохранению данных в файловой системе. Лабораторная работа также направлена на закрепление знаний по структуре веб-проекта, маршрутизации, безопасному вводу и отображению данных.

---

## Структура проекта

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

---

## Описание файлов проекта

### 1. `public/index.php` – Главная страница

Выводит два последних добавленных рецепта. Используются функции `file()`, `array_map()`, `json_decode()` и `array_slice()`.

```php
$recipes = file_exists($filepath)
    ? array_reverse(array_map('json_decode', file($filepath)))
    : [];
$latest = array_slice($recipes, 0, 2);
```

**Краткое пояснение:**

- Читает все строки из `recipes.txt` и декодирует их из JSON в объекты.
- Сначала берутся последние записи (`array_reverse()`), затем 2 из них (`array_slice()`)
- Результат отображается на главной странице с защитой от XSS через `htmlspecialchars()`

---

### 2. `public/recipe/create.php` – Форма добавления рецепта

HTML-страница с формой. Поддерживает ввод:

- Названия рецепта
- Категории (select)
- Ингредиентов и описания (textarea)
- Тегов (select multiple)
- Шагов приготовления (с добавлением шагов через JavaScript)

```php
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);
```

**JavaScript:**

```js
function addStep() {
  document.getElementById('steps')
    .insertAdjacentHTML('beforeend', '<input type="text" name="steps[]" required><br>');
}
document.getElementById('add-step').onclick = addStep;
```

**Краткое пояснение:**

- В случае ошибок ввода данные сохраняются в `$_SESSION`, чтобы форма не очищалась.
- Поддержка динамического добавления полей позволяет вводить произвольное количество шагов рецепта.
- Страница валидирует обязательные поля и предоставляет удобный пользовательский интерфейс.

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

## Контрольные вопросы

**1. Какие методы HTTP применяются для отправки данных формы?**  
→ POST (в проекте используется для отправки формы)

**2. Что такое валидация и чем она отличается от фильтрации?**  
→ Валидация — проверка корректности данных (непустые поля, правильные значения).  
→ Фильтрация — очистка данных от HTML-тегов, пробелов, XSS-уязвимостей.

**3. Какие функции PHP используются для фильтрации данных?**  
→ `trim()` — удаляет пробелы, `strip_tags()` — удаляет HTML, `htmlspecialchars()` — экранирует спецсимволы.

---

## Библиография

1. Официальная документация PHP: [https://www.php.net/manual/ru/]
2. Работа с массивами в PHP: [https://www.php.net/manual/ru/language.types.array.php]
3. Работа с файлами в PHP: [https://www.php.net/manual/ru/function.file.php]
4. Работа с JSON в PHP: [https://www.php.net/manual/ru/function.json-decode.php]
5. Работа с формами и сессиями: [https://www.php.net/manual/ru/reserved.variables.session.php]
