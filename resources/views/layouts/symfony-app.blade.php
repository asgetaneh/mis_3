<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>JU MIS</title>

        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
                <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}"/>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>
        <script src="https://unpkg.com/alpinejs@3.10.2/dist/cdn.min.js" defer></script>


        <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/assets/css/bootstrap.min.css') }}"/>







         <link rel="stylesheet" href="{{ asset('assets-other') }}/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('assets-other') }}/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ asset('assets-other') }}/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('assets-other') }}/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="{{ asset('assets-other') }}/dist/css/skins/_all-skins.min.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="{{ asset('assets-other') }}/bower_components/morris.js/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="{{ asset('assets-other') }}/bower_components/jvectormap/jquery-jvectormap.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="{{ asset('assets-other') }}/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{ asset('assets-other') }}/bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="{{ asset('assets-other') }}/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
         <link rel="stylesheet" href="{{ asset('assets/assets/css/select2.min.css') }}"/>
<!-- jQuery 3 -->
<script src="{{ asset('assets-other') }}/bower_components/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('assets-other') }}/bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('assets-other') }}/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Morris.js charts -->
<script src="{{ asset('assets-other') }}/bower_components/raphael/raphael.min.js"></script>
<script src="{{ asset('assets-other') }}/bower_components/morris.js/morris.min.js"></script>
<!-- Sparkline -->
<script src="{{ asset('assets-other') }}/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="{{ asset('assets-other') }}/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="{{ asset('assets-other') }}/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('assets-other') }}/bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="{{ asset('assets-other') }}/bower_components/moment/min/moment.min.js"></script>
<script src="{{ asset('assets-other') }}/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="{{ asset('assets-other') }}/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="{{ asset('assets-other') }}/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="{{ asset('assets-other') }}/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="{{ asset('assets-other') }}/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="{{ asset('assets-other') }}/dist/js/adminlte.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ asset('assets-other') }}/dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('assets-other') }}/dist/js/demo.js"></script>
<script type="text/javascript">
  
    var url = "{{ route('changeLang') }}";
  
    $(".changeLang").change(function(){
        window.location.href = url + "?lang="+ $(this).val();
    });
  
</script>
        <link
         <!-- ace styles -->
 

 



        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&amp;display=fallback">
        {{-- <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> --}}

        <!-- Styles -->
        {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">

        <!-- Icons -->
        <link href="https://unpkg.com/ionicons@4.5.10-0/dist/css/ionicons.min.css" rel="stylesheet">

        <!-- Small Ionicons Fixes for AdminLTE -->
        <style>
        html {
            background-color: #f4f6f9;
        }

        .nav-icon.icon:before {
            width: 25px;
        }
        </style>


        @livewireStyles
    </head>

    <body class="sidebar-mini layout-fixed layout-navbar-fixed sidebar-expand">
        <div id="app" class="wrapper">
            <div class="main-header">
                @include('layouts.nav')
            </div>

            @include('layouts.sidebar')

            <main class="content-wrapper p-5">
                @yield('content')
            </main>
        </div>

        @stack('modals')

        @livewireScripts

        @stack('scripts')

        <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
        <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>

        @if (session()->has('success'))
        <script>
            var notyf = new Notyf({dismissible: true})
            notyf.success('{{ session('success') }}')
        </script>
        @endif

        <script>
            /* Simple Alpine Image Viewer */
            document.addEventListener('alpine:init', () => {
                Alpine.data('imageViewer', (src = '') => {
                    return {
                        imageUrl: src,

                        refreshUrl() {
                            this.imageUrl = this.$el.getAttribute("image-url")
                        },

                        fileChosen(event) {
                            this.fileToDataUrl(event, src => this.imageUrl = src)
                        },

                        fileToDataUrl(event, callback) {
                            if (! event.target.files.length) return

                            let file = event.target.files[0],
                                reader = new FileReader()

                            reader.readAsDataURL(file)
                            reader.onload = e => callback(e.target.result)
                        },
                    }
                })
            })
        </script>
    </body>
</html>
