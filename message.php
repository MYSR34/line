<?php
 
$accessToken = '+uMfnt06O8HDEgb/zTg4b096gBt7kqi4/y1YUHbKkLcTaBQZBzBbkZMsjMZAqygP0HWsd5XY1FENme7sKze4pPzu515mJlSnUsyCAQwX9qRR1cA4Dx3r6Bh4xa0HASgRtY6lGWp/SF8p5VoYWGipLgdB04t89/1O/w1cDnyilFU=';

//ユーザーからのメッセージ取得
$json_string = file_get_contents('php://input');
$json_object = json_decode($json_string);
 
//取得データ
$replyToken = $json_object->{"events"}[0]->{"replyToken"};        //返信用トークン
$message_type = $json_object->{"events"}[0]->{"message"}->{"type"};    //メッセージタイプ
$message_text = $json_object->{"events"}[0]->{"message"}->{"text"};    //メッセージ内容

//グループ追加時なら送る
if($json_object->{"events"}[0]->{"type"} === "join" && $json_object->{"events"}[0]->{"source"}->{"type"} === "group"){
    $return_message_text = 
        "困ったときのイベント提案くんです！\n".
        "「提案くん イベント教えて」といったように問いかけてくれれば、お答えします！\n".
        "どんなイベントを教えてほしいか登録することもできますよ！「提案くん 設定」で今すぐ送信！\n".
        "詳しくは「提案くん マニュアル」";
    sending_Message($accessToken, $replyToken, $return_message_text);
}
 
//メッセージタイプが「text」以外のときは何も返さず終了
if($message_type != "text") exit;

//テキスト内に特定の文字列を含んでいれば返信実行
if(strpos($message_text,'提案くん') !== false && strpos($message_text,'バイバイ') !== false) {
    //退出させる
    //goodbye($accessToken, $json_object->{"events"}[0]->{"source"}->{"groupId"});

    $groupID = $json_object->{"events"}[0]->{"source"}->{"groupId"};  //これは取れてる

    sending_Message($accessToken, $replyToken, "退出の実装方法が分かりません");

}else if(strpos($message_text,'提案くん') !== false && strpos($message_text,'マニュアル') !== false) {
    //マニュアルを送る
    sending_Manual($accessToken, $replyToken);
}else if(strpos($message_text,'提案くん') !== false && strpos($message_text,'設定') !== false) {
    //設定を送る
    sending_Setting($accessToken, $replyToken);
}else if(strpos($message_text,'提案くん') !== false && strpos($message_text,'イベント') !== false) {
    //イベントを送る
    $eventFlag = false;
    if(strpos($message_text,'1月15日') !== false) {
        $eventFlag = true;
    }
    sending_Events($accessToken, $replyToken, $eventFlag);
}

?>


<?php
//イベントの送信
function sending_Events($accessToken, $replyToken, $event_flag){
    //イベントのデータ
    $json;
    if($event_flag == true){
        $json = file_get_contents('./Event_1_15.json');
    }else{
        $json = file_get_contents('./Event.json');
    }
    $data = json_decode($json, true);
 
    //ポストデータ
    $post_data = [
        "replyToken" => $replyToken,
        "messages" => [$data]
    ];
 
    //curl実行
    $ch = curl_init("https://api.line.me/v2/bot/message/reply");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charser=UTF-8',
        'Authorization: Bearer ' . $accessToken
    ));
    $result = curl_exec($ch);
    curl_close($ch);
}
?>

<?php
//設定の送信
function sending_Setting($accessToken, $replyToken){
    //設定のデータ
    $json = file_get_contents('./Setting.json');
    $data = json_decode($json, true);
 
    //ポストデータ
    $post_data = [
        "replyToken" => $replyToken,
        "messages" => [$data]
    ];
 
    //curl実行
    $ch = curl_init("https://api.line.me/v2/bot/message/reply");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charser=UTF-8',
        'Authorization: Bearer ' . $accessToken
    ));
    $result = curl_exec($ch);
    curl_close($ch);
}
?>

<?php
//メッセージの送信
function sending_Message($accessToken, $replyToken, $return_message_text){
    //レスポンスフォーマット
    $response_format_text = [
        "type" => "text",
        "text" => $return_message_text
    ];
 
    //ポストデータ
    $post_data = [
        "replyToken" => $replyToken,
        "messages" => [$response_format_text]
    ];
 
    //curl実行
    $ch = curl_init("https://api.line.me/v2/bot/message/reply");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charser=UTF-8',
        'Authorization: Bearer ' . $accessToken
    ));
    $result = curl_exec($ch);
    curl_close($ch);
}
?>

<?php
//メッセージの送信
function sending_Manual($accessToken, $replyToken){
    //イベントのデータ
    $json = file_get_contents('./Manual.json');
    $data = json_decode($json, true);
 
    //ポストデータ
    $post_data = [
        "replyToken" => $replyToken,
        "messages" => [$data]
    ];
 
    //curl実行
    $ch = curl_init("https://api.line.me/v2/bot/message/reply");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charser=UTF-8',
        'Authorization: Bearer ' . $accessToken
    ));
    $result = curl_exec($ch);
    curl_close($ch);
}
?>

<?php
//退出
function goodbye($accessToken, $groupID){
    $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($accessToken);
    $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => 'f6b5c6ab06533de3d51871780eb4c6aa']);
    $response = $bot->leaveRoom($groupID);
    echo $response->getHTTPStatus() . ' ' . $response->getRawBody();
}
?>