<?php
class MySQL extends MySQLi
{
	private $link = NULL;
	private static $instance = NULL;
	
	// returns a singleton instance of MySQL class (chainable)
	
	protected function __clone(){ }
	
	public static function getIstance()
	{
		if (!isset(self::$instance))
		{
			$c = __CLASS__;
			self::$instance = new $c;
		}
		return self::$instance;
    }
	

	// connect to MySQL
		
	public function __construct()
	{
		if (FALSE === ($this->link = mysqli_connect(MySERVER,MyUSER,MyPWD,MyDB)))
		{
			throw new Exception('Error : ' . mysqli_connect_error());
		}
		$this->setChar();
	}
	
	// perform query
	
	public function r_query($query)
	{
		if (is_string($query) AND empty($query) === FALSE)
		{
			if ($rs = mysqli_query($this->link,$query))
			{
				return $rs;
			}
			else
			{
				throw new Exception('Error performing query ' . $query . ' Error message :' .mysqli_error($this->link));
				return false;
			}
		}
	}
	
	public function rs_query($query)
	{
		if (is_string($query) AND empty($query) === FALSE)
		{
			if (!mysqli_real_query($this->link,$query))
			{
				throw new Exception('Error performing query ' . $query . ' Error message :' .mysqli_error($this->link));
				return false;
			}
			else
			{
				return new MySQL_RS($this->link);
			}
		}
	}
	
	public function i_query($query)
	{
		if (is_string($query) AND empty($query) === FALSE)
		{	
			if (!mysqli_real_query($this->link,$query))
			{
				throw new Exception('Error performing query ' . $query . ' Error message :' .mysqli_error($this->link));
				return false;
			}
			else
			{
				return true;
			}
		}
	}
	
	public function m_query($query)
	{
		if (is_string($query) AND empty($query) === FALSE)
		{
			if (!mysqli_multi_query($this->link, $query))
			{
				throw new Exception('Error performing query ' . $query . ' Error message :' .mysqli_error($this->link));
				return false;
			}
			else
			{
				do 
				{
					if ($rs = mysqli_store_result($this->link)) 
					{
						while ($row = mysqli_fetch_row($rs)) 
						{
							if(mysqli_error($this->link))
							{
								throw new Exception('Error performing query ' . $query . ' Error message :' .mysqli_error($this->link));
								return false;
							}
						}
						mysqli_free_result($rs);
					}
				} while (mysqli_more_results($this->link) && mysqli_next_result($this->link));
				return true;
			}
		}
	}

	public function getInsertID()
	{
		return mysqli_insert_id($this->link);
	}
	
	/* 
	insert-delete foreign keys on database
	 */
	
	public function doForeignKeys($postdata,$sessiondata,$id)
	{
		global $arrKeyConfig;
		$strSQL = null;
		if(isset($postdata))
		{
			foreach($postdata AS $key=>$value)
			{
				$arrC = explode("-",$key);
				$ref = $arrC[1];
				$zona = $arrC[0];
				$dbtable = $arrKeyConfig[$ref]["maintable"];
				$refdbtable = $arrKeyConfig[$zona."-".$ref]["keytable"];
				$refdbidname = $arrKeyConfig[$ref]["id"];
				$idname = $arrKeyConfig[$zona]["id"];

				if(is_null($sessiondata))
				{
					foreach($value AS $k=>$v)
					{
						$strSQL .= "INSERT INTO $refdbtable ($idname, $refdbidname) VALUES($id,$k);";
					}
				}
				else
				{
					$arrL = array_diff($value,$sessiondata[$key]);
					foreach($arrL AS $k=>$v)
					{
						$strSQL .= "INSERT INTO $refdbtable ($idname, $refdbidname) VALUES($id,$k);";
					}
					$arrL = array_diff($sessiondata[$key],$value);
					foreach($arrL AS $k=>$v)
					{
						$strSQL .= "DELETE FROM $refdbtable WHERE $refdbidname = $k AND $idname = $id;";
					}
				}
			}
			if(!is_null($sessiondata))
			{
				foreach($sessiondata AS $key=>$value)
				{
					//print_r("$key=>$value");
					$arrC = explode("-",$key);
					$ref = $arrC[1];
					$zona = $arrC[0];
					$dbtable = $arrKeyConfig[$ref]["maintable"];
					$refdbtable = $arrKeyConfig[$zona."-".$ref]["keytable"];
					$refdbidname = $arrKeyConfig[$ref]["id"];
					$idname = $arrKeyConfig[$zona]["id"];
					if(!isset($postdata[$key]))
					{
						foreach($value AS $k=>$v)
						{
							$strSQL .= "DELETE FROM $refdbtable WHERE $idname = $id;";
						}
					}
				}
			}
		}
		elseif(!is_null($sessiondata))
		{
			foreach($sessiondata AS $key=>$value)
			{
				//print_r("$key=>$value");
				$arrC = explode("-",$key);
				$ref = $arrC[1];
				$zona = $arrC[0];
				$dbtable = $arrKeyConfig[$ref]["maintable"];
				$refdbtable = $arrKeyConfig[$zona."-".$ref]["keytable"];
				$refdbidname = $arrKeyConfig[$ref]["id"];
				$idname = $arrKeyConfig[$zona]["id"];
				foreach($value AS $k=>$v)
				{
					$strSQL .= "DELETE FROM $refdbtable WHERE $idname = $id;";
				}
			}
		}
		if($strSQL!="")
			return $this->m_query($strSQL);
		else
			return false;
	}
	
