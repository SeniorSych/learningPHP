<?php
function getApps() : array
{
//считываем файл, он возвращает строку
//т.к. тут без джсон, таблица в админке выводиться не будет
//нужно делать преобразование в строку (можно через explode)
//    $str=file_get_contents('db/apps.txt');
//file превращает файл в массив строк,
//сама разбирается как сделан перенос строки и тд
    $lines = file('db/apps.txt');
//можно через array_map, но
    $apps = [];
    foreach ($lines as $line) {
        $apps[] = appStrToArr($line);
    }
    return $apps;
}
//    функция преобразовывает данные из джсон в обычный пхп
// обьект или массив, принимает первым параметром строку, если второй параметр
// не передан - будет создан обьект. Указывае тру чтобы все распаршенные
// джсон обьекты превратились в ассоциативные массивы
//return json_decode($str, true);
//}
//т.к.это самописный формат записи и его нужно считать, то
//теперь придется делать функцию
function appStrToArr(string $str): array
{
   $str=rtrim($str);
   $parts=explode(';', $str);
   return ['dt'=>$parts[0],'name'=>$parts[1],'phone'=>$parts[2]];
}
function addApp(string $name, string $phone) : bool
{
    $dt=date("Y-d-m H:i:s");
//собираем данные из формы в массив
//т.к. saveApps=слишком дорого, переизобретаем систему хранения из:
//    $app=['dt'=>$dt, 'name'=>$name, 'phone'=>$phone];
//    $allApps=getApps();
//    $allApps[]=$app;
//    return saveApps($allApps);
//в:
    $app="$dt;$name;$phone\n";
//умеет принимать три параметра: имя файла в который мы хотим сохранить,
// строка которую мы хотим сохранить, возможность указать константу,
// которая говорит что файл не надо писать целиком, а нужно в конец
// файла дописать строчку
   file_put_contents('db/apps.txt', $app, FILE_APPEND);
   return true;
}
//слишком дорогая функция
//function saveApps(array $apps) : bool
//{
////    преобразовывает пхп данные в джсон (для массива апп, который только что пришел)
////    создает строку
//   $str= json_encode($apps);
////   функция принимает два параметра (имя файла и строка которую туда нужно поместить)
////    работает в жестком режиме, полностью заменяет файл
//   file_put_contents('db/apps.txt', $str);
//   return true;
//}

