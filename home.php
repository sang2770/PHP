
<style>
    .carousel-item>img{
        object-fit:fill !important;
    }
    #carouselExampleControls .carousel-inner{
        height:280px !important;
    }
</style>
<?php 
$brands = isset($_GET['b']) ? json_decode(urldecode($_GET['b'])) : array();
?>
<section class="py-0">
    <div class="container">
        <div class="col-lg-12 py-2">
            <div class="row">
                <div class="col-md-12">
                    <div id="carouselExampleControls" class="carousel slide bg-dark" data-ride="carousel">
                        <div class="carousel-inner">
                            <?php 
                                $upload_path = "uploads/banner";
                                if(is_dir(base_app.$upload_path)): 
                                $file= scandir(base_app.$upload_path);
                                $_i = 0;
                                    foreach($file as $img):
                                        if(in_array($img,array('.','..')))
                                            continue;
                                $_i++;
                                    
                            ?>
                            <div class="carousel-item h-100 <?php echo $_i == 1 ? "active" : '' ?>">
                                <img src="<?php echo validate_image($upload_path.'/'.$img) ?>" class="d-block w-100  h-100" alt="<?php echo $img ?>">
                            </div>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-target="#carouselExampleControls" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-target="#carouselExampleControls" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                        </div>
                </div>
            </div>
            <div class="container px-4 px-lg-5 mt-5">
                <h3 class="text-center font-weight-bolder">Chào mừng quý khách đến <?= $_settings->info('name') ?></h3>
                <hr>
                <div class="row">
                    <?php 
                    $services = $conn->query("SELECT * FROM `service_list` where delete_flag = 0 and `status` = 1 order by (`name`) asc");
                    while($row = $services->fetch_assoc()):
                    ?>
                    
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 py-4">
                        <h4 class="text-center"><b>Dịch vụ <?= $row['name'] ?> </b></h4>
                        <div class="row mx-0 my-2">
                            <div class="col-5 border bg-primary m-0 text-center px-2 py-1">Loại phương tiện</div>
                            <div class="col-7 border bg-primary m-0 text-center px-2 py-1">Giá</div>
                            <?php 
                            $vehicles = $conn->query("SELECT v.name as vehicle, p.price FROM `vehicle_list` v inner join `price_list` p on v.id = p.vehicle_id where p.service_id = '{$row['id']}' and v.delete_flag = 0 and v.status = 1 order by abs(v.`name`) asc ");
                            while($vrow = $vehicles->fetch_assoc()):
                            ?>
                            <div class="col-5 border bg-primary m-0 px-2 py-1"><?= $vrow['vehicle'] ?></div>
                            <div class="col-7 border m-0 px-2 py-1 text-right"><?= format_num($vrow['price']) ?></div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                    <?php endwhile; ?>

                </div>
               
            </div>
    </div>
    </div>
</section>
<script>
    $(function(){
    })

</script>