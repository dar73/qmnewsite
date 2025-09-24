<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Cancelled</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            background-color: #BE1E2D;
            color: #fff;
            padding: 15px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }

        .email-body {
            padding: 20px;
        }

        .email-footer {
            margin-top: 20px;
            text-align: center;
            font-size: 0.85em;
            color: #666;
        }

        h1 {
            margin: 0;
            font-size: 1.5em;
        }

        h2 {
            color: #BE1E2D;
            margin-top: 20px;
        }

        ul {
            padding-left: 20px;
        }

        li {
            margin-bottom: 10px;
        }

        .contact-info {
            margin-top: 20px;
            font-weight: bold;
        }

        a {
            color: #BE1E2D;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Appointment Cancelled</h1>
        </div>
        <div class="email-body">
            <p>Dear <SPNAME>,</p>

            <p>We wanted to inform you that an appointment scheduled with <strong>
                    <CNAME>
                </strong> on <strong>
                    <DTIME>
                </strong>  has been canceled.</p>

            <h3>Appointment Details:</h3>
            <ul>
                <li><strong>Customer:</strong>
                    <CNAME>
                </li>
                <li><strong>Location:</strong>
                    <LOCATION>
                </li>
                <li><strong>Scheduled Date & Time:</strong>
                    <DTIME>
                </li>
            </ul>
            <p>
                At this time, no further action is required on your part. If the customer wishes to reschedule, we will notify you accordingly.</p>

            <p>If you have any questions or need further assistance, please feel free to reach out to us at <a href="mailto:service@thequotemasters.com">service@thequotemasters.com</a> or <a href="tel:866 958-8773">866 958-8773</a>.</p>

            <p>Best regards,</p>
            <p><strong>Platinum Support Desk</strong></p>
            <p><a href="mailto:service@thequotemasters.com">service@thequotemasters.com</a></p>
        </div>
        <div class="email-footer">
            &copy; 2025 The Quote Masters. All rights reserved.
        </div>
    </div>
</body>

</html>