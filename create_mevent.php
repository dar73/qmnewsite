<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common.php');
// session_start();

// if (!isset($_SESSION['access_token'])) {
//     die("Please login first.");
// }

// Replace this with a valid access token obtained via OAuth2 authentication flow
$access_token = "EwBYBMl6BAAUBKgm8k1UswUNwklmy2v7U/S+1fEAARJf2havXnz8pwj7rhdmGm4Ct26iOloN8j7Wbhv/5NMsFTufDf1o6PHOlfT0kpIoz5PvIgW/lXCTMXKskFz9qpMZd0NB2Zp1EgvWvu3+Xz7W5kpFu333X2feri7oRFOiALf4pfUwkvJr3KxCcDXcNwndK3DizNNSZwc2DKeFDQHz7u0nkwWS8pm+xoeyHTQIXuzCMHiPa1wexMj+KcqY1Vy4400Hz7rbRRghXXs7xz1QHIeJy7OEv8b03q9uV/gIfR7n22YOn16MZSN3aI5YVBSuObYk2qS4W6uxIhAkWd+YXNCAZBhjMauHP/g95ykL984fQrvkprENPW/6aH2yLWUQZgAAEIP1Trd1mouKKaw0VzCKL+0gAwyGTQAmyFxO9u4dYeBozhHGD5+zVSMDioK+wLaTQsAT0F7i/CnnHDO15IVOZdLFbRxQZl784l0dc1+61KsbH5/qezrtAiBUdXAxKZxM4EwQWlD5XXVmrNALWK6hALYKpNon135T2P0ba3iNTaxi9CBv0vkJ1z4DQAJSf0aErbNpUbZjGAqfIc59oFeh/kXG9Bn77BDcHAoWcDrJVBV+6wPGcF7E/tnedNIVPyYgKS0kudORzQsE0JgzBxse9JpRVIi261gg1U+PqvP2YG5CPHEEr//WI86ID7x0h0ibid5F9B+lFVtWh1Nz6BewdiWHlJPVvv9ki5CIEmDCGVAIRdwIGpxF4MIRv2xspqeJu6SmGZ/rDl5YzijYZTtzQm8VwVW1IDSrdUcbhYNbSr2fIbzritt3x3Nztlk7iJ4ghYlNN2VE8NvMp9aAnHIIa3M8CZREW4lYI3tyIPrFTgGkHgSVQF9lJBCHyaV03AG0QE2ZINJ+LXTGtVKI8e94LcraAWLlmNTBKunJvjx2+ABkL8kXGApRAGy6jNYjDnjVm9aXpP/eXoZSrStUd8KVo61xbwoc1zC+uMN4RarO2VUhvDXckFIZq4gZuOtOMgTcgD+KTgTk84T0onwutBAw6IS3xphJ55W1h1NR2iapeNAekRDcosV98oaNudOnmFK2c7ci10VnwqaNpZs2pSUWzGV/Wnn1ildEywaHuNmlFXlKW2lPBhZQnnPfZK9LbbMo/z6lpH7zPjdPDr3GL0vhNGKVjJOBxdhXVcJPm6ME/sKfQl8NAySK9Evbj0UlISBNd2hzQjjG0Jqn8NbvemeqLQfEgJc8aSifjmmDnGWxGJShg69JUzxeiUwAwR4HmKNILYk6szJ1dta/EAqCMCBjZ1NbBuZvPK1NqeYQxo6HR7BgEmUu80Wd0QKXhHJFLd0Jy0uVjho2SYuKsypJVyDS/d9x3bG2o+E+vYy/s/Ixf3XNrSPO1kfrYfOBNYfEW8JNJ2O/1qtKIWA8RT8ZBhc1KpgtOipqn+DQ03NnfQyusDTtdkLeGHq+FMfAUsrEOIeWmfKmYQM=";

$access_token = GetXFromYID("SELECT vAccessToken  FROM m_calendar_config WHERE 1");
$refresh_token = GetXFromYID("SELECT vRefreshToken  FROM m_calendar_config WHERE 1");


$DATA= getNewAccessToken($refresh_token);

$refresh_token= $DATA['refresh_token'];
$access_token = $DATA['access_token'];

sql_query("UPDATE m_calendar_config SET vAccessToken = '$access_token', vRefreshToken = '$refresh_token' WHERE 1");
$event_date=TODAY;

$start_time = date("H:i", strtotime(NOW . ' +7 hours'));
$end_time = date("H:i", strtotime($start_time . ' +1 hours'));
$dateTime_start = $event_date . 'T' . $start_time . ':00';
$dateTime_end = $event_date . 'T' . $end_time . ':00';



$timeZone = "America/New_York";

$result = createMicrosoftEvent(
    $access_token,
    "Invite From QM Team For Appointment",
    $dateTime_start,
    $dateTime_end,
    $timeZone,
    "Agenda: Calendar invite testing.",
    "Testing Microsoft Invite",
    [
        ["email" => "michaelchartrand@quotemasters.onmicrosoft.com", "name" => "Michael"],
        ["email" => "darshankubal211997@outlook.com", "name" => "Darshan"]
    ]
);
if ($result) {
    echo "Event created successfully!";
} else {
    echo "Failed to create event.";
}

exit;

// echo $refresh_token;

// DFA(getNewAccessToken($refresh_token));
// exit;

$event_data = [
    "subject" => "Invite From QM Team",
    "start" => [
        "dateTime" => "2025-08-17T10:00:00",
        "timeZone" => "Asia/Kolkata"
    ],
    "end" => [
        "dateTime" => "2025-08-17T11:00:00",
        "timeZone" => "Asia/Kolkata"
    ],
    "body" => [
        "contentType" => "HTML",
        "content" => "Agenda: Calendar invite testing."
    ],
    "location" => [
        "displayName" => "Testing Mircosoft Invite"
    ],
    "attendees" => [
        [
            "emailAddress" => [
                "address" => "michaelchartrand@quotemasters.onmicrosoft.com",
                "name" => "Michael"
            ],
            "type" => "required"
        ],
        [
            "emailAddress" => [
                "address" => "darshankubal211997@outlook.com",
                "name" => "Darshan"
            ],
            "type" => "required"
        ]
        // ,
        // [
        //     "emailAddress" => [
        //         "address" => "charlie@example.com",
        //         "name" => "Charlie"
        //     ],
        //     "type" => "optional" // Optional attendee
        // ]
    ]
];

$ch = curl_init("https://graph.microsoft.com/v1.0/me/events?sendUpdates=all");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $access_token",
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($event_data));

$response = curl_exec($ch);
$token_data = json_decode($response, true);
echo "<pre>";
print_r($token_data);
echo "</pre>";
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpcode == 201) {
    echo "Event created with multiple attendees!";
} else {
   // echo "Error:<br><pre>$response</pre>";
}
