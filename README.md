# Tsukushi
日本語の文章の感情解析
**このライブラリは日本語のみ解析できます。**

## 使い方

1. mysqlにデータベースをインポートします。

  * 単語の辞書形への変換用の辞書 **word_convert_dic_table.sql.zip**
  * 単語の点数付け用の辞書 **word_score_tweet_dic_table.sql.zip** or **word_score_tweet_dic_table.sql.zip**

2. コードを記述します。

```php
  include('./lib/tukushi.php');

  $hoge = new tukushi();

                                                //[デフォルトの設定]
  $hoge->DB_host = "mysql";     //mysqlのホスト名   [localhost]
  $hoge->DB_user = "root";      //mysqlのユーザ名   [root]
  $hoge->DB_pass = "password";  //mysqlのパスワード [password]
  $hoge->DB_name = 'kaken';     //mysqlのDB名      [kaken]
  $DB_charset = "utf8mb4";      //mysqlの文字コード [utf8mb4]
  $hoge->DB_word_convert = "word_convert_dic_table";    //単語の辞書形への変換用の辞書テーブル名 [word_convert_dic_table]
  $hoge->DB_word_score = "word_score_tweet_dic_table";  //単語の点数付け用の辞書テーブル名 [word_score_tweet_dic_table]
  $hoge->APPID = 'appid_here';  //yahoo apisのappid []


  echo $hoge->getscore('解析したい文字列');
```

  このコードを実行すると「解析したい文章」を採点した数値が表示されます。


# Tsukushi
Sentnece analysis for Japanese.
**This library can analysis only Japanese langage.**

## HowTo

1. Inport DB of dictionaly into mysql.

  * The dictionaly for word normalization.  **word_convert_dic_table.sql.zip**
  * The dictionaly for word change to score. **word_score_tweet_dic_table.sql.zip** or **word_score_tweet_dic_table.sql.zip**

2. Writing code.

  ```php
  include('./lib/tukushi.php');

  $hoge = new tukushi();


                                                //[default value]
  $hoge->DB_host = "mysql";     //hostname of mysql   [localhost]
  $hoge->DB_user = "root";      //username of mysql   [root]
  $hoge->DB_pass = "password";  //password of mysql   [password]
  $hoge->DB_name = 'kaken';     //DB name of mysql    [kaken]
  $DB_charset = "utf8mb4";      //charset of mysql    [utf8mb4]
  $hoge->DB_word_convert = "word_convert_dic_table";    //The dictionaly for word normalization. [word_convert_dic_table]
  $hoge->DB_word_score = "word_score_tweet_dic_table";  //The dictionaly for word change to score. [word_score_tweet_dic_table]
  $hoge->APPID = 'appid_here';  //yahoo apisのappid []


  echo $hoge->getscore('For analysis sentence');
  ```

Show score of 「For analysis sentence」 when This code run.




## license

Apache License    Version 2.0, January 2004    http://www.apache.org/licenses/
