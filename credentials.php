<?php

$header = "X-aws-ec2-metadata-token-ttl-seconds: 21600";
$get_token_ch = curl_init("http://169.254.169.254/latest/api/token");
curl_setopt($get_token_ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($get_token_ch, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($get_token_ch, CURLOPT_HTTPHEADER, array($header));
$token = curl_exec($get_token_ch);

if (curl_error($get_token_ch)) {
    print_r("Token Error");
    print curl_error($get_token_ch);
}

$get_user_role_name = curl_init("http://169.254.169.254/latest/meta-data/iam/info");
curl_setopt($get_user_role_name, CURLOPT_RETURNTRANSFER, true);
$token_header = $header = "X-aws-ec2-metadata-token: " . $token;
curl_setopt($get_user_role_name, CURLOPT_HTTPHEADER, array($token_header));
$role_name = curl_exec($get_user_role_name);
curl_close($get_user_role_name);
$role_name_parsed = json_decode($role_name);
if (curl_error($get_user_role_name)) {
    print_r("Role Error");
    print curl_error($get_user_role_name);
} else {
    $splitted = explode("/", $role_name_parsed->InstanceProfileArn);
    $target_role_name = $splitted[1];
}


$get_region = curl_init("http://169.254.169.254/latest/meta-data/hostname");
curl_setopt($get_region, CURLOPT_RETURNTRANSFER, true);
curl_setopt($get_region, CURLOPT_HTTPHEADER, array($token_header));
$region_raw = curl_exec($get_region);

if (curl_error($get_region)) {
    print_r("Token Error");
    print curl_error($get_region);
} else {
    $b = explode(".", $region_raw);
    $aws_region = $b[1];
    print_r('AWS Region:'. $aws_region);
}

$get_credentials = curl_init("http://169.254.169.254/latest/meta-data/iam/security-credentials/" . $target_role_name);
curl_setopt($get_credentials, CURLOPT_RETURNTRANSFER, true);
curl_setopt($get_credentials, CURLOPT_HTTPHEADER, array($token_header));
$credentials_raw = curl_exec($get_credentials);
curl_close($get_credentials);

if (curl_error($get_credentials)) {
    print_r("CredentialsError");
    print curl_error($get_credentials);
} else {
    print_r($credentials_raw);
    $credentials = json_decode($credentials_raw);
}


putenv('AWS_DEFAULT_REGION=' . $aws_region);
putenv('AWS_ACCESS_KEY_ID=' . $credentials->AccessKeyId);
putenv('AWS_SECRET_ACCESS_KEY=' . $credentials->SecretAccessKey);