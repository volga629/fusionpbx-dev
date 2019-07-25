<?php
/*
	FusionPBX
	Version: MPL 1.1

	The contents of this file are subject to the Mozilla Public License Version
	1.1 (the "License"); you may not use this file except in compliance with
	the License. You may obtain a copy of the License at
	http://www.mozilla.org/MPL/

	Software distributed under the License is distributed on an "AS IS" basis,
	WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License
	for the specific language governing rights and limitations under the
	License.

	The Original Code is FusionPBX

	The Initial Developer of the Original Code is
	Mark J Crane <markjcrane@fusionpbx.com>
	Portions created by the Initial Developer are Copyright (C) 2008-2019
	the Initial Developer. All Rights Reserved.

	Contributor(s):
	Mark J Crane <markjcrane@fusionpbx.com>
*/

if ($domains_processed == 1) {

	//if the default groups do not exist add them
		$group = new groups;
		$group->defaults();

	//find rows that have a null group_uuid and set the correct group_uuid
		$sql = "select * from v_user_groups ";
		$sql .= "where group_uuid is null; ";
		$database = new database;
		$result = $database->select($sql, null, 'all');
		if (is_array($result)) {
			foreach($result as $row) {
				if (strlen($row['group_name']) > 0) {
					//get the group_uuid
						$sql = "select group_uuid from v_groups ";
						$sql .= "where group_name = :group_name ";
						$parameters['group_name'] = $row['group_name'];
						$database = new database;
						$group_uuid = $database->select($sql, $parameters, 'column');
						unset($sql, $parameters);

					//set the group_uuid
						$sql = "update v_user_groups set ";
						$sql .= "group_uuid = :group_uuid ";
						$sql .= "where user_group_uuid = :user_group_uuid; ";
						$parameters['group_uuid'] = $group_uuid;
						$parameters['user_group_uuid'] = $row['user_group_uuid'];
						$database = new database;
						$database->execute($sql, $parameters);
						unset($sql, $parameters);
				}
			}
			unset ($result);
		}

}

?>
