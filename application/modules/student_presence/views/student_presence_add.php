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
                        <label for="">Scan Kartu Siswa<small data-toggle="tooltip" title="Wajib diisi"></small></label>
                        <input type="text" class="form-control" name="rfid" id="rfid" autofocus="" placeholder="Scan Kartu Kalian" required>
                    </div>
                </div>

                <div class="loading-spinner" id="loading-spinner">
                    <div class="spinner"></div>
                    <p style="margin-top: 10px;">Memproses...</p>
                </div>

                <div class="error-message" id="error-message"></div>

                <div class="attendance-info" id="attendance-info">
                    <div id="attendance-detail"></div>
                </div>

                <div class="image-container text-center mt-4">
                    <input type="hidden" id="lati" name="latitude">
                    <input type="hidden" id="longi" name="longitude">
                    <div id="area-foto" style="display: flex; justify-content: center;">
                        <img id="foto-santri" src="" alt="Foto Santri">
                    </div>
                    <div id="message" style="padding-top: 5px;"></div>
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
        $('#loading-spinner').removeClass('active');
        $('#rfid').prop('disabled', false);
        $('#rfid').focus();
    }

    function showError(message) {
        hideLoading();
        $('#error-message').text(message).addClass('show');
        setTimeout(function() {
            $('#error-message').removeClass('show');
        }, 5000);
    }

    function showSuccess(data, jenisAbsen) {
        const now = new Date();
        const dateStr = now.toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        const timeStr = now.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });

        const attendanceHtml = `
            <div>${data.keterangan}</div>
            <div>${data.pesan}</div>
            <div class="attendance-time">
                📅 ${dateStr}<br>
                🕐 Pukul: ${timeStr}
            </div>
        `;

        $('#attendance-info').html(attendanceHtml).addClass('show');
        
    }

    $('#rfid').keydown(function(e) {
        if (e.keyCode !== 13) {
            return;
        }

        var rfid = $('#rfid').val().trim();

        if (rfid === '') {
            return;
        }

        if (rfid.length >= 10) {
            showLoading();

            const jenis_absen = $("#jenis-absen").val();
            const longi = $("#longi").val();
            const lati = $("#lati").val();

            $.ajax({
                url: "<?= base_url() . 'student_presence/presence/' ?>",
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

                        if (data.status === 'success') {
                            hideLoading();

                            if (data.foto == null || data.foto == '') {
                                const fullName = data.nama;
                                const firstInitial = fullName.trim()[0].toUpperCase();
                                var avatar = '<span data-letters="' + firstInitial + '"></span>';
                                $("#area-foto").html(avatar);
                            } else {
                                const srcFoto = '<?= base_url('uploads/student/') ?>' + data.foto;
                                var fotoSantri = '<img id="foto-santri" src="' + srcFoto + '" alt="Foto Santri" class="show">'
                                $("#area-foto").html(fotoSantri);
                            }

                            const studentInfo = `
                                <strong>${data.nama}</strong><br>
                                ${data.unit} - ${data.kelas}
                            `;
                            $('#message').html(studentInfo).fadeIn();

                            showSuccess(data, jenis_absen);

                            $("#rfid").val("");
                        } else {
                            hideLoading();
                            $("#area-foto").html('');
                            $('#message').fadeOut();
                            $("#rfid").val("");

                            showError(data.message);
                        }
                    } catch (e) {
                        hideLoading();
                        console.error("Parsing JSON gagal: ", e);
                        showError("Terjadi kesalahan sistem. Silakan coba lagi.");
                    }
                },
                error: function(xhr, status, error) {
                    hideLoading();
                    console.error("AJAX Error: ", status, error);
                    showError("Koneksi ke server gagal. Silakan coba lagi.");
                    $("#rfid").val("");
                }
            });
        }
    });
</script>

<script>
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, handleLocationError);
    } else {
        console.log("Geolocation tidak didukung oleh browser ini.");
    }

    function showPosition(position) {
        $("#longi").val(position.coords.longitude);
        $("#lati").val(position.coords.latitude);
    }

    function handleLocationError(error) {
        console.log("Error getting location: ", error.message);
    }
</script>