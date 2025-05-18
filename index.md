
<?php

function handleForm(PDO $pdo): void {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo "Форма не отправлена";
        return;
    }

    $userName = trim($_POST['user_name'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $yearRaw = $_POST['year'] ?? '';

    if ($userName === '' || $title === '' || $author === '' || $yearRaw === '') {
        echo "Пожалуйста, заполните все поля";
        return;
    }
    if (!filter_var($yearRaw, FILTER_VALIDATE_INT)) {
        echo "Год издания должен быть целым числом";
        return;
    }

    $year = (int)$yearRaw;
    $currentYear = (int)date('Y');

    if ($year < 1) {
        echo "Год издания должен быть больше 0";
        return;
    }

    if ($year > $currentYear) {
        echo "Год издания не может быть больше текущего года";
        return;
    }

    // Добавление книги
    if (addBook($pdo, $userName, $title, $author, $year)) {
        echo "Книга успешно добавлена";
    } else {
        echo "Ошибка при добавлении книги";
    }
}

function addBook(PDO $pdo, string $userName, string $title, string $author, int $year): bool {
    $stmt = $pdo->prepare("INSERT INTO books (user_name, title, author, year) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$userName, $title, $author, $year]);
}

function getBooksByAuthor(PDO $pdo, string $author): array {
    $stmt = $pdo->prepare("SELECT * FROM books WHERE author = ?");
    $stmt->execute([$author]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
}

function deleteBook(PDO $pdo, int $bookId): bool {
    $stmt = $pdo->prepare("DELETE FROM books WHERE id = ?");
    return $stmt->execute([$bookId]);
}
?>
```



```
$sql= "CREATE TABLE IF NOT EXISTS comments (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    article_id INTEGER NOT NULL,
    user_name TEXT NOT NULL,
    content TEXT NOT NULL
)";
$pdo->execute($sql);

function handleForm(PDO $pdo): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo "Форма не отправлена";
        return;
    }

    // Получение данных
    $articleId = $_POST['article_id'] ?? '';
    $userName = $_POST['user_name'] ?? '';
    $content = $_POST['content'] ?? '';

    // Валидация
    if (empty($articleId) || empty($userName) || empty($content)) {
        echo "Пожалуйста, заполните все поля";
        return;
    }

    if (!filter_var($articleId, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])) {
        echo "Номер статьи должен быть положительным целым числом";
        return;
    }

    if (mb_strlen($content) < 5) {
        echo "Комментарий должен содержать не менее 5 символов";
        return;
    }

    // Добавление комментария
    $success = addComment($pdo, (int)$articleId, $userName, $content);
    if ($success) {
        echo "Комментарий успешно добавлен";
    } else {
        echo "Ошибка при добавлении комментария";
    }
}


function addComment(PDO $pdo, int $articleId, string $userName, string $content): bool
{
    $sql = "INSERT INTO comments (article_id, user_name, content) VALUES (:article_id, :user_name, :content)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        ':article_id' => $articleId,
        ':user_name' => $userName,
        ':content' => $content
    ]);
}


