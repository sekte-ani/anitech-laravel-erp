<!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->

    <script src="{{asset('vendor/libs/jquery/jquery.js')}}"></script>
    <script src="{{asset('vendor/libs/popper/popper.js')}}"></script>
    <script src="{{asset('vendor/js/bootstrap.js')}}"></script>
    <script src="{{asset('vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
    <script src="{{asset('vendor/js/menu.js')}}"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{asset('vendor/libs/apex-charts/apexcharts.js')}}"></script>

    <!-- Main JS -->
    <script src="{{asset('js/main.js')}}"></script>

    <!-- Page JS -->
    <script src="{{asset('js/dashboards-analytics.js')}}"></script>

    <!-- Place this tag before closing body tag for github widget button. -->
    <script async defer src="{{asset('https://buttons.github.io/buttons.js')}}"></script>


    {{-- Active SIde --}}

    <script>
      document.addEventListener("DOMContentLoaded", function () {
          let currentUrl = window.location.href;
    
          // Loop melalui setiap menu-item
          document.querySelectorAll(".menu-item a").forEach(function (link) {
              // Jika href dari menu cocok dengan URL saat ini
              if (link.href === currentUrl) {
                  // Tambahkan class active ke parent "menu-item"
                  link.closest(".menu-item").classList.add("active");
    
                  // Jika item berada dalam sub-menu, buka juga parent menu
                  let parentSubMenu = link.closest(".menu-sub");
                  if (parentSubMenu) {
                      parentSubMenu.style.display = "block"; // Pastikan sub-menu terbuka
                      parentSubMenu.closest(".menu-item").classList.add("open");
                      parentSubMenu.closest(".menu-item").classList.add("active");
                  }
              }
          });
      });
    </script>
    
  </body>
</html>