<?php

function songs_function() {
	global $wpdb, $table_prefix,$current_user;
	$current_userid = $current_user->ID;
	
	if (isset($_POST['task']) && ($_POST['task'] == 'Save')) {
	
		$song_id = trim(addslashes($_POST['song_id']));
		$bandid = trim(addslashes($_POST['bandid']));
		$songtitle = trim(addslashes($_POST['songtitle']));
		
		if(($_POST['song_id'] == '') && ($_POST['songtitle'] != '') && ($_POST['bandid'] != ''))	{			

			$wpdb->query("
				INSERT INTO 
					`".$table_prefix."bs_songs`
				(`usercreatorid`,`bandid`,`songtitle`)
					VALUES
				('".$current_userid."','".$bandid."','".$songtitle."')
			");			
			
			
			echo '<div class="updated" id="message"><p>Song successfully added.</p></div>';
		}
		else if(($_POST['song_id'] != '') && ($_POST['songtitle'] != '') && ($_POST['bandid'] != ''))	{
			$wpdb->query("
				UPDATE `".$table_prefix."bs_songs`
					SET `songtitle` = '".$songtitle."',
						`bandid` = '".$bandid."'
						WHERE `id` = '".$song_id."'
			");
			echo '<div class="updated" id="message"><p>Song successfully updated.</p></div>';
		}
		else	{
			echo '<div class="updated" id="message" style="background-color:#FFEBE8;border-color:#CC0000;border-radius:3px 3px 3px 3px;border-style:solid;border-width:1px;">
					<p><strong>ERROR</strong>: Please provide a valid song title and please select a band name.</p></div>';
		}
	}
	
	
	
	
	?> <script language="JavaScript"> var wpurl = '<?php bloginfo('wpurl'); ?>'; </script> <?php
	if (@$_GET['task']) {
		$task = @$_GET['task'];
		switch ($task) {
			case "delete":
				$wpdb->query(" DELETE FROM `".$table_prefix."bs_songs` WHERE `id` = '".trim(addslashes($_GET['id']))."' ");
				echo '<div class="updated" id="message"><p>Song successfully deleted.</p></div>';
				break;	
		}
	}
	if(@$_GET['task'] == 'edit')	{
		$id = @$_GET['id'];
		if($id != '')	{
			$rows = $wpdb->get_results( "SELECT * FROM `".$table_prefix."bs_songs` WHERE `id` = '".$id."'" );
			if(count($rows) > 0)	{
				foreach($rows as $row)	{
					$songid = $row->id;
					$bandid = $row->bandid;
					$songtitle = $row->songtitle ;
				}
			}
			else	{
					$songid = '';
					$bandid = '';
					$songtitle = '';
			}
			$header_display = 'Edit';
		}		
		else	{
			$songid = '';
			$bandid = '';
			$songtitle = '';
			$header_display = 'Add New';
		}
		?>
		<div class="wrap">
			<div class="icon32" id="icon-edit-pages"><br></div>
			<h2 id="add-new-user"><span id="header_display"><?=$header_display;?></span> Song</h2>
			<div id="displaymessage"></div>
			<form method="post" action="<?php bloginfo('wpurl'); ?>/wp-admin/admin.php?page=songs">
				<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<strong>Song Title</strong> <span class="description">(required)</span>
						</th>
						<td>
							<input type="hidden" value="<?=$songid;?>" id="song_id" name="song_id">
							<input type="text" value="<?php echo stripslashes($songtitle); ?>" id="songtitle" name="songtitle" size="60">
						</td>
					</tr>
					<tr>
						<th scope="row">
							<strong>Band</strong> <span class="description">(required)</span>
						</th>
						<td>
							<select name="bandid" id="bandid">
							<option value="">--Select Band--</option>
							<?php
							$rows = $wpdb->get_results( "SELECT * FROM `".$table_prefix."bs_bands` ORDER BY `bandname`" );
							if(count($rows) > 0)	{
								foreach($rows as $row)	{
									?>
									<option value="<?=$row->id;?>" <?php if($row->id == $bandid) { echo 'selected="selected"'; } ?> >
										<?php echo stripslashes($row->bandname); ?>
									</option>
									<?php
								}
							}
							?>
							</select>
							<input type="button" value="Enter" class="button-primary" id="addusersub" name="adduser" onclick="editSong()">
						</td>
					</tr>
				</tbody>
				</table>
				
				<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<strong>List of Songs</strong>
						</th>
						<td>														
							<div id="displaysong" style="width: 380px; height: 150px; overflow: auto; padding:4px; border: 1px solid black;">
							<table>
							<?php	
							$rows = $wpdb->get_results( "SELECT * FROM `".$table_prefix."bs_songs` ORDER BY `songtitle`" );
							if(count($rows) > 0)	{
								foreach($rows as $row)	{			
									?>							
									<tr>
										<td><input type="checkbox" name="song_id" id="song_id" value="<?=$row->id;?>"></td>
										<td><?php echo stripslashes($row->songtitle); ?></td>
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
							<input type="button" value="Remove" class="button-primary" id="addusersub" name="adduser" style="margin-left: 310px;margin-top:10px;" onclick="removeSong()">
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
		<?php
	}
	//else	{
	if((@$_GET['task'] != 'edit') ||	($_POST == 'Cancel') || ($_POST == 'Save')){
		?>
		<div class="wrap">
			<div class="icon32" id="icon-edit-pages"><br></div>
			<h2>
				Songs
				<a class="button add-new-h2" href="<?php bloginfo('wpurl'); ?>/wp-admin/admin.php?page=songs&task=edit">Add New</a>
			</h2>
			<div class="tool-box">
				<table cellspacing="0" class="widefat post fixed">
					<thead><tr>
						<th>Song Title</th>
						<th>Band</th>
						<th>Shows</th>
					</tr></thead>
					<tfoot><tr>
						<th>Song Title</th>
						<th>Band</th>
						<th>Shows</th>
					</tr></tfoot>
					<tbody>
					<?php
					$rows = $wpdb->get_results( "SELECT * FROM `".$table_prefix."bs_songs` ORDER BY `songtitle`" );
					if(count($rows) > 0)	{
						foreach($rows as $row)	{			
							?>
							<tr>
								<td>
									<strong>
										<a href="<?php bloginfo('wpurl'); ?>/wp-admin/admin.php?page=songs&task=edit&id=<?=$row->id;?>">
											<?php echo stripslashes($row->songtitle); ?>
										</a>
									</strong>
									<div class="row-actions">
										<span class="edit">
											<a title="Edit this item" href="<?php bloginfo('wpurl'); ?>/wp-admin/admin.php?page=songs&task=edit&id=<?=$row->id;?>">
												Edit
											</a> | 
										</span>
										<span class="delete">
											<a title="Delete this item" href="<?php bloginfo('wpurl'); ?>/wp-admin/admin.php?page=songs&task=delete&id=<?=$row->id;?>" onclick="if (confirm('Are you sure you want to delete this record?')) {return true;} else {return false;}">
												Delete
											</a> | 
										</span>				
									</div>				
								</td>
								<td>
									<?php
									$band = $wpdb->get_row( "SELECT `bandname` FROM `".$table_prefix."bs_bands` WHERE `id` = '".$row->bandid."'" );
									echo stripslashes($band->bandname);
									?>
								</td>
								<td></td>
							</tr>
							<?php
						}
					}
					else	{
						?>
						<tr><td colspan="3">No Records found</td></tr>
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