	private function getChar()
	{
		return mysqli_character_set_name($this->link);
	}
	
	private function setChar()
	{
		if($this->getChar()!=DEFCHAR)
		{
			if (FALSE === mysqli_set_charset($this->link, DEFCHAR))
			{
				throw new Exception('Error : ' . mysqli_error($this->link));
				return false;
			}
			else
			{
				return true;
			}
		}
	}
	
	public function prepMysql($str)
	{
		$str = stripslashes($str);
		$str = mysqli_real_escape_string($this->link,$str);
		return $str;
	}
	
	public function stopCOMMIT()
	{
		mysqli_autocommit($this->link, false);
	}
	
	public function doCOMMIT($boolResult)
	{
		$boolResult ? mysqli_commit($this->link) : mysqli_rollback($this->link);
		mysqli_autocommit($this->link, true);
	}
	
	// implement destructor to close the database connection

	function __destruct()
	{
		mysqli_close($this->link);
	}
}

class MySQL_RS extends MySQLi_Result
{
	private $_pointer = 0;
	
	// fetch row as an object
	public function fetchObject()
	{
		if (!$row = $this->fetch_object())
			return NULL;
		return $row;
	}
	
	// fetch row as an associative array
	public function fetchAssocArray()
	{
		if (!$row = $this->fetch_assoc())
			return NULL;
		return $row;
	}

	// fetch row as an enumerated array
	public function fetchNumArray()
	{
		if (!$row = $this->fetch_row())
			return NULL;
		return $row;
	}

	// fetch all rows
	public function fetchAll($type = MYSQLI_ASSOC)
	{
		if ($type !== MYSQLI_ASSOC AND $type !== MYSQLI_NUM AND $type !== MYSQLI_BOTH)
			$type = MYSQLI_ASSOC;
		if (!$rows = $this->fetch_all($type))
			return NULL;
		return $rows;
	}

	// get definition information on fields
	public function fetchFieldsInfo()
	{
		if (!$fieldsInfo = $this->fetch_fields())
			throw new Exception('No information available for table fields.');
		return $fieldsInfo;
	}

	// get definition information on next field
	public function fetchFieldInfo()
	{
		if (!$fieldInfo = $this->fetch_field())
			throw new Exception('No information available for current table field.');
		return $fieldInfo;
	}

	// move pointer in result set to specified offset
	public function movePointer($offset)
	{
		$offset = abs((int)$offset);
		$limit = $this->num_rows - 1;
		if ($limit <= 0 OR $offset > $limit)
				return FALSE;
		unset($limit);
		return $this->data_seek($offset);
	}

	// count rows in result set (implementation required by 'count()' method in Countable interface)
	public function count()
	{
		return $this->num_rows;
	}

	// reset result set pointer (implementation required by 'rewind()' method in Iterator interface)
	public function rewind()
	{
		$this->_pointer = 0;
		$this->data_seek($this->_pointer);
		return $this;
	}

	// get current row set in result set (implementation required by 'current()' method in Iterator interface)
	public function current()
	{
		if (!$this->valid())
			throw new Exception('Unable to retrieve current row.');
		$this->movePointer($this->_pointer);
		return $this->fetchObject();
	}

	// get current result set pointer (implementation required by 'key()' method in Iterator interface)
	public function key()
	{
		//return $this->_pointer;
		return $this->current_field;
	}

	// move forward result set pointer (implementation required by 'next()' method in Iterator interface)
	public function next()
	{
		++$this->_pointer;
		$this->movePointer($this->_pointer);
		return $this;
	}

	// determine if result set pointer is valid or not (implementation required by 'valid()' method in Iterator interface)
	public function valid()
	{
		return $this->_pointer < $this->num_rows;
	}

	// free up result set
	public function __destruct()
	{
		$this->close();
	}
}
?>