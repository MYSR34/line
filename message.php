<?php
 
$accessToken = '+uMfnt06O8HDEgb/zTg4b096gBt7kqi4/y1YUHbKkLcTaBQZBzBbkZMsjMZAqygP0HWsd5XY1FENme7sKze4pPzu515mJlSnUsyCAQwX9qRR1cA4Dx3r6Bh4xa0HASgRtY6lGWp/SF8p5VoYWGipLgdB04t89/1O/w1cDnyilFU=';

//ユーザーからのメッセージ取得
$json_string = file_get_contents('php://input');
$json_object = json_decode($json_string);
 
//取得データ
$replyToken = $json_object->{"events"}[0]->{"replyToken"};        //返信用トークン
$message_type = $json_object->{"events"}[0]->{"message"}->{"type"};    //メッセージタイプ
$message_text = $json_object->{"events"}[0]->{"message"}->{"text"};    //メッセージ内容

//グループ追加時に送る
if($json_object->{"events"}[0]->{"type"} === "join" && $json_object->{"events"}[0]->{"source"}->{"type"} === "group"){
    sending_Setting($accessToken, $replyToken, $message_type, $return_message_text);
}
 
//メッセージタイプが「text」以外のときは何も返さず終了
if($message_type != "text") exit;

//テキスト内に特定の文字列を含んでいれば返信実行
if(strpos($message_text,'提案くん') !== false && strpos($message_text,'イベント') !== false) {
    
    //返信実行
    sending_Events($accessToken, $replyToken, $message_type, $return_message_text);
    line://app/{liffId}
    
}

if(strpos($message_text,'提案くん') !== false && strpos($message_text,'設定') !== false) {
    
    //返信実行
    sending_Setting($accessToken, $replyToken, $message_type, $return_message_text);
    
}

?>


<?php
//メッセージの送信
function sending_Events($accessToken, $replyToken, $message_type, $return_message_text){
    //レスポンスフォーマット

    $json = file_get_contents('./Event.json');

    $data = json_decode($json, true);

    $response_format_text = [
        "type" => $message_type,
        "text" => $return_message_text
    ];
 
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
function sending_Setting($accessToken, $replyToken, $message_type, $return_message_text){
    //レスポンスフォーマット

    $json = file_get_contents('./Setting.json');

    $data = json_decode($json, true);

    $response_format_text = [
        "type" => $message_type,
        "text" => $return_message_text
    ];
 
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