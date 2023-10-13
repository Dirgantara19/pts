<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{pagetitle}</title>

    <link rel="stylesheet" href="{theme_url}AdminLTE/my/styles.css">
    </link>


    <!-- Font Awesome -->
    <link rel="stylesheet" href="{theme_url}AdminLTE/plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="{theme_url}AdminLTE/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="{theme_url}AdminLTE/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{theme_url}AdminLTE/plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{theme_url}AdminLTE/dist/css/adminlte.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{theme_url}AdminLTE/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{theme_url}AdminLTE/plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="{theme_url}AdminLTE/plugins/summernote/summernote-bs4.min.css">

    <!-- Datatables Bootstrap4 -->
    <link rel="stylesheet" href="{theme_url}AdminLTE/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{theme_url}AdminLTE/plugins/datatables-rowgroup/css/rowGroup.bootstrap4.min.css">
    <link rel="stylesheet" href="{theme_url}AdminLTE/plugins/datatables-keytable/css/keyTable.bootstrap4.min.css">
    <!-- Buttons -->
    <link rel="stylesheet" href="{theme_url}AdminLTE/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Toastr-->
    <link rel="stylesheet" href="{theme_url}AdminLTE/plugins/toastr/toastr.min.css">
    </link>
    <link rel="stylesheet" href="{theme_url}AdminLTE/plugins/sweetalert2/sweetalert2.min.css">
    </link>
    <link rel="stylesheet" href="{theme_url}AdminLTE/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    </link>
    <link rel="stylesheet" href="{theme_url}AdminLTE/plugins/bootstrap-select/dist/css/bootstrap-select.min.css">
    </link>

    <link rel="stylesheet" type="text/css"
        href="{theme_url}AdminLTE/plugins/simple-calendar/dist/simple-calendar.css" />

    <link rel="stylesheet" type="text/css" href="{theme_url}mystyles/styles.css" />

    <link rel="shortcut icon" href="{theme_url}img/logosmkn1bantul.png" type="image/x-icon">



</head>

