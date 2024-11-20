<?php 
setlocale(LC_TIME, 'vi_VN.UTF-8');
// Hàm định dạng giá tiền Việt
function format_price($amount) {
    return number_format($amount, 0, ',', '.') . '.000₫';
}
function format_price12($amount) {
    return number_format($amount, 0, ',', '.') . '₫';
}

if (isset($_GET['id'])) {
    $qry = $conn->query("SELECT b.*,v.name as vehicle FROM `booking_list` b inner join `vehicle_list` v on b.vehicle_id = v.id where b.id = '{$_GET['id']}' ");
    if ($qry->num_rows > 0) {
        $res = $qry->fetch_array();
        foreach ($res as $k => $v) {
            if (!is_numeric($k)) {
                $$k = $v;
            }
        }
    } else {
        echo '<script> alert("Unknown Booking\'s ID."); location.replace("./?page=bookings"); </script>';
    }
} else {
    echo '<script> alert("Booking\'s ID is required to access the page."); location.replace("./?page=bookings"); </script>';
}
?>
<div class="content py-3">
    <div class="card card-outline card-primary rounded-0 shadow">
        <div class="card-header">
            <h4 class="card-title">Mã code đặt lịch: <b><?= isset($code) ? $code : "" ?></b></h4>
            <div class="card-tools">
                <a href="./?page=bookings" class="btn btn-default border btn-sm"><i class="fa fa-angle-left"></i> Quay lại</a>
            </div>
        </div>
        <div class="card-body">
            <div class="container-fluid">
                <div class="row mb-0">
                    <div class="col-3 border border-primary bg-gradient-primary mb-0"><b>Tên khách hàng</b></div>
                    <div class="col-9 border mb-0"><?= isset($client_name) ? $client_name : '' ?></div>
                    <div class="col-3 border border-primary bg-gradient-primary mb-0"><b>Số điện thoại #</b></div>
                    <div class="col-9 border mb-0"><?= isset($contact) ? $contact : '' ?></div>
                    <div class="col-3 border border-primary bg-gradient-primary mb-0"><b>Email</b></div>
                    <div class="col-9 border mb-0"><?= isset($email) ? $email : '' ?></div>
                    <div class="col-3 border border-primary bg-gradient-primary mb-0"><b>Địa chỉ</b></div>
                    <div class="col-9 border mb-0"><?= isset($address) ? $address : '' ?></div>
                    <div class="col-3 border border-primary bg-gradient-primary mb-0"><b>Loại xe </b></div>
                    <div class="col-9 border mb-0"><?= isset($vehicle) ? $vehicle : '' ?></div>
                    <div class="col-3 border border-primary bg-gradient-primary mb-0"><b>Ngày lấy</b></div>
                    <div class="col-9 border mb-0"><?= isset($schedule) ? strftime("%A, %d tháng %m năm %Y", strtotime($schedule)) : '' ?></div>
                </div>
                <fieldset class="border-bottom">
                    <legend id="text-muted">Services:</legend>
                    <hr>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-sm-12">
                            <div class="row m-0">
                                <div class="col-5 border bg-gradient-primary border-primary text-center mb-0"><b>Tên dịch vụ</b></div>
                                <div class="col-7 border bg-gradient-primary border-primary text-center mb-0"><b>Giá</b></div>
                                <?php 
                                $services = $conn->query("SELECT bs.*, s.name as `service` FROM `booking_services` bs inner join `service_list` s on bs.service_id = s.id where bs.booking_id = '{$id}' ");
                                while ($row = $services->fetch_assoc()):
                                ?>
                                <div class="col-5 border mb-0"><b><?= $row['service'] ?></b></div>
                                <div class="col-7 border mb-0 text-right"><?= format_price12($row['price']) ?></div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-sm-12 text-right pr-lg-3 pr-md-2 pr-sm-1">
                            <dl>
                                <dt class="pr-5"><b>Tổng hóa đơn</b></dt>
                                <dd class=""><h3><?= isset($total_amount) ? format_price($total_amount) : format_price(0) ?></h3></dd>
                                <dt class="pr-5"><b>Trạng thái</b></dt>
                                <dd class="">
                                    <?php 
                                    $status = isset($status) ? $status : '';
                                    switch ($status) {
                                        case 0:
                                            echo '<span class="badge badge-default border px-3 rounded-pill">Chờ duyệt</span>';
                                            break;
                                        case 1:
                                            echo '<span class="badge badge-primary px-3 rounded-pill">Đã duyệt</span>';
                                            break;
                                        case 2:
                                            echo '<span class="badge badge-warning px-3 rounded-pill">Xe đang đến</span>';
                                            break;
                                        case 3:
                                            echo '<span class="badge badge-info px-3 rounded-pill">Đang rửa</span>';
                                            break;
                                        case 4:
                                            echo '<span class="badge badge-success px-3 rounded-pill">Làm xong</span>';
                                            break;
                                        case 5:
                                            echo '<span class="badge badge-danger px-3 rounded-pill">Hủy</span>';
                                            break;
                                    }
                                    ?>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </fieldset>
                <div class="clear-fix mb-2"></div>
                <div class="text-center">
                    <button class="btn btn-primary bg-gradient-primary border col-3 rounded-pill" id="update_status" type="button"><i class="fa fa-edit"></i> Cập nhật</button>
                    <button class="btn btn-danger bg-gradient-danger border col-3 rounded-pill" id="delete_booking" type="button"><i class="fa fa-trash"></i> Xóa</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(function(){
    $('#update_status').click(function(){
        uni_modal("Update Booking Status", "bookings/update_status.php?id=<?= isset($id) ? $id : '' ?>")
    })
    $('#delete_booking').click(function(){
        _conf("Are you sure to delete this Booking permanently?","delete_booking",[])
    })
})

function delete_booking($id){
    start_loader();
    $.ajax({
        url: _base_url_ + "classes/Master.php?f=delete_booking",
        method: "POST",
        data: {id: '<?= isset($id) ? $id : "" ?>'},
        dataType: "json",
        error: err => {
            console.log(err)
            alert_toast("An error occurred.", 'error');
            end_loader();
        },
        success: function(resp) {
            if (typeof resp == 'object' && resp.status == 'success') {
                location.replace('./?page=bookings');
            } else {
                alert_toast("An error occurred.", 'error');
                end_loader();
            }
        }
    })
}
</script>
