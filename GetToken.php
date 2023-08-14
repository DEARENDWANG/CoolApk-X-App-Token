<?php

function getV2Token($deviceCode) {
    $tokenPart1 = "token://com.coolapk.market/dcf01e569c1e3db93a3d0fcf191a622c?";
    $deviceCodeMd5 = md5($deviceCode);
    $timestamp = time();
    $timestampMd5 = md5($timestamp);
    $timestampBase64 = str_replace(['\\r', '\\n', '='], '', base64_encode($timestamp));
    $token = $tokenPart1 . $timestampMd5 . "$" . $deviceCodeMd5 . "&com.coolapk.market";
    $tokenBase64 = str_replace(['\\r', '\\n', '='], '', base64_encode($token));
    $tokenBase64Md5 = md5($tokenBase64);
    $tokenMd5 = md5($token);
    $arg = "\$2y\$10\$$timestampBase64/$tokenMd5";
    $salt = substr($arg, 0, 31) . 'u';
    $crypt = crypt($tokenBase64Md5, $salt);
    $cryptBase64 = base64_encode($crypt);
    return "v2" . $cryptBase64;
}

$deviceCode = "X-App Device Info";
$v2Token = getV2Token($deviceCode);

$output = array(
    "X-App-Token" => $v2Token
);

// 设置适当的响应头，指示返回 JSON 数据
header('Content-Type: application/json');
$jsonOutput = json_encode($output, JSON_PRETTY_PRINT);
echo $jsonOutput;

?>
