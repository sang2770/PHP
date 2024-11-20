<section class="py-5">
    <div class="container">
        <?php if($_settings->chk_flashdata('success_fix')): ?>
            <div class="alert alert-success">
                <div class="d-flex w-100 align-items-center">
                    <div class="col-11"><?= $_settings->flashdata('success_fix') ?></div>
                    <div class="col-1 text-right">
                        <button class="btn-close" type="button" onclick="$(this).closest('.alert').hide('slow').remove()"></button>
                    </div>
                </div>
            </div>
        <?php endif;?>
        <h2 class="text-center"><b>Đặt lịch</b></h2>
        <center>
            <hr class="bg-primary" width="10%" style="height:2px;opacity:1">
        </center>
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10 col-sm-12 col-xs-12">
                <div class="card card-default rounded-0 shadow blur">
                    <div class="card-body container-fluid">
                        <form action="" id="booking-form">
                            <input type="hidden" name="id" value="">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group mb-3">
                                        <label for="client_name" class="control-label mx-3">Họ và tên</label>
                                        <input type="text" class="form-control rounded-pill" id="client_name" name="client_name">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="contact" class="control-label mx-3">Số điện thoại #</label>
                                        <input type="text" class="form-control rounded-pill" id="contact" name="contact">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="email" class="control-label mx-3">Email(không bắt buộc)</label>
                                        <input type="email" class="form-control rounded-pill" id="email" name="email">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="address" class="control-label mx-3">Địa chỉ</label>
                                        <textarea rows="3" class="form-control" id="address" name="address"></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group mb-3">
                                        <label for="vehicle_id" class="control-label mx-3">Loại phương tiện</label>
                                        <select class="form-control rounded-pill" id="vehicle_id" name="vehicle_id">
                                            <option value="" selected disabled></option>
                                            <?php 
                                            $vehicles = $conn->query("SELECT * FROM `vehicle_list` where `status` = 1 and delete_flag = 0 order by abs(`name`) asc");
                                            while($row = $vehicles->fetch_assoc()):
                                            ?>
                                            <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="schedule" class="control-label mx-3">Ngày đến</label>
                                        <input type="date" class="form-control rounded-pill" id="schedule" name="schedule">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="total_amount" class="control-label mx-3">Tổng hóa đơn</label>
                                        <input type="text" class="form-control rounded-pill text-right" id="total_amount" name="total_amount" value="0" readonly>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <h4 class="text-center"><b>Chọn dịch vụ </b></h4>
                            <div class="text-center" id="sel-vehicle-first"><small class="text-muted"><i>Vui lòng chọn loại phương tiện trước </i></small></div>
                            <div id="services-list" class="row m-0 w-100"></div>
                            <div class="clear-fix mb-3"></div>
                            <div class="text-center"><button class="btn btn-primary bg-gradient-primary col-4 rounded-pill"><i class="fa fa-send"></i> Xác nhận đặt lịch</button></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<noscript id="service-row-clone">
    <div class="col-1 border mx-0 text-center">
        <input type="hidden" name="service_id[]">
        <input type="hidden" class="price" name="price[]">
        <div class="form-check">
            <input class="form-check-input service-checkbox" data-id="" type="checkbox" name="service_check[]">
        </div>
    </div>
    <div class="col-5 border mx-0 service_name"></div>
    <div class="col-6 border mx-0 text-end service_price"></div>
</noscript>
<script>
    $(function(){
        $('#vehicle_id').select2({
            width:'100%',
            placeholder:"Please select here",
            containerCssClass :"form-control rounded-pill"
        }).addClass("form-control rounded-pill")
        $('#vehicle_id').change(function(){
            var id = $(this).val()
            start_loader()
            $.ajax({
                url:_base_url_+"classes/Master.php?f=get_vehicle_service",
                method:'POST',
                data:{id:id},
                dataType:'json',
                error:err=>{
                    console.log(err)
                    alert_toast('An error occurred.', 'error')
                    end_loader()
                },
                success:function(resp){
                    if(resp.status == 'success'){
                        $('#services-list').html('')
                        $('#services-list').append('<div class="col-1 border bg-primary m-0"><div>')
                        $('#services-list').append('<div class="col-5 border bg-primary m-0 text-center">Service<div>')
                        $('#services-list').append('<div class="col-6 border bg-primary m-0 text-center">Price<div>')
                        Object.keys(resp.data).map(function(k){
                            var row = $('<div>').append($($('noscript#service-row-clone').html()).clone())
                            row.find('[name="service_id[]"]').attr('name', "service_id["+resp.data[k].id+"]")
                            row.find('[name="price[]"]').attr('name', "price["+resp.data[k].id+"]").val(resp.data[k].price)
                            row.find('[name="service_check[]"]').attr('name', "service_check["+resp.data[k].id+"]").attr('data-id',resp.data[k].id)
                            row.find('.service_name').text(resp.data[k].name)
                            row.find('.service_price').text(resp.data[k].formatted_price)
                            var el = row.html()
                            $('#services-list').append(el)
                        })
                    }else{
                        alert_toast('An error occurred.', 'error')
                    }
                    end_loader()
                },
                complete:function(){
                    $('#services-list .service-checkbox').change(function(){
                        calc_total()
                    })
                    function calc_total(){
                        var total = 0;
                        $('#services-list .service-checkbox:checked').each(function(){
                            var sid = $(this).attr('data-id')
                            total+= parseFloat($('[name="price['+sid+']"]').val())
                        })
                        $('#total_amount').val(total.toLocaleString())
                    }
                }
            })
        })
        $('#booking-form').submit(function(e){
            e.preventDefault()
            var _this = $(this)
            var pop_msg = $('<div>')
            pop_msg.addClass("alert alert-danger rounded-0 err-msg")
            pop_msg.hide()
            $('.erro-msg').remove()
            start_loader()
            if($('#services-list .service-checkbox:checked').length <= 0){
                pop_msg.text("Please Select atleast 1 service first")
                _this.prepend(pop_msg)
                pop_msg.show()
                $('html, body').scrollTop(0)
                end_loader()
            }
            $.ajax({
                url:_base_url_+"classes/Master.php?f=save_booking",
                method:'POST',
                data:_this.serialize(),
                dataType:'JSON',
                error:err=>{
                    console.log(err)
                    pop_msg.text("An error occurred.")
                    _this.prepend(pop_msg)
                    pop_msg.show()
                    $('html, body').scrollTop(0)
                    end_loader()
                },
                success:function(resp){
                    if(resp.status == 'success'){
                        location.reload()
                    }else if(!!resp.msg){
                        pop_msg.text(resp.msg)
                        _this.prepend(pop_msg)
                        pop_msg.show()
                        $('html, body').scrollTop(0)
                    }else{
                        pop_msg.text("An error occurred.")
                        _this.prepend(pop_msg)
                        pop_msg.show()
                        $('html, body').scrollTop(0)
                        end_loader()
                    }
                }
            })
        })
    })
</script>