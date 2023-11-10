<?= $this->extend('template/admin_template'); ?>

<?= $this->section('content'); ?>
<div class="content-header">
    <div class="container-fulid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fulid">
        <div class="row">
            <div class="col-lg-3 col-3">
                <!-- small box -->
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3><?= $totalcritical ?></h3>

                        <p>Critical </p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-skull-crossbones"></i>
                    </div>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-3">
                <!-- small box -->
                <div class="small-box" style="background-color:lightsalmon;">
                    <div class="inner">
                        <h3><?= $totalhigh ?></h3>

                        <p>High</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-3">
                <!-- small box -->
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?= $totalmedium ?></h3>

                        <p>Medium </p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-3">
                <!-- small box -->
                <div class="small-box" style="background-color:yellow;">
                    <div class="inner">
                        <h3><?= $totallow ?></h3>

                        <p>Low </p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-exclamation"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-3">
                <!-- small box -->
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3><?= $totalpending ?></h3>

                        <p>Pending </p>
                    </div>
                    <div class="icon">
                        <i class="far fa-frown"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-3">
                <!-- small box -->
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3><?= $totalprocessing ?></h3>

                        <p>Processing </p>
                    </div>
                    <div class="icon">
                        <i class="far fa-meh"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-3">
                <!-- small box -->
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?= $totalresolved ?></h3>

                        <p>Resolved </p>
                    </div>
                    <div class="icon">
                        <i class="far fa-smile"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-3">
                <!-- small box -->
                <div class="small-box" style="background-color:chartreuse;">
                    <div class="inner">
                        <h3><?= $totaltickets ?></h3>

                        <p>Total Tickets </p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-envelope-open-text"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection(); ?>