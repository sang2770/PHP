<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<div class="card card-outline rounded-0 card-primary">
	<div class="card-header">
		<h3 class="card-title">Danh sách đặt lịch</h3>
		
	</div>
	<div class="card-body">
		<div class="container-fluid">
        <div class="container-fluid">
			<table class="table table-hover table-striped table-bordered">
				<colgroup>
					<col width="5%">
					<col width="15%">
					<col width="20%">
					<col width="20%">
					<col width="15%">
					<col width="10%">
					<col width="15%">
				</colgroup>
				<thead>
					<tr>
						<th>#</th>
						<th>Ngày tạo</th>
						<th>Mã</th>
						<th>Ngày đến</th>
						<th>Loại phương tiện</th>
						<th>Trạng thái</th>
						<th>Điều chỉnh</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$i = 1;
						$qry = $conn->query("SELECT b.*, v.name as vehicle FROM `booking_list` b inner join `vehicle_list` v on b.vehicle_id = v.id order by unix_timestamp(b.date_updated) desc ");
						while($row = $qry->fetch_assoc()):
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td><p class="m-0 truncate-1"><?= date("M d, Y H:i", strtotime($row['date_updated'])) ?></p></td>
							<td><p class="m-0 truncate-1"><?= $row['code'] ?></p></td>
							<td><p class="m-0 truncate-1"><?= date("M d, Y", strtotime($row['schedule'])) ?></p></td>
							<td><p class="m-0 truncate-1"><?= $row['vehicle'] ?></p></td>
							<td class="text-center">
								<?php 
								switch($row['status']){
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
                            </td>
							<td align="center">
								<a class="btn btn-default bg-gradient-light btn-flat btn-sm" href="?page=bookings/view_details&id=<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> Xem</a>
							</td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		
		$('.table').dataTable({
			columnDefs: [
					{ orderable: false, targets: [5] }
			],
			order:[0,'asc']
		});
		$('.dataTable td,.dataTable th').addClass('py-1 px-2 align-middle')
	})
	
</script>