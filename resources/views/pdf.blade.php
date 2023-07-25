<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cobros {{ $user['name'] }}</title>
</head>

<body>
    <h4 align="center"><b><u>{{ $user['name'] }}</u></b></h4>

    <table>
        <thead>
            <tr>
                <th>Razon social</th>
                @foreach($months as $key => $value)
                <th>{{ substr($value, 0, 3) }}</th>
                @endforeach
                <th>Total</th>
            </tr>
        </thead>
        @php
        $sum = 0;
        $sums = array(0,0,0,0,0,0,0,0,0,0,0,0);
        @endphp
        <tbody>
            @foreach($customers as $customer)
            <tr>
                <td style="text-align: left;">{{ $customer->razonsocial }}</td>
                @foreach($months as $key => $value)
                <td style="text-align: right;">{{ $customer->{$value} }}</td>
                @php
                $sums[$key -1] += $customer->{$value};
                @endphp
                @endforeach
                <td style="text-align: right;">{{ $customer->total }}</td>
            </tr>
            @php
            $sum += $customer->total;
            @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>TOTAL</th>
                @foreach($months as $key => $value)
                <th style="text-align: right;">{{ $sums[$key -1] }}</th>
                @endforeach
                <th style="text-align: right;">{{ $sum }}</th>
            </tr>
        </tfoot>
    </table>
</body>

</html>

<style>
    body {
        margin: 0;
        padding: 0;
        font-size: 1em;
        line-height: 1em;
        font-family: Arial, Helvetica, sans-serif;
    }

    .marca-de-agua {
        background-repeat: no-repeat;
        background-position: center;
        width: 100%;
        height: auto;
        margin: auto;
    }

    .marca-de-agua img {
        padding: 0;
        width: 100%;
        height: auto;
        opacity: 0.1;
        position: absolute;
    }

    #main-header {
        height: 50px;
        width: 100%;
        left: 0;
        top: 0;
    }

    hr {
        border: 4px solid #232E63;
        margin-left: 0%;
        margin-right: 20%
    }

    /*
 * Logo
 */
    #logo-header {
        float: right;
        padding: 1px;
        text-decoration: none;
    }

    #logo-header .site-name {
        display: block;
    }

    #logo-header .site-desc {
        display: block;
        font-weight: 300;
        font-size: 0.8em;
        color: #999;
    }

    .logo {
        padding-top: 10px;
        height: 50px;
        float: right;
        margin: 5px;
    }

    table {
        border: 0.1px solid #544F4F;
        font-family: Arial, Helvetica, sans-serif;
        width: 100%;
        border-spacing: 0;
        font-size: 10px;
    }

    th,
    td {
        border: 0.1px solid #544F4F;
        padding: 3px;
        /* font-size: 15px; */
        /* letter-spacing: 1px; */
    }

    thead {
        background-color: #544F4F;
        color: #FFF;
    }

    footer {
        color: black;
        width: 100%;
        height: 81px;
        position: absolute;
        bottom: 0;
        left: 0;
    }
</style>