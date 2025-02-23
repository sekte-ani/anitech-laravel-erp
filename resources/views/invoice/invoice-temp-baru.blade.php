<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Invoice</title>
    <meta name="description" content="" />
    <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon/favicon.ico') }}" />

    <style>
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: Arial, sans-serif;
      }

      body {
        background-color: #f8f9fa;
        padding: 20px;
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

      .header div {
        text-align: right;
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

      .box {
        
        padding: 15px;
        border-radius: 8px;
        
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
      }

      .amount-due {
        background: #f1f1f1;
        padding: 15px;
        border-radius: 8px;
        text-align: right;
        margin-top: 10px;
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
        <img src="{{ asset('img/illustrations/icon_ani.jpg') }}" style="width: auto; height: 200px; "  />
        <div>
          <h4>Invoice</h4>
          <h4>ANI Tech</h4>
          <h5>Application Nusantara Innovation Technology</h5>
          <h5>+6285117202154</h5>
          <h5>aniteknologi@gmail.com</h5>
        </div>
      </div>

      <div class="big-box">
        <div class="box">
            <h6 style="font-size: large">BILL TO</h6>
            <p style="font-size: medium">Firman Hasani Putra</p>
            <p style="font-size: medium">+6289652410787</p>
          </div>
    
          <div class="box">
            <p><strong>Invoice #:</strong> 12</p>
            <p><strong>Date:</strong> 17 Feb 2025</p>
          </div>
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
          <tr>
            <td>Server & Hosting Website</td>
            <td>1</td>
            <td>Rp.300,000</td>
            <td>Rp.300,000</td>
          </tr>
        </tbody>
      </table>

      <div class="summary">
        <div>
          <p><strong>Sub Total</strong></p>
          <p>Rp.300,000</p>
        </div>
        <div>
          <p><strong>Total</strong></p>
          <p>Rp.300,000</p>
        </div>
      </div>

      <div class="amount-due">
        <p><strong>Amount Due</strong></p>
        <h3>Rp.300,000</h3>
      </div>

      <ul>
        <li>Pembayaran harus dibayarkan paling lambat H+3 setelah hosting diberikan kepada pelanggan.</li>
        <li>Jika sisa pembayaran tidak dibayarkan pada H+3 setelah hosting diberikan, maka server akan dimatikan dan hosting akan dicabut.</li>
        <li>Batas penggunaan server dan hosting maksimal 1 bulan.</li>
      </ul>

      <p>Terima kasih atas kepercayaan Anda.</p>
      <p>Best Regard,</p>
      <p>Team ANI Tech</p>
    </div>
  </body>
</html>
