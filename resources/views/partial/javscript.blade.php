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
    
          document.querySelectorAll(".menu-item a").forEach(function (link) {
              if (link.href === currentUrl) {
                  let menuItem = link.closest(".menu-item");
                  if (menuItem) {
                      menuItem.classList.add("active");
                  }
    
                  // Telusuri semua parent yang memiliki .menu-sub
                  let parentSubMenu = menuItem.closest(".menu-sub");
                  while (parentSubMenu) {
                      let parentMenuItem = parentSubMenu.closest(".menu-item");
                      if (parentMenuItem) {
                          parentMenuItem.classList.add("open", "active"); // Tambahkan active
                      }
                      parentSubMenu.style.display = "block";
                      parentSubMenu = parentMenuItem?.closest(".menu-sub"); // Cek jika masih ada parent
                  }
              }
          });
      });
    </script>
    
    
  </body>
</html>