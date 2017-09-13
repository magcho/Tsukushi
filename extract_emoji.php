<?php
/*
LICENSE: GPL v2


Copyright 2017 MagCho, uria

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
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
