<?php
function LogMasterEdit($id, $flag, $mode, $name = '', $desc_str = '', $user_id = false)
{
	global $_POST, $_SERVER, $sess_user_locid, $sess_user_id, $sess_user_name;

	if (empty($name)) {
		if (isset($_POST['txtname']) && !empty($_POST['txtname']) && trim($_POST['txtname']) != '') $name = $_POST['txtname'];
		else if ($flag == 'USR') $name = GetXFromYID("select vName from users where iUserID=$id");

		else if ($flag == 'TRF') {
			$_tq = 'select iHotelID, iRoomTypeID from gen_tariff where iTariffID=' . $id;
			$_tr = sql_query($_tq, '');
			list($h_id, $rt_id) = sql_fetch_row($_tr);

			$name = '';

			if (!empty($name)) $name = substr($name, 0, '-3') . ' Tariff';
		} else if ($flag == 'BBK') $name = GetXFromYID("select vName from cocblock where iBlockID=$id");
	}

	$ip = $_SERVER['REMOTE_ADDR'];

	if (empty($desc_str)) {
		if ($mode == 'I') $desc_str = 'Newly Created';
		else if ($mode == 'U') $desc_str = PrepareEditedDesc();
		else if ($mode == 'D') $desc_str = 'Deleted';
	}

	if ($desc_str != '') {
		$u_id = $u_loc_id = 0;
		$u_name = 'guest';

		if (isset($sess_user_id) && is_numeric($sess_user_id)) {
			$u_id = $sess_user_id;
			$u_loc_id = 0; //$sess_user_locid;
			$u_name = $sess_user_name;
		}

		if (!empty($user_id)) {
			$q = "select iUserID, vName from users where iUserID=$user_id";
			$r = sql_query($q, 'COM.1410');
			if (sql_num_rows($r))
				list($u_id, $u_name) = sql_fetch_row($r);
		}

		if (empty($u_loc_id)) $u_loc_id = 0;
		if (empty($u_id)) $u_id = 0;

		$lmid = NextID('iLMID', 'log_masters');
		$q = "insert into log_masters values ($lmid, $u_loc_id, $u_id, '" . db_input($u_name) . "', '" . NOW . "', $id, '$flag', '" . db_input($name) . "', '" . db_input($desc_str) . "', '$mode', '$ip', 'A')";
		$r = sql_query($q, 'COM.1421');
	}
}

function PrepareEditedDesc()
{
	global $_POST;
	$arr = $_POST;
	$str = '';


	foreach ($arr as $key => $val) {
		$key_len = strlen($key);
		$key_new = substr($key, 0, ($key_len - 4));

		if ((strpos($key, '_old') != ($key_len - 4)) || !isset($arr[$key_new]))
			continue;

		$old = $val;
		$new = $arr[$key_new];

		if ($old != $new) {
			$key_title = $key_new . '_title';
			$title = (isset($arr[$key_title])) ? $arr[$key_title] : substr($key_new, 3);
			$ref_flag = (isset($arr[$key_new . '_ref'])) ? $arr[$key_new . '_ref'] : false;
			$arr_flag = (isset($arr[$key_new . '_arr'])) ? $arr[$key_new . '_arr'] : false;

			if ($ref_flag) {
				$ref_arr = array();

				JustID($old);
				JustID($new);

				if ($ref_flag == 'gen_hotel')
					$ref_arr = GetXArrFromYID("select iHotelID, vName from gen_hotel where iHotelID in ($old, $new)", '3');
				else if ($ref_flag == 'gen_roomtype')
					$ref_arr = GetXArrFromYID("select iRoomTypeID, vName from gen_roomtype where iRoomTypeID in ($old, $new)", '3');
				else if ($ref_flag == 'users')
					$ref_arr = GetXArrFromYID("select iUserID, vName from users where iUserID in ($old, $new)", '3');
				else if ($flag == 'property')
					$ref_arr = GetXArrFromYID("select iPropertyID, vName from property where iPropertyID in ($old, $new)", '3');
				else if ($ref_flag == 'gen_tariff') {
					$_tq = 'select iTariffID, iHotelID, iRoomTypeID from gen_tariff where iTariffID IN (' . $old . ',' . $new . ')';
					$_tr = sql_query($_tq, '');
					if (sql_num_rows($_tr)) {
						while (list($t_id, $h_id, $rt_id) = sql_fetch_row($_tr)) {
							$hName = (!empty($h_id)) ? GetXFromYID("select vName from gen_hotel where iHotelID=$h_id") : '';
							$rtName = (!empty($rt_id)) ? GetXFromYID("select vName from gen_roomtype where iRoomTypeID=$rt_id") : '';

							$name = '';
							if (!empty($hName)) $name .= $hName . ' | ';
							if (!empty($rtName)) $name .= $rtName . ' | ';
							if (!empty($name)) $name = substr($rtName, 0, '-3') . ' Tariff';

							if (!empty($name))
								$ref_arr[$t_id] = $name;
						}
					}
				} else if ($ref_flag == 'cocblock')
					$ref_arr = GetXArrFromYID("select iBlockID, vName from cocblock where iBlockID in ($old, $new)", '3');

				if (count($ref_arr)) {
					$old = (isset($ref_arr[$old])) ? $ref_arr[$old] : 'n/a';
					$new = (isset($ref_arr[$new])) ? $ref_arr[$new] : 'n/a';
				}
			} else if ($arr_flag) {
				global ${$arr_flag};

				$old = (isset(${$arr_flag}[$old])) ? ${$arr_flag}[$old] : 'n/a';
				$new = (isset(${$arr_flag}[$new])) ? ${$arr_flag}[$new] : 'n/a';
			}

			$str .= '| <strong><u>' . strtoupper($title) . '</u>:</strong> <span class="text-danger">' . $old . '</span> -&gt; <span class="text-success">' . $new . '</span>';
		}
	}

	return ($str != '') ? substr($str, 1) : '';
}
