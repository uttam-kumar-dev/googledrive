<?php

require_once '../class/FilePreview.php';



?> 

<h5 class="font-size-16 me-3 mb-0">My Drive</h5>
<div class="row mt-4">

    <?php

        if(!isset($_GET['fd']) && empty($_GET['fd'])){
        $all_folders = ORM::for_table('folders')->where('parent_id', 0)->where('is_deleted', 0)->where('user_id', session()->get('user_id'))->order_by_asc('id')->find_many();

        foreach ($all_folders as $k => $f) {
    ?>

            <div class="col-xl-4 col-sm-6">
                <div class="card shadow-none border card_folder on-contextmenu" data-fid="<?= $f->uuid ?>" data-type="folder" data-isStarred="<?= $f->is_starred ?>">
                    <div class="card-body p-3" ondblclick="window.location.href='<?= BASE_URL ?>pages/home.php?page=my-drive&fd=<?= $f->uuid ?>'">
                        <div class="">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="bx bxs-folder h1 mb-0 text-warning"></i>
                                </div>
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
                            </div>
                            <div class="d-flex mt-3">
                                <div class="overflow-hidden me-auto">
                                    <h5 class="font-size-15 text-truncate mb-1"><a href="javascript: void(0);" class="text-body"><?= $f->title; ?></a></h5>
                                    <p class="text-muted text-truncate mb-0"><?= $f->files; ?> Files</p>
                                </div>
                                <div class="align-self-end ms-2">
                                    <p class="text-muted mb-0 font-size-13"><i class="mdi mdi-clock"></i> <?= timeAgo($f->last_updated); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php }

            $files = ORM::for_table('files')->where('user_id', session()->get('user_id'))->where('is_deleted',0)->where('folder_id', 0)->find_many();
            foreach($files as $file){

                $f_pr = new FilePreview($file, $file, ORM::class);

                $is_preview = $f_pr->canPreview();
            ?>
            <div class="col-xl-4 col-sm-6">
                <div class="card shadow-none border on-contextmenu" data-fid="<?= $file->uuid ?>" data-type="file" data-isStarred="<?= $file->is_starred ?>">
                    <div class="card-body p-3" <?php if($is_preview){?> onclick="window.location.href='<?= BASE_URL ?>d/<?= $file->uuid ?>'" <?php } ?>>
                        <div class="">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="<?= getIcon($file->file_extension); ?> h1 mb-0 text-dark"></i>
                                </div>
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
                            </div>
                            <div class="d-flex mt-3" title="<?= $file->title; ?>">
                                <div class="overflow-hidden me-auto w-50">
                                    <h5 class="font-size-15 text-truncate mb-1"><a href="javascript: void(0);" class="text-body"><?= $file->title; ?></a></h5>
                                    <p class="text-muted text-truncate mb-0"><?= getSize($file->size); ?></p>
                                </div>
                                <div class="align-self-end ms-2">
                                    <p class="text-muted mb-0 font-size-13"><i class="mdi mdi-clock"></i> <?= timeAgo($file->last_updated); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    <?php 
        }if (count($all_folders) == 0 && count($files) == 0) {
            require_once 'no-content.php';
        }}else{
            
            require_once './folders.php';
            require_once './files.php';
        }
 ?>

</div>