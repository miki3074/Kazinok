<?php

$config=['whitetime'=>31536000, /*месяц*/ 'bantime'=>604800, /*неделя*/ 'proactivetime'=>3600, /*час*/ 'referertime'=>604800];

$config['limit']=700; // Количество заходов в минуту, после превышения появляется капча. Убедитесь, что нет никаких ajax скриптов, которые обращаются чаще (чат, например).

$config['counter']=['url'=>1, 'user_agent'=>0]; 
// Если  url установить в ноль, то счетчик будет работать для всего сайта и нужно выставить больше значение limit, если user_agent установить в ноль, не будет учитываться браузер для идентификации пользователя (боты теоретически могут выставлять случайный user_agent)

//$config['referer']=[$_SERVER['HTTP_HOST'], 'yandex.ru', 'google.com', 'google.ru'];
$config['referer']=false; // Рефереры для исключения, ддос боты обычно шлют пустые реферы 
$config['adminEmail']='ilnaz734@gmail.com'; // Емейл для связи с админом сайта (в случае ошибочной блокировки)
	
$config['search_bots']=['Googlebot'=>'Google', 'yandex.com/bots'=>'Yandex', 'mail.ru'=>'mail.ru'];  // 'msn.com','bing.com'
$config['search_hosts']=['Google'=>['.googlebot.com', '.google.com'], 'Yandex'=>['.yandex.com', '.yandex.ru', '.yandex.net'], 'mail.ru'=>['.mail.ru'], 'msn.com'=>['.msn.com'], 'bing.com'=>['.msn.com'] ];	

//Cloudflare
$configCF=
[
	'email'=>'ilnazletsplay@gmail.com',  // email вашего аккаунта в Cloudflare
	'key'=>'43ce1129c0e1c26df03019561e4bd16327c56', // Узнать можно на странице dash.cloudflare.com/profile, Global API Key
	'zone'=>'b574e7e14c914d9640bd867f81bfcb94', // Zone ID домена в Cloudflare, есть во вкладке Overview 
	
	'countries'=>['RU'=>1, 'UA'=>1, 'BY'=>1, 'KZ'=>1, 'LV'=>1, 'EE'=>1, 'AZ'=>1], // страны целевого трафика
	'limit'=>15 // Лимит для IP, прошедших капчу Cloudflare. При превышении лимита, IP банится полностью 
];
 
$config['admin']['pass']='2rh?VQ6mo$6M'; // Пароль админки
