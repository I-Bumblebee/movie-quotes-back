@props(['url', 'salutation', 'text', 'button_text'])


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }}</title>
    <style>
        html {
            font-family: Roboto, sans-serif;
            color: #fff;
        }
        body {
            background: linear-gradient(187.16deg, #181623 0.07%, #191725 51.65%, #0D0B14 98.75%) no-repeat;
            margin:0;
            padding: 80px 0;
        }

        header {
            text-align: center;
        }
        header h1{
            color: #DDCCAA;
            font-size: 12px;
            font-weight: 400;
        }

        section {
            max-width: 75%;
            margin: auto;
        }

        @media only screen and (max-width: 430px) {
            section {
                max-width: 90%;
            }
        }
        p {
            line-height: 24px;
        }

        .salutation {
            margin-bottom: 24px;
            margin-top: 76px;
        }

        .confirm-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #E31221;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 24px;
            margin-bottom: 40px;
        }

        .verification-url {
            color: #DDCCAA;
        }

    </style>
</head>
<body>
<header>
    <img src="{{ asset('images/email-logo.png') }}" alt="quotation icon"/>
    <h1>MOVIE QUOTES</h1>
</header>
<section>
    <p class="salutation">{{ $salutation }}</p>
    <p>{{ $text }}</p>
    <a href="{{ $url }}" class="confirm-button">{{ $button_text }}</a>

    <p>If clicking doesn't work, you can try copying and pasting it to your browser:</p>
    <p class="verification-url">{{ $url }}</p>
    <br>
    <p>If you have any problems, please contact us: support@moviequotes.ge</p>
    <p>MovieQuotes Crew</p>
</section>
</body>
</html>
