<?php if(isset($_SESSION['alert'])){
    foreach ($_SESSION['alert']['msg'] as $msg){ ?>
        <div class="alert alert-default-<?=$_SESSION['alert']['type'];?> border border-<?=$_SESSION['alert']['type'];?>" role="alert">
            <b><?=$msg;?></b>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span class="text-dark" aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php } unset($_SESSION['alert']); } ?>