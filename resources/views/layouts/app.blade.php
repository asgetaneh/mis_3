<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>JU MIS | @yield('title') </title>
        <link rel="icon" href="{{ asset('images/logo.png') }}">

        <!-- Scripts -->
        <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
         <script src="{{ asset('assets/plugins/jquery/jquery.min_print.js') }}"></script>
        {{-- <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script> --}}
        {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script> --}}
        {{-- <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script> --}}
        <script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>

        {{-- <script src="https://unpkg.com/alpinejs@3.10.2/dist/cdn.min.js" defer></script> --}}
        <script src="{{ asset('assets/dist/js/alpine.min.js') }}"></script>
        <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/summernote/summernote-bs4.min.css') }}">

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&amp;display=fallback">
        {{-- <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> --}}

        <!-- Styles -->
        {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
        {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css"> --}}
        {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css"> --}}
        <link rel="stylesheet" href="{{ asset('assets/dist/css/notyf.min.css') }}">

        <link rel="stylesheet" href="{{ asset('assets/dist/css/AdminLTE.min.css') }}">

        {{-- Datatables --}}
        <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

        {{-- Duallistbox for permissions and other stuff --}}
        <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css') }}">

        {{-- Calender Styles --}}
        <link href="{{ asset('assets/plugins/custom/prismjs/prismjs.bundle.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/plugins/custom/prismjs/prismjs.bundle.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/plugins/custom/prismjs/prismjs.bundle.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/calendar/css/redmond.calendars.picker.css') }}">

        <!-- Icons -->
        {{-- <link href="https://unpkg.com/ionicons@4.5.10-0/dist/css/ionicons.min.css" rel="stylesheet"> --}}
        <link href="{{ asset('assets/ionicon/ionicons.min.css') }}" rel="stylesheet">

        <!-- Small Ionicons Fixes for AdminLTE -->
        <style>
        html {
            background-color: #f4f6f9;
        }

        .nav-icon.icon:before {
            width: 25px;
        }
        </style>

        @yield('style')

        @livewireStyles
    </head>

    <body class="sidebar-mini layout-fixed layout-navbar-fixed {{ Request::is('smis/plan/plan-accomplishment/*') || Request::is('smis/plan/plan-accomplishment') || Request::is('smis/plan/get-objectives/*') || Request::is('smis/plan/get-objectives') || Request::is('smis/report/reporting/*') || Request::is('smis/report/reporting') || Request::is('smis/report/get-objectives-reporting/*') || Request::is('smis/report/get-objectives-reporting') ? 'sidebar-collapse' : 'sidebar-expand' }}">
        <div id="app" class="wrapper">
            <div class="main-header">
                @include('layouts.nav')
            </div>

            @include('layouts.sidebar')

            <main class="content-wrapper p-4">
                @yield('content')
            </main>

            <footer class="main-footer">
                <strong>SMIS Application &copy;
                    <script>
                        document.write(new Date().getFullYear());
                    </script>
                    <a href="https://ju.edu.et" target="_blank">Jimma
                        University</a></strong>
                <div class="float-right d-none d-sm-inline-block">
                    <b>Version</b>
                    3.0
                </div>

            </footer>

        </div>

        @stack('modals')

        @livewireScripts

        @stack('script_before')
        <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/dist/js/notyf.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/summernote/summernote-bs4.min.js') }}"></script>
        {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script> --}}

        {{-- Calender Scripts --}}
        <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
        <script src=" {{ asset('assets/calendar/js/jquery.plugin.js') }}"></script>
        <script src=" {{ asset('assets/calendar/js/jquery.calendars.js') }}"></script>
        <script src=" {{ asset('assets/calendar/js/jquery.calendars.plus.js') }}"></script>
        <script src=" {{ asset('assets/calendar/js/jquery.calendars.picker.js') }}"></script>
        <script src=" {{ asset('assets/calendar/js/jquery.calendars.ethiopian.js') }}"></script>
        <script src=" {{ asset('assets/calendar/js/jquery.calendars.ethiopian-am.js') }}"></script>
        <script src=" {{ asset('assets/calendar/js/jquery.calendars.picker-am.js') }}"></script>


        {{-- Datatables setup for EMIS tables --}}
        <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/jszip/jszip.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/pdfmake/pdfmake.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/pdfmake/vfs_fonts.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

        {{-- Initialize the tables --}}
        <script>
            $(function() {
                $("#emisTable").DataTable({
                    // "responsive": true,
                    //  dom: 'Bfrtip',
                    "lengthChange": false,
                    "autoWidth": false,
                    scrollX: true,
                    buttons: [
                        {
                            extend: 'excel',
                            text: 'Download Excel',
                            exportOptions: {
                            }
                        }
                    ]
                }).buttons().container().appendTo('#emisTable_wrapper .col-md-6:eq(0)');

            });
        </script>


        <script>
            $(document).ready(function() {

                $('.select2').select2();

            });
        </script>

        @stack('scripts')

        {{-- <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script> --}}


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
                            thilogins.imageUrl = this.$el.getAttribute("image-url")
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

            $('.summernote').summernote({
                height: 150,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],  // Add this line
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['insert', ['link']],
                    ['view', ['fullscreen']]
                ]
            });
        </script>
    </body>
</html>
