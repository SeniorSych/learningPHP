<?php
/*
1. Создайте вне интерпретатора РНР новый HTML-файл шаблона по образцу, приведенному в
примере 9.1. Напишите программу, в которой функции file_get_contents() и file_put
_contents() применяются для чтения HTML-файла шаблона, вместо переменных в шаблон
подставляются соответствующие значения, а новая страница сохраняется в отдельном файле.
2. Создайте вне интерпретатора РНР новый файл, содержащий ряд адресов электронной почты в
отдельных строках, причем некоторые адреса должны присутствовать в файле неоднократно.
Присвойте новому файлу имя addresses.txt. Затем напишите на РНР программу, читаю-
щую каждую строку из файла addresses.txt и подсчитывающую, сколько раз каждый адрес
упоминается в данном файле. Для каждого адреса из файла addresses.txt эта программа
должна записывать отдельную строку в другой файл, называемый addresses-count.txt.
Каждая строка из файла addresses-count.txt должна состоять из числа, обозначающе-
го, сколько раз данный адрес упоминается в файле addresses.txt, запятой и самого адреса
электронной почты. Такие строки должны записываться в файл addresses-count.txt в от-
сортированном порядке, начиная с адреса, чаще всего упоминаемого в файле addresses.txt,
и кончая адресом, реже всего упоминаемым в файле addresses.txt.
3. Отобразите файл формата CSV в виде HTML-таблицы. Если у вас нет под рукой такого файла
(или программы электронных таблиц), воспользуйтесь данными из примера 9.9.
4. Напишите на РНР программу, отображающую форму, в которой пользователю предлагает-
ся указать имя файла в корневом каталоге документов на вебсервере. Если такой файл су-
ществует на веб-сервере, доступен для чтения и находится в корневом каталоге докумен-
тов, то программа должна отобразить его содержимое. Так, если пользователь введет имя
файла article.html, программа должна отобразить содержимое файла article.html,
находящегося в корневом каталоге документа. А если пользователь введет путь к файлу
catalog/show.php, программа должна отобразить содержимое файла show.php из катало-
га catalog, входящего в корневой каталог документа. О том, как находить корневой каталог
документа на веб-сервере, см. в табл. 7.1.
5. Видоизмените программу из предыдущего упражнения, чтобы она отображала только файлы
с расширением .html. Ведь разрешать пользователям просматривать исходный код РНР лю-
бой страницы веб-сайта опасно, если она содержит уязвимую информацию (например, имена
пользователей и их пароли)
 */
//1.
$file = file_get_contents('page-template.html');
$changer = str_replace('{page_title})','CHANGED', $file);
file_put_contents('changed_page.html', $changer);
//2.
// Чтение содержимого файла addresses.txt
$addresses = file('addresses.txt', FILE_IGNORE_NEW_LINES);

// Подсчет количества упоминаний каждого адреса
$counts = array_count_values($addresses);

// Сортировка адресов по количеству упоминаний
arsort($counts);

// Запись результатов в файл addresses-count.txt
$file = fopen('addresses-count.txt', 'w');
foreach ($counts as $address => $count) {
    fwrite($file, $count . ',' . $address . PHP_EOL);
}
fclose($file);
//3.
// Путь к CSV файлу
$csvFile = 'dishes.csv';

// Чтение содержимого CSV файла
$rows = file($csvFile);

// Создание HTML таблицы
echo '<table>';

// Вывод заголовков таблицы (первая строка CSV файла)
echo '<tr>';
foreach ($rows[0] as $header) {
    echo '<th>' . htmlspecialchars($header) . '</th>';
}
echo '</tr>';

// Вывод данных таблицы (остальные строки CSV файла)
for ($i = 1; $i < count($rows); $i++) {
    echo '<tr>';
    foreach ($rows[$i] as $value) {
        echo '<td>' . htmlspecialchars($value) . '</td>';
    }
    echo '</tr>';
}

echo '</table>';

//4.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $filename = $_SERVER['DOCUMENT_ROOT'].'/'.$_POST['filename'];
    print $filename.'<br>';
    // Проверяем, существует ли файл и доступен ли он для чтения
    if (file_exists($filename) && is_readable($filename)) {
        // Отображаем содержимое файла
        $content = file_get_contents($filename);
        echo nl2br(htmlspecialchars($content));
    } else {
        echo 'Файл не найден или недоступен для чтения.';
    }
}
print <<<FILEFORM
<form method="post" action="$_SERVER[PHP_SELF]">
    <label for="filename">Введите имя файла:</label>
    <input type="text" name="filename" id="filename">
    <button type="submit">Отобразить содержимое</button>
</form>
FILEFORM;

//5.
//добавить эту строку перед циклом if
//$filename = str_replace('.php', '345t6y789', $filename);
