<meta charset="UTF-8">

<meta http-equiv="X-UA-Compatible" content="IE=edge">

<meta name="viewport" content="width=device-width, initial-scale=1.0">





<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">



<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

<link rel="stylesheet" href="css/flaticon.css">



<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">



<title><?php echo $TITLE; ?></title>

<!-- Font Awesome -->

<link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">

<!-- Ionicons -->

<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

<!-- Tempusdominus Bbootstrap 4 -->

<link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">

<!-- iCheck -->

<link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">

<!-- JQVMap -->

<link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">

<!-- Theme style -->

<link rel="stylesheet" href="dist/css/adminlte.min.css">



<!-- overlayScrollbars -->

<link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">

<!-- Daterange picker -->

<link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">

<!-- summernote -->

<link rel="stylesheet" href="plugins/summernote/summernote-bs4.css">



<!-- Select2 -->

<link rel="stylesheet" href="plugins/select2/css/select2.min.css">

<link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

<!-- DataTables -->

<link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">

<link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">

<link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

<!-- SweetAlert2 -->

<link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">

<!-- Bootstrap4 Duallistbox -->

<link rel="stylesheet" href="plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">

<!-- jQuery -->

<script src="plugins/jquery/jquery.min.js"></script>

<!-- jQuery UI 1.11.4 -->

<script src="plugins/jquery-ui/jquery-ui.min.js"></script>

<link rel="stylesheet" href="css/prati.css">

<style>
    .logo_img {
        max-width: 100px;
        height: auto;
    }

    .btn {
        border: 1px solid #315292;
        background: #315292;
        color: #fff;
    }

    .navbar-light .navbar-nav .nav-link {
        color: #fff;
    }

    .review img {
        max-width: 100%;
    }

    img.c_avatar {
        width: 70%;
        border-radius: 20px;
    }

    img.c_rating {
        max-width: 50%;
    }

    .card {
        box-shadow: rgb(0 0 0 / 24%) 0px 3px 8px;
        border-radius: 20px;
    }

    /* floating ginnie */
    .hide_ginnie {
        display: none;
        width: auto;
        z-index: 111;
    }

    .quote_btn:hover+.hide_ginnie {
        display: block;
        position: absolute;
        top: 100%;
        display: flex;

    }

    .zip_form:hover+.hide_ginnie {
        display: block;
        position: absolute;
        bottom: 80%;
        display: flex;

    }

    .hide_ginnie .card-body {
        padding: 2px;
    }

    .hide_ginnie p {
        font-size: 12px;
    }
    .hide_ginnie img {
    width: 100px;
    margin: 5px;
    }
    @media (min-width: 401px) {
        .hide_ginnie.col-lg-4 {
            width: 50%;
            }
    }
    @media (max-width: 400px) {
        .hide_ginnie.col-lg-4 {
            width: 100%;
            }
    }
    @media (max-width: 767px) {
        .quote_btn:hover+.hide_ginnie {
            right: 5px;
        }
    }

    .col-md-6.right_text {
    box-shadow: -15px 7px 24px -13px rgb(0 0 0 / 27%);
    border-radius: 50px 0 0 50px;
    }
    .col-md-6.left_text {
    box-shadow: 13px 11px 24px -13px rgb(0 0 0 / 27%);
    border-radius: 0 50px 50px 0;
    }
    .how_it_works img {
        width: 25%;
    }
    .row.infor {
        margin: 10% 0;
    }
</style>