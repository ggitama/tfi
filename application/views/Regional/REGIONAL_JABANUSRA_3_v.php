<!-- <div class="body flex-grow-1 p-4 ms-5 me-5" style="background-color: #ffff;"> -->

    <div class="row">
        <div class="col-md-12">
            <h2 class="text-center"><?= htmlentities( $iframe_name )?></h2>
        </div>
        <div class="col-md-12">
        <!-- <p class="text-end me-3">Data from Retail stores (excl GrabMart, AlloFresh and B2B) </p> -->
        <p class="text-end me-3">
            <!-- Data Default: Month to date ( 01-<?= date("M-Y") ?> - <?= date("d-M-Y", strtotime("-2 day", strtotime(date('Y-m-d')))) ?> ) </br> -->
            <!-- Data Default: Month to date ( 01-<?= date("M-Y") ?> - 05-<?= date("M-Y") ?> ) </br> -->
        </p>
    </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $iframe_tag?>
        </div>
    </div>

<!-- </div> -->