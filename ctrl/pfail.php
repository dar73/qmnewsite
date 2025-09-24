<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../Images/q_avatar.ico">
    <link rel="stylesheet" href="styles.css">
    <title>Payment Successful</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .success-message {
            text-align: center;
        }

        .success-message h1 {
            color: #28a745;
        }

        .transaction-id {
            font-weight: bold;
            color: #007bff;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="success-message">
            <h1>Payment failed</h1>
            <p>Your transaction was unsuccessful.</p>
            <!-- <p>Transaction ID: <span class="transaction-id">ABC123DEF456</span></p>
            <p>An email receipt has been sent to your registered email address.</p> -->
            <a href="leads_disp.php" class="btn">Go to dashboard</a>
        </div>
    </div>
</body>

</html>