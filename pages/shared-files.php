<?php

$shared = ORM::for_table('file_sharing_access')->where('share_with', session()->get('user_id'))->order_by_desc('date_added')->find_many();


?>

<div class="table-responsive">
    <table class="table align-middle table-nowrap table-hover mb-0">
        <thead class="table-light">
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Shared By</th>
                <th scope="col">Share Date</th>
                <th scope="col" colspan="2"></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($shared as $s) {
             
                $file = get_file($s->file_id, false);

                $user = ORM::for_table('users')->find_one($s->user_id);

            ?>
            <tr>
                <td><a href="javascript: void(0);" class="text-dark fw-medium"><i class="<?= getIcon($file->file_extension);?> font-size-16 align-middle text-primary me-2"></i> <?= $file->title; ?></a></td>
                <td><?= $user->name; ?></td>
                <td><?= date('d M Y, h:i a', $s->date_added); ?></td>
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
<?php } ?>

        </tbody>
    </table>
</div>