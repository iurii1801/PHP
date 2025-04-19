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

> [!NOTE]
> **Примечание**: хотя phpMyAdmin по умолчанию работает с сервером MariaDB, полностью совместимым с MySQL, в рамках отчёта мы будем использовать термин **MySQL**, как указано в задании.

Создана база данных `recipe_book` со следующими таблицами:

## Ход работы

### SQL-структура базы данных

#### Таблица `categories`

```sql
CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

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

**Объяснение:**
Каждый рецепт состоит из шагов. Эта таблица хранит список шагов, связанных с рецептом. Для каждого шага указывается его номер и описание. Используется внешний ключ `recipe_id` для связи с рецептом.

#### Таблица `tags`

```sql
CREATE TABLE tags (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL UNIQUE
);
```

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

**Объяснение:**
Промежуточная таблица для связи рецептов с тегами. Поддерживает связь многие-ко-многим: один рецепт может иметь несколько тегов и наоборот. Используется составной первичный ключ `recipe_id, tag_id`.

