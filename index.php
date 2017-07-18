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
  <script type="text/javascript">
  function checkdiv( obj,id ) {
    if( obj.checked ){
      document.getElementById("log").style.display = "block";
    }
    else {
      document.getElementById("log").style.display = "none";
    }
  }
  </script>
</head>
<!--=====送信フォームここから=====-->
<body>
  <div id="form">
    <form class="a" method="post" action="index.php">
      <input class="input-form" type="text" name="hoge" />
      <input class="input-button" type="submit" />
      <input type="checkbox" name="example" value="表示" onclick="checkdiv(this,'checkBox')">処理ログの表示
    </form>
  </div>
<!--~~~~~~送信フォームここまで~~~~~~-->
<div id="result">
  <?php
    include('./lib/tukushi.php');


    if(isset($_POST['hoge'])){
      echo "「{$_POST['hoge']}」<br>";
    $hoge = new tukushi();
      $hoge->DB_host = "mysql";
      $hoge->DB_user = "root";
      $hoge->DB_pass = "password";
      $hoge->APPID = 'dj0zaiZpPWxRWUFsSjVBbzM4UCZzPWNvbnN1bWVyc2VjcmV0Jng9M2U-';
      $hoge->DB_name = 'kaken';
      $hoge->DB_word_score = 'word_score_dic_table';
      $hoge_r = $hoge->getscore($_POST['hoge']);
      echo "[{$hoge->DB_word_score}]:{$hoge_r}<br>";

      $hoge->DB_word_score = 'word_score_tweet_dic_table';
      $hoge_r = $hoge->getscore($_POST['hoge']);
      echo "[{$hoge->DB_word_score}]:{$hoge_r}";
    }

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
