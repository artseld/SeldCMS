<?php

// Automatic Job
// Additional Functionality

// Timer Class
class Timer
{
	static protected $start_time	= 0;
	static protected $end_time		= 0;

	static public function tStart()
	{
		$time = microtime();
		$time = explode(" ", $time);
		self::$start_time = $time[1] + $time[0];
	}

	static public function tEnd()
	{
		$time = microtime();
		$time = explode(" ", $time);
		self::$end_time = $time[1] + $time[0];
	}

	static public function tTime()
	{
		return (self::$end_time - self::$start_time);
	}
}

// Backup Class
class DBBackup
{
	/* backup the db OR just a table */
	static public function backup_tables($conn, $tables = '*')
	{

		//get all of the tables
		if($tables == '*')
		{
			$tables = array();
			$result = $conn->query('SHOW TABLES');
			while($row = $result->fetch_row())
			{
				$tables[] = $row[0];
			}
		}
		else
		{
			$tables = is_array($tables) ? $tables : explode(',',$tables);
		}
  
		//cycle through
		foreach($tables as $table)
		{
			$result = $conn->query('SELECT * FROM '.$table);
			$num_fields = $result->field_count;

            $return = "";
			$return .= 'DROP TABLE '.$table.';';
			$row2 = $conn->query('SHOW CREATE TABLE '.$table)->fetch_row();
			$return .= "\n\n".$row2[1].";\n\n";

			for ($i = 0; $i < $num_fields; $i++) 
			{
				while($row = $result->fetch_row())
				{
					$return .= 'INSERT INTO '.$table.' VALUES(';
					for($j=0; $j<$num_fields; $j++) 
					{
						$row[$j] = addslashes($row[$j]);
						$row[$j] = ereg_replace("\n","\\n",$row[$j]);
						if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
						if ($j<($num_fields-1)) { $return.= ','; }
					}
					$return.= ");\n";
				}
			}
			$return.="\n\n\n";
		}

		//save file
		$handle = fopen('../backups/db-backup-'.time().'-'.(md5(implode(',',$tables))).'.sql','w+');
		fwrite($handle,$return);
		fclose($handle);
	}
}

/* End of file func.inc.php */
/* Location: ./application/admin/cron/func.inc.php */