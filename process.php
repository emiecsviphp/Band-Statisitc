<?php
	require('./../../../wp-load.php');
	
	global $wpdb, $table_prefix,$current_user;
	
	date_default_timezone_set('UTC');
	$mysqldate = date( 'Y-m-d H:i:s');
	
	$current_userid = $current_user->ID;
	$task = trim(addslashes($_POST['task']));
	
	if( $task == 'editVenue' )	{

		$venue_id = trim(addslashes($_POST['venue_id']));
		$venue_name = trim(addslashes($_POST['venue_name']));

		if($venue_id == '')	{
			$wpdb->query("
				INSERT INTO 
					`".$table_prefix."bs_venues`
				(`usercreatorid`,`venuename`)
					VALUES
				('".$current_userid."','".$venue_name."')
			");
		}
		else	{
			$wpdb->query("
				UPDATE `".$table_prefix."bs_venues`
					SET `venuename` = '".$venue_name."'
						WHERE `id` = '".$venue_id."'
			");
		}
		showVenue();
	}
	
	if( $task == 'deleteVenue' )	{
		$allVals = trim(addslashes($_POST['allVals']));
		$id = explode(',',$allVals);
		foreach($id as $id)	{
			if($id != '')	{
				$wpdb->query("DELETE FROM `".$table_prefix."bs_venues` WHERE `id` = '".$id."'");
			}
		}
		showVenue();
	}
	
	function showVenue()	{
		global $wpdb, $table_prefix,$current_user;
		?>
		<table>		
		<?php	
		$rows = $wpdb->get_results( "SELECT * FROM `".$table_prefix."bs_venues` ORDER BY `venuename`" );
		if(count($rows) > 0)	{
			foreach($rows as $row)	{			
				?>		
				<tr>
					<td>
						<div id="c_b">
							<input type="checkbox" value="<?=$row->id;?>" name="venue_id" id="venue_id" >
						</div>
					</td>
					<td><?php echo stripslashes($row->venuename);?></td>
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
		<?
	}
	
	if( $task == 'deleteBandLinks' )	{
		$bandid = trim(addslashes($_POST['bandid']));
		$allVals = trim(addslashes($_POST['allVals']));
		$id = explode(',',$allVals);
		foreach($id as $id)	{
			if($id != '')	{
				$wpdb->query("DELETE FROM `".$table_prefix."bs_bandlinks` WHERE `id` = '".$id."'");
			}
		}
		
		showBanLinks($bandid);
	}
	
	if( $task == 'addEditLinksBand' )	{
		$bandid = trim(addslashes($_POST['bandid']));
		$link = trim(addslashes($_POST['link']));
		$link_title = trim(addslashes($_POST['link_title']));
		
		$wpdb->query("
			INSERT INTO 
				`".$table_prefix."bs_bandlinks`
			(`usercreatorid`,`bandid`,`links`,`linktitle`)
				VALUES
			('".$current_userid."','".$bandid."','".$link."','".$link_title."')
		");
		showBanLinks($bandid);
	}
	
	function showBanLinks($bandid)	{
		global $wpdb, $table_prefix,$current_user;
		?>
		<table><tbody>
		<?php
		$rows_bs_bandlinks = $wpdb->get_results( "SELECT * FROM `".$table_prefix."bs_bandlinks` WHERE `bandid` = '".$bandid."'" );
		if(count($rows_bs_bandlinks) > 0)	{
			foreach($rows_bs_bandlinks as $row_bandlink)	{
				?>
				<tr>
					<td>
					<input type="checkbox" name="bandlinks_id" id="bandlinks_id" value="<?=$row_bandlink->id;?>">

					</td>
					<td>
					<a href="<?=$row_bandlink->links;?>" title="<?php echo stripslashes($row_bandlink->linktitle); ?>" alt="<?php echo stripslashes($row_bandlink->linktitle); ?>">
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
	
	if( $task == 'editSong' )	{

		$song_id = trim(addslashes($_POST['song_id']));
		$bandid = trim(addslashes($_POST['bandid']));
		$songtitle = trim(addslashes($_POST['songtitle']));

		if($song_id == '')	{
			$wpdb->query("
				INSERT INTO 
					`".$table_prefix."bs_songs`
				(`usercreatorid`,`bandid`,`songtitle`)
					VALUES
				('".$current_userid."','".$bandid."','".$songtitle."')
			");
		}
		else	{
			$wpdb->query("
				UPDATE `".$table_prefix."bs_songs`
					SET `songtitle` = '".$songtitle."',
						`bandid` = '".$bandid."'
						WHERE `id` = '".$song_id."'
			");
		}
		showSong();
	}
	
	if( $task == 'deleteSong' )	{
		$allVals = trim(addslashes($_POST['allVals']));
		$id = explode(',',$allVals);
		foreach($id as $id)	{
			if($id != '')	{
				$wpdb->query("DELETE FROM `".$table_prefix."bs_songs` WHERE `id` = '".$id."'");
			}
		}
		showSong();
	}

	function showSong()	{
		global $wpdb, $table_prefix,$current_user;
		?>
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
		<?php
	}
	
	if( $task == 'editShow' )	{

		$show_id = trim(addslashes($_POST['show_id']));
		$showname = trim(addslashes($_POST['showname']));
		$dateshow = trim(addslashes($_POST['dateshow']));
		$venueid = trim(addslashes($_POST['venueid']));
		$songid = trim(addslashes($_POST['songid']));
		$order = trim(addslashes($_POST['order']));
		$set = trim(addslashes($_POST['set']));

		if($show_id == '')	{
			$wpdb->query("
				INSERT INTO 
					`".$table_prefix."bs_shows`
				(`usercreatorid`,`showname`,`date`,`venueid`,`songid`,`songorder`,`set`)
					VALUES
				('".$current_userid."','".$showname."','".$dateshow."','".$venueid."','".$songid."','".$order."','".$set."')
			");
		}
		else	{
			$wpdb->query("
				UPDATE ".$table_prefix."bs_songs	
					SET `songtitle` = '".$songtitle."',
						`bandid` = '".$bandid."'
						WHERE `id` = '".$song_id."'
			");
		}
		showShows();
	}
	
	if( $task == 'deleteShow' )	{
		$allVals = trim(addslashes($_POST['allVals']));
		$id = explode(',',$allVals);
		foreach($id as $id)	{
			if($id != '')	{
				$wpdb->query("DELETE FROM `".$table_prefix."bs_shows` WHERE `id` = '".$id."'");
			}
		}
		showShows();
	}
	
	function showShows()	{
		global $wpdb, $table_prefix,$current_user;
		?>
		<table>
			<tr>
				<?php	
					$rows = $wpdb->get_results( "SELECT * FROM `".$table_prefix."bs_songs` as songs, `".$table_prefix."bs_shows` as shows
													WHERE shows.songid = songs.id ORDER BY songs.songtitle" );
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
				
			</tr>
		</table>
		<?php
	}
	
	if( $task == 'deleteShowSongs' )	{
		$show_id = trim(addslashes($_POST['show_id']));
		$allVals = trim(addslashes($_POST['allVals']));
		$id = explode(',',$allVals);
		foreach($id as $id)	{
			if($id != '')	{
				$wpdb->query("DELETE FROM `".$table_prefix."bs_showsongs` WHERE `id` = '".$id."'");
			}
		}
		
		displayShowSongs($show_id);
	}
	
	if( $task == 'addEditSongShow' )	{
		$show_id = trim(addslashes($_POST['show_id']));
		$songid = trim(addslashes($_POST['songid']));
		$order = trim(addslashes($_POST['order']));
		$songsets = trim(addslashes($_POST['songsets']));
		
		$wpdb->query("
			INSERT INTO 
				`".$table_prefix."bs_showsongs`
			(`usercreatorid`,`showid`,`songid`,`songorder`,`set`)
				VALUES
			('".$current_userid."','".$show_id."','".$songid."','".$order."','".$songsets."')
		");
		displayShowSongs($show_id);
	}
	
	function displayShowSongs($show_id)	{
		global $wpdb, $table_prefix,$current_user;
		?>
		<table><tbody>
		<?php
		$rows_bs_showsongs = $wpdb->get_results( "SELECT * FROM `".$table_prefix."bs_showsongs` WHERE `showid` = '".$show_id."' ORDER BY `set`,`songorder`" );
		if(count($rows_bs_showsongs) > 0)	{
			foreach($rows_bs_showsongs as $rows_bs_showsong)	{
				$song = $wpdb->get_row( "SELECT `songtitle` FROM `".$table_prefix."bs_songs` WHERE id = '".$rows_bs_showsong->songid."'" );
				?>
				<tr>
					<td><input type="checkbox" name="showsong_id" id="showsong_id" value="<?=$rows_bs_showsong->id;?>"></td>
					<td><?php echo stripslashes($song->songtitle); ?></td>
					<td><?php echo stripslashes($rows_bs_showsong->songorder); ?></td>
					<td><?=$rows_bs_showsong->set;?></td>
				</tr>
				<?php
			}
		}
		?>
		</tbody></table>
		<?php
	}
	
	if( $task == 'getSongsByBand' )	{

		echo '<select name="songid" id="songid">';
		$bandid = trim(addslashes($_POST['bandid']));
		$rows = $wpdb->get_results( "SELECT * FROM `".$table_prefix."bs_songs` WHERE `bandid` = '".$bandid."' ORDER BY bandid, songtitle" );
		if(count($rows) > 0)	{
			foreach($rows as $row)	{
				?>
				<option value="<?=$row->id;?>">
					<?php echo stripslashes($row->songtitle); ?>
				</option>
				<?php
			}
		}
		else	{
			echo '<option value="">--Select Song--</option>';
		}
		echo '</select>';
	}