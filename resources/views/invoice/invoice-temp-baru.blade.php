<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Invoice</title>
    <meta name="description" content="" />
    <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon/favicon.ico') }}" />

    <style>
      @page {
          size: A4;
          margin: 20mm;
      }

      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: Arial, sans-serif;
      }

      body {
        background-color: #f8f9fa;
        padding: 20px;
        font-size: 12px;
      }

      .container {
        max-width: 800px;
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        margin: auto;
      }

      .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
      }

      .header img {
        width: 100px;
        height: 120px;
      }

      .big-box {
        background: #f1f1f1;
        margin-left: 10px;
        margin-right: 10px;
        padding-bottom: 5px;
        border-radius: 8px;
        margin-bottom: 10px;
        flex-direction: row;
        display: flex;
        flex-wrap: nowrap;
        justify-content: space-between;
      }

      table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
      }

      table, th, td {
        border: 1px solid #ddd;
      }

      th, td {
        padding: 10px;
        text-align: left;
      }

      th {
        background-color: #007bff;
        color: white;
      }

      .summary {
        text-align: right;
        margin-top: 20px;
      }

      .summary div {
        display: flex;
        justify-content: space-between;
        width: 250px;
        margin-left: auto;
        font-size: 13px;
      }

      .amount-due {
        background: #f1f1f1;
        padding: 15px;
        border-radius: 8px;
        text-align: right;
        margin-top: 10px;
        font-size: 15px;
      }

      ul {
        margin-top: 20px;
        padding-left: 20px;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <div class="header">
        <table width="100%" style="border-style: none;" border="0">
          <tr>
              <td style="border: none;">
                  <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents(public_path('img/illustrations/icon_ani.jpg'))) }}" style="max-width: 500px; height: 200px; width: auto;">
              </td>
              <td style="border: none; text-align: right;">
                  <b>Invoice</b><br>
                  ANI Tech<br>
                  Application Nusantara Innovation Technology<br>
                  +6285117202154<br>
                  aniteknologi@gmail.com
              </td>
          </tr>
        </table>
      </div>

      <div class="big-box">
        <table width="100%" style="background-color: #f5f5f5; padding: 10px; border-style: none;" border="0">
          <tr>
              <td style="border: none; padding: 15px; border-radius: 8px;">
                  <b>BILL TO</b><br>
                  {{ $invoice->orders->first()?->client->name }}<br>
                  {{ $invoice->orders->first()?->client->phone }}
              </td>
              <td style="border: none; text-align: right; padding: 15px; border-radius: 8px;">
                  <b>Invoice #: {{ $invoice->invoice_number }}</b><br>
                  <b>Date: {{ $invoice->formatted_date }}</b>
              </td>
          </tr>
        </table>
      </div>
      

      <table>
        <thead>
          <tr>
            <th>Item</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Amount</th>
          </tr>
        </thead>
        <tbody>
          @foreach($invoice->orders as $o)
          <tr>
            <td>{{ $o->item }}</td>
            <td>{{ $o->quantity }}</td>
            <td>Rp{{ number_format($o->price, 0, ',','.') }}</td>
            <td>Rp{{ number_format($o->amount, 0, ',','.') }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>

      <div class="summary">
        <div>
          <p><strong>Sub Total</strong></p>
          <p>Rp{{ number_format($invoice->sub_total, 0, ',','.') }}</p>
        </div>
        <div>
          <p><strong>Total</strong></p>
          <p>Rp{{ number_format($invoice->total, 0, ',','.') }}</p>
        </div>
      </div>

      <div class="amount-due">
        <p><strong>Amount Due</strong></p>
        <h3>Rp{{ number_format($invoice->amount_due, 0, ',','.') }}</h3>
      </div>

      <ul>
        {{ strip_tags($invoice->notes) }}
      </ul>
    </div>
  </body>
</html>