function getCommentsByArticleId(PDO $pdo, string $articleId): array
{
    $sql = "SELECT * FROM comments WHERE article_id = :article_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':article_id' => $articleId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function deleteComment(PDO $pdo, int $commentId): bool
{
    $sql = "DELETE FROM comments WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([':id' => $commentId]);
// нужно добавить еще одну строку не помню какую штота с return тоже обработка ошибки
}
```

Что делает веб-браузер? 

Позволяет открывать и взаимодействовать с веб-страницами   
Какой протокол является основным для передачи данных в вебе? 

HTTP  
Какая технология является одной из наиболее популярных для разработки серверной логики веб-приложений? 

PHP   
Какое устройство или программа выполняет запрос к серверу и получает ответ? 

Клиент   
Какая из следующих задач НЕ относится к обязанностям бэкенд-разработчика? 

Создание пользовательского интерфейса   
Что такое бэкенд в веб-разработке? 

Серверная часть веб-приложения   
Как называется сообщение, отправляемое клиентом серверу для получения данных или выполнения действия? 

Запрос   
Какие технологии чаще всего используются во фронтенде? 

HTML, CSS, JavaScript   
В каком случае сервер может выступать в роли клиента? 

Когда сервер запрашивает данные у другого сервера   
Чем отличается веб-приложение от веб-сайта? 

Веб-приложение предоставляет интерактивные функции, а веб-сайт -статические страницы   
Какой из следующих примеров работы бэкенда является корректным? 

Обработка запроса для сохранения данных пользователя в базу данных   
Что представляет собой веб-разработка? 

Процесс создания веб-сайтов и веб-приложений   
Какие технологии используются для создания статических веб-страниц? 

HTML, CSS, JavaScript  
Какой НТТР-код указывает на успешную обработку запроса? 

200   
Какой из перечисленных элементов является примером сервера? 

Веб-сервер   
Какая задача выполняется веб-сервером? 

Он принимает запросы клиентов, обрабатывает их и отправляет ответ   
Какую роль играет гиперссылка на веб-странице? 

Обеспечивает переход на другие страницы или разделы сайта   
Что выполняют клиентские скрипты? 

Запускаются на клиентском устройстве и создают интерактивные элементы   
Какой из следующих пунктов описывает фронтенд? 

Создает и отображает пользовательский интерфейс   
Что произойдёт, если клиент отправит запрос на несуществующую страницу? 

Сервер отправит код состояния 404 Not Found 
Как называется стандартный главный файл проекта, который открывается по умолчанию при обращении к сайту? 

index.php   
Какой оператор используется для вывода данных в PHP? 

echo   
Какое из следующих имен переменных не является корректным? 

$1name   
Какие переменные в PHP чувствительны к регистру? 

Все переменные  
Как расшифровывается PHP? 

Hypertext PreProcessor   
Какой символ является талисманом РНР? 

Слон   
Какой тег используется для начала РНР-кода? 

<?php   
Как обозначаются переменные в РНР? 

Начинаются с $   
Какой из следующих примеров соответствует рекомендациям по разделению РНР и НTML? 

<?php if ($loggedIn): ?> 

<р>Добро пожаловать, <?= $userName ?>!</p> 

<?php else: ?> 

<р>Пожалуйста, войдите в систему.</p> 

<?php endif; ?>   
Что из перечисленного не является особенностью РНР? 

Компиляция в машинный код перед выполнением   
Какие из следующих вариантов являются правильными способами создания константы в РНР? 

define("PI", 3.14); 

const PI = 3.14   
Какой из следующих вариантов корректно выведет "I like bananas!"? 

echo "I like bananas!";   
В каком случае закрывающий тег ?> не требуется? 

Когда файл состоит только из РНР-кода или заканчивается РНР-кодом   
Что делает PSR-12? 

Определяет соглашения по стилю кода   
Какой оператор используется для объединения строк в РНР? 

.   
Можно ли объявить переменную в РНР без присвоения ей значения? 

Нет, это вызовет ошибку   
Почему рекомендуется использовать отступы и пробелы в коде? 

Упрощает чтение кода   
Какой язык разметки обычно используется в сочетании с РНР для создания веб-страниц? 

HTML   
Какой оператор в РНР работает быстрее для вывода данных? 

echo   
Чем отличается print от echo? 

print возвращает 1, а echo не имеет возвращаемого значения   
Как проверить успешную установку РНР? 

Открыть командную строку и выполнить php -v   
Какой код корректно объединит переменную $name со строкой "Hello, "? 

echo "Hello, {$name}"; 

echo "Hello, " . $name;   
Какая функция позволяет проверить, определена ли переменная? 

isset()   
Какое расширение должны иметь файлы с РНР-кодом? 

.php   
Что означает синтаксис int|float в объявлении аргументов функции? 

Переменная может быть либо целым числом, либо числом с плавающей точкой   
Какая функция используется для явного преобразования строки в целое число? 

intval()   
Что делает функция var_dump()? 

Выводит тип и значение переменной, включая длину строки   
Какой тип данных используется для представления целых чисел в PHP? 

int   
Какие из следующих утверждений об операторах в PHP верны? 

Оператор === сравнивает значения и типы 

Оператор . используется для объединения строк 

Унарные операторы работают с одним операндом   

  

Какой из следующих операторов является тернарным? 

? :   
Какая из следующих переменных содержит значение типа float? 

$b = 3.14;   
Что означает оператор . в PHP? 

Конкатенация   
Какие из перечисленных типов относятся к скалярным в PHP? 

bool   
Какой оператор используется для объединения строк в PHP? 

.   
Какое преимущество НЕ относится к явному указанию типов в PHP? 

Ускорение работы интерпретатора   
В каком случае можно указывать тип переменной в PHP? 

В аргументах функции, возвращаемом значении и свойствах класса  
Какой тип данных в PHP может содержать значения разных типов? 

mixed   
Какой оператор используется для подавления ошибок в PHP? 

@  
Какие из следующих утверждений верны относительно преобразования типов в PHP? 

Неявное преобразование происходит автоматически при выполнении операций 

intval($v) и (int) $v можно использовать для приведения к целому числу 

(float) $v и floatval($v) всегда дают одинаковый результат   
Какой из следующих операторов является тернарным? 

? :   
Какой оператор в PHP можно использовать для безопасного получения значения массива, если ключ может отсутствовать? 

?? (null coalescing) 
Какой тип ключей используется в индексированном многомерном массиве? 
Числовые 
Что такое многомерный массив в PHP? 
Массив, содержащий другие массивы в качестве элементов 
Какая функция сортирует ассоциативный массив по значениям, сохраняя ключи? 
asort() 
Какой тип массива чаще всего используют для представления сущностей (объектов) в PHP? 
Ассоциативный массив 
Какие типы данных можно хранить в одном массиве в PHP? 
Разные типы данных, включая вложенные массивы 
Какая функция используется для сортировки индексированного массива по возрастанию? 
sort()  
Какой функцией можно добавить элементы в начало массива? 
array_unshift()  
Какую функцию используют, чтобы найти ключ элемента массива по значению? 
array_search() 
Какую функцию можно использовать для разбиения строки на массив? 
explode()  
Какой из вариантов правильно передаёт массив в функцию с помощью Spread-оператора? 
sum(...$arr) 
Что произойдет при обращении к несуществующему ключу массива в PHP? 
Выведется null и будет сгенерировано предупреждение    
Чем Spread-оператор отличается от array_merge() при объединении массивов? 
Spread-оператор быстрее и лаконичнее    
Что делает функция array_push($array, 'A', 'B')? 
Добавляет 'A' и 'B' в конец массива $array    
Какой цикл предпочтительнее использовать для перебора ассоциативных массивов в PHP? 
foreach 
Какая функция позволяет получить все значения определённого ключа из многомерного массива? 
array_column() 
Какой PHP-код используется для вывода всех элементов многомерного массива? 
foreach($array as $item) { 
	 foreach($item as $value) {  
		echo $value; 
	 } 
}  
Какой способ создания индексированного массива НЕ является корректным в PHP? 
$arr = new Array(1, 2, 3);  
Какая функция в PHP используется для проверки, является ли переменная массивом? 
is_array()  
Что делает Spread-оператор (...) в PHP? 
Распаковывает массив в отдельные значения  
Какой ключ будет присвоен новому элементу при добавлении в индексированный массив с помощью $array[] = 'new value';? 
Следующий доступный числовой индекс  
Какой тип данных представляет собой массив в PHP? 
Структура данных "ключ-значение"  
Какая из следующих конструкций верно объявляет функцию в PHP?  

function sum($a, $b): int { return $a + $b; }  
Когда стоит разбивать код на функции?  

Когда код используется несколько раз, сложен или должен быть понятным. 
Как вызвать функцию в PHP?  

Написать имя функции и передать аргументы в () В 
Какая функция позволяет удалить пробелы из начала и конца строки? 

rim()  
Что делает функция rtrim()? 

Удаляет пробелы с конца строки 
Каковы основные характеристики функций? 

Принимают неограниченное количество аргументов и возвращают одно значение.  
Как обозначается передача аргумента по ссылке? 

Используется символ &  
Что такое анонимная функция в PHP?  

Функция, не имеющая имени и которая может быть присвоена переменной.  
Какая функция возвращает подстроку заданной длины из исходной строки? 

substr()  
Какая функция в PHP используется для замены одной подстроки на другую в строке? 

str_replace() 
Что делает директива declare(strict_types=1); в PHP?  

Включает строгую проверку типов аргументов и возвращаемых значений  
Что такое функция в программировании?  

Блок кода, выполняющий определённую задачу. 
Какая функция преобразует строку в нижний регистр?  

strtolower()  
Что вернёт выражение `strpos("Hello World", "World")`? 

6 
Где должны находиться аргументы со значением по умолчанию в списке параметров? 

После аргументов без значений по умолчанию  
Какой тип данных можно использовать в качестве аргумента функции в PHP?  

Любые типы данных  
Какая функция преобразует все символы строки в верхний регистр?  

strtoupper()  
Какая функция позволяет удалить пробелы из начала и конца строки? 
trim()  
Какой результат выведет следующий код? 

<?php 
function checkType(int $x) { 

    return "Received: " . $x; 

} 

echo checkType("5"); 

Received: 5 
Какой тип ключей поддерживают индексированные массивы в PHP? 

Только целые числа 
Как передать функции переменное количество аргументов?  

Использовать ... перед именем аргумента: function sum(... numbers) { ... }  
Какое правило наименования функций в PHP является верным?  

Функция должна начинаться с буквы или _  
Какое преимущество даёт строгая типизация в PHP?  

Упрощает отладку и предотвращает ошибки, связанные с неверными типами данных.  
Что вернёт функция strtolower("PHP Developer")?  

php developer  
Вставьте недостающую строку, чтобы функция getGreeting() возвращала "Good Morning" по умолчанию, но позволяла изменять приветствие. 

<?php 

function getGreeting(string $greeting = "Good Morning"): string { 

return $greeting; 

} 
Какую функцию в PHP нужно использовать, чтобы получить длину строки?  

strlen()  
  

Что выведет следующий код?  

<?php echo strpos("I love PHP", "love") 

2  
Какой результат выведет следующий код?  

<?php  

    $message = "Hello";  

    $greet = function ($name) use ($message) {  

        $message = $message ?? $name ?? "unknown";  

        return "$message, $name!";  

    };  

    echo $greet("Anna"); 

"Hello, Anna!"  
Какой результат выведет следующий код?  

<?php  

    declare(strict_types=1);  

    function sum(float $a, float $b): float {  

        return $a + $b;  

    }  

echo sum(3, 4); 

7 
Какой результат выведет следующий код? 

<?php  

    function modify(&$value) {  

        $value = "Changed";  

    }  

$text = "Original";  

modify($text);  

echo $text; 

Changed 
Какой результат выведет следующий код?  

<?php  

    function factorial($n) {  

        if ($n <= 1) {  

            return 1; 

        } return $n * factorial($n - 1);  

    }  

echo factorial(4); 

24  
Что выведет следующий код?  

<?php  

    echo substr("Hello World", 0, 5); 

"Hello"  Какой результат выведет следующий код?  

<?php  

    declare(strict_types=1);  

    function greet(string $name): string {  

        return "Hello, " . $name;  

    }  

echo greet(123); 

Ошибка типа (TypeError)  
Какой результат выведет следующий код?  

<?php  

    $sum = function($a, $b) {  

        return $a + $b;  

    };  

echo $sum(4, 6);  

10  
Какой результат выведет следующий код без строгой типизации?  

<?php  

    function add(int $a, int $b): int {  

        return $a + $b;  

    }  

echo add(4.9, 5.9); 

10 
Какой результат выведет следующий код?  

<?php  

    function example() {  

        echo "Hello ";  

        return "World";  

    }  

echo example(); 

Hello World  
Какой результат выведет следующий код?  

<?php  

    function add(&$x) {  

        $x += 2;  

    }  

$y = 10;  

add($y); echo $y; 

12  
Какой результат выведет следующий код?  

<?php  

    function test(&$var) {  

        $var += 10;  

    }  

$num = 5;  

test($num);  

echo $num;   

15 
Какой результат выведет следующий код? 

$person = [ 

    'name' => 'John', 

    'age' => 30, 

    'city' => 'New York' 

]; 
echo array_search('New York', $person); 

Ответ: city. 
Какой результат выведет следующий код? php  

<?php  

    function multiply($a, $b = 2) {  

        return $a * $b;  

    }  

echo multiply(5); 

10  
Какой результат выведет следующий код?  

<?php  

    function sum(...$numbers) {  

        return array_sum($numbers);  

    }  

echo sum(1, 2, 3, 4); 

10  
Какой результат выведет следующий код? 

<?php  

    function myFunction() {  

        return "Hello";  

    }  

$func = "myFunction";  

echo $func();   

Hello 
Какой результат выведет следующий код? 
$array = ['apple', 'banana', 'cherry']; 
echo implode(', ', $array); 

apple, banana, cherry 
Какой результат будет у следующего кода? 
<?php 
$arr = [ 
    "10" => "ten", 
    10.5 => "ten point five", 
    true => "boolean" 
]; 
print_r($arr); 
Array ( [10] => "ten point five" [1] => "boolean" ) 
Какой результат выведет следующий код? 
$arr = [1, 2, 3]; 
$arr["key"] = "value"; 
echo count($arr); 
?> 
Ответ: 4 
Какой результат вернёт: 

count(['apple', 'banana', 'orange']) 

3	 
 

Какой результат будет у следующего кода? 

<?php 

$arr = ["a", "b", "c"]; 

unset($arr[1]); 

print_r($arr); 

Array ( [0] => "a", [2] => "c" ) 
Какой результат выведет следующий код? 

$array = [1, 2, 3]; 

array_push($array, 4, 5); 

print_r($array); 

[1, 2, 3, 4, 5] 
 

Какой результат выведет следующий код? 
$persons = [ 
    ['name' => 'Bob', 'age' => 28], 
    ['name' => 'Alice', 'age' => 25], 
    ['name' => 'John', 'age' => 30] 
]; 
echo count($persons); 
3 
Какой тип данных вернёт функция gettype() для переменной $var = "42";? 

<?php 

$var = "42"; 

echo gettype($var); 

string   
Какой результат будет выведен на экран? 

<?php 

$value = (float) "5.5abc"; 

echo $value; 

5.5   
Что выведет данный код? 

<?php 

$x = 0.1 + 0.2; 

var_dump($x == 0.3); 

bool(false)   
Какой результат будет у следующего фрагмента кода? 

<?php 

$result = (string) 123 + "7"; 

echo $result; 

130   
Какой результат будет у следующего выражения? 

<?php 

$value = (bool) 0; 

var_dump($value); 

bool(false)   
Что будет результатом следующего кода? 

<?php 

$i = 0; 

while ($i < 3) { 

 echo $i; 

 $i++; 

 if ($i == 2) { 

 break; 

 } 

} 

01   
Что выведет следующий код? 

<?php 

$x = 10; 

$x .= " apples"; 

echo $x; 

10 apples   
Какой результат будет выведен при выполнении следующего кода? 

<?php 

$score = 75; 

if ($score >= 90) { 

 echo "Отлично"; 

} elseif ($score >= 75) { 

 echo "Хорошо"; 

} else { 

 echo "Удовлетворительно"; 

} 

Хорошо   
Что выведет данный код? 

<?php 

var_dump((bool) "0"); 

bool(false)   
Какой результат выведет следующий код? 

<?php 

function checkType(mixed $value): string { 

 return gettype($value); 

} 

echo checkType(42.5); 

float   
Какой результат будет выведен? 

<?php 

for ($i = 0; $i < 4; $i++) { 

 if ($i === 2) { 

 continue; 

 } 

 echo $i; 

} 

013  
Какой результат будет выведен на экран? 

<?php 

$x = 3; 

$y = 5; 

$result = $x > $y ? $x : $y; 

echo $result; 

5   
Какой результат выведет следующий код? 

<?php 

$a = 5; 

$b = "5"; 

var_dump($a == $b); 

var_dump($a === $b); 

bool(true) 

bool(false) 
Что вернёт следующий код? 

<?php 

$var = null; 

echo isset($var) ? 'true' : 'false'; 

false   
Какой результат будет выведен на экран? 

<?php 

$number = 5; 

if ($number < 10) { 

 echo "Меньше 10"; 

} elseif ($number > 10) { 

 echo "Больше 10"; 

} else { 

 echo "Равно 10"; 

} 

Меньше 10   
Какой вывод будет у следующего скрипта? 

<?php 

$a = 10; 

$b = 2; 

$result = $a % $b; 

echo $result; 

0   

Какой будет результат выполнения следующего кода? 

<?php 

echo 2 ** 3; 

8   
Какой результат будет у следующего кода? 

<?php 

$name = "Alice"; 

echo "Hello, {$name)"; 

Hello, Alice 
Какой результат выведет следующий код? 

<?php 

define("MESSAGE", "Hello, world!"); 

echo MESSAGE; 

Hello, world! 
Какой результат выведет следующий код? 

<?php 

echo "Hello, "; 

echo "world!"; 

Hello, world!   
Какой результат выведет следующий код? 

<?php 

$name = "Alice"; 

function greet() { 

global $name; 

echo "Hello, " $name; 

} 

greet(); 

Hello, Alice   
Какой результат будет получен в браузере при выполнении следующего кода? 

<?php 

$x = "10"; 

$y = 10; 

if ($x === $y) { 

echo "Identical"; 

} else { 

echo "Not identical' 

} 

Not identical   
Какой результат будет получен при выполнении этого кода? 

<?php 

echo "5" + 3; 

8   
Что будет выведено в браузере при выполнении следующего кода? 

<?php 

$a = 5; 

$b = "5"; 

if ($a == $b) { 

echo "Equal"; 

} else { 

echo "Not equal"; 

} 

Equal   
Какой результат выведет следующий код? 

<?php 

if (true) { 

$message = "Hello, block!"; 

} 

echo $message; 

Hello, block!   
Какой результат выведет следующий код? 

<?php 

$age = 25; 

$age = "25 years"; 

echo $age; 

"25 years"   
Какой результат выведет следующий код? 
$array = ['apple', 'banana', 'cherry']; 
echo count($array); 

3  
Какой вывод будет у следующего кода? 
<?php 
$arr = [1, 2, "3", true, null]; 
echo count($arr); 
5  
Какое значение вернёт следующий код? 
array_search(30, ['name' => 'John', 'age' => 30, 'city' => 'New York']) 
Ответ: 'age'  
Какой результат вернёт 
in_array('apple', ['banana', 'apple', 'grape'])? 
true 
Что выведет следующий код? 
<?php 
$arr = [ 
 "fruit" => "apple", 
 "color" => "red" 
]; 
echo $arr["vegetable"] ?? "Not found"; 
Not Found  
Какой результат выведет следующий код? 
<?php 
$arr = [ 
 "name" => "Alice", 
 "age" => 25, 
 10 => "ten", 
 true => "boolean key" 
]; 
echo $arr[true]; 
boolean key  
Какой результат выведет следующий код? 
$array = ['apple', 'banana', 'orange']; 
$removed = array_pop($array); 
echo $removed; 
Ответ: orange 
Какой результат выведет следующий код? 
$students = [ 
 ['name' => 'Emma', 'age' => 22], 
 ['name' => 'Noah', 'age' => 24] 
]; 
$students[] = ['name' => 'Sophia', 'age' => 23]; 
echo $students[2]['name']; 
Sophia 

Какая функция позволяет добавить элементы в начало массива? 

Ответ: array_unshift(). 
Какой результат будет после выполнения следующего кода? 

$arr1 = [1, 2]; 

$arr2 = [3, 4]; 

$merged = [...$arr1, ...$arr2]; 

print_r($merged); 

[1, 2, 3, 4] 
 

Что будет выведено в консоль? 

$arr = []; 

$arr[false] = "No"; 

$arr[0] = "Zero"; 

echo $arr[false]; 

Zero. 
 
 
 
 
 
 

Допишите код для вывода строки “Hello, World” 

<?php 
echo "Hello, World"; 
 

Исправьте ошибку: переменная $name объявлена неправильно. 

<?php 

$name = "MyName"; 
 

Дополните код, чтобы он корректно использовал статическую переменную для подсчёта вызовов функции. 

<?php 

function callCounter() { 

    static $count = 0; 

    $count++; 

    echo $count . "\n"; 

} 
 

Дополните код: объявите переменную $пате и присвойте ей значение "Alice", затем используйте ее в строке "Hello, Alice!". 

<?php 

$name = "Alice"; 

echo "Hello, {$name}"; 
 

Создайте константу РІ со значением 3.14 с использованием define(), а затем создайте другую константу Е со значением 2.71, используя const. 

<?php 

define("PI", 3.14); 

const E = 2.71; 
 

Дополните код, чтобы он корректно использовал переменную $message в блоке if. 

<?php 

if (true) { 

    $message = "PHP is awesome!"; 

} 
 

Объявите переменную $name и присвойте ей строку "John". Затем создайте еще одну переменную $greeting, которая содержит строку "Hello, John!", используя $name. 

<?php 

$name = "John"; 

$greeting = "Hello, {$name}!"; 
 

Дополните код так, чтобы переменная $dbName использовалась как константа. 

<?php 
const DB_NAME = "my_database"; 
 

Дополните код: Используйте echo для вывода строки "I love php!" 

<?php 

echo "I love php!"; 
 

Найдите и исправьте ошибку в следующем коде: 

<?php 

function sum(int $a, int $b): int { 

 return $a + $b; 

} 
 

Используя альтернативный синтаксис, напишите цикл for, который выведет числа от 1 до 3. 

<?php for ($i = 1; $i <= 3; $i++): ?> 

 <p><?= $i ?></p> 

<?php endfor; ?> 
 

Найдите и исправьте ошибку в следующем коде 

<?php 

function toFloat(mixed $value): float { 

 return (float) $value; 

} 
 

Дана переменная $number, содержащая целое число. Используя тернарный оператор, запишите в переменную $result строку. 

"Even" — если число чётное 

"Odd" — если число нечётное 

Переменную $number объявлять не надо. 

<?php 

$result = $number % 2 == 0? "Even" : "Odd"; 

echo $result;   
 

Допишите отсутствующую часть, чтобы программа вывела: 

"Переменные равны" если значения и типы совпадают, иначе "Переменные не равны" 

<?php 

$a = 10; 

$b = "10"; 

if ($a === $b) { 

 echo "Переменные равны"; 

} else { 

 echo "Переменные не равны"; 

} 
 

Заполните пропуски, чтобы программа корректно считала количество элементов в многомерном массиве. 
<?php 
$nestedArray = [ 
    'fruits' => ['apple', 'banana'], 
    'vegetables' => ['carrot', 'broccoli'] 
]; 
$count = count($nestedArray, COUNT_RECURSIVE); 
 

Даны 3 массива $arr1, $arr2, $arr3. 
Напишите код, который объединяет три массива с использованием Spread-оператора. 
<?php 
$merged = [...$arr1, ...$arr2, ...$arr3]; 
 

Создайте ассоциативный массив $book со следующими данными: 
"title" → "1984" 
"author"→ "George Orwell" 
"year" → 1949 
<?php 
$book = array ( 
 "title" => "1984", 
 "author" => "George Orwell", 
 "year" => 1949 
); 
 

Создайте ассоциативный массив $user со следующими данными: 
"username" → "john_doe" 
"email" → "john@example.com" 
"profile" (вложенный массив), содержащий: 
"name" → "John Doe" 
"age" → 30 
"location" → "New York" 
 
<?php 
$user = [ 
 "username" => "john_doe", 
 "email" => "john@example.com", 
 "profile" => [ 
 	"name" => "John Doe", 
 	"age" => 30, 
 	"location" => "New York" 
 ], 
]; 
 

Заполните пропуски в коде, чтобы программа корректно считала количество элементов в многомерном массиве. 

$nestedArray = [ 

    'fruits' => ['apple', 'banana'], 

    'vegetables' => ['carrot', 'broccoli'] 

]; 

$count = count($nestedArray, COUNT_RECURSIVE); 
 

Создайте ассоциативный массив $user со следующими данными: 

name → "Alice" age → 28 city → "Paris" 

<?php  

$user = [ 

    'name' => 'Alice', 

    'age' => 28, 

    'city' => 'Paris' 

]; 
 

Дан массив $numbers. Заполните его числами от 10 до 50 с шагом 10. 
Массив $numbers объявлять не нужно. 
<?php 
for ($i = 10; $i <= 50; $i += 10) { 
 $numbers[] = $i; 
}  
 

Дополните массив $person, добавив в него ключи : 
"job" (значение "Developer") 
"country" (значение "Germany") 
<?php 
$person = [ 
 "name" => "Michael", 
 "age" => 35, 
]; 
$person["job"] = "Developer"; 
$person["country"] = "Germany";  
 

Создайте многомерный массив $students, где каждый элемент — это массив с данными студента: 
Имя (name) string 
Возраст (age) int 
Добавьте в массив следующих студентов: 
John, 22 
Emma, 20 
David, 23 
<?php 
$students = array( 
 array('name' => 'John', 'age' => 22), 
 array('name' => 'Emma', 'age' => 20), 
 array('name' => 'David', 'age' => 23) 
);  
 

Дополните код, чтобы он корректно создавал индексированный массив и выводил первый элемент. 
<?php 
$fruits = []; 
$fruits[] = "Apple"; 
$fruits[] = "Banana"; 
$fruits[] = "Cherry";  
В следующем коде есть ошибка, исправьте её так, чтобы массив заполнялся корректно. 
<?php 
$values = []; 
$values[] = 10; 
$values[] = 20; 
$values[] = 30; 
 

Вставьте недостающие части кода, чтобы функция greet() возвращала строку "Hello, User!" по умолчанию, но позволяла передавать другое имя. 

<?php  

    function greet($name = "User") {  

        return "Hello, " . $name . "!";  

    } 
 

Вставьте недостающие слова, чтобы функция возвращала первые 3 символа переданной строки. 

<?php  

    function firstThreeChars($string) {  

        return substr($string, 0, 3);  

    } 
 

Исправьте ошибку в коде, чтобы он корректно выводил мог принимать числа с плавающей точкой.  

<?php  

    function sum(float $a, float $b) {  

        return $a + $b;  

} 
 

Исправьте ошибку в коде, чтобы функция multiplyByTwo() изменила значение переменной $num передаваемой по ссылке.  

<?php  

    function multiplyByTwo(&$num) {  

        $num *= 2;  

} 
 

Дополните код, вставив пропущенное слово, чтобы анонимная функция использовала переменную из внешней области видимости. 

<?php  

    $sayGreeting = function ($name) use ($greeting) {  

        return "$greeting, $name!"; 

    }; 
 

Напишите код, который объявляет функцию isEven(int $num): bool, возвращающую true, если число чётное, и false, если нечётное.  

<?php  

    function isEven(int $num): bool {  

        return $num % 2 === 0;  

    } 
 

Дополните код так, чтобы он возвращал длину строки без пробелов в начале и конце. <?php  

    function trimmedLength($str) {  

        return strlen(trim($str));  

 } 
 

Напишите функцию toUpper(), принимающую строку и возвращающую её полностью в верхнем регистре.  

<?php  

    function toUpper($str) {  

        return strtoupper($str);  

    } 
 

Исправьте ошибку в коде, чтобы функция divide() корректно обрабатывала деление на 0, возвращая строку "error: division by zero".  

<?php  

    function divide($a, $b) {  

        if ($b === 0) {  

            return "error: division by zero";  

        } return $a / $b;  

    } 
 

Дополните код, указав верный тип возвращаемого значения функции. 

<?php 

function sum(int $a, int $b): int { 

    return $a + $b; 

} 
 

Дополните код, вставив недостающие элементы, чтобы включить строгую типизацию и указать, что функция должна возвращать строку. 

<?php  

    declare(strict_types=1);  

    function sayHello(string $name): string {  

        return "Hello, $name!";  

    } 
 

Напишите код, который преобразует массив $array в строку с разделителем $del и записывает результат в переменную $result 

<?php 

$result = implode($del, $array); 
 

Допишите отсутствующий блок в конструкции switch, чтобы программа выводила "Число пять" при вводе 5, и "Неизвестное число" для остальных значений. 

<?php 

switch ($number) { 

    case 5: 

        echo "Число пять"; 

        break; 

    default: 

        echo "Неизвестное число"; 

} 
 

Найдите и исправьте ошибку в следующем коде 

<?php 

function toBoolean(string $value): bool { 

    return boolval($value); 

} 
 

Дополните код так, чтобы функция calculateArea() возвращала площадь прямоугольника. 

Если хотя бы одна из сторон не является положительным числом (ноль также запрещён), функция должна вернуть false. 

<?php 

function calculateArea(float $length, float $width) { 

    if ($length <= 0 || $width <= 0) { 

        return false;  

    } 

    return $length * $width;  

} 
 

Что делает оператор return в функции PHP? 

Завершает выполнение функции и возвращает указанное значение. 
 

Что выведет следующий код? 

<?php 

$greet = function() { 

    return "Hello!"; 

}; 

echo $greet(); 

Hello! 
 

Какой результат выведет следующий код? 

<?php 

function demo($a, $b = 2, $c = 3) { 

    return $a + $b + $c; 

} 

echo demo(1, 4); 

8 
 

Какие элементы входят в объявление функции в PHP 

Имя функции, аргументы, тело функции, возвращаемое значение 
 

Как объявить функцию в PHP? 

С помощью ключевого слова function 
 

Объявите три переменные 

$name, содержащую строку "Alice". 

$age, содержащую число 30. 

$isStudent, содержащую true (логический тип). 

<?php 

$name = "Alice"; 

$age = 30; 

$isStudent = true; 
 

Какой результат будет у следующего выражения? 

<?php 
echo 15 % 4; 

3 
 

Напишите функцию calculatePower(), которая принимает два аргумента — число и степень — и возвращает результат возведения числа в указанную степень. 

<?php 

function calculatePower(int|float $base, int $power): float {  

return $base ** $power; 

} 
 

Напишите функцию convertToInt(), которая принимает строку и возвращает её целочисленное значение, используя явное преобразование типа. 

<?php 

function convertToInt(string $value): int { 

    return (int)$value; 

} 
 

Какой синтаксис используется для создания ассоциативного массива в PHP? 

$arr = ['key' => 'value']; 
 

Какой результат выведет следующий код? 

$employees = [ 
    ['name' => 'John', 'position' => 'Developer'], 
    ['name' => 'Alice', 'position' => 'Designer'] 
]; 
echo $employees[1]['name']; 

Alice 
 

Напишите код, который сортирует массив по убыванию и сохраняет связь ключей с их значениями. 

$grades = [ 
    'Alice' => 85, 
    'Bob' => 92, 
    'Charlie' => 78 
]; 

<?php 

arsort($grades); 
 

Какой результат выведет следующий код? 

$array = ['a', 'b', 'c']; 
array_unshift($array, 'x', 'y'); 
print_r($array); 

['x', 'y', 'a', 'b', 'c'] 
 

Напишите код, который в переменную $count записывает количество элементов в массиве $array. 

<?php 

$count = count($array); 
 

Что выведет следующий код? 

<?php 
$arr = [1 => "A", 2 => "B", 3 => "C"]; 
$arr[] = "D"; 
print_r($arr); 

[1 => "A", 2 => "B", 3 => "C", 4 => "D"] 
 

Заполните пропуски в коде, чтобы он корректно перебирал массив $users (индексированный массив, содержащий имена пользователей) и выводил имя каждого пользователя. 

<?php 

foreach ($users as $user) { 

    echo $user . "\n"; 

} 
 

Какой стиль наименования функций рекомендуется в PHP? 

camelCase 
 

Какой результат выведет следующий код? 

<?php  

function outer() {  

function inner() {  

return "Hello from inner!";  

}  

} 

outer();  

echo inner(); 

Hello from inner! 
 

Дополните код, чтобы убрать пробелы только с начала строки. 

<?php 

$trimmedText = ltrim($text); 
 

Напишите функцию reverseString($string), которая принимает строку и возвращает её в обратном порядке. 

<?php 

function reverseString(string $string): string { 

    return strrev($string); 

} 
 

Исправьте код так, чтобы функция корректно определяла, начинается ли строка с заданной подстроки. 

<?php 
function startsWith($string, $startString) { 
    return strpos($string, $startString) === 0; 
} 

ничего не справляется с этим заданием. можно попробовать на  свой страх и риск: 

function startsWith($string, $startString) { 

    return strpos($startString , $string) === 0; 

} 
 

Какой суперглобальный массив используется для получения данных из формы, отправленной методом POST? 

$_POST 
 

Какой метод позволяет группировать несколько значений в массив в данных формы? 

Квадратные скобки [] в имени поля 
 

Какой метод отправки данных является более безопасным для передачи конфиденциальной информации? 

POST 
 

Что означает "фильтрация данных формы"? 

Удаление или преобразование нежелательных символов 
 

Что делает функция filter_var($email, FILTER_VALIDATE_EMAIL)? 

Проверяет, корректен ли email 
 

Что будет результатом выполнения следующего кода? 

$errors = [ 
    'email' => ['Неверный email.'] 
]; 
if (!empty($errors['email'])) { 
    echo $errors['email'][0]; 
} 

Неверный email. 
 

Что выведет следующий код PHP при отправке формы методом POST с полями: 

name: Alex 

email: alex@gmail.com 

message: Привет! 

if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
    $name = $_POST['name'] ?? 'Нет имени'; 
    $email = $_POST['email'] ?? 'Нет email'; 
    echo "Имя: $name, Email: $email"; 
} 

Имя: Alex, Email: alex@gmail.com 
 

Что произойдет при следующем вызове кода, если метод отправки формы — GET? 

if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
    echo "Форма отправлена методом POST"; 
} else { 
    echo "Форма не отправлена методом POST"; 
} 
Форма не отправлена методом POST 
 

Реализуйте PHP-скрипт, который принимает данные формы методом POST и сохраняет их в переменные: 

$name 

$email 

$message 

Если соответствующие поля отсутствуют в запросе, в переменные должно быть записано: 

'Нет имени' — если не передано поле name 

'Нет email' — если не передано поле email 

'Нет сообщения' — если не передано поле message 

После этого скрипт должен вывести строку вида: 

Имя: <name>, Email: <email>, Сообщение: <message> 

<?php 

$name = isset($_POST['name']) && $_POST['name'] !== '' ? $_POST['name'] : 'Нет имени';  

$email = isset($_POST['email']) && $_POST['email'] !== '' ? $_POST['email'] : 'Нет email';  

$message = isset($_POST['message']) && $_POST['message'] !== '' ? $_POST['message'] : 'Нет сообщения'; 

echo "Имя: $name, Email: $email, Сообщение: $message"; 
 

Реализуйте PHP-скрипт submit.php, который: 

Принимает поле name методом POST. 

Удаляет пробелы в начале и в конце (trim). 

Экранирует специальные символы (htmlspecialchars). 

Выводит строку: Имя: <значение> 

<?php 

$name = isset($_POST['name']) ? $_POST['name'] : ''; 

$cleanName = htmlspecialchars(trim($name)); 

echo "Имя: $cleanName"; 
 

Какой суперглобальный массив используется для получения данных о загружаемых файлах? 

$_FILES 
 

Какой метод лучше всего использовать для фильтрации и сортировки данных на странице? 

GET 
 

Что произойдет, если в форме не указать атрибут `method`? 

Данные отправятся методом GET по умолчанию. 
 

Что делает функция htmlspecialchars()? 

Преобразует специальные HTML-символы в сущности 
 

Какая функция полностью удаляет HTML теги из строки? 

strip_tags() 
 

Что произойдет при отправке формы с несколькими email-адресами методом POST? 

<form method="POST" action="/submit.php"> 
    <input type="text" name="emails[]" value="john@example.com"> 
    <input type="text" name="emails[]" value="jane@example.com"> 
    <input type="text" name="emails[]" value="bob@example.com"> 
    <button type="submit">Отправить</button> 
</form> 
$emails = $_POST['emails'] ?? []; 
foreach ($emails as $email) { 
    echo "Email: " . htmlspecialchars($email) . "<br>"; 
} 

Email: john@example.com 
Email: jane@example.com 
Email: bob@example.com 
 

Что будет выведено при выполнении следующего кода, если данные не отправлены? 

if (empty($_POST['username'])) { 
    echo "Имя пользователя не указано"; 

} 
Имя пользователя не указано 
 

Реализуйте скрипт, который: 

Получает поле name методом POST. 

Проверяет, что оно содержит от 3 до 50 символов. 

Выводит: 

Имя допустимо, если проверка пройдена. 

Недопустимая длина имени, если нет. 
<?php 

$name = $_POST['name'] ?? ''; 

$name = trim($name); 

$length = strlen($name); 

if ($length >= 3 && $length <= 50) { 

    echo "Имя допустимо"; 

} else { 

    echo "Недопустимая длина имени"; 

} 
 

Реализуйте PHP-скрипт, который: 

Принимает данные формы с полем name=message, отправленной методом POST. 

Проверяет, что запрос был выполнен методом POST 

Если метод запроса не POST, выведите: Неверный метод запроса 

Иначе: 

Если поле message не передано или пустое, выведите: Сообщение не заполнено 

Иначе выведите: Сообщение отправлено: <message> 

<?php 

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { echo "Неверный метод запроса"; } else { $message = $_POST['message'] ?? ''; 

if (trim($message) === '') { 
    echo "Сообщение не заполнено"; 
} else { 
    echo "Сообщение отправлено: " . htmlspecialchars($message); 
} 

} 
 

Какой атрибут формы отвечает за указание метода отправки данных на сервер? 

method 
 

Какой атрибут формы отключает встроенную валидацию браузера? 

novalidate 

Какой атрибут тега <input> позволяет задать предварительное значение, отображаемое в поле? 

value 
 

Какая функция проверяет, пустое ли значение? 

empty() 
 

Что произойдёт, если пользователь введёт число 12.5, а фильтр FILTER_VALIDATE_INTиспользуется для валидации? 

Проверка не пройдёт 
 

Что выведет следующий код?  

<form method="POST" action="/submit.php">  

<label><input type="checkbox" name="hobbies[]" value="футбол" checked> Футбол</label><label><input type="checkbox" name="hobbies[]" value="баскетбол" checked> Баскетбол</label>  

<label><input type="checkbox" name="hobbies[]" value="теннис"> Теннис</label>  

<button type="submit">Отправить</button>  

</form>  

$hobbies = $_POST['hobbies'] ?? [];  

echo implode(', ', $hobbies); 

футбол, баскетбол 
 

Что выведет следующий код, если в $_POST['price'] содержится строка "100.50"? 

$price = $_POST['price'] ?? ''; 
if (filter_var($price, FILTER_VALIDATE_FLOAT)) { 
    echo 'Корректная цена'; 
} else { 
    echo 'Ошибка: не число'; 
} 

Корректная цена 
 

Реализуйте скрипт, который проверяет, дал ли пользователь согласие на обработку персональных данных, поставив галочку (checkbox) в соответствующее поле. 

<?php 

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { 

    echo "Форма не отправлена"; 

    exit; 

} 

if (isset($_POST['form']['agree']) && $_POST['form']['agree'] === 'yes') { 

    echo "Согласие получено"; 

} else { 

    echo "Вы не дали согласие"; 

} 
 

Реализуйте PHP-скрипт submit.php, который: 

Принимает данные формы, отправленные методом POST. 

Ожидает получение массива hobbies[] — значений, выбранных пользователем через флажки (checkbox). 

Скрипт должен: 

Проверить, была ли форма отправлена. 

Проверить, был ли передан массив $_POST['hobbies']. 

Если массив пуст, вывести: Нет интересов 

Если массив содержит хотя бы одно значение, вывести: Интересы: <перечисление через запятую, без пробелов> Пример: Интересы: A,B,C 

<?php  

if ($_SERVER['REQUEST_METHOD'] === 'POST') {  

if (isset($_POST['hobbies']) &&  

is_array($_POST['hobbies'])) {  

$hobbies = $_POST['hobbies'];  

if (empty($hobbies)) {  

echo "Нет интересов";  

} else {  

echo "Интересы: " . implode(",", $hobbies);  

}  

} else {  

echo "Нет интересов";  

}  

} 
 

Что произойдет, если в форме отсутствует атрибут name у поля ввода? 

Данные этого поля не будут отправлены на сервер. 
 

Какой метод отправки данных рекомендуется использовать для передачи больших объемов информации и файлов? 

POST 
 

Что делает is_numeric()? 

Проверяет, является ли значение числом 
 

Что может произойти, если не фильтровать пользовательские данные и сразу отобразить их на странице? 

Возможна XSS-атака 
 

Какой результат выполнения кода, если пользователь ввёл имя длиной 2 символа? 

$name = $_POST['name'] ?? ''; 
if (strlen($name) < 3 || strlen($name) > 50) { 
    echo 'Ошибка: неверная длина'; 
} else { 
    echo 'Имя корректно'; 
} 

Ошибка: неверная длина 
 

Что будет выведено, если в поле name содержится строка <b>John</b>? 

$name = $_POST['name'] ?? ''; 
echo strip_tags($name); 

John 
 

Что выведет следующий код? 

<form method="POST" action="/submit.php"> 
    <label><input type="checkbox" name="sports[]" value="football" checked> Футбол</label> 
    <label><input type="checkbox" name="sports[]" value="basketball" checked> Баскетбол</label> 
    <label><input type="checkbox" name="sports[]" value="tennis"> Теннис</label> 
    <button type="submit">Отправить</button> 
</form> 
$sports = $_POST['sports'] ?? []; 
echo implode(', ', $sports); 

Футбол, Баскетбол 
 

Реализуйте PHP-скрипт submit.php, который должен: 

Принимать поле email методом POST. 

Проверять, является ли email корректным (FILTER_VALIDATE_EMAIL). 

Выводить: 

Email корректен, если формат допустим. 

Некорректный email, если нет. 

<?php 

$email = $_POST['email'] ?? ''; 

if (filter_var($email, FILTER_VALIDATE_EMAIL)) { 

    echo 'Email корректен'; 

} else { 

    echo 'Некорректный email'; 

} 
 

Реализуйте PHP-скрипт, который обрабатывает форму, содержащую несколько полей ввода email’ов. 

<?php 

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { 

    echo "Форма не отправлена\n"; 

} else { 

    if (isset($_POST['emails']) && is_array($_POST['emails'])) { 

        $emails = array_filter($_POST['emails'], function ($email) { 

            return !empty(trim($email)); 

        }); 

        if (empty($emails)) { 

            echo "Email адреса не переданы\n"; 

        } else { 

            echo "Количество email адресов: " . count($emails) . "\n"; 

        } 

    } else { 

        echo "Email адреса не переданы\n"; 

    } 

} 
 
Какая функция в PHP используется для удаления пробелов в начале и конце строки? 

trim() 
 

Что будет выведено, если пользователь отправил форму с полем name, содержащим значение " Alice "? 

$name = $_POST['name'] ?? " "; 
echo trim($name); 

"Alice" 
 

Какой результат даст следующий код, если в поле age введено значение "25"? 

$age = $_POST['age'] ?? ''; 
if (!filter_var($age, FILTER_VALIDATE_INT, ['options' => ['min_range' => 18, 'max_range' => 100]])) { 
    echo 'Ошибка: возраст недопустим'; 
} else { 
    echo 'Возраст принят'; 
} 

Возраст принят 
 

Что выведет следующий код? 

<form method="POST" action="/submit.php"> 
    <select name="country"> 
        <option value="md" selected>Молдова</option> 
        <option value="us">США</option> 
        <option value="fr">Франция</option> 
    </select> 
    <button type="submit">Отправить</button> 
</form> 
$country = $_POST['country'] ?? 'Не указано'; 
echo "Страна: $country"; 

Страна: md 
 

Что произойдет, если при отправке формы методом GET данные превысят максимальную длину URL? 

Данные будут обрезаны. 
 

Что такое рендеринг в контексте шаблонизации? 

Процесс подстановки данных в шаблон и генерация HTML 
 

Что делает функция `extract($vars)` в шаблонизаторе на PHP-файлах? 

Преобразует ключи массива в переменные 
 

Какова основная цель шаблонизации в PHP? 

Отделение логики от представления 
 

В каком случае шаблон layout используется наиболее эффективно? 

Когда нужно задать общий каркас для всех страниц сайта 
 

Каков результат выполнения? 

$posts = [ 
  ["title" => "Первая", "excerpt" => "..."], 
  ["title" => "Вторая", "excerpt" => "..."] 
]; 
foreach ($posts as $post) { 
  echo renderTemplate("post.php", ["post" => $post]); 
} 

Файл post.php 

<article><?= htmlspecialchars($post['title']) ?></article> 

<article>Первая</article><article>Вторая</article> 
 

Что произойдёт при выполнении кода? 

$template = "Добро пожаловать, {{USERNAME}}!"; 
$data = ["username" => "Алексей"]; 
 
foreach ($data as $key => $value) { 
    $placeholder = '{{' . strtoupper($key) . '}}'; 
    $template = str_replace($placeholder, $value, $template); 
} 
echo $template; 

Добро пожаловать, Алексей! 
 

Почему рекомендуется использовать `htmlspecialchars()` внутри шаблонов? 

Для предотвращения выполнения вредоносного HTML/JS 
 

Что такое лейаут (layout) в контексте шаблонов? 

Общий каркас HTML-документа с повторяющимися элементами 
 

В чём заключается преимущество шаблонизаторов вроде Twig и Smarty по сравнению с ручными подходами? 

Поддержка наследования шаблонов, условий, циклов и фильтров 
 

Что происходит при вызове ob_get_clean() после ob_start()? 

Получаем содержимое буфера и очищаем его 
 

Что выведет следующий код? 

$template = "<p>{{greeting}}, {{name}}!</p>"; 
$html = str_replace( 
    ["{{greeting}}", "{{name}}"], 
    ["Здравствуйте", "Анна"], 
    $template 
); 
echo $html; 

<p>Здравствуйте, Анна!</p> 
 

Что отобразится в браузере при выполнении следующего кода? 

$title = "Главная"; 
require_once "header.php"; 

Файл header.php 

<head><title><?php echo $title; ?></title></head> 

<head><title>Главная</title></head> 
Какой элемент шаблона чаще всего предназначен для повторного использования внутри страниц? 

Блок (или компонент) 
 

Что является основным недостатком функций-компонентов в шаблонизации? 

Смешивание логики и представления в одной функции 
 

Что выведет следующий код? 

function renderPostCard($post) { 
    return "<h2>{$post['title']}</h2>"; 
} 
echo renderPostCard(['title' => 'Новости']); 

<h2>Новости</h2> 
 

Какой результат выполнения следующего кода? 

$products = ["Чай", "Кофе"]; 
$list = "<ul>"; 
foreach ($products as $item) { 
    $list .= "<li>$item</li>"; 
} 
$list .= "</ul>"; 
echo $list; 

<ul><li>Чай</li><li>Кофе</li></ul> 
 

Что выведет следующий код? 

ob_start(); 
echo "Привет, "; 
echo "мир!"; 
$output = ob_get_clean(); 
echo $output; 

Привет, мир! 
 

Какая программа используется для управления базами данных MySQL через графический интерфейс в составе XAMPP? 

PhpMyAdmin 
 

Какая функция используется в PDO для выполнения простого SQL-запроса без параметров? 

query() 
 

Какое расширение PHP нужно активировать для работы с базой данных PostgreSQL через PDO? 

pdo_pgsql 
 

Что возвращает метод `lastInsertId()` в PDO? 

Идентификатор последней вставленной записи 
 

Что делает функция mysqli_connect_error()? 

Возвращает текст последней ошибки подключения 
 

Что выведет следующий код, если в таблице есть три записи? 

<?php 
$sql = "SELECT id, name FROM users"; 
$result = mysqli_query($conn, $sql); 
$users = mysqli_fetch_all($result, MYSQLI_ASSOC); 
echo count($users); 

3 
 

Какой результат выполнения следующего кода, если SQL-запрос написан без ошибок и таблица существует? 

<?php 
$sql = "SELECT id, name FROM users"; 
$result = mysqli_query($conn, $sql); 
if ($result === false) { 
    echo 'Ошибка запроса'; 
} else { 
    echo 'Запрос выполнен успешно'; 
} 

Запрос выполнен успешно 
 

Что выведет данный код? 

```php <?php 

$sql = "SELECT title FROM products WHERE price > 500"; $result = mysqli_query($conn, $sql); $items = mysqli_fetch_all($result, MYSQLI_ASSOC); echo count($items); 

2 
 

Какой оператор необходимо использовать для добавления новой записи в таблицу users? $sql = "_____ INTO users (name, email) VALUES ('John', 'john@example.com')"; 

INSERT 
 

Задание: 

Напишите код для выбора всех платежей с суммой больше заданной: $minAmount. 

Сохраните суммы платежей в переменной $payments в порядке возрастания. 

Если платежи не найдены, то выведите на экран строку "Not found". 

Обязательно защититесь от SQL-инъекций. 

<?php 

$stmt = $db->prepare("SELECT amount FROM payments WHERE amount > :minAmount ORDER BY amount ASC"); 

$stmt->execute([':minAmount' => $minAmount]); 

$payments = $stmt->fetchAll(PDO::FETCH_COLUMN); 

if (empty($payments)) { 

    echo "Not found"; 

} 
 

Что произойдет при ошибке подключения в режиме PDO::ERRMODE_EXCEPTION? 

Будет выброшено исключение PDOException 
 

Какое расширение обеспечивает универсальный интерфейс для работы с различными СУБД в PHP? 

PDO 
 

Что делает функция mysqli_stmt_execute()? 

Выполняет подготовленный SQL-запрос 
 

Что обязательно следует сделать с конфигурационным файлом в системе контроля версий (например, Git)? 

Добавить его в .gitignore 
 

Какой самый надежный способ защиты от SQL-инъекций? 

Использование подготовленных выражений (prepared statements) 
 

Что вернет код: 

<?php 
$stmt = $pdo->prepare("SELECT COUNT(*) FROM employees WHERE department = ?"); 
$stmt->execute(["IT"]); 
$count = $stmt->fetchColumn(); 
echo $count; 

3 
 

Что произойдет при выполнении кода: 

<?php 
$stmt = $pdo->prepare("INSERT INTO clients (name, email) VALUES (?, ?)"); 
$stmt->execute(["Bob", "alice@example.com"]); 
?> 
Будет выброшено исключение о нарушении уникальности 
 

Что выведет данный код после успешной вставки новой записи? 

<?php 
$name = "Anna"; 
$email = "anna@example.com"; 
$sql = "INSERT INTO users (name, email) VALUES ('$name', '$email')"; 
$result = mysqli_query($conn, $sql); 
if ($result) { 
    echo mysqli_insert_id($conn); 
} else { 
    echo 'Ошибка вставки'; 
} 
Новый ID записи 
 

Задание: 

Напишите SQL-запрос для добавления нового товара: Название: "Phone", Цена: 799.99 

<?php 

$sql = "INSERT INTO products (title, price) VALUES ('Phone', 799.99)"; 
 

Какую функцию необходимо вызвать для выполнения SQL-запроса (из расширения mysqli)? 

$result = mysqli_query($conn, $sql); 
 

Что произойдет при успешном выполнении функции mysqli_query() с запросом типа INSERT? 

Вернется true 
 

Что возвращает функция mysqli_fetch_all($result, MYSQLI_ASSOC)? 

Массив, в котором каждый элемент представлен как ассоциативный массив, где ключи - название столбов таблицы 
 

Что происходит при вызове метода closeCursor() на объекте PDOStatement? 

Освобождаются ресурсы, связанные с текущим результатом запроса 
 

Как называется функция MySQLi для подготовки запроса с плейсхолдерами? 

mysqli_prepare 
 

Что делает метод prepare() в PDO? 

Подготавливает SQL-запрос с плейсхолдерами для безопасной подстановки данных 
 

Что произойдет при выполнении следующего кода, если пользователь в поле login введет admin' OR '1'='1'? 

<?php 
$login = $_POST['login']; 
$password = $_POST['password']; 
$query = "SELECT * FROM users WHERE login = '$login' AND password = '$password'"; 
$result = mysqli_query($connection, $query); 
if (mysqli_num_rows($result) > 0) { 
    echo "Welcome!"; 
} else { 
    echo "Invalid login or password."; 
} 

Будет выведено "Welcome!" даже без правильного пароля 
 

Что вернет следующий код? 

<?php 
$stmt = $pdo->prepare("SELECT * FROM users;"); 
$stmt->execute(); 
$user = $stmt->fetch(PDO::FETCH_ASSOC); 
$user = $stmt->fetch(PDO::FETCH_ASSOC); 
echo $user['name']; 

Anna 

 
 
Что выведет следующий код? 

<?php 
$sql = "SELECT products.title, categories.name AS category  
        FROM products 
        JOIN categories ON products.category_id = categories.id 
        WHERE categories.name = 'Electronics'"; 
$result = mysqli_query($conn, $sql); 
 
$items = mysqli_fetch_all($result, MYSQLI_ASSOC); 

echo count($items); 
?> 

2 
 

Задание: 

Дополните код для вставки нового товара с названием $name и ценой $price. 

<?php 

$stmt = $db->prepare("INSERT INTO products (title, price) VALUES (?, ?)"); 

$stmt->execute([$name, $price]); 
 

Дополните пропущенную строку в файле php.ini, чтобы активировать расширение для работы с MySQL  

extension=mysqli 
 

Какие действия необходимо выполнить для правильной подготовки PHP-окружения к работе с базами данных? 

Перезапустить веб-сервер после изменений 

Рекомендуется настроить кодировку данных в БД 

Активировать необходимые расширения 
 

Какая функция используется для извлечения одной строки результата в виде ассоциативного массива? 

mysqli_fetch_assoc 
 

Какое основное преимущество использования базы данных вместо файлов для хранения информации? 

Структурированное хранение и эффективное управление данными 
 

Какой порт по умолчанию используется для подключения к серверу PostgreSQL? 

5432 
 

Какое основное преимущество использования PDO по сравнению с MySQLi? 

Поддержка разных СУБД через единый интерфейс 
 

Что произойдет при выполнении следующего кода, если пользователь введет в поле login значение admin' --? 

<?php 
$login = $_POST['login']; 
$password = $_POST['password']; 
$query = "SELECT * FROM users WHERE login = '$login' AND password = '$password'"; 
$result = mysqli_query($conn, $query); 

Будет проигнорировано условие проверки пароля 
 

Что произойдет при успешном подключении к базе данных через PDO? 

<?php 
$dsn = "mysql:host=localhost;dbname=my_database;charset=utf8"; 
$dbUser = "root"; 
$dbPass = ""; 
try { 
    $pdo = new PDO($dsn, $dbUser, $dbPass); 
    echo "Соединение установлено"; 
} catch (PDOException $e) { 
    echo "Ошибка: " . $e->getMessage(); 
} 

Будет выведено "Соединение установлено" 
 

Задание: 

Дополните код для получения количества удалённых строк. 

<?php 

$stmt = $db->prepare($sql); 

$stmt->execute(); 

if ($stmt->rowCount() > 0) { 

    $deletedRows = $stmt->rowCount(); 

} 
 

Задание: 

Дополните код так, чтобы правильно подготовить запрос выбора всех пользователей с именем "Ivan" (Используйте расширение PDO) 

<?php 

$stmt = $db->prepare("SELECT * FROM users WHERE name = ?"); 

$stmt->execute(['Ivan']); 

$users = $stmt->fetchAll(PDO::FETCH_ASSOC); 
 

Какая функция возвращает количество строк, затронутых последним запросом UPDATE или DELETE? 

mysqli_affected_rows 
 
Что такое SQL-инъекция? 

Внедрение вредоносного кода в SQL-запрос через пользовательский ввод 
 

Почему данные для подключения к базе данных нельзя оставлять в открытом виде? 

Это чувствительная информация, которая может быть использована злоумышленниками 
 

Какой параметр конфигурации рекомендуется установить для корректной работы с Unicode-данными? 

default_charset = "UTF-8" 
 

Что произойдет, если функция mysqli_connect() не сможет установить соединение с базой данных? 

Вернет false 
 

Что выведет следующий код при успешном подключении к базе данных? 

<?php 
$conn = mysqli_connect('localhost', 'root', '', 'my_database'); 
if (!$conn) { 
    die('Ошибка подключения: ' . mysqli_connect_error()); 
} 
echo 'Успешное подключение'; 

Успешное подключение 
 

Какое сообщение выведет данный код, если в таблице нет записи с указанным ID? 

<?php 
$id = 50; 
$sql = "UPDATE users SET email = 'test@example.com' WHERE id = $id"; 
$result = mysqli_query($conn, $sql); 
if (mysqli_affected_rows($conn) > 0) { 
    echo 'Email обновлён'; 
} else { 
    echo 'Ничего не обновлено'; 
} 

Ничего не обновлено 
 

Задание: 

Напишите код для обновления статуса заказа на 'cancelled' по заданному $id. 

Если заказ с $id не найден, то выведите: "Заказ не найден" 

Обязательно защититесь от SQL-инъекций. 

<?php 

$stmt = $db->prepare("UPDATE orders SET status = 'cancelled' WHERE id = ?"); 

$stmt->execute([$id]); 

if ($stmt->rowCount() > 0) { 

} else { 

    echo "Заказ не найден"; 

} 
 

Задание: 
Дополните SQL-запрос для получения всех пользователей с именем "Ivan": 

<?php 

$sql = "SELECT * FROM users WHERE name = 'Ivan'"; 
 

Что нужно сделать после работы с результатом запроса для освобождения ресурсов памяти? 

Вызвать mysqli_free_result($result) 
 

Какое расширение предназначено для работы с MySQL и поддерживает как процедурный, так и объектно-ориентированный подход? 

mysqli 
 

Какая функция используется для установления соединения с базой данных в MySQLi в процедурном стиле? 

mysqli_connect 
 

Задание: 

Выведите список всех книг с указанием имени автора в формате. 

<ul> 
<li>Author1 | Book1</li> 
<li>Author2 | Book2</li> 
<ul> 
<?php 

$query = " 

    SELECT authors.name AS author, books.title AS book 

    FROM books 

    JOIN authors ON books.author_id = authors.id 

"; 

$result = $db->query($query); 

echo "<ul>"; 

while ($row = $result->fetch(PDO::FETCH_ASSOC)) { 

    echo "<li>{$row['author']} | {$row['book']}</li>"; 

} 

echo "</ul>"; 
 

Какой флаг следует установить, чтобы запретить доступ к cookie из JavaScript? 

HttpOnly 
 

Какой PHP-функцией устанавливаются cookies? 

setcookie 
 

Что рекомендуется сохранять в сессии после успешной аутентификации? 

Только идентификатор пользователя 
 

Что такое аутентификация в веб-разработке? 

Процесс проверки подлинности пользователя 
 

Как называется подход, когда доступ управляется через роли? 

RBAC 
 

Почему не рекомендуется разделять сообщения об ошибках при неправильном вводе логина или пароля? 

Это может привести к атаке User Enumeration 
 

Что выведет данный код? 

<?php 
session_start(); 
$_SESSION['user'] = ['role' => 'admin']; 
echo isset($_SESSION['user']) ? 'Авторизован' : 'Не авторизован'; 

Авторизован 
 

Что выведет данный код? 

<?php 
session_start(); 
$_SESSION['auth'] = true; 
unset($_SESSION['auth']); 
echo empty($_SESSION['auth']) ? 'Не авторизован' : 'Авторизован'; 

Не авторизован 
 

Что выведет данный код? 

<?php 
 
$password = 'secret'; 
$hash = password_hash($password, PASSWORD_DEFAULT); 
echo password_verify('secret', $hash) ? 'OK' : 'FAIL'; 

OK 
 

Допишите код для установки cookie с именем language, значением en, сроком действия 30 дней. 

<?php 

setcookie('language', 'en', time() + (30 * 24 * 60 * 60)); 
 

Какие флаги рекомендуется устанавливать для безопасности сессионной cookie? 

SameSite 

HttpOnly 

Secure 
 

Каким образом cookies позволяют сохранять состояние между HTTP-запросами? 

Браузер сохраняет данные и отправляет их серверу при каждом запросе 
 

Что обязательно нужно делать с паролями перед сохранением в базе данных? 

Хешировать с помощью криптографической функции 
 

Что определяет процесс авторизации? 

Проверку прав пользователя на выполнение действий 
 

Что такое логин в контексте аутентификации? 

Уникальный идентификатор пользователя 
 

Какая функция PHP используется для явного завершения текущей сессии пользователя? 

session_destroy() 
 

Что будет выведено при первом запросе пользователя? 

<?php 
session_start(); 
if (!isset($_SESSION['counter'])) { 
    $_SESSION['counter'] = 1; 
} else { 
    $_SESSION['counter']++; 
} 
echo $_SESSION['counter']; 

1 
 

Что выведет данный код? 

<?php 
session_start(); 
$_SESSION['user_id'] = 5; 
echo isset($_SESSION['user_id']) ? 'Yes' : 'No'; 

Yes 
 

Что выведет данный код? 

<?php 
session_start(); 
echo session_id() === '' ? 'Нет сессии' : 'Сессия активна'; 

Сессия активна 
 

Напишите код для хеширования пароля $password (алгоритмом по умолчанию - bcrypt) и сохраните его в переменную $hashedPassword 

<?php 

$password = 'iLoveCookies'; 

$hashedPassword = password_hash($password, PASSWORD_DEFAULT); 
 

Что означает, что HTTP-протокол не сохраняет состояние? 

Сервер обрабатывает каждый запрос независимо и не "помнит" предыдущие запросы 
 

Как удалить определённое значение из сессии? 

unset($_SESSION['ключ']) 
 

В чём отличие роли от права? 

Роль отвечает за статус пользователя, право — за конкретные действия 
 

Какая функция используется для проверки соответствия пароля и хэша? 

password_verify() 
 

Какой основной риск возникает при хранении паролей в открытом виде? 

Возможность утечки всех паролей при взломе базы 
 

Что такое роль в контексте авторизации? 

Статус пользователя, определяющий его права 
 

Что выведет данный код при втором запросе пользователя? 

<?php 
setcookie('mode', 'dark', time() + 3600, '/', '', false, true); 
if (isset($_COOKIE['mode'])) { 
    echo $_COOKIE['mode']; 
} else { 
    echo 'Куки не установлена'; 
} 

dark 
 

Что выведет данный код? 

<?php 
$token = bin2hex(random_bytes(16)); 
echo strlen($token); 

32 
 

Что выведет следующий код? 

<?php 
session_start(); 
$_SESSION['user'] = ['role' => 'editor']; 
function isAdminOrEditor() { 
    return isset($_SESSION['user']) && in_array($_SESSION['user']['role'], ['admin', 'editor']); 
} 
echo isAdminOrEditor() ? 'Есть доступ' : 'Нет доступа'; 

Есть доступ 
 

Напишите код, чтобы создать сессию, сохранить в ней ключ cart_items со значением массива [$product1, $product2, $product3]. 

<?php 

session_start(); 

$_SESSION['cart_items'] = [$product1, $product2, $product3]; 
 

Какой максимальный рекомендуемый размер одного cookie по стандарту? 

~4 KB 
 

Какой суперглобальный массив используется для работы с данными сессии в PHP? 

$_SESSION 
Что такое "учетные данные" в контексте аутентификации? 

Пара логин-пароль для входа 
 

Какие алгоритмы хеширования считаются рекомендуемыми для паролей? 

bcrypt 

sha-256 
Что определяет процесс авторизации? 

Проверку прав пользователя на выполнение действий 
 

Что будет выведено, если пользователь сделает 3 запроса? 

<?php 
session_start(); 
if (!isset($_SESSION['visits'])) { 
    $_SESSION['visits'] = 0; 
} 
$_SESSION['visits']++; 
echo "Вы посетили эту страницу {$_SESSION['visits']} раз(а)"; 

Вы посетили эту страницу 3 раз(а) 
 

Что выведет код? 

<?php 
session_start(); 
define('SESSION_TIMEOUT', 1800); 
$_SESSION['last_activity'] = time() - 2000; 
if (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT) { 
    echo 'Сессия устарела'; 
} else { 
    echo 'Сессия активна'; 
} 

Сессия устарела 
 

Найдите и исправьте ошибку в коде установки сессионной переменной. 

<?php 

session_start(); 

$_SESSION['user_id'] = 123; 
 

Напишите код для установки HttpOnly cookie auth_token со значением dksf9ui8sdfu8usdfsdfjjsf98 

<?php 

setcookie('auth_token', 'dksf9ui8sdfu8usdfsdfjjsf98', [ 

    'httponly' => true, 

    'path' => '/',  

    'secure' => true,    

    'samesite' => 'Lax' 

]); 
 

Напишите код для проверки, что пользователь имеет роль admin. 

Если пользователь имеет роль admin, выведите сообщение "Доступ разрешен". 

Иначе завершите выполнение программы с текстом "Доступ запрещен". 

Роль сохранена в сессии (ключ = role). 

<?php 

session_start(); 

if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') { echo "Доступ разрешен"; } else { exit("Доступ запрещен"); } 
 

Напишите код для проверки наличия куки theme и вывода её значения, либо сообщения Куки не найдена, если куки не установлена. 

<?php 

if (isset($_COOKIE['theme'])) { 

    echo $_COOKIE['theme']; 

} else { 

    echo 'Куки не найдена'; 

} 
 

Какой код ответа обычно возвращает сервер после успешного удаления ресурса? 

204 No Content 
 

Чем веб-сервис отличается от обычного веб-приложения? 

Веб-сервис предназначен для взаимодействия между программами, а веб-приложение — для работы с пользователем 
 

Что происходит, если клиент отправляет OPTIONS-запрос на сервер? 

Сервер сообщает, какие методы доступны для данного ресурса 
 

Почему современная разработка часто разделяет фронтенд и бэкенд через API? 

Чтобы разные команды могли работать независимо друг от друга 
 

Как называется необязательное ограничение REST, которое позволяет серверу отправлять исполняемый код клиенту? 

Code on Demand 
 

Что позволяет клиенту в REST понимать, в каком формате представлены данные в ответе сервера? 

Заголовок Content-Type 
 

Какой из перечисленных методов HTTP не является идемпотентным? 

POST 
 

Что является важнейшей частью любого API, даже самого простого, как обмен через файл? 

Строгое соглашение о формате и структуре данных 
 

Что означает термин REST в контексте API? 

Representational State Transfer 
 

Что происходит, если при проектировании API игнорировать архитектурные стандарты? 

Появляются сложности интеграции и ошибки во взаимодействии между компонентами 
 

Что такое API операционной системы? 

Набор функций для взаимодействия программ с ОС (например, работа с файлами, сетью) 
 

Какую основную задачу выполняет API в программировании? 

Обеспечивает взаимодействие между программами через стандартизированные запросы и ответы 
 

Какой формат данных чаще всего используется для ответов в RESTful API? 

JSON 
 

Какой заголовок сообщает клиенту, можно ли кэшировать ответ сервера? 

Cache-Control 
 

Какой HTTP-метод используется для получения ресурса без его изменения? 

GET 
 

Что означает кэшируемость (Cacheable) в контексте REST? 

Возможность клиента использовать сохранённые ответы без повторного запроса, если сервер это разрешил 
 

Что происходит при повторном выполнении идемпотентного запроса? 

Результат будет тем же, что и при первом выполнении 
 

Кто является автором архитектурного стиля REST? 

Рой Филдинг 
 

Какой HTTP-метод применяется для полного обновления существующего ресурса? 

PUT 
 

Какой принцип REST требует, чтобы сервер не хранил информацию о состоянии клиента между запросами? 

Stateless 
 

Какое преимущество даёт принцип «слоистой системы» в архитектуре REST? 

Возможность масштабирования и внедрения промежуточных слоёв без изменения логики клиента 