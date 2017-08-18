<?php
/**
 *
 */
// $text = "おさ(-_-;)かな🐟🕢";
$text = "大阪いくのか！裏山！！なおセレクトで😎😎";

require_once("./lib/emoji.php");
echo remove_emoji($text);
print_r(extract_emoji($text)).'<br />';

// require_once('./lib/aa.php');
// echo remove_aa($text);
// print_r(extract_aa($text));
//



// function toCodePoint($string, $encoding = 'UTF-8')
// {
//     return bin2hex(mb_convert_encoding($string, 'UTF-32BE', $encoding));
// }
// echo toCodePoint('🐟'), PHP_EOL;
