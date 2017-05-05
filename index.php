<?php
 define(APPID, 'YahooAPIsのトークンをここに入れてください。') ?>
<!doctype hmtl>
<html>
<head>
  <meta charset="utf-8" />
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
    div#credit{
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
    <form class="a" method="post" action="index.php" >
      <input class="input-form" type="text" name="hoge"/>
      <input class="input-button" type="submit"  />
      <input type="checkbox" name="example" value="表示" onclick="checkdiv(this,'checkBox')">処理ログの表示
    </form>
  </div>
<!--=====送信フォームここまで=====-->
<?php
function get_Text()
{
    global $text;
    $text = $_POST['hoge'];
}; function getFlow()
 {
     global $sentence_flow;
     global $text;
     $appid = APPID;
     $url = 'http://jlp.yahooapis.jp/DAService/V1/parse?appid='.$appid.'&sentence='.urlencode($text);
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
         echo $i.'番<br>';
         echo $sentence_flow[$i]['value'].'<br>';
         echo 'to'.$sentence_flow[$i]['to'].'<br>';
         echo 'score'.$sentence_flow[$i]['score'].'<br>';
         echo '<br>';
     }
 } function getWord()
 {
     global $sentence_word;
     global $text;
     $sentence_word = array();
     $i = 0;
     $appid = APPID;
     $url = 'http://jlp.yahooapis.jp/MAService/V1/parse?appid='.$appid.'&results=ma';
     $url .= '&sentence='.urlencode($text);
     $xml = simplexml_load_file($url);
     foreach ($xml->ma_result->word_list->word as $value) {
         $sentence_word[$i]['word'] = (string) $value->surface;
         $sentence_word[$i]['score'] = 0;
         ++$i;
     }
 } function getDic()
 {
     global $sentence_word;
     foreach ($sentence_word as $key => $word) {
         for ($i = 1;$i <= 6;++$i) {
             $openFile = 'dic_'.$i.'.txt';
             $file = fopen($openFile, 'r');
             if ($file) {
                 while ($line = fgets($file)) {
                     preg_match('/^([ぁ-んァ-ヶー一-龠]+)＠/u', $line, $preg);
                     $search_dic_word = $preg[1];
                     if ($sentence_word[$key]['score'] == 0) {
                         if ($sentence_word[$key]['word'] == $search_dic_word) {
                             $mozime = strpos($line, '＠');
                             preg_match("/＠([0-9]\.[0-9]+)/", $line, $retArr);
                             $sentence_word[$key]['score'] = (float) $retArr[1];
                             break;
                         } else {
                         }
                     }
                 }
             } else {
                 echo '＜＜エラー＞＞辞書ファイルが開けませんでした。';
             }
         }
     }
     fclose($file);
 } function getResult()
 {
     global $sentence_flow;
     global $sentence_word;
     global $sentence_result;
     echo '<pre>';
     echo '$sentence_flow==';
     echo var_dump($sentence_flow);
     echo '</pre>';
     echo '<pre>';
     echo '$sentence_word==';
     echo var_dump($sentence_word);
     echo '</pre>';
     $m = count($sentence_flow) - 1;
     $n = count($sentence_word) - 1;
     $ii = 0;
     $wordSum = 0;
     $wordCount = 0;
     for ($i = 0;$i <= $m;++$i) {
         $flow = $sentence_flow[$i]['value'];
         for (;$ii <= $n;++$ii) {
             $word = $sentence_word[$ii]['word'];
             echo '###'.$word.'###';
             if ($ii == $n) {
                 $wordSum += $sentence_word[$ii]['score'];
                 ++$wordCount;
             }
             echo '<br />'.$flow.'=!='.$word;
             $III = $i + 1;
             echo '  //文節数='.$III.'  //単語数='.$ii;
             ++$wordCount;
             $wordSum += $sentence_word[$ii]['score'];
             if (strpos($flow, $word) === false || $ii == $n) {
                 $sentence_flow[$i]['score'] = $wordSum / $wordCount;
                 $wordCount = 0;
                 $wordSum = 0;
                 ++$ii;
                 break;
             }
             echo '    $wordCount='.$wordCount.'      $wordSum='.$wordSum.'      $i='.$i.'<br />';
         }
         echo '<br />「'.$flow.'」の点数は'.$sentence_flow[$i]['score'].'<br /><br />';
     }
     echo '<pre>';
     var_dump($sentence_flow);
     echo '</pre>';
 } function getResult2()
 {
     global $sentence_flow;
     global $sentence_result;
     foreach ($sentence_flow as $key => $value) {
         if ($sentence_flow[$key]['to'] == -1) {
             $keySave = $key;
             $flow_tree[0]['toNum'] = $sentence_flow[$key];
             break;
         }
     }
     $flowSum = 0;
     $flowSumCount = 1;
     foreach ($sentence_flow as $key => $value) {
         if ($sentence_flow[$key]['to'] == $keySave) {
             $flowSum = $sentence_flow[$key]['score'];
             $search_2 = $key;
             foreach ($sentence_flow as $key_2 => $value_2) {
                 if ($sentence_flow[$key_2]['to'] == $search_2) {
                     ++$flowSumCount;
                     $flowSum += $sentence_flow[$key_2]['score'];
                     break;
                 }
             }
             $flowAve[$key] = (float) $flowSum / (float) $flowSumCount;
             $flowSumCount = 1;
         }
     }
     foreach ($flowAve as $key_3 => $value_3) {
         $sum += $value_3;
     }
     $sum = $sum / count($flowAve);
     $sum += $sentence_flow[$keySave]['score'];
     $sentence_result = $sum / 2;
 } function showResult()
 {
     global $text;
     global $sentence_result;
     echo '</div><div id="result"><font size=60>';
     echo '「'.$text.'」の感情スコアは'.$sentence_result.'点です。';
     echo '</font></div>';
 } get_Text(); getFlow(); getWord(); getDic(); getResult(); getResult2(); showResult(); echo '<div id="credit"><pre>'; echo '＜クレジット＞
Hiroya Takamura, Takashi Inui, Manabu Okumura,
"Extracting Semantic Orientations of Words using Spin Model",
In Proceedings of the 43rd Annual Meeting of the Association for Computational Linguistics (ACL2005) ,
pages 133--140, 2005.
<!-- Begin Yahoo! JAPAN Web Services Attribution Snippet --></pre>
<a href="http://developer.yahoo.co.jp/about">
<img src="http://i.yimg.jp/images/yjdn/yjdn_attbtn1_88_35.gif" width="88" height="35" title="Webサービス by Yahoo! JAPAN" alt="Web Services by Yahoo! JAPAN" border="0" style="margin:15px 15px 15px 15px"></a>
<!-- End Yahoo! JAPAN Web Services Attribution Snippet -->
<img src="/QR.gif" width="200px" height="200px"/>
</pre></div>';
