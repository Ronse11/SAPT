<!-- resources/views/navigations/print.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Printable View</title>
    <style>
        /* General styles */
        body {
            font-family: Arial, sans-serif;
        }

        .content {
            padding: 20px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            margin-bottom: 20px;
        }

        /* Styles for screen view */
        /* @media screen {
            .no-print {
                display: none;
            }
        } */

        /* Styles for print view */
        @media print {
            body {
                font-size: 12pt;
                line-height: 1.5;
                margin: 0;
                padding: 20px;
            }

            .content {
                background-color: #fff;
                border: none;
                margin-bottom: 0;
            }

            h1 {
                font-size: 24pt;
                color: #333;
            }

            p {
                margin-bottom: 10px;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="content">
        <button class="no-print" onclick="window.print()">Print this page</button>
        <h1>Welcome to the Printable Page</h1>
        <p>This is the content that will be printed.</p>
        <!-- Additional content -->
    </div>
    <p class="no-print">
        For best print results, please disable the "Headers and footers" option in your print settings.
    </p>
</body>
</html>
