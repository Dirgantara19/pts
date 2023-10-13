<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{pagetitle}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{theme_url}AdminLTE/plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{theme_url}AdminLTE/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{theme_url}AdminLTE/dist/css/adminlte.css">
    <link rel="stylesheet" href="{theme_url}vendor/toastr/build/toastr.min.css">
    <link rel="stylesheet" href="{theme_url}mystyles/styles.css">
    <link rel="shortcut icon" href="{theme_url}img/logosmkn1bantul.png" type="image/x-icon">

</head>

<body class="hold-transition login-page bg-auth">
    {content}

    <!-- /.login-box -->
    <!-- jQuery -->
    <script src="{theme_url}AdminLTE/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="{theme_url}AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="{theme_url}AdminLTE/dist/js/adminlte.min.js"></script>
    <script src="{theme_url}vendor/sweetalert2/sweetalert2.js"></script>
    <script src="{theme_url}vendor/toastr/build/toastr.min.js"></script>
    <script type="text/javascript">
        <?= $this->sweetalert->SwalToastNew(); ?>
    </script>



</body>

</html>