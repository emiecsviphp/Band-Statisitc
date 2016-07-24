<?php

function band_function() {
	global $wpdb, $table_prefix,$current_user;
	$current_userid = $current_user->ID;
	?> <script language="JavaScript"> var wpurl = '<?php bloginfo('wpurl'); ?>'; </script> <?php
	if (($_POST['task']) == 'Save'){

		$bandid = $_POST['bandid'];
		$bandname = $_POST['bandname'];
		$image = $_POST['image'];
		$bio = $_POST['bio'];
		
		$links = $_POST['links'];
		$linktitle = $_POST['linktitle'];

		if($_POST['bandid'] == '')	{
			//insert band
			$wpdb->query("
				INSERT INTO 
					`".$table_prefix."bs_bands`
				(`usercreatorid`,`imagefilename`,`bandname`,`bio`)
					VALUES
				('".$current_userid."','".$image."','".$bandname."','".$bio."')
			");
			
			$bandid = $wpdb->get_row( "SELECT id FROM `".$table_prefix."bs_bands` ORDER BY id DESC LIMIT 1" );
			//insert bandlinks
			for($i=0;$i<count($links);$i++)	{
				$wpdb->query("
					INSERT INTO 
						`".$table_prefix."bs_bandlinks`
					(`usercreatorid`,`bandid`,`links`,`linktitle`)
						VALUES
					('".$current_userid."','".$bandid->id."','".$links[$i]."','".$linktitle[$i]."')
				");
			}

			$savemessage = '<div class="updated" id="message"><p>Band successfully added.</p></div>';
		}
		else	{
		
			//update band
			$wpdb->query("
				UPDATE `".$table_prefix."bs_bands`
					SET `imagefilename` = '".$image."',
						`bandname` = '".$bandname."',
						`bio` = '".$bio."'
					WHERE id = '".$bandid."'
			");		

			$savemessage = '<div class="updated" id="message"><p>Band successfully updated.</p></div>';
		}

	}

	if (@$_GET['task']) {
		$task = @$_GET['task'];
		switch ($task) {
			case "delete":
				$bandid = $wpdb->get_row( "SELECT `id` FROM `".$table_prefix."bs_bands` WHERE `id` = '".addslashes($_GET['id'])."'" );				
				$wpdb->query(" DELETE FROM `".$table_prefix."bs_bandlinks` WHERE `bandid` = '".$bandid->id."' ");
				$wpdb->query(" DELETE FROM `".$table_prefix."bs_bands` WHERE `id` = '".addslashes($_GET['id'])."' ");
				$savemessage = '<div class="updated" id="message"><p>Band successfully deleted.</p></div>';
				break;	
		}
	}	
	if(@$_GET['task'] == 'edit')	{
		$id = @$_GET['id'];
		if($id != '')	{
			$rows = $wpdb->get_results( "SELECT * FROM `".$table_prefix."bs_bands` WHERE `id` = '".$id."'" );
			if(count($rows) > 0)	{
				foreach($rows as $row)	{
					$bandid = $row->id;
					$imagefilename = $row->imagefilename;
					$bandname = $row->bandname;
					$bio = $row->bio;
				}
			}
			else	{
					$bandid = '';
					$imagefilename = '';
					$bandname = '';
					$bio = '';
			}
			$header_display = 'Edit';
		}		
		else	{
			$bandid = '';
			$imagefilename = '';
			$bandname = '';
			$bio = '';
			$header_display = 'Add New';
		}
		?>
		<div class="wrap">
			<div class="icon32" id="icon-edit-pages"><br></div>
			<h2 id="add-new-user"><?=$header_display;?> Band</h2>
			<div id="displaymessage"></div>
			<form method="post" action="<?php bloginfo('wpurl'); ?>/wp-admin/admin.php?page=band" name="formband" id="formband">
				<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<strong>Band Name</strong> <span class="description">(required)</span>
						</th>
						<td>
							<input type="hidden" value="<?php echo $bandid; ?>" id="bandid" name="bandid">
							<input type="text" value="<?php echo stripslashes($bandname); ?>" id="bandname" name="bandname" size="80">
						</td>
					</tr>
					<tr>
						<th scope="row">
							<strong>Bio</strong>
						</th>
						<td>
							<textarea rows="10" cols="77" name="bio" id="bio"><?=$bio;?></textarea>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<strong>Links</strong>
						</th>
						<td>
							<table><tbody>
								<tr>
									<td>
										<table><tbody>
											<tr>
												<td>
													<input type="text" value="Enter Url" id="url" name="url" size="25">
												</td>
											</tr>
											<tr>
												<td>
													<input type="text" value="Enter Link Title" id="url_title" name="url_title" size="25">
												</td>
											</tr>
											<tr>
												<td>
												<?php
												if(@$_GET['id'] != '')	{
													$addjsfunction = 'addEditLinksBand()';
												}
												else	{
													//$addjsfunction = 'addLinksBand()';
													$addjsfunction = 'addRow(\'dataTable\')';
												}
												?>							
													<input type="button" value="Add" class="button-primary" id="addusersub" name="adduser" style="float:right;" onclick="<?=$addjsfunction;?>">
												</td>
											</tr>											
										</tbody></table>
									</td>
									<td>
										<?php
										if(@$_GET['id'] != '')	{
											?>
											<div id="displaylinks" style="width: 290px; height: 150px; overflow: auto; padding:4px; border: 1px solid black;">
											<table><tbody>
											<?php
											$rows_bs_bandlinks = $wpdb->get_results( "SELECT * FROM `".$table_prefix."bs_bandlinks` WHERE `bandid` = '".$_GET['id']."'" );
											if(count($rows_bs_bandlinks) > 0)	{
												foreach($rows_bs_bandlinks as $row_bandlink)	{
													?>
													<tr>
														<td>
														<input type="checkbox" name="bandlinks_id" id="bandlinks_id" value="<?=$row_bandlink->id;?>">

														</td>
														<td>
														<a href="<?php echo $row_bandlink->links; ?>" title="<?php echo stripslashes($row_bandlink->linktitle); ?>" alt="<?php echo stripslashes($row_bandlink->linktitle); ?>">
															<?php echo stripslashes($row_bandlink->linktitle); ?>
														</a>
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
											<input type="hidden" name="storage_url" id="storage_url" />
											<input type="hidden" name="storage_urltitle" id="storage_urltitle" />
											<div id="displaylinks" style="width: 290px; height: 150px; overflow: auto; padding:4px; border: 1px solid black;">
											<table id="dataTable"><tbody>
												<tr>
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
											$deletejsfunction = 'removeEditBandlinks()';
										}
										else	{
											$deletejsfunction = 'deleteRow(\'dataTable\')';
										}
										?>
										<input type="button" value="Remove" class="button-primary" id="addusersub" name="adduser" style="margin-left: 220px;margin-top:10px;" onclick="<?=$deletejsfunction;?>">
									</td>									
								</tr>
							</tbody></table>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<strong>Image</strong>
						</th>
						<td>
							<!--<input type="file" value="" id="user_login" name="user_login">-->
							<input type="text" value="<?=$imagefilename;?>" id="image" name="image" size="80">
						</td>
					</tr>
				</tbody>
				</table>
				<div style="margin-top: 50px; margin-left: 300px;">
					<input type="hidden" value="" class="button-primary" id="task" name="task">
					<input type="button" value="Save" class="button-primary" id="addusersub" name="task" onclick="SaveBand()">
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
				Bands
				<a class="button add-new-h2" href="<?php bloginfo('wpurl'); ?>/wp-admin/admin.php?page=band&task=edit">Add New</a>
			</h2>
			<?=$savemessage;?>
			<div class="tool-box">
				<table cellspacing="0" class="widefat post fixed">
					<thead><tr>
						<th>Image</th>
						<th>Bands</th>
						<th style="width:35%">Bio</th>
						<th>Links</th>
					</tr></thead>
					<tfoot><tr>
						<th>Image</th>
						<th>Bands</th>
						<th>Bio</th>
						<th>Links</th>
					</tr></tfoot>
					<tbody>
					<?php
					$rows = $wpdb->get_results( "SELECT * FROM `".$table_prefix."bs_bands` ORDER BY `bandname`" );
					if(count($rows) > 0)	{
						foreach($rows as $row)	{
							?>
						<tr>
							<td>
								<img height="70" width="70" src="<?=$row->imagefilename;?>">
							</td>
							<td>
								<strong>
									<a href="<?php bloginfo('wpurl'); ?>/wp-admin/admin.php?page=band&task=edit&id=<?=$row->id;?>">
										<?php echo stripslashes($row->bandname); ?>
									</a>
								</strong>
								<div class="row-actions">
									<span class="edit">
										<a title="Edit this item" href="<?php bloginfo('wpurl'); ?>/wp-admin/admin.php?page=band&task=edit&id=<?=$row->id;?>">
											Edit
										</a> | 
									</span>
									<span class="delete">
										<a title="Delete this item" href="<?php bloginfo('wpurl'); ?>/wp-admin/admin.php?page=band&task=delete&id=<?=$row->id;?>" onclick="if (confirm('Are you sure you want to delete this record?')) {return true;} else {return false;}">
											Delete
										</a> | 
									</span>				
								</div>				
							</td>
							<td><?=$row->bio;?></td>
							<td>
								<table><tbody>
								<?php
								$counter = 0;
								$rows_bs_bandlinks = $wpdb->get_results( "SELECT * FROM `".$table_prefix."bs_bandlinks` WHERE `bandid` = '".$row->id."'" );
								if(count($rows_bs_bandlinks) > 0)	{
									foreach($rows_bs_bandlinks as $row_bandlink)	{
										?>
										<tr><td>
											<a href="<?php echo $row_bandlink->links; ?>" title="<?php echo stripslashes($row_bandlink->linktitle); ?>" alt="<?php echo stripslashes($row_bandlink->linktitle); ?>"><?php echo stripslashes($row_bandlink->linktitle); ?></a>
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