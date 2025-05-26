<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Nightly Inventory Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f9fc;
            color: #333;
            padding: 30px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background-color: #ffffff;
            border: 1px solid #e1e1e1;
            border-radius: 8px;
            padding: 20px;
        }

        h2 {
            color: #2c3e50;
            margin-top: 0;
        }

        p {
            line-height: 1.6;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #777;
            text-align: center;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }

        .button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Keith Closet Report</h2>
        <p>Hello,</p>
        <p>The  {{$reportType}} report has been generated successfully. Please find the attached PDF report for more details.</p>
        <p>If you have any questions or notice any discrepancies, please contact the warehouse or inventory team.</p>

        <p>Thank you, <br>The BCHMS System</p>

        <a href='{{ route("admin.report.download", [
            "type" => strtolower($reportType),
            "date" => $date,
        ]) }}' class="button">Download Report</a>
        <div class="footer">
            This is an automated email from the BCHMS system. Please do not reply.
        </div>
    </div>
</body>
</html>
