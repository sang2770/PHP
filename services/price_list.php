<?php

require_once('../config.php');
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * from `service_list` where id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
    if(isset($id)){
        $price_list = $conn->query("SELECT * FROM `price_list` where service_id = '{$id}'");
        $price_arr = array_column($price_list->fetch_all(MYSQLI_ASSOC),'price', 'vehicle_id');
    }
}
?>
<style>
    #uni_modal .modal-footer{
        display:none !important;
    }
</style>
<div class="container-fluid">
    <div class="row mx-0 w-100">
        <div class="px-2 py-1 col-5 border m-0 bg-primary bg-opacity-25 aling-middle text-center">Loại phương tiện</div>
        <div class="px-2 py-1 col-7 border m-0 bg-primary bg-opacity-25 aling-middle text-center">Giá</div>
        <?php 
        $vehicles = $conn->query("SELECT * FROM `vehicle_list` where delete_flag = 0 and `status` = 1 order by abs(`name`) asc");
        while($row = $vehicles->fetch_assoc()):
        ?>
        <div class="px-2 py-1 col-5 border m-0 bg-primary bg-opacity-25 aling-middle"><?= $row['name'] ?></div>
        <div class="px-2 py-1 col-7 border m-0 aling-middle text-right"><?= isset($price_arr[$row['id']]) ? format_num($price_arr[$row['id']]) : 0 ?></div>
        <?php endwhile; ?>
    </div>
    <div class="clear-fix mb-4"></div>
    <div class="text-right">
        <button class="btn btn-default border btn-sm" type="button" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
    </div>
</div>