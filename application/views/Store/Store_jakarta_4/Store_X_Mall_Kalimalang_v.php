<div class='row'>
    <div class='col-md-12'>
        <h2 class='text-center'><?= htmlentities($iframe_name) ?></h2>
    </div>
    <p class="text-end me-3">
        <!-- Data Default: Month to date ( 01-<?= date("M-Y") ?> - <?= date("d-M-Y", strtotime("-2 day", strtotime(date('Y-m-d')))) ?> ) </br> -->
        <!-- Data Default: Month to date ( 01-<?= date("M-Y") ?> - 05-<?= date("M-Y") ?> ) </br> -->
    </p>
</div>
<div class='row'>
    <div class='col-md-12'>
        <?= $iframe_tag ?>
    </div>
</div>