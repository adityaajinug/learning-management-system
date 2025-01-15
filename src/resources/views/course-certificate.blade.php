<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Completion</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
            background-color: #f4f4f9;
        }
        .certificate {
            border: 10px solid #2c3e50;
            padding: 30px;
            margin: 20px auto;
            width: 80%;
            background-color: #ffffff;
        }
        .certificate h1 {
            font-size: 2.5em;
            margin-bottom: 20px;
        }
        .certificate p {
            font-size: 1.2em;
            margin: 5px 0;
        }
        .certificate .signature {
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <h1>Certificate of Completion</h1>
        <p>This certifies that</p>
        <h2>{{ $user->name }}</h2>
        <p>has successfully completed the course</p>
        <h3>{{ $course->name }}</h3>
        <p>on {{ now()->format('F d, Y') }}</p>
    </div>
</body>
</html>
