# Tsukushi
日本語の文章の感情解析
**このライブラリは日本語のみ解析できます。**


つくしは他の日本語の自然言語解析ソフトウェアの名前になぞらえて3音の食べ物であり、Tsukushiの名前の独自性、作者が寿司が好きだったため名付けられました。

## 使い方

1. MySQLにデータベースをインポートします。

  * 単語の辞書形への変換用の辞書 **word_convert_dic_table.sql.zip**
  * 単語の点数付け用の辞書 **word_score_tweet_dic_table.sql.zip** or **word_score_tweet_dic_table.sql.zip**

2. ライブラリを読み込みます
```
require_once('./lib/Tsukusi.php');
```


3. Tsukushiオブジェクトを呼び出し設定します。
```
$hoge = new Tsukushi([
  'DB_host' => 'mysql',
  'DB_user' => 'root',
  'DB_pass' => 'password',
  'DB_name' => 'kaken',
  'APPID' => 'YAHOO　apis　のアプリケーションIDを入力してください',
  'DB_word_score' => 'word_score_dic_table'
]);
```

4. getscoreメソッドを実行すると、引数に指定した文章の点数が返されます。
```
echo $hoge->getscore('解析したい文章');
```
  このコードを実行すると「解析したい文章」を採点した数値が表示されます。

## ライセンス

LICENSE: GPL v2


Copyright 2017- magcho, uria

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




----

# Tsukushi
Sentnece analysis for Japanese.
**This library can analysis only Japanese langage.**




"Tsukushi" is modeling other natural language analysis software and
developer likes SUSHI.



## HowTo

1. Inport DB of dictionaly into mysql.

  * The dictionaly for word normalization.  **word_convert_dic_table.sql.zip**
  * The dictionaly for word change to score. **word_score_tweet_dic_table.sql.zip** or **word_score_tweet_dic_table.sql.zip**


2. Include library.
```
require_once('./lib/Tsukusi.php');
```


3. Call Tsukushi object and setting.
```
$hoge = new Tsukushi([
  'DB_host' => 'mysql',
  'DB_user' => 'root',
  'DB_pass' => 'password',
  'DB_name' => 'kaken',
  'APPID' => 'YAHOO　apis　のアプリケーションIDを入力してください',
  'DB_word_score' => 'word_score_dic_table'
]);
```

4. Run the method getscore, and return float number that sentence have sentiment analysis score.
```
echo $hoge->getscore('For analysis sentence');
```

  Show score of 「For analysis sentence」 when This code run.

## license

LICENSE: GPL v2


Copyright 2017 magcho, uria

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
