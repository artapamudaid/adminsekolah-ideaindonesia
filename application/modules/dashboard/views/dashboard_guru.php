<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Dashboard
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="page-wrapper">
            <div class="container">
                <ul class="infographic-cards">
                    <li class="color-1">
                        <h3>Waktu</h3>
                        <h2 id="currentTime" style="font-weight: bold;"><?= date('H:i') ?></h2>
                    </li>
                    <li class="color-2">
                        <h3>Jam Ke</h3>
                        <h2 style="font-weight: bold;"><?= $jam_ke ?></h2>
                    </li>

                </ul>

            </div>
            <div class="container">
                <ul class="infographic-cards">

                    <li class="color-3">
                        <a href="<?php echo site_url('guru/jurnal') ?>" style="text-decoration:none; color:#000">
                            <h1> <i class="fa fa-file-text-o text-stock"></i></h1>
                            <h2>Jurnal</h2>
                        </a>
                    </li>

                    <li class="color-4">
                        <a href="<?php echo site_url('guru/nilai') ?>" style="text-decoration:none; color:#000">
                            <h1> <i class="fa fa-file-o text-stock"></i></h1>
                            <h2>Penilaian</h2>
                        </a>
                    </li>

                    <li class="color-5">

                        <a href="<?php echo site_url('guru/izin') ?>" style="text-decoration:none; color:#000">
                            <h1> <i class="fa fa-edit text-stock"></i></h1>
                            <h2>Perizinan</h2>
                        </a>
                    </li>

                </ul>

            </div>
        </div>
    </section>
    <!-- /.content -->

    <section class="content">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="box box-success">
                    <div class="box-header">
                        <table class="table table-responsive table-bordered" style="white-space: nowrap;">
                            <thead>
                                <tr>
                                    <th>
                                        Jam
                                    </th>
                                    <th>
                                        Kelas
                                    </th>
                                    <th>
                                        Mata Pelajaran
                                    </th>
                                    <th>
                                        Keterangan
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $presensi = array();
                                foreach ($presensi_guru as $ps) {
                                    $presensi[] = $ps;
                                }
                                foreach ($jadwal as $jd) :
                                ?>
                                    <tr>
                                        <td style="text-align: center;">
                                            <?= $jd['jam_pelajaran_name'] . ' (' . $jd['jam_pelajaran_start'] . ' - ' . $jd['jam_pelajaran_end'] . ')' ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <?= $jd['class_name'] ?>
                                        </td>
                                        <td>
                                            <?= $jd['lesson_name'] ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <?php foreach ($presensi as $p) :
                                                if ($p['presensi_guru_schedule_id'] == $jd['schedule_id']) {
                                                    switch ($p['presensi_guru_attend']) {
                                                        case '0':
                                                            echo '<i class="fa fa-remove" style="color:red;"></i>';
                                                            break;
                                                        case '1':
                                                            echo '<i class="fa fa-check" style="color:green;"></i>';
                                                            break;
                                                        case '2':
                                                            echo '<i class="fa fa-info" style="color:black;"></i>';
                                                            break;
                                                        default:
                                                            echo '<i class="fa fa-remove" style="color:red;"></i>';
                                                            break;
                                                    }
                                                }
                                            endforeach; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    body {
        margin: 0;
        padding: 0;
        font-family: "Mulish", sans-serif;
        font-size: 14px;
        background: #c8d3d6;
    }

    :root {
        --color-1: #f68121;
        --color-2: #ed1c24;
        --color-3: #582c8b;
        --color-4: #0166b4;
        --color-5: #b166b4;
    }

    .page-wrapper {
        padding: 100px 0;
    }

    .infographic-cards {
        width: 100%;
        position: relative;
        display: flex;
        gap: 2%;
        justify-content: center;
        padding-left: 0;

    }

    .infographic-cards li {
        list-style: none;
        width: calc(100% /4);
        height: 230px;
        padding: 25px 30px;
        background: #eee;
        position: relative;
        background-image: linear-gradient(to bottom right, #e9eaecda, #ffffff);
        filter: drop-shadow(10px 10px 10px rgba(0, 0, 0, 0.25));
        box-shadow: inset 2px 2px 2px white, inset -1px -1px 1px rgba(0, 0, 0, 0.25);
        text-align: center;
        display: inline-block;
        margin-bottom: 25px;
        border-radius: 20px;
    }

    .infographic-cards li.color-1 {
        border: 10px solid var(--color-1);
    }

    .infographic-cards li.color-2 {
        border: 10px solid var(--color-2);
    }

    .infographic-cards li.color-3 {
        border: 10px solid var(--color-3);
    }

    .infographic-cards li.color-4 {
        border: 10px solid var(--color-4);
    }

    .infographic-cards li.color-5 {
        border: 10px solid var(--color-5);
    }


    .infographic-cards li i {
        font-size: 35px;
        margin-bottom: 15px;
    }

    .infographic-cards li.color-1 i {
        color: var(--color-1);
    }

    .infographic-cards li.color-2 i {
        color: var(--color-2);
    }

    .infographic-cards li.color-3 i {
        color: var(--color-3);
    }

    .infographic-cards li.color-4 i {
        color: var(--color-4);
    }

    .infographic-cards li.color-5 i {
        color: var(--color-5);
    }

    .number-box {
        position: absolute;
        bottom: 0;
        padding: 15px;
        font-size: 23px;
        border-radius: 60px 60px 0 0;
        width: 60px;
        height: 50px;
        left: 50%;
        transform: translateX(-50%);
        box-shadow: inset 0.25em 0.25em 0.25em rgba(0, 0, 0, 0.2), 0em 0.05em rgba(255, 255, 255, 0);
        font-weight: 800;
        color: #fff;
    }

    .infographic-cards li.color-1 .number-box {
        background: var(--color-1);
    }

    .infographic-cards li.color-2 .number-box {
        background: var(--color-2);
    }

    .infographic-cards li.color-3 .number-box {
        background: var(--color-3);
    }

    .infographic-cards li.color-4 .number-box {
        background: var(--color-4);
    }

    .infographic-cards li.color-5 .number-box {
        background: var(--color-5);
    }

    @media screen and (max-width:991px) {
        .infographic-cards {
            flex-wrap: wrap;
        }

        .infographic-cards li {
            flex: 0 0 auto;
            width: calc(100% /2.5);
        }
    }

    @media screen and (max-width:578px) {

        .infographic-cards li {
            width: calc(100% /1);
        }
    }
</style>
<script>
    function showTime() {
        var currentTime = document.getElementById("currentTime");

        var date = new Date();

        var hour = date.getHours();
        var min = date.getMinutes();

        hour = (hour < 10 ? "0" : "") + hour;
        min = (min < 10 ? "0" : "") + min;

        var time = hour + ":" + min;
        currentTime.innerText = `${time}`;
    }

    setInterval(showTime, 10000);
</script>