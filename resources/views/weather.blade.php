<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Weather</title>
</head>
<body>
<td style="padding: 1rem 2rem; vertical-align: top; width: 100%;" align="center">
    <table role="presentation"
           style="max-width: 600px; border-collapse: collapse; border: 0px none; border-spacing: 0px; text-align: left;">
        <tbody>
        <tr>
            <td style="padding: 40px 0px 0px;">
                <div style="text-align: left;">
                    <div style="padding-bottom: 20px;"><img src="https://i.ibb.co/Qbnj4mz/logo.png" alt="Company"
                                                            style="width: 56px;"></div>
                </div>
                <div style="padding: 20px; background-color: rgb(255, 255, 255);">
                    <div style="color: rgb(0, 0, 0); text-align: left;">
                        <p><strong>Timezone:</strong> {{$info[5]}}</p>
                        <p><strong>Temperature:</strong> {{$info[0]}} C </p>
                        <p><strong>Pressure:</strong> {{$info[1]}} {{$measure[0]}}</p>
                        <p><strong>Precip:</strong> {{$info[2]}} {{$measure[1]}}</p>
                        <p><strong>Wind:</strong> {{$info[3]}} {{$measure[2]}}</p>
                        <p><strong>{{$info[4]}}</strong></p>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</td>
</body>
</html>
