<?php

require_once('../../config.php');
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
    /* Chrome, Safari, Edge, Opera */
    input[name="price[]"]::-webkit-outer-spin-button,
    input[name="price[]"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
    }

    /* Firefox */
    input[name="price[]"][type=number] {
    -moz-appearance: textfield;
    }
    input[name="price[]"]:focus{

    }
</style>
<div class="container-fluid">
	<form action="" id="price-form">
		<input type="hidden" name ="id" value="<?php echo isset($id) ? $id : '' ?>">
		<div class="row mx-0 w-100">
            <div class="px-2 py-1 col-5 border m-0 bg-primary bg-opacity-25 aling-middle text-center">Loại phương tiện</div>
            <div class="px-2 py-1 col-7 border m-0 bg-primary bg-opacity-25 aling-middle text-center">Giá</div>
            <?php 
            $vehicles = $conn->query("SELECT * FROM `vehicle_list` where delete_flag = 0 and `status` = 1 order by abs(`name`) asc");
            while($row = $vehicles->fetch_assoc()):
            ?>
            <div class="px-2 py-1 col-5 border m-0 bg-primary bg-opacity-25 aling-middle"><?= $row['name'] ?></div>
            <div class="px-2 py-1 col-7 border m-0 aling-middle">
                <input type="hidden" name="vehicle_id[]" value="<?= $row['id'] ?>">
                <input type="number" class="form-control form-control-sm rounded-0 text-right border-0 bg-transparent" step="any" name="price[]" value="<?= isset($price_arr[$row['id']]) ? $price_arr[$row['id']] : 0 ?>">
            </div>
            <?php endwhile; ?>
        </div>
	</form>
</div>
<script>
	$(document).ready(function(){
		$('#price-form').submit(function(e){
			e.preventDefault();
            var _this = $(this)
			 $('.err-msg').remove();
			start_loader();
			$.ajax({
				url:_base_url_+"classes/Master.php?f=save_price",
				data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
				error:err=>{
					console.log(err)
					alert_toast("An error occured",'error');
					end_loader();
				},
				success:function(resp){
					if(typeof resp =='object' && resp.status == 'success'){
						location.reload()
					}else if(resp.status == 'failed' && !!resp.msg){
                        var el = $('<div>')
                            el.addClass("alert alert-danger err-msg").text(resp.msg)
                            _this.prepend(el)
                            el.show('slow')
                            $("html, body, .modal").scrollTop(0);
                            end_loader()
                    }else{
						alert_toast("An error occured",'error');
						end_loader();
                        console.log(resp)
					}
				}
			})
		})

	})
</script>