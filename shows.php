<?php

function shows_function() {
	global $wpdb, $table_prefix,$current_user;
	$current_userid = $current_user->ID;
	?> <script language="JavaScript"> var wpurl = '<?php bloginfo('wpurl'); ?>'; </script> <?php

	$current_userid = $current_user->ID;
	
	if (($_POST['task']) == 'Save'){

		$show_id = $_POST['show_id'];
		$showname = $_POST['showname'];
		$dateshow = $_POST['dateshow'];
		$venueid = $_POST['venueid'];

		$songid = $_POST['songids'];
		$order = $_POST['order'];
		$sets = $_POST['sets'];

		if($_POST['show_id'] == '')	{
			//insert shows
			$wpdb->query("
				INSERT INTO 
					`".$table_prefix."bs_shows`
				(`usercreatorid`,`showname`,`date`,`venueid`)
					VALUES
				('".$current_userid."','".$showname."','".$dateshow."','".$venueid."')
			");

			$show = $wpdb->get_row( "SELECT `id` FROM `".$table_prefix."bs_shows` ORDER BY `id` DESC LIMIT 1" );
			//insert showsongs
			for($i=0;$i<count($songid);$i++)	{
				$wpdb->query("
					INSERT INTO 
						`".$table_prefix."bs_showsongs` 
					(`usercreatorid`,`showid`,`songid`,`songorder`,`set`)
						VALUES
					('".$current_userid."','".$show->id."','".$songid[$i]."','".$order[$i]."','".$sets[$i]."')
				");
			}

			$savemessage = '<div class="updated" id="message"><p>Shows successfully added.</p></div>';
		}
		else	{
		
			//update shows
			$wpdb->query("
				UPDATE ".$table_prefix."bs_shows
					SET `showname` = '".$showname."',
						`date` = '".$dateshow."',
						`venueid` = '".$venueid."'
					WHERE id = '".$_POST['show_id']."'
			");		

			$savemessage = '<div class="updated" id="message"><p>Show successfully updated.</p></div>';
		}

	}
	
	if (@$_GET['task']) {
		$task = @$_GET['task'];
		switch ($task) {
			case "delete":
				$wpdb->query("DELETE FROM `".$table_prefix."bs_show_userattended` WHERE `showid` = '".trim(addslashes($_GET['id']))."'");
				$wpdb->query("DELETE FROM `".$table_prefix."bs_showsongs` WHERE `showid` = '".trim(addslashes($_GET['id']))."'");
				$wpdb->query("DELETE FROM `".$table_prefix."bs_shows` WHERE `id` = '".trim(addslashes($_GET['id']))."'");
				echo '<div class="updated" id="message"><p>Shows successfully deleted.</p></div>';
				break;
		}
	}

	if(@$_GET['task'] == 'edit')	{
		$id = @$_GET['id'];
		if($id != '')	{
			$rows = $wpdb->get_results( "SELECT * FROM `".$table_prefix."bs_shows` WHERE `id` = '".$id."'" );
			if(count($rows) > 0)	{
				foreach($rows as $row)	{
					$showid = $row->id;
					$showname = $row->showname;
					$showdate = $row->date;
					$showvenue = $row->venueid;
				}
			}
			else	{
					$showid = '';
					$showname = '';
					$showdate = '';
					$showvenue = '';
			}
			$header_display = 'Edit';
		}		
		else	{
			$showid = '';
			$showname = '';
			$showdate = '';
			$showvenue = '';
			$header_display = 'Add New';
		}
		?>
		<div class="wrap">
			<div class="icon32" id="icon-edit-pages"><br></div>
			<h2 id="add-new-user"><?=$header_display;?> Show</h2>
			<div id="displaymessage"></div>
			<form method="post" action="<?php bloginfo('wpurl'); ?>/wp-admin/admin.php?page=shows" name="formshow" id="formshow">
				<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<strong>Show's Name</strong> <span class="description">(required)</span>
						</th>
						<td>
							<input type="hidden" value="<?=$showid;?>" id="show_id" name="show_id">
							<input type="text" value="<?php echo stripslashes($showname); ?>" id="showname" name="showname" size="60">
						</td>
					</tr>
					<tr>
						<th scope="row">
							<strong>Date</strong>
						</th>
						<td>
							<script language="JavaScript"> var cal = new CalendarPopup();  </script> 
							<input type="text" id="dateshow" name="dateshow" size="20" value="<?=$showdate;?>" >
							<input type="button" name="Button" value="..."  id="Button" onClick="cal.select(document.forms['formshow'].dateshow,'Button','yyyy-MM-dd'); return false;">
							<strong>Venue</strong>
							<select name="venueid" id="venueid">
								<option value="">--Select Venue--</option>
								<?php
								$rows = $wpdb->get_results( "SELECT * FROM `".$table_prefix."bs_venues` ORDER BY venuename" );
								if(count($rows) > 0)	{
									foreach($rows as $row)	{
										?>
										<option value="<?=$row->id;?>" <?php if($row->id == $showvenue)	{ echo 'selected="selected"'; } ?> >
											<?php echo stripslashes($row->venuename);?>
										</option>
										<?php
									}
								}
								?>								
							</select>							
						</td>
					</tr>
					<tr>
						<th scope="row" style="width:240px;">
							<strong>Songs Performed at this Show</strong>
						</th>
						<td>
							<select name="bandid" id="bandid" onchange="getSongsByBand()">
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
							<span id="displaySongsByBand">
							<select name="songid" id="songid">
								<option value="">--Select Song--</option>
							</select>
							</span>
							<!--
							<select name="songid" id="songid">
								<option value="">--Select Song--</option>
								<?php
								/*
								$rows = $wpdb->get_results( "SELECT * FROM `".$table_prefix."bs_songs` ORDER BY bandid, songtitle" );
								if(count($rows) > 0)	{
									foreach($rows as $row)	{
										$band = $wpdb->get_row( "SELECT bandname FROM `".$table_prefix."bs_bands` WHERE id = '".$row->bandid."'" );
										?>
										<option value="<?=$row->id;?>"><i>
											<?php echo stripslashes($band->bandname); ?>: </i><?php echo stripslashes($row->songtitle); ?>
										</option>
										<?php
									}
								}
								*/
								?>
							</select>-->
							<strong>Order</strong>
							<input type="text" value="" id="order" name="order" size="10">
							<!--<input type="button" value="Enter" class="button-primary" id="addusersub" name="adduser" onclick="editShow()">-->
							<?php
							if(@$_GET['id'] != '')	{
								$addjsfunction = 'addEditSongShow()';
							}
							else	{
								$addjsfunction = 'addSongShow(\'dataTable\')';
							}
							?>
							<strong>Set</strong>
							<select name="songsets" id="songsets">
								<option value="First Set">First Set</option>
								<option value="Second Set">Second Set</option>
								<option value="Encore">Encore</option>
							</select>
							<input type="button" value="Enter" class="button-primary" id="addusersub" name="adduser" onclick="<?=$addjsfunction;?>">

						</td>
					</tr>
					<tr>
						<th scope="row">
							<strong>Selected Songs</strong>
						</th>
						<td>
							<div id="displayshow" style="width: 380px; height: 150px; overflow: auto; padding:4px; border: 1px solid black;">

							<?php
							if(@$_GET['id'] != '')	{
								?>
								<table><tbody>
								<?php
								$rows_bs_showsongs = $wpdb->get_results( "SELECT * FROM `".$table_prefix."bs_showsongs` WHERE `showid` = '".$_GET['id']."' ORDER BY `set`,`songorder`" );
								if(count($rows_bs_showsongs) > 0)	{
									foreach($rows_bs_showsongs as $rows_bs_showsong)	{
										$song = $wpdb->get_row( "SELECT `songtitle` FROM `".$table_prefix."bs_songs` WHERE id = '".$rows_bs_showsong->songid."'" );
										?>
										<tr>
											<td><input type="checkbox" name="showsong_id" id="showsong_id" value="<?=$rows_bs_showsong->id;?>"></td>
											<td>
												<?php echo stripslashes($song->songtitle); ?>
											</td>
											<td>
												<?php echo stripslashes($rows_bs_showsong->songorder); ?>
											</td>
											<td>
												<?php echo $rows_bs_showsong->set; ?>
											</td>
										</tr>
										<?php
									}
								}
								?>
								</tbody></table>
								<?php
							}
							else	{
								?>
								<table id="dataTable"><tbody>
									<tr>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
									</tr>
								</tbody></table>
								<?php
							}
							?>
							</div>
							<?php
							if(@$_GET['id'] != '')	{
								$deletejsfunction = 'removeEditShowSongs()';
							}
							else	{
								$deletejsfunction = 'deleteRow(\'dataTable\')';
							}
							?>
							<!--<input type="button" value="Remove" class="button-primary" id="addusersub" name="adduser" style="margin-left: 310px;margin-top:10px;" onclick="removeShow()">-->
							<input type="button" value="Remove" class="button-primary" id="addusersub" name="adduser" style="margin-left: 310px;margin-top:10px;" onclick="<?=$deletejsfunction;?>">
						</td>
					</tr>
					<!--
					<tr>
						<th scope="row">
							<strong>Set</strong>
						</th>
						<td>
							<select name="songsets" id="songsets">
								<option value="First Set">First Set</option>
								<option value="Second Set">Second Set</option>
								<option value="Encore">Encore</option>
							</select>
						</td>
					</tr>
					-->
				</tbody>
				</table>
				<div style="margin-top: 50px; margin-left: 300px;">
					<input type="hidden" value="" class="button-primary" id="task" name="task">
					<input type="button" value="Save Show Info" class="button-primary" id="addusersub" name="task" onclick="SaveShows()">
					<input type="submit" value="Cancel" class="button-primary" id="addusersub" name="task">
				</div>
			</form>
		</div>		
		<?php
	}
	else	{
		?>
		<div class="wrap">
			<div class="icon32" id="icon-edit-pages"><br></div>
			<h2>
				Shows
				<a class="button add-new-h2" href="<?php bloginfo('wpurl'); ?>/wp-admin/admin.php?page=shows&task=edit">Add New</a>
			</h2>
			<div class="tool-box">
				<table cellspacing="0" class="widefat post fixed">
					<thead><tr>
						<th>Date</th>
						<th>Show/Event Name</th>
						<th>Venue</th>
						<th>Songs</th>
					</tr></thead>
					<tfoot><tr>
						<th>Date</th>
						<th>Show/Event Name</th>
						<th>Venue</th>
						<th>Songs</th>
					</tr></tfoot>
					<tbody>
					<?php
					$rows = $wpdb->get_results( "SELECT * FROM `".$table_prefix."bs_shows` as shows ORDER BY shows.showname" );					
					if(count($rows) > 0)	{
						foreach($rows as $row)	{
							$venue = $wpdb->get_row( "SELECT `venuename` FROM `".$table_prefix."bs_venues` WHERE id = '".$row->venueid."'" );
							?>
							<tr>
								<td><?=$row->date;?></td>
								<td>
									<strong>
										<a href="<?php bloginfo('wpurl'); ?>/wp-admin/admin.php?page=shows&task=edit&id=<?=$row->id;?>">
											<?php echo stripslashes($row->showname); ?>
										</a>
									</strong>
									<div class="row-actions">
										<span class="edit">
											<a title="Edit this item" href="<?php bloginfo('wpurl'); ?>/wp-admin/admin.php?page=shows&task=edit&id=<?=$row->id;?>">
												Edit
											</a> | 
										</span>
										<span class="delete">
											<a title="Delete this item" href="<?php bloginfo('wpurl'); ?>/wp-admin/admin.php?page=shows&task=delete&id=<?=$row->id;?>" onclick="if (confirm('Are you sure you want to delete this record?')) {return true;} else {return false;}">
												Delete
											</a> | 
										</span>				
									</div>				
								</td>
								<td><?php echo stripslashes($venue->venuename); ?></td>
								<td>
								<table><tbody>
								<?php
								$counter = 0;
								$rows_bs_showsongs = $wpdb->get_results( "SELECT * FROM `".$table_prefix."bs_showsongs` WHERE `showid` = '".$row->id."' ORDER BY `set`,`songorder`" );
								if(count($rows_bs_showsongs) > 0)	{
									foreach($rows_bs_showsongs as $rows_bs_showsong)	{
										$song = $wpdb->get_row( "SELECT `songtitle` FROM `".$table_prefix."bs_songs` WHERE id = '".$rows_bs_showsong->songid."'" );
										?>
										<tr><td>
											<?php echo stripslashes ($song->songtitle); ?>
										</td></tr>
										<?php
										$counter++;
										if($counter == 3)	{
											break;
										}
									}
								}
								?>
								</tbody></table>
								</td>
							</tr>
							<?php
						}
					}
					else	{
						?>
						<tr><td colspan="4">No Records found</td></tr>
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