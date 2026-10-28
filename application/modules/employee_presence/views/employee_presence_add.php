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

                <div class="image-container text-center mt-4">
                    <input type="hidden" id="lati" name="latitude">
                    <input type="hidden" id="longi" name="longitude">
                    <div id="area-foto" style="display: flex; justify-content: center;">
                        <img id="foto-pegawai" src="" alt="Foto Santri">
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

    $('#rfid').keydown(function() {
        var rfid = $('#rfid').val().trim();

        if (rfid === '') {
            return;
        }
        if (rfid.length >= 10) {
            // console.log(rfid);
            const jenis_absen = $("#jenis-absen").val();
            const longi = $("#longi").val();
            const lati = $("#lati").val();

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
                    // Cek output dari server di console
                    console.log(response);

                    try {
                        var data = JSON.parse(response); // Parsing JSON

                        if (data.status === 'success') {
                            if (data.foto == null || data.foto == '') {
                                const fullName = data.nama;
                                const firstInitial = fullName.trim()[0].toUpperCase();
                                var avatar = '<span data-letters="' + firstInitial + '"></span>';
                                $("#area-foto").html(avatar);
                            } else {
                                const srcFoto = '<?= base_url('uploads/student/') ?>' + data.foto;
                                var fotoSantri = '<img id="foto-pegawai" src="' + srcFoto + '" alt="Foto Santri">'
                                $("#area-foto").html(fotoSantri);
                                $('#foto-pegawai').attr('src', '<?= base_url('uploads/student/') ?>' + data.foto).addClass('show');
                            }

                            $('#message').html(data.keterangan + '<br>' + data.pesan).fadeIn();
                            $("#rfid").val("");
                            // alert(data.keterangan);
                        } else {
                            $("#area-foto").html('');
                            $('#message').fadeOut();
                            $("#rfid").val("");

                            alert(data.message);

                        }
                    } catch (e) {
                        console.error("Parsing JSON gagal: ", e);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error: ", status, error);
                }
            });
        }
        // }, 100); //fungsi set timeout
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