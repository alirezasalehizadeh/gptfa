<?php

require_once 'vendor/autoload.php';

// ###############################################################
//  Telegram Input
// ###############################################################

$telegramInput = json_decode(file_get_contents("php://input"), TRUE);
$chatId = $telegramInput["message"]["chat"]["id"];
$message = $telegramInput["message"]["text"];


// ###############################################################
//  Chatgpt Request
// ###############################################################

$apiKey = "";

$chatgptRequest = new \GuzzleHttp\Client;
$chatgptResponse = $chatgptRequest->request("POST", "https://api.openai.com/v1/chat/completions",[
    'headers' => [
        'Authorization' => "Bearer {$apiKey}",
    ],
    'json' => [
        'model' => 'gpt-3.5-turbo',
        'messages' => [[
            'role' => 'user',
            'content' => $message,
        ]]
    ]
]);

$jsonChatgptResponse = json_decode($chatgptResponse->getBody());
$chatgptResult = $jsonChatgptResponse->choices[0]->message->content;

// ###############################################################
//  Telegram Response
// ###############################################################

$telegramRequest = new \GuzzleHttp\Client;
$telegramRequest->request("POST", "https://api.telegram.org/bot<token>/sendmessage",[
    'query' => ['chat_id' => $chatId, 'text' => $chatgptResult]
]);
