<html
  lang="en"
  class="light-style layout-menu-fixed layout-compact"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path=""
  data-template="vertical-menu-template-free"
  data-style="light">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title> Invoice </title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <!-- Page CSS -->
    <style>
      .dot {
          width: 10px;
          height: 10px;
          border-radius: 50%;
          display: inline-block;
          margin-right: 5px;
      }
    </style>

    <!-- Helpers -->
    <script src="{{ asset('vendor/js/helpers.js') }}"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('js/config.js') }}"></script>
  </head>

<body>
    <div class="d-flex justify-content-around mt-5 mx-5">
        <div class="align-self-center">
            <img src="{{asset('img/illustrations/Logo_Anitech.png')}}" class="img-fluid" style="width: 100px; height: 120px; ">
        </div>
        <div class="d-flex justify-content-end flex-column ">
            <h4 class="text-end">Invoice</h4>
            <h4 class="text-end">ANI Tech</h4>
            <h6 class="text-end fs-6 fw-light">Application Nusantara Innovation Technology</h6>
            <h6 class="text-end fs-6 fw-light">+6285117202154</h6>
            <h6 class="text-end fs-6 fw-light">aniteknologi@gmail.com</h6>
        </div>
    </div>
    <div class="d-flex justify-content-center">
        <div class="d-flex justify-content-around mt-5 mx-5 w-75 bg-light pt-4 rounded ">
            <div class="flex-column">
                <h6 class="text-end fs-6">BILL TO</h6>
                <h6 class="text-end fs-6 fw-light">Firman Hasani Putra</h6>
                <h6 class="text-end fs-6 fw-light">+6289652410787</h6>
            </div>
    
            <div class="d-flex justify-content-around">
                <div class= "flex-column">
                    <h6 class=" fs-6">Invoice #</h6>
                    <h6 class=" fs-6">Date</h6>
                </div>
                <div class="flex-column ms-5">
                    <h6 class="text-end fs-6 fw-light">12</h6>
                    <h6 class="text-end fs-6 fw-light">17 Feb 2025</h6>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-center mt-5 px-5 mx-5">
        <table class="table table-striped w-75">
            <thead>
              <tr>
                <th scope="col">Item</th>
                <th scope="col" style="width:12%">Quantity</th>
                <th scope="col" style="width:12%">Price</th>
                <th scope="col" style="width:12%">Amount</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Server & Hosting Website</th>
                <td>1</td>
                <td>Rp.300,000</td>
                <td>Rp.300,000</td>
              </tr>
            </tbody>
          </table>
    </div>
    <div class="d-flex justify-content-center mt-5 px-5 mx-5">
        <div class="d-flex justify-content-end w-75 ">
            <div class="d-flex flex-column" style="width: 500px">

                <div class="d-flex justify-content-end">
                    <div class="d-flex flex-column"> 
                        <div class="d-flex justify-content-between" style="width:14rem;">
                            <p class="fs-5 ">Sub Total</p>
                            <p class="fs-5">Rp.300,000</p>
                        </div>
                        <div class="d-flex justify-content-between" style="width:14rem;">
                            <p class="fs-5 ">Total</p>
                            <p class="fs-5">Rp.300,000</p>
                        </div>
                    </div>
                </div>
                
                <hr style="color: #000;">
                <div class="bg-light d-flex flex-column p-4 rounded">
                    <div class="d-flex">
                        <p class="fs-5">Amount Due</p>
                    </div>
                    <div class="d-flex justify-content-end">
                        <p class="fs-3">Rp.300,000</p>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    <div class="d-flex justify-content-center mt-5 px-5 mx-5">
        <div class="d-flex flex-column mt-5 px-5 mx-5 w-75">
            <ul>
                <li>Pembayaran harus dibayarkan paling lambat H+3 setelah hosting diberikan kepada pelanggan.</li>
                <li>Jika sisa pembayaran tidak dibayarkan pada H+3 setelah hosting diberikan, maka server akan dimatikan dan hosting akan dicabut.</li>
                <li>Batas penggunaan server dan hosting maksimal 1 bulan.</li>
            </ul>
            <p>Terima kasih atas kepercayaan Anda.</p>

            <div class="d-flex mt-5 flex-column">
                <p>Best Regard,</p>
                <p>Team ANI Tech</p>
            </div>
        </div>
    </div>
    







    {{-- JS CDN Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>