
<div class="row mt-4">

    <?php


    $all_folders = ORM::for_table('folders')->where('is_starred', 1)->where('is_deleted', 0)->where('user_id', session()->get('user_id'))->order_by_asc('id')->find_many();

    foreach ($all_folders as $k => $f) {
    ?>

        <div class="col-xl-4 col-sm-6">
            <div class="card shadow-none border">
                <div class="card-body p-3">
                    <div class="">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bx bxs-folder h1 mb-0 text-warning"></i>
                            </div>
                            <div>
                                <i role="button" class='bx <?= $f->is_starred ? 'bxs-star' : 'bx-star'?> h4 text-secondary starred_document' data-fid="<?= $f->uuid ?>" data-type="folder"></i>
                                </div>
                        </div>
                        <div class="d-flex mt-3">
                            <div class="overflow-hidden me-auto">
                                <h5 class="font-size-15 text-truncate mb-1"><a href="<?= BASE_URL ?>pages/home.php?fd=<?= $f->uuid ?>" class="text-body"><?= $f->title; ?></a></h5>
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



    $files = ORM::for_table('files')->where('is_starred', 1)->where('user_id', session()->get('user_id'))->where('is_deleted', 0)->find_many();
    foreach ($files as $file) {
    ?>
        <div class="col-xl-4 col-sm-6">
            <div class="card shadow-none border">
                <div class="card-body p-3" onclick="window.location.href='<?= BASE_URL ?>pages/home.php?f=<?= $file->uuid ?>'">
                    <div class="">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="<?= get_file_icon($file->file_type); ?> h1 mb-0 text-dark"></i>
                            </div>
                            <div>
                                <i role="button" class='bx <?= $file->is_starred ? 'bxs-star' : 'bx-star'?> h4 text-secondary starred_document' data-fid="<?= $file->uuid ?>" data-type="file"></i>
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
    }
    if (count($all_folders) == 0 && count($files) == 0) {
        require_once 'no-content.php';
    }
    ?>

</div>