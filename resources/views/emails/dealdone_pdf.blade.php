<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #fff;
            color: #000;
            margin: 0;
            padding: 40px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        p {
            margin: 0;
            padding: 0;
        }

        .invoice-header {
            width: 100%;
            margin-bottom: 40px;
        }

        .invoice-header td {
            vertical-align: top;
        }

        .company-name {
            font-weight: bold;
            font-size: 18px;
            /* text-transform: uppercase; */
        }

        .company-address {
            font-size: 12px;
            color: #444;
            line-height: 1.6;
        }

        .logo {
            text-align: right;
            font-weight: bold;
            font-size: 22px;
        }

        .logo img {
            height: 100px;
            width: 120px;
        }

        .logo span {
            color: #e53935;
        }

        .section {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .section table {
            width: 100%;
        }

        .section th,
        .section td {
            font-size: 13px;
            padding: 4px 0;
            text-align: left;
        }

        .section th {
            color: #666;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
        }

        .billto-name {
            font-weight: bold;
            font-size: 14px;
        }

        .invoice-info {
            text-align: right;
        }

        .item-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .item-table th,
        .item-table td {
            border-bottom: 1px solid #eee;
            padding: 10px;
            font-size: 13px;
        }

        .item-table th {
            text-transform: uppercase;
            font-size: 11px;
            color: #666;
        }

        .totals {
            width: 50%;
            float: right;
            margin-top: 20px;
        }

        .totals td {
            padding: 4px 0;
            font-size: 13px;
        }

        .totals td:last-child {
            text-align: right;
        }

        .amount-due {
            margin-top: 40px;
            font-weight: bold;
            font-size: 16px;
            border-top: 1px solid #000;
            padding-top: 10px;
        }

        .amount-due td {
            padding: 6px 0;
        }

        .amount-due td:last-child {
            text-align: right;
        }

        .small-note {
            font-size: 12px;
            color: #333;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <h1 style="margin-bottom: 15px">Invoice</h1>

    <table class="invoice-header">
        <tr>
            <td>
                <p class="company-name">PhoneXpress</p>
                <p class="company-address">

                    Hello@phonexpress.com.au<br>
                    https://phonexpress.getdemo.in/<br>
                    +61 422 794 777<br>
                    ABN : 24 674 768 435
                </p>
            </td>
            <td class="logo"><img src="https://www.getdemo.in/Phoneexpress/assets/images/logo.png" /><span></span></td>
        </tr>
    </table>

    <!-- BILL TO + INVOICE INFO -->
    <table class="section">
        <tr>
            <td>
                <table>
                    <tr>
                        <th style="padding-left: 10px !important;">BILL TO</th>
                    </tr>
                    <tr>
                        <td class="billto-name" style="padding-left: 10px !important;">{{ $data['Name'] }}</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 10px !important;">{{ $data['Email'] }}</td>
                    </tr>
                </table>
            </td>
            <td class="invoice-info">
                <table>
                    <tr>
                        <th>INVOICE NUMBER</th>
                        <td> {{ $data['DealData']['invoiceno'] }}</td>
                    </tr>
                    <tr>
                        <th>ISSUED</th>
                        <td>{{ date('d-m-Y') }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- ITEMS -->
    <table class="item-table">
        <thead>
            <tr style="text-align: left; background:#ccc">
                <th width="60%">Item</th>
                <th>Price</th>
                <th style="text-align: center">Quantity</th>
                <th style="text-align: right">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    {{ $data['DealData']['Brand'] }}<br>
                    <span class="small-note"> Model: {{ $data['DealData']['Model'] }}<br>
                        IMEI: {{ $data['DealData']['imei_1'] }}</span>
                </td>
                <td>${{ number_format($data['DealData']['Amount'], 2) }}</td>
                <td style="text-align: center">1</td>
                <td style="text-align: right">${{ number_format($data['DealData']['Amount'], 2) }}</td>
            </tr>
        </tbody>
    </table>

    <!-- PAYMENT INSTRUCTIONS -->
    <table style="margin-top: 25px; width: 100%;">
        <tr>
            <td width="60%">
                {{-- <strong>Payment Instructions</strong><br><br>
                <span class="small-note">
                    BANK DETAILS BELOW :<br>
                    NAME : PhoneXpress<br>
                    SUNCORP BANK :<br>
                    BSB : 484-799<br>
                    ACCOUNT : 122210380
                </span> --}}
            </td>
            <td>
                <table class="totals">
                    {{-- <tr>
                        <td>Subtotal</td>
                        <td>$1,400.00</td>
                    </tr>
                    <tr>
                        <td>GST Included (10%)</td>
                        <td>$127.27</td>
                    </tr>
                    <tr>
                        <td>Net</td>
                        <td>$1,272.73</td>
                    </tr> --}}
                    <tr>
                        <td><strong>Total</strong></td>
                        <td><strong>${{ number_format($data['DealData']['Amount'], 2) }}</strong></td>
                    </tr>
                    {{-- <tr>
                        <td>Paid on 12 Oct 2025</td>
                        <td>$1,400.00</td>
                    </tr> --}}
                </table>
            </td>
        </tr>
    </table>
    <h5 style="margin-top: 15px;">*GST has been included in the above total.</h5>

    <!-- AMOUNT DUE -->
    {{-- <table class="amount-due">
        <tr>
            <td>Amount due</td>
            <td>$0.00</td>
        </tr>
    </table> --}}


</body>

</html>
