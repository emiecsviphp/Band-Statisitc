<?php
	require('./../../../../wp-load.php');
	
	global $wpdb, $table_prefix,$current_user;
	
	date_default_timezone_set('UTC');
	$mysqldate = date( 'Y-m-d H:i:s');
	
	$task = trim(addslashes($_POST['task']));

	if($task == 'addShowUserAttended')	{
		echo '<table>
			<thead><tr>
				<th colspan="2"></th>
			</tr></thead>
			<tfoot><tr>
				<th colspan="2"></th>
			</tr></tfoot>
			<tbody>';
		$vbulletin_userid = trim(addslashes($_POST['current_userid']));
		$allVals = trim(addslashes($_POST['allVals']));
		$ids = explode(',',$allVals);
		$ids = array_unique($ids);
		foreach($ids as $id)	{
			if($id != '')	{
				$wpdb->query("
					INSERT INTO 
						`".$table_prefix."bs_show_userattended`
					(`showid`,`vbulletin_userid`)
						VALUES
					('".$id."','".$vbulletin_userid."')
				");
			}
		}

		$my_attended_show = array();
		
		$rows = $wpdb->get_results( "SELECT * FROM `".$table_prefix."bs_show_userattended` WHERE `vbulletin_userid` = '".$vbulletin_userid."'");
		if(count($rows) > 0)	{
			foreach($rows as $row)	{
				$my_attended_show[] = $row->showid;
			}
		}
		
		$all_shows = array();
		$shows_rows = $wpdb->get_results( "SELECT * FROM `".$table_prefix."bs_shows` ORDER BY `date` DESC");
		if(count($shows_rows) > 0)	{
			foreach($shows_rows as $shows_row)	{
				$all_shows[] = $shows_row->id;
			}
		}
		
		if(count($rows) > 0)	{
			$unattended_shows = array_diff($all_shows, $my_attended_show);
			if(count($unattended_shows) > 0)	{
				foreach($unattended_shows as $unattended_show)	{
					$shows = $wpdb->get_row( "SELECT `date`,`venueid` FROM `".$table_prefix."bs_shows` WHERE id = '".$unattended_show."'" );
					$venue = $wpdb->get_row( "SELECT `venuename` FROM `".$table_prefix."bs_venues` WHERE id = '".$shows->venueid."'" );
					?>		
					<tr>
						<td>
							<input type="checkbox" value="<?=$unattended_show;?>" name="showid" id="showid" style="margin-left:10px; margin-right:10px;">
						</td>
						<td><?=$shows->date;?> - <?=$venue->venuename;?></td>
					</tr>
					<?php
				}
			}
			else	{
					?>		
					<tr>
						<td colspan="2">
							No Records Found
						</td>
					</tr>
					<?php
			}
		}
		else	{
			displayAllShows();
		}
		echo '</tbody></table>';
	}
	function displayAllShows()	{
		global $wpdb, $table_prefix,$current_user;
		$rows = $wpdb->get_results( "SELECT * FROM `".$table_prefix."bs_shows`");
		if(count($rows) > 0)	{
			foreach($rows as $row)	{
				$venue = $wpdb->get_row( "SELECT `venuename` FROM `".$table_prefix."bs_venues` WHERE id = '".$row->venueid."'" );
				?>		
				<tr>
					<td>
						<input type="checkbox" value="<?=$row->id;?>" name="showid" id="showid" style="margin-left:10px; margin-right:10px;">
					</td>
					<td><?=$row->date;?> - <?=$venue->venuename;?></td>
				</tr>
				<?php
			}
		}
		else	{
			?>
			<tr><td colspan="2">No Records found</td></tr>
			<?php
		}
	}
	
	if($task == 'removeShowUserAttended')	{
		$vbulletin_userid = trim(addslashes($_POST['current_userid']));
		$allVals = trim(addslashes($_POST['allVals']));
		$id = explode(',',$allVals);
		
		foreach($id as $id)	{
			if($id != '')	{
				$wpdb->query(" DELETE FROM `".$table_prefix."bs_show_userattended` WHERE `id` = '".$id."' ");
			}
		}
		echo '<table>
			<thead><tr>
				<th colspan="2"></th>
			</tr></thead>
			<tfoot><tr>
				<th colspan="2"></th>
			</tr></tfoot>
			<tbody>';
		displayMyShows($vbulletin_userid);
		echo '</tbody></table>';
	}
	
	function displayMyShows($vbulletin_userid)	{
		global $wpdb, $table_prefix,$current_user;
		$rows = $wpdb->get_results( "SELECT * FROM `".$table_prefix."bs_show_userattended` WHERE `vbulletin_userid` = '".$vbulletin_userid."'");
		if(count($rows) > 0)	{
			foreach($rows as $row)	{
				$shows = $wpdb->get_row( "SELECT `date`,`venueid` FROM `".$table_prefix."bs_shows` WHERE id = '".$row->showid."'" );
				$venue = $wpdb->get_row( "SELECT `venuename` FROM `".$table_prefix."bs_venues` WHERE id = '".$shows->venueid."'" );
				?>
				<tr>
					<td>
						<input type="checkbox" value="<?php echo $row->id; ?>" name="my_showid" id="my_showid" style="margin-left:10px; margin-right:10px;">
					</td>
					<td><?php echo $shows->date; ?> - <?php echo $venue->venuename; ?></td>
				</tr>
				<?php
			}
		}
	}
	
	if($task == 'displayUnattendedShows')	{
	
		echo '<table>
			<thead><tr>
				<th colspan="2"></th>
			</tr></thead>
			<tfoot><tr>
				<th colspan="2"></th>
			</tr></tfoot>
			<tbody>';
	
		$vbulletin_userid = trim(addslashes($_POST['current_userid']));
		$my_attended_show = array();
		$rows = $wpdb->get_results( "SELECT * FROM `".$table_prefix."bs_show_userattended` WHERE `vbulletin_userid` = '".$vbulletin_userid."'");
		if(count($rows) > 0)	{
			foreach($rows as $row)	{
				$my_attended_show[] = $row->showid;
			}
		}
		
		$all_shows = array();
		$shows_rows = $wpdb->get_results( "SELECT * FROM `".$table_prefix."bs_shows` ORDER BY `date` DESC");
		if(count($shows_rows) > 0)	{
			foreach($shows_rows as $shows_row)	{
				$all_shows[] = $shows_row->id;
			}
		}
		
		if(count($rows) > 0)	{
			$unattended_shows = array_diff($all_shows, $my_attended_show);
			if(count($unattended_shows) > 0)	{
				foreach($unattended_shows as $unattended_show)	{
					$shows = $wpdb->get_row( "SELECT `date`,`venueid` FROM `".$table_prefix."bs_shows` WHERE id = '".$unattended_show."'" );
					$venue = $wpdb->get_row( "SELECT `venuename` FROM `".$table_prefix."bs_venues` WHERE id = '".$shows->venueid."'" );
					?>		
					<tr>
						<td>
							<input type="checkbox" value="<?=$unattended_show;?>" name="showid" id="showid" style="margin-left:10px; margin-right:10px;">
						</td>
						<td><?=$shows->date;?> - <?=$venue->venuename;?></td>
					</tr>
					<?php
				}
			}
			else	{
					?>		
					<tr>
						<td colspan="2">
							No Records Found
						</td>
					</tr>
					<?php
			}
		}
		else	{
			displayAllShows();
		}
		echo '</tbody></table>';
	}
	
	
	if($task == 'paginateUnattendedShows')	{

		echo '<table>
			<thead><tr>
				<th colspan="2"></th>
			</tr></thead>
			<tfoot><tr>
				<th colspan="2"></th>
			</tr></tfoot>
			<tbody>';
			

		$vbulletin_userid = trim(addslashes($_POST['current_userid']));
		$startlimit = trim(addslashes($_POST['startlimit']));
		$end_limit = trim(addslashes($_POST['end_limit']));
		$current_page = trim(addslashes($_POST['current_page']));
		$checked_showids = trim(addslashes($_POST['checked_showids']));
		$bandid = trim(addslashes($_POST['bandid']));


		$parse_checked_showids = explode(',',$checked_showids);
		$parse_checked_showids = array_unique($parse_checked_showids);		
		
		$my_attended_show = array();
		$rows = $wpdb->get_results( "SELECT * FROM `".$table_prefix."bs_show_userattended` WHERE `vbulletin_userid` = '".$vbulletin_userid."'");
		if(count($rows) > 0)	{
			foreach($rows as $row)	{
				$my_attended_show[] = $row->showid;
			}
		}
		
		$all_shows = array();
		$shows_rows = $wpdb->get_results( "SELECT * FROM `".$table_prefix."bs_shows` ORDER BY `date` DESC");
		if(count($shows_rows) > 0)	{
			foreach($shows_rows as $shows_row)	{
				$all_shows[] = $shows_row->id;
			}
		}
		
		if(count($rows) > 0)	{
			$ids_concatenate = '';
			$rows = $wpdb->get_results( "SELECT * FROM `".$table_prefix."bs_show_userattended` WHERE `vbulletin_userid` = '".$vbulletin_userid."'");
			if(count($rows) > 0)	{
				$last_row = count($rows);
				foreach($rows as $row)	{
					$ids_concatenate = $ids_concatenate." '".$row->showid."',";
				}
			}
			
			$ids_concatenate = substr($ids_concatenate, 0, -1);		
			/*
			$rows = $wpdb->get_results("
						SELECT * FROM `".$table_prefix."bs_shows` WHERE `id` NOT IN (".$ids_concatenate.") 
							ORDER BY `date` DESC LIMIT ".$startlimit." , ".$end_limit.";
					");
			*/
			
			if($bandid != '')	{
				$rows = $wpdb->get_results("
							SELECT DISTINCT(bssh.id), bssh.date, bssh.venueid, bssh.showname FROM `".$table_prefix."bs_shows` bssh, `".$table_prefix."bs_showsongs` bsss, `".$table_prefix."bs_songs` bss, `".$table_prefix."bs_bands` bsb
							WHERE bssh.id NOT IN (".$ids_concatenate.") 
							AND bssh.id = bsss.showid
							AND bsss.songid = bss.id
							AND bss.bandid = bsb.id
							AND bss.bandid = '".$bandid."'
							ORDER BY bssh.date DESC LIMIT ".$startlimit." , ".$end_limit.";
						");
			}
			else	{
				$rows = $wpdb->get_results("
							SELECT * FROM `".$table_prefix."bs_shows` WHERE `id` NOT IN (".$ids_concatenate.") 
								ORDER BY `date` DESC LIMIT ".$startlimit." , ".$end_limit.";
						");
			}

			if(count($rows) > 0)	{
				foreach($rows as $row)	{
					$venue = $wpdb->get_row( "SELECT `venuename` FROM `".$table_prefix."bs_venues` WHERE id = '".$row->venueid."'" );
					?>
					<tr>
						<td>
							<input type="checkbox" value="<?php echo $row->id; ?>" name="showid" id="showid" style="margin-left:10px; margin-right:10px;" <?php if (in_array($row->id, $parse_checked_showids)) { echo 'checked="yes"'; } ?> >
						</td>
						<td>
							<?php echo $row->date; ?> - <?php echo $venue->venuename; ?>
							<input type="hidden" value="<?php echo $current_page; ?>" name="current_page" id="current_page" style="margin-left:10px; margin-right:10px;">
							<input type="hidden" value="<?php echo $checked_showids; ?>" name="checked_showids" id="checked_showids" style="margin-left:10px; margin-right:10px;">
						</td>
					</tr>
					<?php
				}
			}
			else	{
					?>
					<tr>
						<td colspan="2">
							<input type="hidden" value="<?php echo $current_page; ?>" name="current_page" id="current_page" style="margin-left:10px; margin-right:10px;">
							<input type="hidden" value="<?php echo $checked_showids; ?>" name="checked_showids" id="checked_showids" style="margin-left:10px; margin-right:10px;">
							No Records Found
						</td>
					</tr>
					<?php
			}
		}
		else	{
			global $wpdb, $table_prefix,$current_user;
			
			/*
			$rows = $wpdb->get_results( "
						SELECT * FROM `".$table_prefix."bs_shows` ORDER BY `date` DESC LIMIT ".$startlimit." , ".$end_limit.";
					");
			*/
					
			if($bandid != '')	{
				$rows = $wpdb->get_results("
							SELECT DISTINCT(bssh.id), bssh.date, bssh.venueid, bssh.showname FROM `".$table_prefix."bs_shows` bssh, `".$table_prefix."bs_showsongs` bsss, `".$table_prefix."bs_songs` bss, `".$table_prefix."bs_bands` bsb
							WHERE bssh.id = bsss.showid
							AND bsss.songid = bss.id
							AND bss.bandid = bsb.id
							AND bss.bandid = '".$bandid."'
							ORDER BY bssh.date DESC LIMIT ".$startlimit." , ".$end_limit.";
						");
			}
			else	{
				$rows = $wpdb->get_results( "
							SELECT * FROM `".$table_prefix."bs_shows` ORDER BY `date` DESC LIMIT ".$startlimit." , ".$end_limit.";
						");
			}
					
			if(count($rows) > 0)	{
				foreach($rows as $row)	{
					$venue = $wpdb->get_row( "SELECT `venuename` FROM `".$table_prefix."bs_venues` WHERE id = '".$row->venueid."'" );
					?>		
					<tr>
						<td>
							<input type="checkbox" value="<?php echo $row->id; ?>" name="showid" id="showid" style="margin-left:10px; margin-right:10px;" <?php if (in_array($row->id, $parse_checked_showids)) { echo 'checked="yes"'; } ?> >
							<input type="hidden" value="<?php echo $current_page; ?>" name="current_page" id="current_page" style="margin-left:10px; margin-right:10px;">
							<input type="hidden" value="<?php echo $checked_showids; ?>" name="checked_showids" id="checked_showids" style="margin-left:10px; margin-right:10px;">
						</td>
						<td>
							<?php echo $row->date; ?> - <?php echo $venue->venuename; ?>
						</td>
					</tr>
					<?php
				}
			}
			else	{
				?>
				<tr>
					<td colspan="2">
						<input type="hidden" value="<?php echo $current_page; ?>" name="current_page" id="current_page" style="margin-left:10px; margin-right:10px;">
						<input type="hidden" value="<?php echo $checked_showids; ?>" name="checked_showids" id="checked_showids" style="margin-left:10px; margin-right:10px;">
						No Records Found
					</td>
				</tr>
				<?php
			}
		}
		echo '</tbody></table>';	
	}