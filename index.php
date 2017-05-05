<?php
function init()
{
    define('DB_HOST', 'mysql.dev');         // SQLのホスト名を入力してください
    define('DB_PORT', '3306');              // SQLのポート番号を指定してください
    define('DB_USER', 'root');              // SQLのユーザ名を入力してください
    define('DB_PASSWORD', 'example');       // SQLのパスワードを入力してください
    define('DB_NAME', 'kaken28_db');　　     // DB名を入力してください
    define('APP_ID', 'yahooapisのトークンを入力してください。');   // yahooapiのトークンを入力してください
    define('WORD_CONVERT_DIC', 'word_convert_dic_table');
    define('WORD_SCORE_DIC', 'word_score_tweet_dic_table');
};

?>
<!doctype hmtl>
<!--1.入力された文章を変数($text)へ代入する-->
<html>
<head>
  <meta charset="utf-8" />
  <title>感情解析v3</title>
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
<?php
function get_Text()
{
    global $text;
    echo '<div id="log">';
    echo 'DB info { WORD_CONVERT_DIC = '.WORD_CONVERT_DIC.' } <br />';
    echo 'DB info { WORD_SCORE_DIC = '.WORD_SCORE_DIC.' } <br />';
    echo 'START = getText()<br />';
    $text = $_POST['hoge'];
    echo '>> 受け取った文字列 : '.$text.'<br />';
    echo 'END = getText()<br /><br />';
}; function getFlow()
 {
     echo 'START = getFlow()<br />';
     global $sentence_flow;
     global $text;
     $url = 'http://jlp.yahooapis.jp/DAService/V1/parse?appid='.APP_ID.'&sentence='.urlencode($text);
     $xml = simplexml_load_file($url);
     $seg = null;
     $chunks = $xml->Result->ChunkList->Chunk;
     foreach ($chunks as $chunk) {
         $id = (int) $chunk->Id;
         $dependency = (int) $chunk->Dependency;
         $morphems = $chunk->MorphemList->Morphem;
         foreach ($morphems as $morphem) {
             $seg = $seg.$morphem->Surface;
         }
         $sentence_flow[$id]['to'] = $dependency;
         $str[$id][$dependency] = $seg;
         $seg = '';
     }
     for ($i = 0;$i < count($str);++$i) {
         $key = array_keys($str[$i]);
         $sentence_flow[$i]['value'] = $str[$i][$key[0]];
         $sentence_flow[$i]['score'] = 0;
         $sentence_flow[$i]['layer'] = 0;
         echo '>> '.$i.'番 ';
         echo $sentence_flow[$i]['value'].' ';
         echo 'to'.$sentence_flow[$i]['to'].'<br>';
     }
     echo 'END = getFlow()<br /><br />';
 } function getWord()
 {
     echo 'START = getWord()<br />';
     global $sentence_word;
     global $text;
     $sentence_word = array();
     $i = 0;
     $url = 'http://jlp.yahooapis.jp/MAService/V1/parse?appid='.APP_ID.'&results=ma';
     $url .= '&sentence='.urlencode($text);
     $xml = simplexml_load_file($url);
     foreach ($xml->ma_result->word_list->word as $value) {
         $sentence_word[$i]['word'] = (string) $value->surface;
         $sentence_word[$i]['score'] = 0;
         ++$i;
     }
     function intoFlowScore()
     {
         global $sentence_flow;
         global $sentence_word;
         $i = 0;
         foreach ($sentence_flow as $key => $value) {
             for ($M = count($sentence_word);$i < $M; ++$i) {
                 echo '>> '.$value['value'].'====='.$sentence_word[$i]['word'];
                 if (strpos($value['value'], $sentence_word[$i]['word']) !== false) {
                     $sentence_word[$i]['flow'] = $key;
                     echo '  HIT!!<br />';
                 } else {
                     echo '  next<BR />';
                     break;
                 }
             }
         }
     }
     intoFlowScore();
     echo 'END = getWord()<br /><br />';
 } function getWordConvert()
 {
     function getWordConvertDebug()
     {
         global $sentence_word;
         echo '<table bordercolor="#000" boder="1"><tr>';
         for ($MAX = count($sentence_word), $i = 0; $i < $MAX; ++$i) {
             echo '<td>'.$i.'</td>';
         }
         echo '</tr><tr>';
         foreach ($sentence_word as $value) {
             echo '<td>'.$value['word'].'</td>';
         }
         echo '</tr></table>';
     }
     getWordConvertDebug();
     echo 'START = getWordConvert()';
     global $sentence_word;
     $DB_TABLE = WORD_CONVERT_DIC;
     $dbhost = 'mysql:host='.DB_HOST.';dbname='.DB_NAME.';port='.DB_PORT.';charset=utf8';
     try {
         $pdo = new PDO($dbhost, DB_USER, DB_PASSWORD);
         $search_sql = ' WHERE ';
         foreach ($sentence_word as $key => $word) {
             if ($key == 0) {
                 $search_sql .= "changed='".$word['word']."' ";
             } else {
                 if (mb_strlen($word['word']) == 2) {
                     $search_sql .= "OR changed='".$word['word']."' ";
                 }
             }
         }
         $sql = 'SELECT * FROM '.$DB_TABLE.$search_sql;
         $statement = $pdo->query($sql);
         $dataBody = $statement->fetchAll(PDO::FETCH_ASSOC);
         $pdo = null;
     } catch (PDOException $e) {
         echo 'Error:'.$e->getMessage();
     }
     for ($i = 0, $SENTENCE_WORD_LEN = count($sentence_word);$SENTENCE_WORD_LEN > $i;++$i) {
         foreach ($dataBody as $DBrow) {
             if ($sentence_word[$i]['word'] == $DBrow['changed']) {
                 $sentence_word[$i]['word'] = $DBrow['origin'];
             }
         }
     }
     getWordConvertDebug();
     echo 'END getWordConvert()<br /><br />';
 } function getDicSql()
 {
     echo 'START = getDicSql()<br />';
     function getDicSpl_word()
     {
         global $sentence_word;
         $DB_TABLE = WORD_SCORE_DIC;
         $dbhost = 'mysql:host='.DB_HOST.';dbname='.DB_NAME.';port='.DB_PORT.';charset=utf8';
         try {
             $pdo = new PDO($dbhost, DB_USER, DB_PASSWORD);
             $search_sql = ' WHERE ';
             foreach ($sentence_word as $key => $word) {
                 if ($key == 0) {
                     $search_sql .= "word='".$word['word']."' ";
                 } else {
                     $search_sql .= "OR word='".$word['word']."' ";
                 }
             }
             $sql = 'SELECT * FROM '.$DB_TABLE.$search_sql;
             $statement = $pdo->query($sql);
             $dataBody = $statement->fetchAll(PDO::FETCH_ASSOC);
             $pdo = null;
         } catch (PDOException $e) {
             echo 'Error:'.$e->getMessage();
         }
         for ($i = 0, $SENTENCE_WORD_LEN = count($sentence_word) - 1;$i <= $SENTENCE_WORD_LEN;++$i) {
             foreach ($dataBody as $value) {
                 if ($sentence_word[$i]['word'] == $value['word']) {
                     $sentence_word[$i]['score'] = (float) $value['score'] + 0.0;
                 }
             }
         }
     }
     function getDicSpl_flow()
     {
         global $sentence_flow;
         global $sentence_word;
         $sum = [];
         $count = [];
         foreach ($sentence_flow as $key1 => $value1) {
             foreach ($sentence_word as $value2) {
                 if ($value2['flow'] == $key1) {
                     $sum[$key1] += $value2['score'];
                     ++$count[$key1];
                 }
             }
             foreach ($count as $key3 => $value3) {
                 $sentence_flow[$key1]['score'] = $sum[$key3] / $value3;
             }
         }
     }
     getDicSpl_word();
     getDicSpl_flow();
     echo 'END = getDicSql()<br /><br />';
 } function getResultNode()
 {
     global $sentence_flow;
     global $sentence_result;
     global $currentLayer;
     global $rootNodeNum;
     echo 'START = SearchRootNode()<br />';
     $currentLayer = 1;
     $rootNodeNum = 0;
     foreach ($sentence_flow as $key1 => $value1) {
         if ($value1['to'] == -1) {
             $rootNodeNum = $key1;
             $CurrentNodeNum = $rootNodeNum;
             $sentence_flow[$rootNodeNum]['layer'] = $currentLayer;
         }
     }
     echo '>> ROOT NODE ='.$rootNodeNum.'<br />>> ';
     echo var_dump($sentence_flow[$rootNodeNum]).'<br />END = SearchRootNode<br />';
     function searchConectNode($F_CurrentNodeNum)
     {
         global $sentence_flow;
         global $currentLayer;
         echo 'START FUNCTION = searchConectNode()<br />';
         foreach ($sentence_flow as $key2 => $value2) {
             if ($value2['to'] == $F_CurrentNodeNum) {
                 ++$currentLayer;
                 $sentence_flow[$key2]['layer'] = $currentLayer;
                 echo '>> '.$F_CurrentNodeNum.'<='.$key2.'<br />'.'>> $sentence_flow[key] key='.$key2.'  ';
                 echo var_dump($sentence_flow[$key2]).'<br />';
                 echo 'END FUNCTION = searchConectNode<br /><br />';
                 searchConectNode($key2);
             }
         }
         $foundEndNodeFlag = true;
         --$currentLayer;
         echo '>> NOT Found Child currentNode='.$F_CurrentNodeNum.'<br />';
     }
     searchConectNode($CurrentNodeNum);
 } function score()
 {
     global $sentence_flow;
     global $sentence_result;
     global $rootNodeNum;
     function getMaxLayer($toArray, $option)
     {
         $MAX_Layer = 0;
         foreach ($toArray as $value) {
             if ($value['layer'] > $MAX_Layer) {
                 $MAX_Layer = $value['layer'];
             }
         }
         switch ($option) { case 1: return (int) $MAX_Layer; break; case 2: foreach ($toArray as $key => $value) {
     if ($value['layer'] == $MAX_Layer) {
         return (int) $key;
     }
 } break; default: echo 'getMaxLayer関数にoptionを指定してください。'; break; }
     }
     function getNodeFamily($toArray, $currentNode, $option)
     {
         switch ($option) { case 1: return $toArray[$currentNode]['to']; break; case 2: $motherNode = $toArray[$currentNode]['to']; $i = 0; foreach ($toArray as $key => $value) {
     if ($value['to'] == $motherNode) {
         $returnArray[$i] = $key;
         ++$i;
     }
 }

return $returnArray; break; default: echo 'getNodeFamily関数のオプションを確認してください'; break; }
     }
     function forDebug()
     {
         global $sentence_flow;
         global $sentence_result;
         for ($i = count($sentence_flow), $j = 0;$j < $i;++$j) {
             $sentence_flow[$j]['score'] = rand(0, 100) / 100;
         }
     }
     $currentNodeNum = getMaxLayer($sentence_flow, 2);
     $flag = false;
     while (!$flag) {
         $sibling = getNodeFamily($sentence_flow, $currentNodeNum, 2);
         $result = 0;
         for ($node_size = count($sibling), $i = 0;$i < $node_size;++$i) {
             $j = $sibling[$i];
             $result += $sentence_flow[$j]['score'];
         }
         echo '>> sentence_flow['.$currentNodeNum.']の兄弟は'.$node_size.'個 * * 合計='.$result.'点 * ';
         $result = $result / $node_size;
         echo '平均='.$result.'点<br />';
         $motherNodeNum = getNodeFamily($sentence_flow, $currentNodeNum, 1);
         $sentence_flow[$motherNodeNum]['score'] = ($sentence_flow[$motherNodeNum]['score'] + $result) / 2;
         for ($node_size = count($sibling), $i = 0;$i < $node_size;++$i) {
             $j = $sibling[$i];
             $sentence_flow[$j]['layer'] = 0;
         }
         $currentNodeNum = getMaxLayer($sentence_flow, 2);
         if ($currentNodeNum == $rootNodeNum) {
             $flag = true;
             $sentence_result = $sentence_flow[$rootNodeNum]['score'];
         }
     }
 } function showResult()
 {
     global $text;
     global $sentence_result;
     global $sentence_flow;
     echo '</div><div id="result">';
     echo '<b class="result">「'.$text.'」</b><br />の感情スコアは<b class="result">'.$sentence_result.'点</b>で';
     if ($sentence_result >= 0) {
         echo '<b class="result">ポジティブな文章と判断されました。</b>';
     } else {
         echo '<b class="result">ネガティブな文章と判断されました。</b>';
     }
     echo '</div>';
 } function echo_dump($var)
 {
     echo '>>>>>>>>>><pre class="var-dump">';
     echo var_dump($var);
     echo '</pre>>>>>>>>>>><br />';
 } init(); get_Text(); if ($text != '') {
     getFlow();
     getWord();
     getWordConvert();
     getDicSql();
     echo_dump($sentence_word);
     echo_dump($sentence_flow);
     getResultNode();
     score();
     showResult();
 } else {
     echo '</div><div id="result">';
     echo '上のフォームに解析したい文章を入力してください。';
     echo '</div>';
 } echo '</div>';?>

<div class="credit">
	<h2>Credit</h2>
    <p style="margin-left: 15px;float: left;">
    Hiroya Takamura, Takashi Inui, Manabu Okumura,<br>
    "Extracting Semantic Orientations of Words using Spin Model",<br>
    In Proceedings of the 43rd Annual Meeting of the Association for Computational Linguistics (ACL2005) ,<br>
    pages 133--140, 2005.
    </p>
    <!-- Begin Yahoo! JAPAN Web Services Attribution Snippet -->
   	<p style="float: left;>
    <a href="http://developer.yahoo.co.jp/about">
        <img src="http://i.yimg.jp/images/yjdn/yjdn_attbtn1_88_35.gif" width="88" height="35" title="Webサービス by Yahoo! JAPAN" alt="Web Services by Yahoo! JAPAN" border="0" style="margin:15px 15px 15px 15px">
    </a>
    </p>
    <!-- End Yahoo! JAPAN Web Services Attribution Snippet -->
    <p style="margin-left: 15px;padding-top: 15px" style="float: left;">
    	IPAdictionary　IPA情報処理推進機構
    </p>
</div>
