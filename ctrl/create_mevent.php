<?php
session_start();

if (!isset($_SESSION['access_token'])) {
    die("Please login first.");
}

$access_token = $_SESSION['access_token'];

$event_data = [
    "subject" => "Team Sync Meeting",
    "start" => [
        "dateTime" => "2025-07-30T10:00:00",
        "timeZone" => "Asia/Kolkata"
    ],
    "end" => [
        "dateTime" => "2025-07-30T11:00:00",
        "timeZone" => "Asia/Kolkata"
    ],
    "body" => [
        "contentType" => "HTML",
        "content" => "Agenda: Project status and next steps."
    ],
    "location" => [
        "displayName" => "Conference Room or Teams Meeting"
    ],
    "attendees" => [
        [
            "emailAddress" => [
                "address" => "alice@example.com",
                "name" => "Alice"
            ],
            "type" => "required"
        ],
        [
            "emailAddress" => [
                "address" => "bob@example.com",
                "name" => "Bob"
            ],
            "type" => "required"
        ],
        [
            "emailAddress" => [
                "address" => "charlie@example.com",
                "name" => "Charlie"
            ],
            "type" => "optional" // Optional attendee
        ]
    ]
];

$ch = curl_init("https://graph.microsoft.com/v1.0/me/events");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $access_token",
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($event_data));

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpcode == 201) {
    echo "Event created with multiple attendees!";
} else {
    echo "Error:<br><pre>$response</pre>";
}
