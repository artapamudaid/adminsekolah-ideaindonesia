<div class="container mt-5">
    <h2 class="text-center">Scan Kartu Siswa</h2>
    <div class="row justify-content-center">
        <div class="col-md-4">
            <input type="text" id="nis" class="form-control" placeholder="Scan Disini" autofocus>
        </div>
    </div>

    <div class="image-container text-center mt-4">
        <img id="foto-siswa" src="" alt="Foto Siswa">
        <div id="message"></div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#nis').keydown(function() {
        // setTimeout(function() {   //fungsi set timeout
        var nis = $('#nis').val().trim();

        if (nis === '') {
            return;
        }
        if (nis.length >= 10) {
            // console.log("done");3759816899

            $.ajax({
                url: "<?= base_url() . 'student_presence/presence/' ?>",
                method: 'POST',
                data: {
                    nis: nis
                },
                success: function(response) {
                    // Cek output dari server di console
                    console.log(response);

                    try {
                        var data = JSON.parse(response); // Parsing JSON

                        if (data.status === 'success') {
                            $('#foto-siswa').attr('src', '<?= base_url('uploads/student/') ?>' + data.foto).addClass('show');
                            $('#message').text(data.keterangan + ' Selamat Datang, ' + data.nama).fadeIn();
                            $("#nis").val("");
                            // alert(data.keterangan);
                        } else {
                            $('#foto-siswa').removeClass('show').fadeOut();
                            $('#message').fadeOut();
                            $("#nis").val("");

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