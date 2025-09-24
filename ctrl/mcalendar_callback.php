<?php
include "../includes/common.php";


//$_SESSION['ADMIN_CALENDAR_SETUP'] = true;

$client_id = 'cd7591fd-db33-4acf-b3cf-8bdd9031d685';
$client_secret = 'JCz8Q~eWZ1_GV3V.mOSlolgep.NdnUJTJt.2UaQE';
$tenant_id = 'common'; // or your specific tenant id
$redirect_uri = 'https://thequotemasters.com/ctrl/mcalendar_callback.php';
$access_token = '';
$refresh_token = '';
if (isset($_GET['code'])) {
    $code = $_GET['code'];

    $token_url = "https://login.microsoftonline.com/$tenant_id/oauth2/v2.0/token";

    $post_fields = [
        'client_id' => $client_id,
        'scope' => 'https://graph.microsoft.com/Calendars.ReadWrite offline_access',
        'code' => $code,
        'redirect_uri' => $redirect_uri,
        'grant_type' => 'authorization_code',
        'client_secret' => $client_secret,
    ];

    $ch = curl_init($token_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_fields));

    $response = curl_exec($ch);
    $token_data = json_decode($response, true);
    curl_close($ch);

    if (isset($token_data['access_token'])) {
         $access_token = $token_data['access_token'];
        $refresh_token = $token_data['refresh_token'];
        // DFA($_SESSION);
        if (isset($_SESSION['ADMIN_CALENDAR_SETUP'])) {
            //UPDATE `ops_calendar_config` SET `vAccessToken`='[value-1]',`vRefreshToken`='[value-2]' WHERE 1
            //INSERT INTO `m_calendar_config`(`vAccessToken`, `vRefreshToken`) VALUES ('[value-1]','[value-2]')
            sql_query("delete from m_calendar_config");
            sql_query("insert into m_calendar_config(vAccessToken, vRefreshToken) values('$access_token', '$refresh_token')");
            $_SESSION[PROJ_SESSION_ID]->success_info = "Microsoft Calendar setup successfully Done!!";
            header('location:home.php');
            exit;
        }


        $activity_txt = "Successfully completed the Microsoft Calendar setup";
        AddSPActivity($sess_user_id, 4, $ACTIVITY_TIMELINE_ARR[4], "app_sp", $activity_txt, "U");

        //SP USER
        sql_query("update service_providers set vRefreshToken='$refresh_token' ,vAccessToken='$access_token' where id='$sess_user_id' ");
        $_SESSION[PROJ_SESSION_ID]->success_info = "Microsoft Calendar setup successfully Done!!";
        header('location:v_profile.php');
        sql_close();
        exit;

       // DFA($token_data);
        //exit;
        //header("Location: create_event.php");
       // exit;
    } else {
        //echo "Error fetching token";
        $_SESSION[PROJ_SESSION_ID]->error_info = "Error fetching token!!";
        header('location:v_profile.php');
        sql_close();
        exit;
    }
}