<body class="hold-transition sidebar-mini layout-fixed">

    <!-- jQuery -->
    <script src="{theme_url}AdminLTE/plugins/jquery/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{theme_url}AdminLTE/plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
    $.widget.bridge("uibutton", $.ui.button);
    </script>
    <!-- Bootstrap 4 -->
    <script src="{theme_url}AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- ChartJS -->
    <script src="{theme_url}AdminLTE/plugins/chart.js/Chart.min.js"></script>
    <!-- Sparkline -->
    <script src="{theme_url}AdminLTE/plugins/sparklines/sparkline.js"></script>
    <!-- JQVMap -->
    <script src="{theme_url}AdminLTE/plugins/jqvmap/jquery.vmap.min.js"></script>
    <script src="{theme_url}AdminLTE/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="{theme_url}AdminLTE/plugins/jquery-knob/jquery.knob.min.js"></script>
    <!-- daterangepicker -->
    <script src="{theme_url}AdminLTE/plugins/moment/moment.min.js"></script>
    <script src="{theme_url}AdminLTE/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{theme_url}AdminLTE/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Summernote -->
    <script src="{theme_url}AdminLTE/plugins/summernote/summernote-bs4.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="{theme_url}AdminLTE/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="{theme_url}AdminLTE/dist/js/adminlte.js"></script>

    <!-- Datatables -->
    <script src="{theme_url}AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{theme_url}AdminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{theme_url}AdminLTE/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="{theme_url}AdminLTE/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="{theme_url}AdminLTE/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <script src="{theme_url}AdminLTE/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="{theme_url}AdminLTE/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="{theme_url}AdminLTE/plugins/jszip/jszip.min.js"></script>
    <script src="{theme_url}AdminLTE/plugins/pdfmake/pdfmake.min.js"></script>
    <script src="{theme_url}AdminLTE/plugins/pdfmake/vfs_fonts.js"></script>
    <script src="{theme_url}AdminLTE/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{theme_url}AdminLTE/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="{theme_url}AdminLTE/plugins/datatables-rowgroup/js/dataTables.rowGroup.min.js"></script>
    <script src="{theme_url}AdminLTE/plugins/datatables-rowgroup/js/rowGroup.bootstrap4.min.js"></script>
    <script src="{theme_url}AdminLTE/plugins/datatables-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="{theme_url}AdminLTE/plugins/datatables-keytable/js/keyTable.bootstrap4.min.js"></script>

    <!-- Toastr-->
    <script src="{theme_url}AdminLTE/plugins/toastr/toastr.min.js"></script>
    <script src="{theme_url}AdminLTE/plugins/simple-calendar/dist/jquery.simple-calendar.min.js"></script>



    <script type="text/javascript" src="{theme_url}AdminLTE/plugins/sweetalert2/sweetalert2.min.js"></script>
    <script type="text/javascript" src="{theme_url}AdminLTE/plugins/bootstrap-select/dist/js/bootstrap-select.min.js">
    </script>
    <script type="text/javascript">
    $(document).ready(function() {
        let currentUrl = window.location.href;
        $('nav a').each(function() {
            if ($(this).attr('href') === currentUrl) {
                $(this).addClass('active');

            }
        });

        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "500",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        <?= $this->sweetalert->SwalToastNew(); ?>


        let inputFile = $('.custom-file-input');
        let labelFile = $('.custom-file-label');

        inputFile.on('change', function(event) {
            let fileName = event.target.files[0].name;
            labelFile.text(fileName);

        });


        $('.check-all-trig').on('click', function() {
            let input = $(this).is(":checked");
            if (input) {
                $('.check-id').prop("checked", true);
            } else {
                $('.check-id').prop("checked", false);
            }
        });

        $('table').css('width', '100%');
        user_info();
    });

    function user_info() {
        $.get("<?= base_url('profile/user_info'); ?>", function(data) {
            $('.user-img').attr('src', '<?= $theme_url . 'img/profile/'; ?>' + data.img);
            $('.user-fullname-title').text(data.full_name);
        }, 'json');
    }
    </script>

    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{theme_url}img/logosmkn1bantul.png" alt="..." height="150px"
                width="150px">
        </div>

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            {navbar}
        </nav>
        <!-- /.navbar -->


        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="<?= base_url('admin'); ?>" class="brand-link">
                <img src="{theme_url}img/logosmkn1bantul.png" alt="SMKn 1 Bantul"
                    class="brand-image img-circle elevation-3">


                <?php if ($this->ion_auth->is_programmer()) : ?>
                <span class="brand-text font-weight-light ml-3">Programmer</span>
                <?php elseif ($this->ion_auth->is_admin()) : ?>
                <span class="brand-text font-weight-light ml-3">Administrator</span>
                <?php else : ?>
                <span class="brand-text font-weight-light ml-3">Guru</span>
                <?php endif; ?>



            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                {sidebar}
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header border-bottom border-dark mb-4">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h4 class="ml-2">{subtitle}</h1>

                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <?php $segment1 = $this->uri->segment(1); ?>
                                <?php $segment2 = $this->uri->segment(2); ?>

                                <li class="breadcrumb-item"><a
                                        href="<?= ($segment1) ? ucwords($segment1) : ''; ?>"><?php if ($segment1) {
                                                                                                                        echo ucwords($segment1);
                                                                                                                    } ?></a>
                                </li>
                                <li class="breadcrumb-item"><a
                                        href="<?= ($segment2) ? ucwords($segment2) : ''; ?>"><?php if ($segment2) {
                                                                                                                        echo ucwords($segment2);
                                                                                                                    } ?></a>
                                </li>
                            </ol>

                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">

                    {content}
                </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <strong class="text-black">Developed By <b>SiMbah 12 PPLG 2 Force 24.</b></strong>

            <div class="float-right d-none d-sm-inline-block">
                <b></b>
            </div>
        </footer>
        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->

        <!-- Scroll to Top Button-->
        <!-- <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a> -->

        <!-- Logout Modal-->
        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <a class="btn btn-primary" href="<?= base_url('auth/logout'); ?>">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ./wrapper -->

</body>

</html>