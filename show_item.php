<style type="text/css">
	.item{
		background-color: #e6ecf7;
		height:auto;
		padding: 20px;
		margin-top: 10px;
		border-radius: 10px;

	}
	.item:hover{
		background-color: #becce5;
		-webkit-box-shadow: 10px -9px 23px -10px rgba(0,0,0,0.75);
		-moz-box-shadow: 10px -9px 23px -10px rgba(0,0,0,0.75);
		box-shadow: 10px -9px 23px -10px rgba(0,0,0,0.75);
	}
</style>

<?php 
	require 'config_DB/DB_connect.php';
	$config = parse_ini_file("config_DB/sport_type.ini");
	if(isset($_POST['item_type'])){
		if($_POST['item_type']!="0"){
			$sql ="SELECT * FROM `sport_inventory` WHERE `item_type` = '{$_POST['item_type']}' ";

			$disable = "";



			if($res = mysqli_query($connect, $sql)){
				echo "<h2>{$config[$_POST['item_type']]}</h2><hr>";
				while ($row = mysqli_fetch_assoc($res)) {
					if($row['item_total'] * 1 <= 0){
					    $disabled = "disabled";
					}else{
					     $disabled = "";
					}

					echo "<div class='col-md-3 '>";
					echo "<div class='item' align='center'>";
					echo "<img id='{$row['item_id']}'  src='img_item/{$row['item_img']}' style='height:150px;width: 150px;' > </img>";
					
					echo "<p id='Name-{$row['item_id']}'>{$row['item_name']} (<b>{$row['item_total']}</b>)</p>";
					echo "<button class='btn btn-info borrow' {$disabled} item-id='{$row['item_id']}'>ยืมอุปกรณ์</button>";
					echo "</div>";

					echo "</div>";
				}
			}
		}else{
			$item_list =  array(1,2,3);
			foreach ($item_list as $key => $value) {
				$sql ="SELECT * FROM `sport_inventory` WHERE `item_type` = '{$value}' ";
				if($res = mysqli_query($connect, $sql)){
				echo "<h2>{$config[$value]}</h2><hr>";
				echo "<div class='row'>";
				while ($row = mysqli_fetch_assoc($res)) {
					if($row['item_total'] * 1 <= 0){
					    $disabled = "disabled";
					}else{
					     $disabled = "";
					}
					echo "<div class='col-md-3 '>";
					echo "<div class='item' align='center'>";
					echo "<img id='{$row['item_id']}'  src='img_item/{$row['item_img']}' style='height:150px;width: 150px;' > </img>";
					
					echo "<p id='Name-{$row['item_id']}'>{$row['item_name']} จำนวน (<b>{$row['item_total']}</b>) ชิ้น</p>";
					echo "<button class='btn btn-info borrow' {$disabled} item-id='{$row['item_id']}'>ยืมอุปกรณ์</button>";
					echo "</div>";

					echo "</div>";
				}
				echo "</div>";
			}
			}

		}
		
	}



?>

<div class="modal fade" id="modal-borrow" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">อุปกรณ์</h4>
        </div>
        <div class="modal-body">
        	<div class="row">
        		<div class="col-md-3">
        			<img id="modal-img" style="width:100%;height: 100%;" />
        		</div>
        		<div class="col-md-9">
        		     <form action="#" id="modal-item">
	        			<p id="modal-name">name</p>
	        			<p style="color: red">**ไม่ควรยืมอุปกรณ์เกิน <b id="alert">90</b> ชิ้น**</p>
	        			<p>
	        			<input class="form-control" type="number" id="num-item" min="1" required="">
	        			<input type="hidden" id="item-hide-id">
	        			<input type="hidden" id="note" value="outclass">
	        			</p>
	        			<p><button type="submit" class="btn btn-info">ok</button></p>
        		     </form>
        		</div>
        	</div>
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
</div>
<script type="text/javascript">
	$('.borrow').click(function(event) {
		var item_id = $(this).attr('item-id');
		$("#item-hide-id").val(item_id);
		$("#num-item").val('');
		var url_img =  $("#"+item_id).attr('src');
		var name_item = $('#Name-'+item_id).text();
		$("#modal-name").text(name_item);
		var total = $('#Name-'+item_id+' b').text(); 
		$("#alert").text(total);
		$("#num-item").attr('max', total);
		$("#modal-img").attr('src', url_img);
		$("#modal-borrow").modal('toggle');

	});

	$("form#modal-item").submit(function(event) {
		let item_id = $("#item-hide-id").val();
		let amount = $("#num-item").val();
		var note = $("#note").val();
		$.post('service/service_session_cart.php', {item_id: item_id,amount:amount,method:'set',note:note}, function() {
			
		}).done(function(data){
			if(data == 'true'){
				get_count_item();
				$("#modal-borrow").modal('toggle');
			}else{
				alert(data);
			}
		});
		//alert($("#item-hide-id").val()+" num "+$("#num-item").val());
	});
</script>