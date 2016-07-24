<?php

function venues_function() {
	global $wpdb, $table_prefix,$current_user;

	$current_userid = $current_user->ID;
	if (isset($_POST['task']) && ($_POST['task'] == 'Save')) {
	
		$venue_id = trim(addslashes($_POST['venue_id']));
		$venue_name = trim(addslashes($_POST['venue_name']));
		
		if(($_POST['venue_id'] == '') && ($_POST['venue_name'] != ''))	{			
			$wpdb->query("
				INSERT INTO 
					`".$table_prefix."bs_venues`
				(`usercreatorid`,`venuename`)
					VALUES
				('".$current_userid."','".$venue_name."')
			");
			echo '<div class="updated" id="message"><p>Venue successfully added.</p></div>';
		}
		else if(($_POST['venue_id'] != '') && ($_POST['venue_name'] != ''))	{
			$wpdb->query("
				UPDATE `".$table_prefix."bs_venues`
					SET `venuename` = '".$venue_name."'
						WHERE `id` = '".$venue_id."'
			");
			echo '<div class="updated" id="message"><p>Venue successfully updated.</p></div>';
		}
		else	{
			echo '<div class="updated" id="message" style="background-color:#FFEBE8;border-color:#CC0000;border-radius:3px 3px 3px 3px;border-style:solid;border-width:1px;">
					<p><strong>ERROR</strong>: Please provide a valid venue.</p> </div>';
		}
	}

	if (@$_GET['task']) {
		$task = @$_GET['task'];
		switch ($task) {
			case "delete":
				$wpdb->query(" DELETE FROM `".$table_prefix."bs_venues` WHERE `id` = '".trim(addslashes($_GET['id']))."' ");
				echo '<div class="updated" id="message"><p>Venue successfully deleted.</p></div>';
				break;	
		}
	}
	?> <script language="JavaScript"> var wpurl = '<?php bloginfo('wpurl'); ?>'; </script> <?php
	if(@$_GET['task'] == 'edit')	{
		$id = @$_GET['id'];
		if($id != '')	{
			$rows = $wpdb->get_results( "SELECT * FROM `".$table_prefix."bs_venues` WHERE `id` = '".$id."'" );
			if(count($rows) > 0)	{
				foreach($rows as $row)	{
					$venue_id = $row->id;
					$venue_name = $row->venuename ;
				}
			}
			else	{
					$venue_id = '';
					$venue_name = '';
			}
			$header_display = 'Edit';
		}		
		else	{
			$venue_id = '';
			$venue_name = '';
			$header_display = 'Add New';
		}
		?>
		<div class="wrap">
			<div class="icon32" id="icon-edit-pages"><br></div>
			<h2 id="add-new-user"><span id="header_display"><?=$header_display;?></span> Venue</h2>
			<div id="displaymessage"></div>
			<form method="post" action="<?php bloginfo('wpurl'); ?>/wp-admin/admin.php?page=venues">
				<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<strong>Venue</strong> <span class="description">(required)</span>
						</th>
						<td>
							<input type="hidden" value="<?php echo $venue_id; ?>" id="venue_id" name="venue_id">
							<input type="text" value="<?php echo stripslashes($venue_name); ?>" id="venue_name" name="venue_name" size="60">
							<input type="button" value="Enter" class="button-primary" id="addusersub" name="adduser" onclick="editVenue()">
						</td>
					</tr>
				</tbody>
				</table>
				
				<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<strong>List of Venues</strong>
						</th>
						<td>														
							<div id="displayvenue" style="width: 380px; height: 150px; overflow: auto; padding:4px; border: 1px solid black;">
							<table>
							<?php	
							$rows = $wpdb->get_results( "SELECT * FROM `".$table_prefix."bs_venues` ORDER BY `venuename`" );
							if(count($rows) > 0)	{
								foreach($rows as $row)	{			
									?>		
									<tr>
										<td>
											<div id="c_b">
												<input type="checkbox" value="<?=$row->id;?>" name="venue_idcb" id="venue_idcb" >
											</div>
										</td>
										<td><?php echo stripslashes($row->venuename); ?></td>
									</tr>
									<?php
								}
							}
							else	{
								?>
								<tr><td colspan="2">No Records found</td></tr>
								<?php
							}
							?>
							</table>
							</div>
							<input type="button" value="Remove" class="button-primary" id="addusersub" name="adduser" style="margin-left: 310px;margin-top:10px;" onclick="removeVenue()">
						</td>
					</tr>
				</tbody>
				</table>
				<div style="margin-top: 50px; margin-left: 300px;">
					<input type="submit" value="Save" class="button-primary" id="addusersub" name="task">
					<input type="submit" value="Cancel" class="button-primary" id="addusersub" name="task">
				</div>
			</form>
		</div>
		<?
	}
	else	{
		?>
		<div class="wrap">
			<div class="icon32" id="icon-edit-pages"><br></div>
			<h2>
				Venues
				<a class="button add-new-h2" href="<?php bloginfo('wpurl'); ?>/wp-admin/admin.php?page=venues&task=edit">Add New</a>
			</h2>
			<div class="tool-box">
				<table cellspacing="0" class="widefat post fixed">
					<thead><tr>
						<th>Venue</th>
					</tr></thead>
					<tfoot><tr>
						<th>Venue</th>
					</tr></tfoot>
					<tbody>
					<?php
					$rows = $wpdb->get_results( "SELECT * FROM `".$table_prefix."bs_venues` ORDER BY `venuename`" );
					if(count($rows) > 0)	{
						foreach($rows as $row)	{			
							?>
							<tr>
								<td>
									<strong>
										<a href="<?php bloginfo('wpurl'); ?>/wp-admin/admin.php?page=venues&task=edit&id=<?=$row->id;?>"><?php echo stripslashes($row->venuename); ?></a>
									</strong>
									<div class="row-actions">
										<span class="edit">
											<a title="Edit this item" href="<?php bloginfo('wpurl'); ?>/wp-admin/admin.php?page=venues&task=edit&id=<?=$row->id;?>">
												Edit
											</a> | 
										</span>
										<span class="delete">
											<a title="Delete this item" href="<?php bloginfo('wpurl'); ?>/wp-admin/admin.php?page=venues&task=delete&id=<?=$row->id;?>" onclick="if (confirm('Are you sure you want to delete this record?')) {return true;} else {return false;}">
												Delete
											</a> | 
										</span>				
									</div>				
								</td>
							</tr>
							<?php
						}
					}
					else	{
						?>
						<tr><td colspan="1">No Records found</td></tr>
						<?php
					}
					?>
					</tbody>
				</table>
			</div>
		</div>
		<?php
	}
}