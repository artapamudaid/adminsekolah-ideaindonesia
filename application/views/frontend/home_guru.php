<style>
  /* Animasi untuk gerakan acak */
  @keyframes moveRandomly {
      0% {
          transform: translate(0, 0);
      }
      100% {
          transform: translate(calc(100vw * var(--x)), calc(100vh * var(--y)));
      }
  }

  /* Style dasar untuk elemen */
  .moving-element {
      position: absolute;
      animation: moveRandomly var(--duration) infinite;
      animation-timing-function: linear;
      opacity: 0.7;
  }

  /* Lingkaran */
  .circle {
      width: 30px;
      height: 30px;
      background-color: rgba(255, 0, 0, 0.7);
      border-radius: 50%;
  }

  /* Kotak */
  .square {
      width: 30px;
      height: 30px;
      background-color: rgba(0, 0, 255, 0.7);
  }

  /* Segitiga */
  .triangle {
      width: 0;
      height: 0;
      border-left: 15px solid transparent;
      border-right: 15px solid transparent;
      border-bottom: 30px solid rgba(0, 255, 0, 0.7);
  }
</style>
<!-- JavaScript untuk membuat elemen acak -->
<script>
    const numberOfElements = 30; // Jumlah elemen yang ingin ditambahkan
    const container = document.body; // Kontainer untuk elemen

    // Fungsi untuk membuat elemen acak
    function createRandomElement() {
        const element = document.createElement("div");
        const type = ["circle", "square", "triangle"]; // Tipe elemen yang berbeda
        const randomType = type[Math.floor(Math.random() * type.length)];
        element.className = `moving-element ${randomType}`;

        // Pengaturan animasi dengan variabel acak
        element.style.setProperty("--x", Math.random());
        element.style.setProperty("--y", Math.random());
        element.style.setProperty("--duration", `${Math.random() * 10 + 10}s`);

        // Posisi awal acak
        const xStart = Math.random() * 100;
        const yStart = Math.random() * 100;
        element.style.top = `${yStart}vh`;
        element.style.left = `${xStart}vw`;

        return element;
    }

    // Membuat dan menambahkan elemen acak
    for (let i = 0; i < numberOfElements; i++) {
        const element = createRandomElement();
        container.appendChild(element);
    }
</script>

<!-- Isi konten lainnya -->
<section class="content">
  <div class="row">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-12">
          <div class="box box-solid" style="background-color:#7EA1FF;">
            <div class="box-header text-center">
              <h1 style="color:white;">Pilih Layanan Guru</h1>
            </div>
          </div>
          <div class="box-body">
            <div class="d-flex flex-column align-items-center">
            <?php
              foreach ($layanan_guru as $row):
              ?>
                <center>
                  <a href="<?php echo base_url() . 'layanan_guru/'.$row['layanan_link'] ?>" class="btn btn-info active mb-10" style="max-width: 100%; width: 80%; border-radius: 10px;" role="button" aria-pressed="true"><?= $row['layanan_nama'] ?></a><br><br>
                </center>
              <?php
              endforeach;
              ?>
	 
              <a href="<?php echo site_url(); ?>" class="btn btn-block btn-danger">Kembali</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>