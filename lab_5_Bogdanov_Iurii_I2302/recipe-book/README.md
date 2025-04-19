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
