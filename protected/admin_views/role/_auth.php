<?php 
	if (is_array($operation)) {
		foreach ($operation as $parent => $child) {
			if($parent){
?>
			<div class="op_row">
				<div class="edit_row parent">
					<div class="edit_checkbox">
						<label><?php echo $parent;?></label>
					<?php if (in_array($parent, $has_operation)) {?>
						<input type="checkbox" checked="checked" />
					<?php } else {?>
						<input type="checkbox" />
					<?php }?>
					</div>
				</div>
		<?php 	
			} else {
				echo '<div class="op_row"><div class="edit_row parent"></div>';
			}

			if(count($child) > 0){
				foreach ($child as $value) {
		?>
				<div class="edit_row child">
					<div class="edit_checkbox">
						<label><?php echo $value;?></label>
					<?php if (in_array($value, $has_operation)) {?>
						<input type="checkbox" checked="checked" />
					<?php } else {?>
						<input type="checkbox" />
					<?php }?>
					</div>
				</div>
		<?php    
				}
			}
		?>
			</div>
<?php	
		}
	}
?>