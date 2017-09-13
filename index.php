<!--
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
 -->
<!doctype hmtl>
<!--1.入力された文章を変数($text)へ代入する-->
<html>
<head>
  <meta charset="utf-8" />
  <title>感情解析v4</title>
  <style type="text/css">
    div#form{
      float: left;
      background-color: #b2b2ff;
      padding: 20px;
      margin:10px,20px,0px,20px;
      display: block;
      width: 80%;
    }
    .input-form{
      width: 70%;
      height: 30px;
      font-size: 100%;
    }
    .input-botton{
      height: 30px;
    }
    div#log{
      float: left;
      background-color: #b2ffb2;
      padding: 20px;
      margin:10px,20px,0px,20px;
      display: none;
      width:80%;
    }
    div.credit{
      display: block;
      background-color: #d8ffb2;
      padding: 20px;
      margin:10px,20px,0px,20px;
      float: left;
      width: 80%;
    }
    div#result{
      display: block;
      padding: 20px;
      margin:10px,20px,0px,20px;
      background: #ffd8b2;
      float: left;
      width: 80%;
    }
    pre.var-dump{
      margin:0 0 0 2em;
    }
    b.result{
      font-size: 1.5em;
    }
  </style>
  <!-- <script type="text/javascript">
  function checkdiv( obj,id ) {
    if( obj.checked ){
      document.getElementById("log").style.display = "block";
    }
    else {
      document.getElementById("log").style.display = "none";
    }
  }
  </script> -->
</head>
<!--=====送信フォームここから=====-->
<body>
  <div id="form">
    <form class="a" method="post" action="index.php">
      <input class="input-form" type="text" name="sentence" />
      <input class="input-button" type="submit" />
      <!-- <input type="checkbox" name="example" value="表示" onclick="checkdiv(this,'checkBox')">処理ログの表示 -->
    </form>
  </div>
<!--~~~~~~送信フォームここまで~~~~~~-->
<div id="result">
  <?php
    echo "「{$_POST['sentence']}」<br />";
    include('./lib/Tsukushi.php');

    $hoge = new Tsukushi([
      'DB_host' => 'mysql',
      'DB_user' => 'root',
      'DB_pass' => 'password',
      'DB_name' => 'kaken',
      'APPID' => '',
      'DB_word_score' => 'word_score_dic_table'
    ]);
    echo "[{$hoge->DB_word_score}]: ".$hoge->getscore($_POST['sentence'])."<br>";

    $huga = new Tsukushi([
      'DB_host' => 'mysql',
      'DB_user' => 'root',
      'DB_pass' => 'password',
      'DB_name' => 'kaken',
      'APPID' => '',
      'DB_word_score' => 'word_score_tweet_dic_table'
    ]);
    echo "[{$huga->DB_word_score}]: ".$huga->getscore($_POST['sentence'])."<br>";


  ?>
</div>

<div class="credit">
	<h2>Credit</h2>
    <p style="margin-left: 15px;float: left;">
    Hiroya Takamura, Takashi Inui, Manabu Okumura,<br>
    "Extracting Semantic Orientations of Words using Spin Model",<br>
    In Proceedings of the 43rd Annual Meeting of the Association for Computational Linguistics (ACL2005) ,<br>
    pages 133--140, 2005.
    </p>
    <!-- Begin Yahoo! JAPAN Web Services Attribution Snippet -->
   	<p style="float: left";>
    <a href="http://developer.yahoo.co.jp/about">
        <img src="http://i.yimg.jp/images/yjdn/yjdn_attbtn1_88_35.gif" width="88" height="35" title="Webサービス by Yahoo! JAPAN" alt="Web Services by Yahoo! JAPAN" border="0" style="margin:15px 15px 15px 15px">
    </a>
    </p>
    <!-- End Yahoo! JAPAN Web Services Attribution Snippet -->
    <p style="margin-left: 15px;padding-top: 15px" style="float: left;">
    	IPAdictionary　IPA情報処理推進機構
    </p>
</div>
