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

    <script>
        const BASE_URL = '<?= BASE_URL ?>';
        const CURRENT_FOLDER = '<?= isset($_GET['fd']) ? $_GET['fd'] : '' ?>';
    </script>
</head>

<body>
    <?= csrf()->input(); ?>
    <div class="conatiner-fluid mx-2">
        <div class="row gx-0">
            <div class="col-2">
                <div class="card border w-inherit position-fixed">
                    <div class="card-body">


                        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                            <li class="nav-item">
                                <a class="nav-link rounded-pill px-2 active" aria-current="page" href="<?= BASE_URL ?>pages/home.php"><i class="mdi mdi-home me-1"></i> Home</a>
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
                                <a class="nav-link rounded-pill px-2" href="<?= BASE_URL?>pages/home.php?page=starred"> <i class="mdi mdi-star-outline me-1"></i>Starred</a>
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
                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#upload_files"><i class="mdi mdi-file-outline me-1"></i> File</a>
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

                                    <h5 class="font-size-16 me-3 mb-3"><?= breadcrumbs(); ?></h5>
                                    

                                    <?php init_page(); ?>
                                    
                                    <!-- end row -->
                                   

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <!-- MODALS -->

    <!-- MODAl for add folder -->
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
    <!-- Modal end here -->

    <!-- Modal for upload files -->
    <div class="modal" tabindex="-1" id="upload_files">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Files</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" id="file_upload_form" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="card border-dashed shadow-none">
                            <div class="card-body py-5">
                                <div class="text-center">

                                    <label for="file_input" class="d-block" role="button">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="108" height="108" viewBox="0 0 24 24" style="fill: #dbdbdb;">
                                            <path d="M13 19v-4h3l-4-5-4 5h3v4z"></path>
                                            <path d="M7 19h2v-2H7c-1.654 0-3-1.346-3-3 0-1.404 1.199-2.756 2.673-3.015l.581-.102.192-.558C8.149 8.274 9.895 7 12 7c2.757 0 5 2.243 5 5v1h1c1.103 0 2 .897 2 2s-.897 2-2 2h-3v2h3c2.206 0 4-1.794 4-4a4.01 4.01 0 0 0-3.056-3.888C18.507 7.67 15.56 5 12 5 9.244 5 6.85 6.611 5.757 9.15 3.609 9.792 2 11.82 2 14c0 2.757 2.243 5 5 5z"></path>
                                        </svg>
                                        <input type="file" class="d-none" name="files[]" multiple id="file_input">

                                        <span class="fs-3 text-gray d-block">Choose Files</span>
                                    </label>

                                    <p class="small mb-0 mt-2"><b>Note:</b> Make sure the file must be less than 20MB, and you can select up to 10 files at once </p>

                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated" id="file_progress_bar" role="progressbar" aria-label="Animated striped example" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-secondary">Reset</button>
                        <button type="submit" id="upload_file_btn" data-bs-dismiss="modal" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal end here -->

    <!-- END MODALS -->

    <div class="position-fixed end-0 bottom-0 w-25 mh-100 d-none" id="file_upload_sidebar">
        <div class="card border shadow rounded-1">
            <div class="card-header" id="file_upload_header" data-bs-toggle="collapse" data-bs-target="#file_accordian" aria-expanded="false" aria-controls="file_accordian">
                <div class="header-status">
                    <div class="spinner-border spinner-border-sm" role="status">
                    </div> Uploading Files..
                </div>

                <div class="completed d-none justify-content-between">
                    <span class="d-block">Completed</span>
                    <span class="d-block" role="button" onclick="closeFileProgressBar()">X</span>
                </div>
            </div>

            <div class="accordion" id="accordian_files_upload">
                <div class="accordion-item rounded-0 border-top-0">
                    <div id="file_accordian" class="accordion-collapse collapse" aria-labelledby="file_accordian" data-bs-parent="#accordian_files_upload">
                        <div class="p-3" style="overflow-y:scroll;max-height:100vh" id="file_progress_bar_container">

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <!-- Toasts -->
    <div class="toast-container position-fixed bottom-0 start-0 p-3">
        <div class="toast text-bg-secondary align-items-center" id="notification" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    
                </div>
                <button type="button" class="btn-close me-2 m-auto btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
    <!-- end Toasts -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="<?= assets('js/file-upload.js'); ?>"></script>
    <script src="<?= assets('js/services.js'); ?>"></script>
    <script>
        <?php if (session()->has('open_modal', true)) { ?>
            var myModal = new bootstrap.Modal(document.getElementById('<?= session()->get_flash_message('modal_id'); ?>'));
            myModal.show();
        <?php } ?>
    </script>
</body>

</html>