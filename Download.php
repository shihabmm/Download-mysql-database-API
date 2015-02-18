<?php 

class Download
{
	public function Download($dbname,$filepath)
	{
		include 'Connection.php';
		$dbname = $dbname;
		$conn = new Connection($dbname,'','');
		$con = $conn->getConnection();
		$con->beginTransaction();
		$result = $con->query('show tables');
		$tables = array();
		$return = '';
		foreach($result as $rows)
		{
			$tables[] = $rows['Tables_in_'.$dbname];
		}
		foreach($tables as $table)
		{
			$stmt = $con->query('show create table '.$table); 
			$row =$stmt->fetch(PDO::FETCH_ASSOC);
			$return.= 'DROP TABLE ' . $table . ';'."\n";
			$return.=$row['Create Table'].';'."\n\n";
			
			$stmt = $con->query("select * from $table");
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			foreach($result as $row)
			{
				$arr = array_values($row);
				$return.="insert into $table values(";
				$last = count($arr)-1;
				foreach($arr as $key=>$value)
				{
					if(is_numeric($value))
					{
						if($key!=$last)
						{
							$return.=$value.',';
						}
						else
						{
							$return.=$value;
						}
					}
					else
					{
						if($key!=$last)
						{
							$return.="'".$value."',"; 
						}
						else
						{
							$return.="'".$value."'"; 
						}
					}
				}
				$return .= ');'."\n";
			}
			$return.="\n";
		}
		
		
		$filename = $filepath;
		$handle = fopen($filename, 'w+');
		fwrite($handle, $return);
		fclose($handle);
		$file = $filename;
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename=' . basename($file));
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		readfile($filename);
	}
}


