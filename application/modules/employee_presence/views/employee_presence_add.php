<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="box box-primary">
            <div class="box-body">

                <div id="presensiForm">
                    <div class="form-group">
                        <label for="">Jenis Absen<small data-toggle="tooltip" title="Wajib diisi"></small></label>
                        <select class="form-control" name="jenis_absen" id="jenis-absen" required>
                            <option value="DATANG">DATANG</option>
                            <option value="PULANG">PULANG</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Scan Kartu Pegawai<small data-toggle="tooltip" title="Wajib diisi"></small></label>
                        <input type="text" class="form-control" name="rfid" id="rfid" autofocus="" placeholder="Scan Kartu" required>
                    </div>
                </div>

                <div class="image-container text-center mb-4">
                    <input type="hidden" id="lati" name="latitude">
                    <input type="hidden" id="longi" name="longitude">
                    <div id="area-foto" style="display: flex; justify-content: center; margin-bottom: 10px">
                        <img id="foto-pegawai" src="" alt="Foto Pegawai">
                    </div>
                    <div id="message" style="padding-top: 5px;"></div>
                </div>

                <div class="attendance-info" id="attendance-info">
                    <div id="attendance-detail"></div>
                </div>

            </div>
        </div>
    </div>
</section>

<script>
    $('#jenis-absen').on('change', function() {
        $('#rfid').focus();
    });

    function hideAllMessages() {
        $('#error-message').removeClass('show');
        $('#attendance-info').removeClass('show');
        $('#area-foto').html('');
        $('#message').fadeOut();
    }

    function showLoading() {
        hideAllMessages();
        $('#loading-spinner').addClass('active');
        $('#rfid').prop('disabled', true);
    }

    function hideLoading() {
        $('#rfid').prop('disabled', false);
        $('#rfid').focus();
    }

    function showSuccess(data, jenis_absen) {
        const now = new Date();

        const days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
        const months = [
            "Januari", "Februari", "Maret", "April", "Mei", "Juni",
            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        ];

        const dateStr = `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;

        const timeStr = now.toLocaleTimeString('id-ID');
        const attendanceHtml = `
            <div>${data.keterangan}</div>
            <div>${data.pesan}</div>
            <div class="attendance-time">
            📌 Jenis: <b>${jenis_absen}</b><br>
                📅 ${dateStr}<br>
                🕐 ${timeStr} WIB
            </div>
        `;

        $('#attendance-info').html(attendanceHtml).addClass('show');
    }

    $('#rfid').keydown(function(e) {
        if (e.keyCode !== 13) return;

        var rfid = $('#rfid').val().trim();
        if (rfid === '') return;

        if (rfid.length >= 10) {
            const jenis_absen = $("#jenis-absen").val();
            const longi = $("#longi").val();
            const lati = $("#lati").val();

            showLoading();

            $.ajax({
                url: "<?= base_url() . 'employee_presence/presence/' ?>",
                method: 'POST',
                data: {
                    jenis_absen: jenis_absen,
                    rfid: rfid,
                    longitude: longi,
                    latitude: lati
                },
                success: function(response) {
                    console.log(response);

                    try {
                        var data = JSON.parse(response);

                        hideLoading();

                        if (data.status === 'success') {

                            // FOTO / AVATAR
                            if (!data.foto) {
                                const firstInitial = data.nama.trim()[0].toUpperCase();
                                $("#area-foto").html(`<span data-letters="${firstInitial}"></span>`);
                            } else {
                                const srcFoto = '<?= base_url('uploads/student/') ?>' + data.foto;
                                $("#area-foto").html(`<img src="${srcFoto}" class="show">`);
                            }

                            showSuccess(data, jenis_absen);

                            $("#rfid").val("");

                        } else {
                            $("#area-foto").html('');
                            $('#message').fadeOut();
                            $("#rfid").val("");

                            alert(data.message);
                        }

                    } catch (e) {
                        hideLoading();
                        console.error("Parsing JSON gagal: ", e);
                    }
                },
                error: function(xhr, status, error) {
                    hideLoading();
                    console.error("AJAX Error: ", status, error);
                }
            });
        }
    });
</script>
<script>
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    }

    function showPosition(position) {
        $("#longi").val(position.coords.longitude);
        $("#lati").val(position.coords.latitude);
    }
</script>