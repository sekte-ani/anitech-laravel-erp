@include('auth.partial-auth.head-auth')
<body>
    <!-- Content -->

    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
            @yield('content-auth')
            @include('sweetalert::alert')
        </div>
      </div>
    </div>

    <!-- / Content -->
    @include('partial.javscript')
  </body>
</html>