<h5 class="font-size-16 me-3 mb-0">Recent Folders</h5>
<div class="row mt-4">

    <?php


        $all_folders = ORM::for_table('folders')->where('is_deleted', 0)->where('user_id', session()->get('user_id'))->order_by_desc('id')->limit(50)->find_many();


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
        if (count($all_folders) == 0) {
            require_once 'no-content.php';
        }
     ?>

</div>