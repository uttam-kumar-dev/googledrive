<?php include __DIR__ . '/../config/config.php';

is_logged_in(true);

session()->set('current_url', $_SERVER['REQUEST_URI']);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Drive</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/5.3.45/css/materialdesignicons.css" integrity="sha256-NAxhqDvtY0l4xn+YVa6WjAcmd94NNfttjNsDmNatFVc=" crossorigin="anonymous" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <link rel="stylesheet" type="text/css" href="<?= assets('css/style.css'); ?>">
</head>

<body>

    <div class="conatiner-fluid mx-2">
        <div class="row gx-0">
            <div class="col-2">
                <div class="card border w-inherit position-fixed">
                    <div class="card-body">


                        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                            <li class="nav-item">
                                <a class="nav-link rounded-pill px-2 active" aria-current="page" href="#"><i class="mdi mdi-home me-1"></i> Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link rounded-pill px-2" href="#"><i class="mdi mdi-book-lock me-1"></i> My Drive</a>
                            </li>

                            <li>
                                <hr class="divider">
                            </li>

                            <li class="nav-item">
                                <a class="nav-link rounded-pill px-2" href="#"> <i class="mdi mdi-account-multiple me-1"></i> Shared with me</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link rounded-pill px-2" href="#"> <i class="mdi mdi-clock-time-seven me-1 "></i> Recent</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link rounded-pill px-2" href="#"> <i class="mdi mdi-star-outline me-1"></i>Starred</a>
                            </li>

                            <li>
                                <hr class="divider">
                            </li>

                            <li class="nav-item">
                                <a class="nav-link rounded-pill px-2" href="#"> <i class="mdi mdi mdi-alert-circle-outline me-1"></i> Spam</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link rounded-pill px-2" href="#"> <i class="mdi mdi mdi-trash-can me-1"></i>Trash</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link rounded-pill px-2" href="#"> <i class="mdi mdi mdi-cloud-lock-outline"></i> Storage</a>
                            </li>

                            <li class="nav-item">
                                <div class="progress animated-progess custom-progress mt-3 mb-1" style="height: 5px;">
                                    <div class="progress-bar bg-gradient bg-primary" role="progressbar" style="width: <?= getSizeAll(); ?>%" aria-valuenow="<?= getSizeAll(); ?>" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>

                                <p class="text-muted"><?= getSizeAll(true); ?> of 15 GB used</p>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-10">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-lg-4 col-sm-6">
                                            <div class="search-box mb-2 me-2">
                                                <div class="position-relative">
                                                    <input type="text" class="form-control bg-light border-light rounded" placeholder="Search...">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" class="eva eva-search-outline search-icon">
                                                        <g data-name="Layer 2">
                                                            <g data-name="search">
                                                                <rect width="24" height="24" opacity="0"></rect>
                                                                <path d="M20.71 19.29l-3.4-3.39A7.92 7.92 0 0 0 19 11a8 8 0 1 0-8 8 7.92 7.92 0 0 0 4.9-1.69l3.39 3.4a1 1 0 0 0 1.42 0 1 1 0 0 0 0-1.42zM5 11a6 6 0 1 1 6 6 6 6 0 0 1-6-6z"></path>
                                                            </g>
                                                        </g>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-8 col-sm-6">
                                            <div class="mt-4 mt-sm-0 d-flex align-items-center justify-content-sm-end">

                                                <div class="mb-2 me-2">
                                                    <div class="dropdown">
                                                        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="mdi mdi-plus me-1"></i> Create New
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#create_folder"><i class="mdi mdi-folder-outline me-1"></i> Folder</a>
                                                            <a class="dropdown-item" href="#"><i class="mdi mdi-file-outline me-1"></i> File</a>
                                                        </div>


                                                    </div>
                                                </div>

                                                <div class="dropdown mb-0">
                                                    <a class="btn btn-link text-muted dropdown-toggle p-1 mt-n2" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                        <i class="mdi mdi-dots-vertical font-size-20"></i>
                                                    </a>

                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="#">Share Files</a>
                                                        <a class="dropdown-item" href="#">Share with me</a>
                                                        <a class="dropdown-item" href="#">Other Actions</a>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                    </div>

                                    <h5 class="font-size-16 me-3 mb-0"><?= breadcrumbs(); ?></h5>
                                    <div class="row mt-4">
                                        <div class="col-xl-4 col-sm-6">
                                            <div class="card shadow-none border">
                                                <div class="card-body p-3">
                                                    <div class="">
                                                        <div class="dropdown float-end">
                                                            <a class="text-muted dropdown-toggle font-size-16" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                                <i class="bx bx-dots-vertical-rounded font-size-20"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item" href="#">Edit</a>
                                                                <a class="dropdown-item" href="#">Action</a>
                                                                <a class="dropdown-item" href="#">Remove</a>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar align-self-center me-3">
                                                                <div class="avatar-title rounded bg-soft-primary text-primary font-size-24">
                                                                    <i class="mdi mdi-google-drive"></i>
                                                                </div>
                                                            </div>

                                                            <div class="flex-1">
                                                                <h5 class="font-size-15 mb-1">Google Drive</h5>
                                                                <a href="" class="font-size-13 text-muted"><u>View Folder</u></a>
                                                            </div>
                                                        </div>
                                                        <div class="mt-3 pt-1">
                                                            <div class="d-flex justify-content-between">
                                                                <p class="text-muted font-size-13 mb-1">20GB</p>
                                                                <p class="text-muted font-size-13 mb-1">50GB used</p>
                                                            </div>
                                                            <div class="progress animated-progess custom-progress">
                                                                <div class="progress-bar bg-gradient bg-primary" role="progressbar" style="width: 90%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="90">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end col -->

                                        <div class="col-xl-4 col-sm-6">
                                            <div class="card shadow-none border">
                                                <div class="card-body p-3">
                                                    <div class="">
                                                        <div class="dropdown float-end">
                                                            <a class="text-muted dropdown-toggle font-size-16" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                                <i class="bx bx-dots-vertical-rounded font-size-20"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item" href="#">Edit</a>
                                                                <a class="dropdown-item" href="#">Action</a>
                                                                <a class="dropdown-item" href="#">Remove</a>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar align-self-center me-3">
                                                                <div class="avatar-title rounded bg-soft-info text-info font-size-24">
                                                                    <i class="mdi mdi-dropbox"></i>
                                                                </div>
                                                            </div>

                                                            <div class="flex-1">
                                                                <h5 class="font-size-15 mb-1">Dropbox</h5>
                                                                <a href="" class="font-size-13 text-muted"><u>View Folder</u></a>
                                                            </div>

                                                        </div>
                                                        <div class="mt-3 pt-1">
                                                            <div class="d-flex justify-content-between">
                                                                <p class="text-muted font-size-13 mb-1">20GB</p>
                                                                <p class="text-muted font-size-13 mb-1">50GB used</p>
                                                            </div>
                                                            <div class="progress animated-progess custom-progress">
                                                                <div class="progress-bar bg-gradient bg-info" role="progressbar" style="width: 90%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="90">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end col -->

                                        <div class="col-xl-4 col-sm-6">
                                            <div class="card shadow-none border">
                                                <div class="card-body p-3">
                                                    <div class="">
                                                        <div class="dropdown float-end">
                                                            <a class="text-muted dropdown-toggle font-size-16" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                                <i class="bx bx-dots-vertical-rounded font-size-20"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item" href="#">Edit</a>
                                                                <a class="dropdown-item" href="#">Action</a>
                                                                <a class="dropdown-item" href="#">Remove</a>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar align-self-center me-3">
                                                                <div class="avatar-title rounded bg-soft-primary text-primary font-size-24">
                                                                    <i class="mdi mdi-apple-icloud"></i>
                                                                </div>
                                                            </div>

                                                            <div class="flex-1">
                                                                <h5 class="font-size-15 mb-1">One Drive</h5>
                                                                <a href="" class="font-size-13 text-muted"><u>View Folder</u></a>
                                                            </div>

                                                        </div>
                                                        <div class="mt-3 pt-1">
                                                            <div class="d-flex justify-content-between">
                                                                <p class="text-muted font-size-13 mb-1">20GB</p>
                                                                <p class="text-muted font-size-13 mb-1">50GB used</p>
                                                            </div>
                                                            <div class="progress animated-progess custom-progress">
                                                                <div class="progress-bar bg-gradient bg-primary" role="progressbar" style="width: 90%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="90">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end col -->

                                    </div>
                                    <!-- end row -->

                                    <?php if(isset($_GET['fd']) && !empty($_GET['fd'])){ 
                                        require_once 'folders.php';
                                     }else{
                                        require_once 'recent_folders.php';
                                     } ?>
                                    <!-- end row -->
                                    <div class="d-flex flex-wrap">
                                        <h5 class="font-size-16 me-3">Recent Files</h5>
                                        <div class="ms-auto">
                                            <a href="javascript: void(0);" class="fw-medium text-reset">View All</a>
                                        </div>
                                    </div>
                                    <hr class="mt-2">
                                    <div class="table-responsive">
                                        <table class="table align-middle table-nowrap table-hover mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th scope="col">Name</th>
                                                    <th scope="col">Date modified</th>
                                                    <th scope="col">Size</th>
                                                    <th scope="col" colspan="2">Members</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><a href="javascript: void(0);" class="text-dark fw-medium"><i class="mdi mdi-file-document font-size-16 align-middle text-primary me-2"></i> index.html</a></td>
                                                    <td>12-10-2020, 09:45</td>
                                                    <td>09 KB</td>
                                                    <td>
                                                        <div class="avatar-group">
                                                            <div class="avatar-group-item">
                                                                <a href="javascript: void(0);" class="d-inline-block">
                                                                    <img src="https://bootdey.com/img/Content/avatar/avatar6.png" alt="" class="rounded-circle avatar-sm">
                                                                </a>
                                                            </div>
                                                            <div class="avatar-group-item">
                                                                <a href="javascript: void(0);" class="d-inline-block">
                                                                    <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="" class="rounded-circle avatar-sm">
                                                                </a>
                                                            </div>
                                                            <div class="avatar-group-item">
                                                                <a href="javascript: void(0);" class="d-inline-block">
                                                                    <div class="avatar-sm">
                                                                        <span class="avatar-title rounded-circle bg-success text-white font-size-16">
                                                                            A
                                                                        </span>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <a class="font-size-16 text-muted" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                                <i class="mdi mdi-dots-horizontal"></i>
                                                            </a>

                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item" href="#">Open</a>
                                                                <a class="dropdown-item" href="#">Edit</a>
                                                                <a class="dropdown-item" href="#">Rename</a>
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item" href="#">Remove</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><a href="javascript: void(0);" class="text-dark fw-medium"><i class="mdi mdi-folder-zip font-size-16 align-middle text-warning me-2"></i> Project-A.zip</a></td>
                                                    <td>11-10-2020, 17:05</td>
                                                    <td>115 KB</td>
                                                    <td>
                                                        <div class="avatar-group">
                                                            <div class="avatar-group-item">
                                                                <a href="javascript: void(0);" class="d-inline-block">
                                                                    <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="" class="rounded-circle avatar-sm">
                                                                </a>
                                                            </div>
                                                            <div class="avatar-group-item">
                                                                <a href="javascript: void(0);" class="d-inline-block">
                                                                    <img src="https://bootdey.com/img/Content/avatar/avatar2.png" alt="" class="rounded-circle avatar-sm">
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <a class="font-size-16 text-muted" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                                <i class="mdi mdi-dots-horizontal"></i>
                                                            </a>

                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item" href="#">Open</a>
                                                                <a class="dropdown-item" href="#">Edit</a>
                                                                <a class="dropdown-item" href="#">Rename</a>
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item" href="#">Remove</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><a href="javascript: void(0);" class="text-dark fw-medium"><i class="mdi mdi-image font-size-16 align-middle text-muted me-2"></i> Img-1.jpeg</a></td>
                                                    <td>11-10-2020, 13:26</td>
                                                    <td>86 KB</td>
                                                    <td>
                                                        <div class="avatar-group">
                                                            <div class="avatar-group-item">
                                                                <a href="javascript: void(0);" class="d-inline-block">
                                                                    <div class="avatar-sm">
                                                                        <span class="avatar-title rounded-circle bg-info text-white font-size-16">
                                                                            K
                                                                        </span>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                            <div class="avatar-group-item">
                                                                <a href="javascript: void(0);" class="d-inline-block">
                                                                    <img src="https://bootdey.com/img/Content/avatar/avatar3.png" alt="" class="rounded-circle avatar-sm">
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <a class="font-size-16 text-muted" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                                <i class="mdi mdi-dots-horizontal"></i>
                                                            </a>

                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item" href="#">Open</a>
                                                                <a class="dropdown-item" href="#">Edit</a>
                                                                <a class="dropdown-item" href="#">Rename</a>
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item" href="#">Remove</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><a href="javascript: void(0);" class="text-dark fw-medium"><i class="mdi mdi-text-box font-size-16 align-middle text-muted me-2"></i> update list.txt</a></td>
                                                    <td>10-10-2020, 11:32</td>
                                                    <td>08 KB</td>
                                                    <td>
                                                        <div class="avatar-group">
                                                            <div class="avatar-group-item">
                                                                <a href="javascript: void(0);" class="d-inline-block">
                                                                    <img src="https://bootdey.com/img/Content/avatar/avatar4.png" alt="" class="rounded-circle avatar-sm">
                                                                </a>
                                                            </div>
                                                            <div class="avatar-group-item">
                                                                <a href="javascript: void(0);" class="d-inline-block">
                                                                    <img src="https://bootdey.com/img/Content/avatar/avatar5.png" alt="" class="rounded-circle avatar-sm">
                                                                </a>
                                                            </div>
                                                            <div class="avatar-group-item">
                                                                <a href="javascript: void(0);" class="d-inline-block">
                                                                    <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="" class="rounded-circle avatar-sm">
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <a class="font-size-16 text-muted" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                                <i class="mdi mdi-dots-horizontal"></i>
                                                            </a>

                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item" href="#">Open</a>
                                                                <a class="dropdown-item" href="#">Edit</a>
                                                                <a class="dropdown-item" href="#">Rename</a>
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item" href="#">Remove</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><a href="javascript: void(0);" class="text-dark fw-medium"><i class="mdi mdi-folder font-size-16 align-middle text-warning me-2"></i> Project B</a></td>
                                                    <td>10-10-2020, 10:51</td>
                                                    <td>72 KB</td>
                                                    <td>
                                                        <div class="avatar-group">
                                                            <div class="avatar-group-item">
                                                                <a href="javascript: void(0);" class="d-inline-block">
                                                                    <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="" class="rounded-circle avatar-sm">
                                                                </a>
                                                            </div>
                                                            <div class="avatar-group-item">
                                                                <a href="javascript: void(0);" class="d-inline-block">
                                                                    <img src="https://bootdey.com/img/Content/avatar/avatar3.png" alt="" class="rounded-circle avatar-sm">
                                                                </a>
                                                            </div>
                                                            <div class="avatar-group-item">
                                                                <a href="javascript: void(0);" class="d-inline-block">
                                                                    <div class="avatar-sm">
                                                                        <span class="avatar-title rounded-circle bg-danger text-white font-size-16">
                                                                            3+
                                                                        </span>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <a class="font-size-16 text-muted" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                                <i class="mdi mdi-dots-horizontal"></i>
                                                            </a>

                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item" href="#">Open</a>
                                                                <a class="dropdown-item" href="#">Edit</a>
                                                                <a class="dropdown-item" href="#">Rename</a>
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item" href="#">Remove</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><a href="javascript: void(0);" class="text-dark fw-medium"><i class="mdi mdi-text-box font-size-16 align-middle text-muted me-2"></i> Changes list.txt</a></td>
                                                    <td>09-10-2020, 17:05</td>
                                                    <td>07 KB</td>
                                                    <td>
                                                        <div class="avatar-group">
                                                            <div class="avatar-group-item">
                                                                <a href="javascript: void(0);" class="d-inline-block">
                                                                    <img src="https://bootdey.com/img/Content/avatar/avatar4.png" alt="" class="rounded-circle avatar-sm">
                                                                </a>
                                                            </div>
                                                            <div class="avatar-group-item">
                                                                <a href="javascript: void(0);" class="d-inline-block">
                                                                    <img src="https://bootdey.com/img/Content/avatar/avatar6.png" alt="" class="rounded-circle avatar-sm">
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <a class="font-size-16 text-muted" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                                <i class="mdi mdi-dots-horizontal"></i>
                                                            </a>

                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item" href="#">Open</a>
                                                                <a class="dropdown-item" href="#">Edit</a>
                                                                <a class="dropdown-item" href="#">Rename</a>
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item" href="#">Remove</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><a href="javascript: void(0);" class="text-dark fw-medium"><i class="mdi mdi-image font-size-16 align-middle text-success me-2"></i> Img-2.png</a></td>
                                                    <td>09-10-2020, 15:12</td>
                                                    <td>31 KB</td>
                                                    <td>
                                                        <div class="avatar-group">
                                                            <div class="avatar-group-item">
                                                                <a href="javascript: void(0);" class="d-inline-block">
                                                                    <div class="avatar-sm">
                                                                        <span class="avatar-title rounded-circle bg-pink text-white font-size-16">
                                                                            L
                                                                        </span>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                            <div class="avatar-group-item">
                                                                <a href="javascript: void(0);" class="d-inline-block">
                                                                    <img src="https://bootdey.com/img/Content/avatar/avatar2.png" alt="" class="rounded-circle avatar-sm">
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <a class="font-size-16 text-muted" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                                <i class="mdi mdi-dots-horizontal"></i>
                                                            </a>

                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item" href="#">Open</a>
                                                                <a class="dropdown-item" href="#">Edit</a>
                                                                <a class="dropdown-item" href="#">Rename</a>
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item" href="#">Remove</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><a href="javascript: void(0);" class="text-dark fw-medium"><i class="mdi mdi-folder font-size-16 align-middle text-warning me-2"></i> Project C</a></td>
                                                    <td>09-10-2020, 10:11</td>
                                                    <td>20 KB</td>
                                                    <td>
                                                        <div class="avatar-group">
                                                            <div class="avatar-group-item">
                                                                <a href="javascript: void(0);" class="d-inline-block">
                                                                    <img src="https://bootdey.com/img/Content/avatar/avatar4.png" alt="" class="rounded-circle avatar-sm">
                                                                </a>
                                                            </div>
                                                            <div class="avatar-group-item">
                                                                <a href="javascript: void(0);" class="d-inline-block">
                                                                    <img src="https://bootdey.com/img/Content/avatar/avatar5.png" alt="" class="rounded-circle avatar-sm">
                                                                </a>
                                                            </div>
                                                            <div class="avatar-group-item">
                                                                <a href="javascript: void(0);" class="d-inline-block">
                                                                    <div class="avatar-sm">
                                                                        <span class="avatar-title rounded-circle bg-success text-white font-size-16">
                                                                            A
                                                                        </span>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                            <div class="avatar-group-item">
                                                                <a href="javascript: void(0);" class="d-inline-block">
                                                                    <img src="https://bootdey.com/img/Content/avatar/avatar2.png" alt="" class="rounded-circle avatar-sm">
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <a class="font-size-16 text-muted" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                                <i class="mdi mdi-dots-horizontal"></i>
                                                            </a>

                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item" href="#">Open</a>
                                                                <a class="dropdown-item" href="#">Edit</a>
                                                                <a class="dropdown-item" href="#">Rename</a>
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item" href="#">Remove</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><a href="javascript: void(0);" class="text-dark fw-medium"><i class="bx bxs-file font-size-16 align-middle text-primary me-2"></i> starter-page.html</a></td>
                                                    <td>08-10-2020, 03:22</td>
                                                    <td>11 KB</td>
                                                    <td>
                                                        <div class="avatar-group">
                                                            <div class="avatar-group-item">
                                                                <a href="javascript: void(0);" class="d-inline-block">
                                                                    <img src="https://bootdey.com/img/Content/avatar/avatar8.png" alt="" class="rounded-circle avatar-sm">
                                                                </a>
                                                            </div>
                                                            <div class="avatar-group-item">
                                                                <a href="javascript: void(0);" class="d-inline-block">
                                                                    <img src="https://bootdey.com/img/Content/avatar/avatar2.png" alt="" class="rounded-circle avatar-sm">
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <a class="font-size-16 text-muted" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                                <i class="mdi mdi-dots-horizontal"></i>
                                                            </a>

                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item" href="#">Open</a>
                                                                <a class="dropdown-item" href="#">Edit</a>
                                                                <a class="dropdown-item" href="#">Rename</a>
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item" href="#">Remove</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <!-- MODALS -->
    <div class="modal" tabindex="-1" id="create_folder">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Folder</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="<?= BASE_URL ?>services/manager.php">
                    <?= csrf()->input(); ?>

                    <input type="hidden" name="__P_F__" value="<?= isset($_GET['fd']) ? $_GET['fd'] : '' ?>">
                    <div class="modal-body">
                        <?php if ($msg = session()->get_flash_message('error')) { ?>
                            <div class="alert alert-info"><?= $msg ?></div>
                        <?php } ?>
                        <div class="form-floating mb-3">
                            <input type="text" name="folder_name" class="form-control" id="folder" placeholder="Folder name">
                            <label for="folder">Folder name</label>
                        </div>

                        <small class="text-danger py-1">* Each folder you create takes up 4KB of space.</small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="create_folder" class="btn btn-primary">Create Folder</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- END MODALS -->


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

    <script>
        <?php if (session()->has('open_modal', true)) { ?>
        var myModal = new bootstrap.Modal(document.getElementById('<?= session()->get_flash_message('modal_id'); ?>'));
        myModal.show();
        <?php } ?>
    </script>
</body>

</html>