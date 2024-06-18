<?php
include_once('includes/config.php');

$private_available = false;
$table_available = false;

$stores = [16, 21, 25, 23];

if (isset($_POST['day_select'], $_POST['year_select'], $_POST['month_select']) && !empty($_POST['day_select']) && !empty($_POST['year_select']) && !empty($_POST['month_select'])) {


    $_POST['month_select'] = $_POST['month_select'] < 10 ? '0' . $_POST['month_select'] : $_POST['month_select'];

    $date = $_POST['year_select'] . '-' . $_POST['month_select'] . '-' . $_POST['day_select'];



    if ($date < date('Y-m-d')) {
        $_SESSION['msg'] = 'Please select date greater than from current date';
        header('location:' . $_SERVER['PHP_SELF']);
        exit;
    }

    $slots_all = ORM::for_table('membership_slots')->where('status', 1)->find_many();

    $slot_arr = [];

    foreach ($slots_all as $s) {
        $slot_arr[] = $s->id;
    }



    $booked_slot = [];
    $st = array(16, 21, 23);
    foreach ($st as $store_id) {

        $private = ORM::for_table('shared_reservation_2')->where('status', 1)->where('store_id', $store_id)->where('reservation_date', $date)->find_many();

        foreach ($private as $p) {
            $booked_slot[] = $p->id;
        }

        $diff = array_diff($slot_arr, $booked_slot);

        if (!empty($diff)) {
            $private_available = true;
            break 1;
        }
    }


    $membership_tables = ORM::for_table('membership_tables')->where('store_id', 25)->where('status', 1)->find_many();

    foreach ($membership_tables as $m) {

        foreach ($slots_all as $s) {

            $private = ORM::for_table('shared_reservation_2')->where('store_id', 25)->where('slot', $s->id)->where('status', 1)->where('reservation_date', $date)->where_raw('(FIND_IN_SET(' . $m->id . ', table_id))')->find_many();

            if ($private->count() == 0) {
                $table_available = true;
                break 2;
            }
        }
    }

    $private_min_price = ORM::for_table('membership_packages')->where('status', 1)->where('type', 'PRIVATE')->min('price');
    $private_max_price = ORM::for_table('membership_packages')->where('status', 1)->where('type', 'PRIVATE')->max('price');

    $table_min_price = ORM::for_table('membership_packages')->where('status', 1)->where('type', 'SUBSCRIPTION')->min('price');
    $table_max_price = ORM::for_table('membership_packages')->where('status', 1)->where('type', 'SUBSCRIPTION')->max('price');
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="apple-touch-icon" sizes="180x180" href="https://24kmember.com/latest/images/logo/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="https://24kmember.com/latest/images/logo/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="https://24kmember.com/latest/images/logo/favicon-16x16.png">
    <link rel="manifest" href="https://24kmember.com/latest/images/logo/site.webmanifest">
    <!--  CSS Files -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://24kmember.com/latest/assets_home/css/owl.carousel.min.css">
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/swiper/swiper-bundle.css">
    <!-- Template Main CSS File -->
    <link href="https://24kmember.com/latest/assets_home/fonts/font.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://24kmember.com/latest/styles/style.css">
    <link href="https://24kmember.com/latest/assets_home/css/style.css" rel="stylesheet" />
    <title><?php echo BRAND_NAME; ?></title>
    <link href="https://24kmember.com/latest/dashboard_asset/css/style.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://24kmember.com/latest/styles/style-ajax-dashboard.css">
    <link href="https://24kmember.com/latest/admin/styles/booking_style.css" rel="stylesheet" />
    <style>
        .custom-select:focus {
            -webkit-appearance: auto !important;
        }

        .nav-fill .nav-item {
            color: #8b8787;
        }

        .new_grad {
            padding: 2px;
            border: none;
            border-radius: 10px;
            color: #F2C94C;
            font-size: 16px;
            font-weight: 500;
            background-image: linear-gradient(91.38deg, #FF9700 1.03%, rgba(180, 62, 235, 0.89) 48.76%, #F8CD54 100%);
        }
    </style>
</head>

<body class="pb-0" style="background:#000;">
    <div id="page">
        <div class="pt-4">
            <div class="container">

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="#"><img src="https://24kmember.com/latest/images/logo/logo.png" data-bs-toggle="modal" data-bs-target="#galleria25" class="logos" alt="logo"></a>
                            <a href="#"><img src="https://24kmember.com/latest/images/stores/logo/room24new.png" data-bs-toggle="modal" data-bs-target="#galleria23" class="logos" alt="logo"></a>
                            <a href="#"><img src="https://24kmember.com/latest/images/stores/logo/24kMirror.png" data-bs-toggle="modal" data-bs-target="#galleria16" class="logos" alt="logo"></a>
                            <a href="#"><img src="https://24kmember.com/latest/images/stores/logo/dreams.png" data-bs-toggle="modal" data-bs-target="#galleria21" class="logos" alt="logo"></a>
                        </div>
                        <div class="d-flex justify-content-between align-items-center gap-3">
                            <div class="package-bdr w-50">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#galleria25" class="btn package-details-btn text-center font-9">View pics</a>
                            </div>

                            <div class="package-bdr w-50">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#galleria23" class="btn package-details-btn text-center font-9">View pics</a>
                            </div>

                            <div class="package-bdr w-50">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#galleria16" class="btn package-details-btn text-center font-9">View pics</a>
                            </div>

                            <div class="package-bdr w-50">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#galleria21" class="btn package-details-btn text-center font-9">View pics</a>
                            </div>

                        </div>


                        <div class="d-flex justify-content-between align-items-center  mt-5">
                            <p class="text-center font-14 font-600 mb-0 pt-2"><span class="grdient_color">Location:</span> Downtown Brooklyn</p>

                            <div class="d-flex gap-2">
                                <div class="package-bdr w-50">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#detailsModal1" class="btn package-details-btn text-center font-14">Menu</a>
                                </div>
                                <div class="package-bdr w-50">
                                    <a href="<?= APP_URL ?>signin.php" type="button" class="btn package-details-btn text-center font-14">Login</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-0">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <h3 class="text-center text-white font-22">Check Availability</h3>
                    </div>
                </div>



                <?php echo handle_msg('msg') ?>

                <div class="row">

                    <form action="" method="post" id="booking_form_main" onsubmit="document.getElementById('up_submit').style.pointerEvents='none';">
                        <input type="hidden" name="year_select" id="year_select">
                        <input type="hidden" name="month_select" id="month_select">
                        <input type="hidden" name="day_select" id="day_select">
                        <input type="hidden" name="slot" id="slot_id">



                        <div class="row mb-0">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-3">
                                <label class="grdient_color font-600">Select Month</label>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <a href="#" class="btn-left btn-link p-2 toggle text-dark"><i class="fa fa-angle-left"></i></a>
                                    </div>
                                    <div class="flex-grow-1 w-100 o-hidden">
                                        <ul class="nav nav-fill text-uppercase small position-relative flex-nowrap" id="mnthDiv" style="overflow:auto;">

                                            <?php
                                            $currentDate = new DateTime();
                                            $endDate = (new DateTime())->modify('+6 months');
                                            $filteredDates = array();
                                            $currentDate->modify('first day of this month');
                                            while ($currentDate <= $endDate) {

                                                if (!($currentDate->format('Y-m-d') >= '2024-06-15')) {
                                                    $currentDate->modify('+1 day');
                                                    continue;
                                                }

                                                $daynum =  $currentDate->format('d');
                                                $day_show = $currentDate->format('D');
                                                $month_show = $currentDate->format('M');
                                                $year = $currentDate->format('Y');
                                                $month_number = $currentDate->format('m');
                                            ?>

                                                <li class="nav-item" id="<?php echo 'nav_link_date1' . $month_number; ?>" onclick="selectMonth('nav_link_date1<?= $month_number ?>',<?= $month_number ?>,<?= $year ?>);">
                                                    <a href="#" onclick="return false;" class="nav-link">
                                                        <span style="color: #8b8787;">
                                                            <?php echo $month_show; ?><br>
                                                    </a>
                                                </li>
                                            <?php


                                                $currentDate->modify('+1 Month');
                                            }
                                            ?>

                                        </ul>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <a href="#" class="btn-right btn-link toggle p-2 text-dark"><i class="fa fa-angle-right"></i></a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-3">
                                <label class="grdient_color font-600">Select Day</label>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <a href="#" class="btn-left btn-link p-2 toggle text-dark"><i class="fa fa-angle-left"></i></a>
                                    </div>
                                    <div class="flex-grow-1 w-100 o-hidden">
                                        <ul class="nav nav-fill text-uppercase small position-relative flex-nowrap" id="dayDiv" style="overflow:auto;">


                                        </ul>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <a href="#" class="btn-right btn-link toggle p-2 text-dark"><i class="fa fa-angle-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>





                        <div class="col-lg-12 col-sm-12 my-3 text-center"> <input type="submit" name="submit" id="up_submit" value="Check A Date" class="btn btn-s btn_grad font-600 rounded-s scale-box" style="font-size: 18px !important; width: 95%; color: #fff !important;"></div>

                    </form>
                </div>



                <div class="row mb-2">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <h3 class="text-center text-white font-22 mb-3"><?= ($table_available || $private_available) ? 'There is Availablity' : ''; ?></h3>
                    </div>


                    <?php if ($table_available) { ?>

                        <div class="col-lg-6 col-md-6 col-sm-6 col-6">

                            <div class="grad_box mb-3">
                                <div class="p10">
                                    <h5 class="package-title mb-0 letter-space-04 font-18">Table Booking</h5>
                                    <h5 class="mb-1 mt-4 package-price letter-space-04 pb-2 pt-1 font-18">From:<br />$<?= $table_min_price . ' - $' . $table_max_price ?></h5>
                                    <div class="package-bdr w-100">
                                        <a href="https://thagalleria.com/signin.php?source=24kmember&date=<?= $date ?>" class="btn package-details-btn text-center font-14">Confirm Package</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php }
                    if ($private_available) { ?>

                        <div class="col-lg-6 col-md-6 col-sm-6 col-6">

                            <div class="grad_box mb-3">
                                <div class="p10">
                                    <h5 class="package-title mb-0 letter-space-04 font-18">100% Private Dining</h5>
                                    <h5 class="mb-1 package-price letter-space-04 py-2 font-18">From:<br />$<?= $private_min_price . ' - $' . $private_max_price ?></h5>
                                    <div class="package-bdr w-100">
                                        <a href="https://24kmember.com/latest/signin.php?source=24kmember&date=<?= $date ?>" class="btn package-details-btn text-center font-14">Confirm Package</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php } ?>
                </div>





            </div>
        </div>
    </div>


    <!-- package menu -->
    <?php



    require_once('includes/package_menu.php'); ?>



    <!-- Details Modal Start -->
    <div class="modal" id="detailsModal1" style="padding-top: 0px; padding-bottom: 0px; z-index: 9999;" aria-modal="true" role="dialog">
        <div class="modal-dialog mt-5">

            <!-- Modal body -->
            <div class="modal-body" style="padding:0;">
                <div class="rounded-m new_grad" style="pointer-events:all;">
                    <div class="rounded-m p-2" style="background-color: #181717;">
                        <div class="menu-title">
                            <a href="#" class="close-menumb-3" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i></a>
                            <h3 class="grdient_color1 mt-2 ">Menu </h3>
                        </div>
                        <div class="">

                            <p class=".f_16 fw_600 mb-2" style="color:#ffffff;font-size:18px;"></p>

                            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                <?php
                                $counter = 1;
                                $menu_item = ORM::for_table('sys_menu')->where('store_id', 25)->where('category_id', 39)->where('status', 1)->order_by_asc('sort_order')->find_many();

                                foreach ($menu_item as $v) {
                                ?>

                                    <div>

                                        <a href="#">
                                            <h1 class="text-white py-3 font-26"><?php echo $v->menu_name ?></h1>
                                        </a>

                                        <?php
                                        $item_count = 0;
                                        $sub_item = ORM::for_table('sys_menu_options')->where('menu_id', $v->id)->find_many();
                                        if (count($sub_item) > 0) {
                                            foreach ($sub_item as $v1) {
                                        ?>
                                                <a href="#">
                                                    <h3 class="font-20 ms-4 mb-2" style="color:#F6BB42;"><?php echo $v1->title ?></h3>
                                                </a>
                                    <?php $item_count++;
                                                if (count($sub_item) == $item_count) {
                                                    echo '</div>';
                                                }
                                            }
                                        }
                                        if (count($sub_item) == 0) {
                                            echo '</div>';
                                        }
                                        $counter++;
                                    } ?>

                                    </div>



                            </div>


                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- Details Modal End   -->


    <!-- galleria25 Modal Start -->

    <div class="modal" id="galleria25" tabindex="-1" style="padding-top: 0px; padding-bottom: 0px; z-index: 9999;" aria-modal="true">
        <div class="modal-dialog mt-5" style="background-color: #181717;">
            <div class="modal-content rounded-m btn_grad">
                <div class="rounded-m p-2" style="background-color: #181717;">
                    <div class="modal-header">

                        <h3 class="grdient_color1 ">The Galleria </h3>
                        <button type="button" class="close-menumb-3 " data-bs-dismiss="modal"><i class="fa fa-times-circle"></i></button>

                        <!--<h5 class="modal-title text-white py-3 font-26">The Galleria</h5>-->
                        <!--   <button type="button" class="btn-close close-menumb-3 mt-2 bg-white" data-bs-dismiss="modal" aria-label="Close"></button>-->
                    </div>
                    <div class="modal-body" style="padding:0;">

                        <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <?php

                                $images = ORM::for_table('sys_gallery')->where('store_id', 25)->where('type', 0)->find_many();

                                foreach ($images as $k => $image) { ?>

                                    <div class="carousel-item <?= $k == 0 ? 'active' : ''; ?>">
                                        <img class="d-block w-100" src="https://24kmember.com/images/stores/gallery/<?= $image->image ?>" alt="Second slide">
                                    </div>

                                <?php } ?>

                            </div>

                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>


                    </div>

                </div>
            </div>

        </div>
    </div>


    <!-- galleria25 Modal End   -->

    <!-- galleria23 Modal Start -->

    <div class="modal" id="galleria23" tabindex="-1" style="padding-top: 0px; padding-bottom: 0px; z-index: 9999;" aria-modal="true">
        <div class="modal-dialog mt-5">
            <div class="modal-content rounded-m btn_grad">
                <div class="rounded-m p-2" style="background-color: #181717;">
                    <div class="modal-header">

                        <h3 class="grdient_color1 ">Room 24k</h3>
                        <button type="button" class="close-menumb-3 " data-bs-dismiss="modal"><i class="fa fa-times-circle"></i></button>

                    </div>
                    <div class="modal-body">
                        <div id="room24k" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">

                                <?php

                                $images = ORM::for_table('sys_gallery')->where('store_id', 23)->where('type', 0)->find_many();

                                foreach ($images as $k => $image) { ?>

                                    <div class="carousel-item <?= $k == 0 ? 'active' : ''; ?>">
                                        <img class="d-block w-100" src="https://24kmember.com/images/stores/gallery/<?= $image->image ?>" alt="Second slide">
                                    </div>

                                <?php } ?>

                                <button class="carousel-control-prev" type="button" data-bs-target="#room24k" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#room24k" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>


                            </div>

                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

        <!-- galleria23 Modal End -->

        <!-- galleria16 Modal Start -->

        <div class="modal" id="galleria16" tabindex="-1" style="padding-top: 0px; padding-bottom: 0px; z-index: 9999;" aria-modal="true">
            <div class="modal-dialog mt-5">
                <div class="modal-content rounded-m btn_grad">

                    <div class="rounded-m p-2" style="background-color: #181717;">
                        <div class="modal-header">

                            <h3 class="grdient_color1 ">24k MIRROR </h3>
                            <button type="button" class="close-menumb-3" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i></button>

                        </div>
                        <div class="modal-body">

                            <div id="mirror" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">

                                    <?php
                                    $images = ORM::for_table('sys_gallery')->where('store_id', 16)->where('type', 0)->find_many();

                                    foreach ($images as $k => $image) { ?>

                                        <div class="carousel-item <?= $k == 0 ? 'active' : ''; ?>">
                                            <img class="d-block w-100" src="https://24kmember.com/images/stores/gallery/<?= $image->image ?>" alt="Second slide">
                                        </div>


                                    <?php } ?>


                                </div>

                                <button class="carousel-control-prev" type="button" data-bs-target="#mirror" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#mirror" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>

                            </div>


                        </div>

                    </div>

                </div>
            </div>
        </div>


        <!-- galleria16 Modal End -->


        <!-- galleria21 Modal Start -->

        <div class="modal" id="galleria21" tabindex="-1" style="padding-top: 0px; padding-bottom: 0px; z-index: 9999;" aria-modal="true">
            <div class="modal-dialog mt-5">
                <div class="modal-content rounded-m btn_grad">

                    <div class="rounded-m p-2" style="background-color: #181717;">

                        <div class="modal-header">

                            <h3 class="grdient_color1">24K DREAMS</h3>
                            <button type="button" class="close-menumb-3 " data-bs-dismiss="modal"><i class="fa fa-times-circle"></i></button>


                        </div>
                        <div class="modal-body">
                            <div id="dream" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    <?php
                                    $images = ORM::for_table('sys_gallery')->where('store_id', 21)->where('type', 0)->find_many();

                                    foreach ($images as $k => $image) { ?>

                                        <div class="carousel-item <?= $k == 0 ? 'active' : ''; ?>">
                                            <img class="d-block w-100" src="https://24kmember.com/images/stores/gallery/<?= $image->image ?>" alt="Second slide">
                                        </div>

                                    <?php } ?>

                                </div>

                                <button class="carousel-control-prev" type="button" data-bs-target="#dream" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#dream" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>

                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>


        <!-- galleria21 Modal End -->






        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
        <script src="https://24kmember.com/admin/scripts/custom.js"></script>

        <script>
            function selectMonth(valchkk, val, yearval) {


                $("#mnthDiv li").removeClass("active-date");

                $('#' + valchkk).addClass('active-date');

                document.getElementById("year_select").value = yearval;
                document.getElementById("month_select").value = val;

                document.getElementById("day_select").value = '';
                document.getElementById("slot_id").value = '';

                $.ajax({
                    url: "https://24kmember.com/latest/ajax-get-month-start.php",
                    type: "POST",
                    data: {
                        month: val,
                        year: yearval,
                    },
                    cache: false,
                    success: function(result) {
                        $("#dayDiv").html(result);
                    }
                });

            }

            function selectDay(valchkk, month, year) {

                document.getElementById("day_select").value = valchkk;

                document.getElementById("slot_id").value = '';

                $("#dayDiv li").removeClass("active-date");

                $('#' + valchkk).addClass('active-date');

                $.ajax({
                    url: 'membership_process.php',
                    type: "POST",
                    data: {
                        action: 'get_slots',
                    },
                    cache: false,
                    success: function(result) {

                        $("#slotDiv").html(result);

                    }
                });

            }

            function selectSlot(valchkk, slot_id) {

                document.getElementById("slot_id").value = slot_id;

                $("#slotDiv li").removeClass("active-date");
                $('#' + valchkk).remove("active-date");

                $('#' + valchkk).addClass('active-date');

            }
        </script>
</body>

</html>