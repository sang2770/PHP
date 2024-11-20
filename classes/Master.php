<?php
require_once('../config.php');
Class Master extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}
	function capture_err(){
		if(!$this->conn->error)
			return false;
		else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
			return json_encode($resp);
			exit;
		}
	}
	function save_vehicle(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!empty($data)) $data .=",";
				$v = addslashes(trim($v));
				$data .= " `{$k}`='{$v}' ";
			}
		}
		$check = $this->conn->query("SELECT * FROM `vehicle_list` where `name` = '{$name}' ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;
		if($this->capture_err())
			return $this->capture_err();
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Vehicle Name already exists.";
			return json_encode($resp);
			exit;
		}
		if(empty($id)){
			$sql = "INSERT INTO `vehicle_list` set {$data} ";
		}else{
			$sql = "UPDATE `vehicle_list` set {$data} where id = '{$id}' ";
		}
			$save = $this->conn->query($sql);
		if($save){
			$bid = !empty($id) ? $id : $this->conn->insert_id;
			$resp['status'] = 'success';
			if(empty($id))
				$resp['msg'] = "New Vehicle successfully saved.";
			else
				$resp['msg'] = " Vehicle successfully updated.";
			
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		if($resp['status'] == 'success')
			$this->settings->set_flashdata('success',$resp['msg']);
			return json_encode($resp);
	}
	function delete_vehicle(){
		extract($_POST);
		$del = $this->conn->query("UPDATE `vehicle_list` set `delete_flag` = 1 where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Vehicle successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function save_service(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$this->conn->real_escape_string($v)}' ";
			}
		}
		$check = $this->conn->query("SELECT * FROM `service_list` where `name` = '{$name}' ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;
		if($this->capture_err())
			return $this->capture_err();
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Service already exist.";
			return json_encode($resp);
			exit;
		}
		if(empty($id)){
			$sql = "INSERT INTO `service_list` set {$data} ";
			$save = $this->conn->query($sql);
		}else{
			$sql = "UPDATE `service_list` set {$data} where id = '{$id}' ";
			$save = $this->conn->query($sql);
		}
		if($save){
			$resp['status'] = 'success';
			if(empty($id))
				$this->settings->set_flashdata('success'," New Service successfully saved.");
			else
				$this->settings->set_flashdata('success'," Service successfully updated.");
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}
	function delete_service(){
		extract($_POST);
		$del = $this->conn->query("UPDATE `service_list` set delete_flag = 1 where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Service successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function save_price(){
		extract($_POST);
		$data = "";
		foreach($price as $k => $v){
			if(!empty($data)) $data .=", ";
			$data .= "('{$id}', '{$vehicle_id[$k]}', '{$v}')";
		}
		if(!empty($data)){
			$this->conn->query("DELETE FROM `price_list` where service_id = '{$id}'");
			$sql = "INSERT INTO `price_list` (`service_id`, `vehicle_id`, `price`) VALUES {$data}";
			$save = $this->conn->query($sql);
			if($save){
				$resp['status'] = 'success';
				$resp['msg'] = 'Price List has been updated successfully';
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = 'An error occurred while saving the data.';
				$resp['error'] = $this->conn->error;
				$resp['sql'] = $sql;
			}
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = 'No Price Data has been sent.';
		}
		if($resp['status'] == 'success' && isset($resp['msg']))
		$this->settings->set_flashdata('success', $resp['msg']);
		return json_encode($resp);
	}
	function get_vehicle_service(){
		extract($_POST);
		$qry = $this->conn->query("SELECT s.name, p.price,s.id FROM `price_list` p inner join `service_list` s on p.service_id = s.id where s.status = 1 and s.delete_flag = 0 and p.vehicle_id = '{$id}' order by s.name asc");
		$data = [];
		while($row = $qry->fetch_assoc()){
			$row['formatted_price'] = format_num($row['price']);
			$data[]= $row;
		}
		return json_encode(['status' => 'success', 'data' => $data]);
	}
	function save_booking(){
		$_POST['total_amount'] = str_replace(",", "", $_POST['total_amount']);
		if(empty($id)){
			$prefix = date("Ym");
			$code = sprintf("%'.05d",1);
			while(true){
				$check = $this->conn->query("SELECT * FROM `booking_list` where code = '{$prefix}{$code}' ")->num_rows;
				if($check > 0){
					$code = sprintf("%'.05d",ceil($code) + 1);
				}else{
					break;
				}
			}
			$_POST['code'] = $prefix.$code;
		}
		extract($_POST);
		$booking_tbl_allowed_fields = ["code","client_name","contact","email","address","status","vehicle_id","schedule","total_amount"];
		$data = "";
		foreach($_POST as $k =>$v){
			if(in_array($k,$booking_tbl_allowed_fields)){
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$this->conn->real_escape_string($v)}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `booking_list` set {$data} ";
		}else{
			$sql = "UPDATE `booking_list` set {$data} where id = '{$id}' ";
		}
		$save = $this->conn->query($sql);
		if($save){
			$bid = empty($id) ? $this->conn->insert_id : $id;
			$resp['bid'] = $bid;
			$data="";
			foreach($service_check as $k =>$v){
				if(!in_array($k,array_merge($booking_tbl_allowed_fields,['id']))){
					if(!empty($data)) $data .=", ";
					$k = $this->conn->real_escape_string($k);
					$v = $this->conn->real_escape_string($v);
					$data .= "('{$bid}', '{$k}', '{$price[$k]}')";
				}
			}
			if(!empty($data)){
				$this->conn->query("DELETE FROM `booking_services` where booking_id = '{$bid}' ");
				$sql2 = "INSERT INTO `booking_services` (`booking_id`, `service_id`, `price`) VALUES {$data}";
				$save2= $this->conn->query($sql2);
				if($save2){
					$resp['status'] = 'success';
					$code = $this->conn->query("SELECT code FROM `booking_list` where id = '{$bid}'")->fetch_array()['code'];
					if(empty($id)){
						$resp['process'] = "new";
						$resp['msg'] = 'Your booking has been sent successfully. Your Booking Reference Code is <b>'.$code.'</b>. We will reach you as soon as we sees you booking request using your given contact information. Thank you!';
					}else{
						$resp['process'] = "old";
						$resp['msg'] = "Booking Details has been updated successfully.";
					}
				}else{
					$resp['status'] = 'failed';
					if(empty($id)){
						$this->conn->query("DELETE FROM `booking_list` where id = '{$bid}'");
						$resp['error'] = $this->conn->error;
						$resp['sql'] = $sql2;
					}
					$resp['msg'] = "Booking has failed to saved due to an error occurred.";
				}
			}
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = 'Booking has failed to saved due to an error occurred.';
			$resp['error'] = $this->conn->error;
			$resp['sql'] = $sql;
		}
		if($resp['status'] == 'success' && isset($resp['msg'])){
			if($resp['process'] == 'new'){
				$this->settings->set_flashdata("success_fix", $resp['msg']);
			}else{
				$this->settings->set_flashdata("success", $resp['msg']);
			}
		}
		return json_encode($resp);
	}
	function update_status(){
		extract($_POST);
		$update = $this->conn->query("UPDATE `booking_list` set `status` = '{$status}' where id = '{$id}'");
		if($update){
			$resp['status'] = 'success';
			$resp['msg'] = "Booking Status has been updated successfully";
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = "Booking Status has failed to update";
		}
		return json_encode($resp);
	}
	function delete_booking(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `booking_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Booking successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function delete_img(){
		extract($_POST);
		if(is_file($path)){
			if(unlink($path)){
				$resp['status'] = 'success';
			}else{
				$resp['status'] = 'failed';
				$resp['error'] = 'failed to delete '.$path;
			}
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = 'Unkown '.$path.' path';
		}
		return json_encode($resp);
	}
}

$Master = new Master();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();
switch ($action) {
	case 'save_vehicle':
		echo $Master->save_vehicle();
	break;
	case 'delete_vehicle':
		echo $Master->delete_vehicle();
	break;
	case 'save_service':
		echo $Master->save_service();
	break;
	case 'delete_service':
		echo $Master->delete_service();
	break;
	case 'save_price':
		echo $Master->save_price();
	break;
	case 'update_status':
		echo $Master->update_status();
	break;
	case 'get_vehicle_service':
		echo $Master->get_vehicle_service();
	break;
	case 'save_booking':
		echo $Master->save_booking();
	break;
	case 'delete_booking':
		echo $Master->delete_booking();
	break;
	case 'delete_img':
		echo $Master->delete_img();
	break;
	default:
		// echo $sysset->index();
		break;
}