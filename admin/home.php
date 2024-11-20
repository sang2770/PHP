<h1> <?php echo $_settings->info('name') ?></h1>
<hr>
<div class="row">
        <div class="col-12 col-sm-6 col-md-6">
            <div class="info-box">
              <span class="info-box-icon bg-gradient-primary elevation-1"><i class="fas fa-car"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Loại phương tiện</span>
                <span class="info-box-number">
                  <?php 
                    $vehicle = $conn->query("SELECT * FROM vehicle_list where delete_flag = 0")->num_rows;
                    echo format_num($vehicle);
                  ?>
                  <?php ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-6">
            <div class="info-box">
              <span class="info-box-icon bg-gradient-light elevation-1"><i class="fas fa-th-list"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Tổng số dịch vụ</span>
                <span class="info-box-number">
                  <?php 
                    $services = $conn->query("SELECT * FROM service_list where delete_flag = 0 ")->num_rows;
                    echo format_num($services);
                  ?>
                  <?php ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-6">
            <div class="info-box">
              <span class="info-box-icon bg-gradient-teal elevation-1"><i class="fas fa-calendar-day"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Lịch đặt</span>
                <span class="info-box-number">
                  <?php 
                    $upcoming = $conn->query("SELECT * FROM booking_list where `status` = 1 and date(`schedule`) = '".date('Y-m-d')."' ")->num_rows;
                    echo format_num($upcoming);
                  ?>
                  <?php ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-6">
            <div class="info-box">
              <span class="info-box-icon bg-gradient-maroon elevation-1"><i class="fas fa-star"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Đã hoàn thành</span>
                <span class="info-box-number">
                  <?php 
                    $done = $conn->query("SELECT * FROM booking_list where `status` = 4 and date(`schedule`) = '".date('Y-m-d')."' ")->num_rows;
                    echo format_num($done);
                  ?>
                  <?php ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
        </div>
<div class="container">
  <?php 
    $files = array();
      $fopen = scandir(base_app.'uploads/banner');
      foreach($fopen as $fname){
        if(in_array($fname,array('.','..')))
          continue;
        $files[]= validate_image('uploads/banner/'.$fname);
      }
  ?>
  <div id="tourCarousel"  class="carousel slide" data-ride="carousel" data-interval="3000">
      <div class="carousel-inner h-100">
          <?php foreach($files as $k => $img): ?>
          <div class="carousel-item  h-100 <?php echo $k == 0? 'active': '' ?>">
              <img class="d-block w-100  h-100" style="object-fit:contain" src="<?php echo $img ?>" alt="">
          </div>
          <?php endforeach; ?>
      </div>
      <a class="carousel-control-prev" href="#tourCarousel" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Lui</span>
      </a>
      <a class="carousel-control-next" href="#tourCarousel" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Tới</span>
      </a>
  </div>
</div>
