<?php
namespace core;

/*
@dev_name 	:	kuldeep singh
@description:	medium level crud library
*/
class Model extends \config\config
{
	protected 	$_db = null;
	protected 	$_tableName;
	protected	$_arrWhereClouseColumns;
	protected	$_getWhereClauseParam;
	protected	$_limitParams;
	protected	$_offsetParams;
	protected	$_arrLimitOffsetData;
	protected	$_tablesBinding;
	protected	$_selectCoumns;
	protected	$_arrSetColumns;
	protected	$_arrSetRowData;
	protected	$_databaseDriver=	'mysql';
	protected	$_orderBy;
	protected	$_groupBy;
	protected	$_having;
	protected	$_operator = '=';
	public 		$_arrReadData = array();

	public function __construct()
	{
		//$this->_displayAllQuries = true;
		parent::__construct();
		try{
			if($this->_databaseDriver == 'mysql'){
				$_dns 	= 	$this->_databaseDriver.':dbname=' . $this->_databaseConfig['database'] . ";host=" . $this->_databaseConfig['host'];
			}else{
				$_dns 	= 	$this->_databaseDriver.':Server='.$this->_databaseConfig['host'].';Database='.$this->_databaseConfig['database'];
			}
			$this->_db = new \PDO($_dns, $this->_databaseConfig['username'], $this->_databaseConfig['password']);
			$this->_db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		}catch(\PDOException $_e){
			$this->__queryErrorDebug('Unable to connect database.', $_e);
		}
	}

	/* reset param values */
	public function __resetObjVars()
	{
		$this->_tableName				= 	'';
		$this->_arrWhereClouseColumns	=	[];
		$this->_getWhereClauseParam		=	'';
		$this->_limitParams				=	'';
		$this->_offsetParams			=	'';
		$this->_arrLimitOffsetData		= 	[];
		$this->_tablesBinding 			=	'';
		$this->_selectCoumns			=	'*';
		$this->_arrSetColumns			=	[];
		$this->_arrSetRowData			=	[];
		$this->_orderBy					=	'';
		$this->_groupBy					=	'';
		$this->_having					=	'';
	}

	/*
	@description: begin transation
	*/
	public function __beginTransactions()
	{
		$this->_db->beginTransaction();
	}

	/*
	@description: rollback if something not inserted/updated in tables.
	*/
	public function __rollBack()
	{
		$this->_db->rollBack();
	}

	/*
	@description: Commit TRANSACTIONS.
	*/
	public function __commitTransactions()
	{
		$this->_db->commit();
	}

	/*
	@description: setup where clause in @string
	*/
	public function __setTable(string $_tableName)
	{
		$this->__resetObjVars();
		$this->_tableName	=	$_tableName;
		return $this;
	}

	/*
	@description: setup where clause in @array
	*/
	public function __setArrSelectColumns(array $_arrColumns)
	{
			$_arrColumns			=	$this->__arrayFilter($_arrColumns);
		if(!empty($_arrColumns)){
			$this->_selectCoumns	=	implode($_arrColumns, ', ');
		}
		return $this;

	}

	/*
	@description:	set order by @array, @string
	*/
	public function __setOrderBy(array $_arrOrderByColumns, string $_order='asc')
	{
			$_arrOrderByColumns	=	$this->__arrayFilter($_arrOrderByColumns);
		if(!empty($_arrOrderByColumns)){
			$this->_orderBy		=	' ORDER BY '. implode($_arrOrderByColumns, ',').' '.$_order.' ';
		}
		return $this;
	}

	/*
	@description:	set order by @string
	*/
	public function __setGroupBy(string $_groupByColumns)
	{
		if($_groupByColumns != ''){
			$this->_groupBy	=	' GROUP BY '.$_groupByColumns.' ';
		}
		return $this;
	}

	/*
	@description:	set order by @string
	*/
	public function __setHaving(string $_havingCondition)
	{
		if($_havingCondition != ''){
			$this->_having	=	' having '.$_havingCondition.' ';
		}
		return $this;
	}

	public function __arrayFilter(array $_array)
	{
	    $_returnArray = array();
	    foreach($_array AS $_key=>$_value){
				if($_value===''){
					$_value = NULL;
				}
	      $_returnArray[$_key] = $_value;
	    }
		return   $_returnArray;
	}

	/*
	@description: insert and update data in @array
	*/
	public function __setData(array $_arrData)
	{
			$this->_arrSetColumns	=	$this->_arrSetRowData	=	[];
			$_arrData				=	$this->__arrayFilter($_arrData);

		if(is_array($_arrData) && !empty($_arrData)){
			foreach($_arrData as $_key=>$_val){

				$_dataReferrer	=	str_replace('.', '_', $_key);

				$this->_arrSetColumns[$_key]			=	$_key.' = :'.$_dataReferrer;
				$this->_arrSetRowData[$_dataReferrer]	=	$_val;
			}
		}
		return $this;
	}

	/*
	@description: set comparing operator ( =, <=>, >, >=, IS NULL, IS NOT NULL, <, <=, LIKE, !=, <>, NOT LIKE )
	*/
	public function __setArrComparisonOperator(array $_arrOperators)
	{
			$_arrOperators		=	$this->__arrayFilter($_arrOperators);
		if(!empty($_arrOperators)){
			$this->_operator 	=	$_arrOperators;
		}
		return $this;
	}

	/*
	@description: setup where clause in @array
	*/
	public function __setArrWhereClauseUsingAnd(array $_arrWhereClouse)
	{
			$_arrWhereClouse	=	$this->__arrayFilter($_arrWhereClouse);
		if(is_array($_arrWhereClouse) && !empty($_arrWhereClouse)){
				$_i = 0;
			foreach($_arrWhereClouse as $_key=>$_val){
				$_dataReferrer	=	str_replace('.', '_', $_key);

				$this->_operator[$_i]	=	(!isset($this->_operator[$_i]) || !is_string($this->_operator[$_i]))? '=':strtoupper($this->_operator[$_i]);

				/* condition to handle NULL and LIKE statements */
				if(strpos($this->_operator[$_i], 'NULL') !== false){ //NULL

					$this->_arrWhereClouseColumns[$_key]	=	$_key.' ' . $this->_operator[$_i];

				}else if(strpos($this->_operator[$_i], 'LIKE') !== false){ //like

					$_val	=	str_replace('LIKE', $_val, $this->_operator[$_i]);
					$this->_arrWhereClouseColumns[$_key]	=	$_key.' LIKE :'.$_dataReferrer;
					$this->_arrSetRowData	=	array_merge($this->_arrSetRowData, [$_dataReferrer=>$_val]);

				}else{

					$this->_arrWhereClouseColumns[$_key]	=	$_key.' ' . $this->_operator[$_i] . ' :'.$_dataReferrer;
					$this->_arrSetRowData	=	array_merge($this->_arrSetRowData, [$_dataReferrer=>$_val]);

				}
				$_i++;
			}
			$this->_getWhereClauseParam	=	' WHERE '.implode($this->_arrWhereClouseColumns, ' AND ');
		}
		return $this;
	}

	/*
	@description: setup where clause in @array
	*/
	public function __setArrWhereClauseUsingOr(array $_arrWhereClouse)
	{
				$_arrWhereClouse=	$this->__arrayFilter($_arrWhereClouse);

		if(is_array($_arrWhereClouse) && !empty($_arrWhereClouse)){
				$_arrInValues	=	[];
			foreach($_arrWhereClouse as $_column=>$_arrValues){

				$_arrInValues[]	=	$_column." IN(".implode(", ", array_map(function($_val){return sprintf("'%s'", $_val);}, $_arrValues)).") ";

			}

			$this->_getWhereClauseParam	=	' WHERE '.implode($_arrInValues, ' OR ');
		}
		return $this;
	}

	/*
	@description: setup where clause in @array
	*/
	public function __setArrWhereClauseUsingIN(array $_arrWhereClouse, string $_gate='OR')
	{
			$_arrWhereClouse	=	$this->__arrayFilter($_arrWhereClouse);

		if(is_array($_arrWhereClouse) && !empty($_arrWhereClouse)){
				$_arrInValues	=	[];
			foreach($_arrWhereClouse as $_column=>$_arrValues){

				$_arrInValues[]	=	$_column." IN(".implode(", ", array_map(function($_val){return sprintf("'%s'", $_val);}, $_arrValues)).") ";

			}
			$this->_getWhereClauseParam	=	' WHERE '.implode($_arrInValues, ' '.$_gate. ' ');
		}
		return $this;
	}

	/*
	@description: SQL BETWEEN Operator
	*/
	public function __setBetweenWhereClause(array $_arrBetweenData)
	{
			$_arrBetweenData		=	$this->__arrayFilter($_arrBetweenData);
		if(!empty($_arrBetweenData)){
				$_arrBetweenValues		=	[];
			foreach($_arrBetweenData as $_column=>$_betweenData){
				$_arrBetweenValues[] 	=	'('.$_column.' BETWEEN '.implode($_betweenData, ' AND ').')';
			}
			$this->_getWhereClauseParam	=	' WHERE '.implode($_arrBetweenValues, ' AND ');
		}
		return $this;
	}

	/*
	@description: setup limit and offset @array, @string
	*/
	public function __setBunchWhereClause(array $_arrWhereClouse, string $_bunchSeperator)
	{
		return $this;
	}

	/*
	@description: setup limit and offset @int
	*/
	public function __setLimitOffset(int $_limit, int $_offset=0)
	{
		if($_limit > 0 && $_offset >= 0 && $this->_orderBy != ""){
			//	$this->_limitParams		=	($this->_databaseDriver == 'mysql')? ' limit :limit':' ROWS FETCH NEXT :limit ROWS ONLY ';
			//$this->_offsetParams		=	' offset :offset';
			$this->_arrLimitOffsetData	=	['limit'	=>	$_limit, 'offset'	=>	$_offset];
		}
		return $this;
	}

	/*
	@description: setup tables joining @string, @array
	*/
	public function __setJoin(string $_joinTable, string $_joinType, array $_arrJoinCondition)
	{
			$_arrJoinCondition		=	$this->__arrayFilter($_arrJoinCondition);
		if($_joinTable != "" && $_joinType != '' && !empty($_arrJoinCondition)){
			$this->_tablesBinding	.=	' '.$_joinType.' join '.$_joinTable.' on('.implode($_arrJoinCondition, ' AND ').') ';
		}
		return $this;
	}

	/*
	@description: create records @int
	*/
	public function __createRecords():int
	{

		try{
			if($this->_databaseDriver == 'mysql'){
				$_sqlStmt 	= 	'INSERT INTO '.$this->_tableName.' SET '.implode($this->_arrSetColumns, ', ');
			}else{

				$_sqlStmt 	= 	'INSERT INTO '.$this->_tableName.' ('. implode(array_keys($this->_arrSetColumns), ', ') .') values('. implode(preg_filter('/^/', ':', array_keys($this->_arrSetColumns)), ', ') .')';
			}

			if($this->_displayAllQuries === true){
				$this->__printQueries($_sqlStmt);
			}
			$_result 	= 	$this->_db->prepare($_sqlStmt);
			$_status 	= 	$_result->execute($this->_arrSetRowData);
			if ($_status) {
				return $this->_db->lastInsertId();
			}else{
				return 0;
			}
		}catch(\Exception $_e){
			$this->__queryErrorDebug($_sqlStmt, $_e);
		}finally{
			//$result->closeCursor();
		}
	}

	/*
	@description: select records in @array
	*/
	public function __readRecords():bool
	{
		try{
			if($this->_databaseDriver == 'mysql'){
				$_sqlStmt 	= 	'SELECT '.$this->_selectCoumns.' FROM '.$this->_tableName . $this->_tablesBinding . $this->_getWhereClauseParam . $this->_groupBy . $this->_having . $this->_orderBy  . $this->_limitParams . $this->_offsetParams ;
			}else{
				/* set condition according to MS-SQL */
				$_sqlStmt 	= 	'SELECT '.$this->_selectCoumns.' FROM '.$this->_tableName . $this->_tablesBinding . $this->_getWhereClauseParam . $this->_groupBy . $this->_having . $this->_orderBy . $this->_offsetParams. $this->_limitParams;
			}

			if($this->_displayAllQuries === true){
				$this->__printQueries($_sqlStmt);
			}

			$_result 	= 	$this->_db->prepare($_sqlStmt);
			if(!empty($this->__arrayFilter($this->_arrLimitOffsetData))){
				$_result->bindParam(':limit', $this->_arrLimitOffsetData['limit'], \PDO::PARAM_INT);
				$_result->bindParam(':offset', $this->_arrLimitOffsetData['offset'], \PDO::PARAM_INT);
			}

			if(!empty($this->__arrayFilter($this->_arrSetRowData))){
				$_status 	= 	$_result->execute($this->_arrSetRowData);
			}else{
				$_status 	= 	$_result->execute();
			}

			$this->_arrReadData	=	$_result->fetchAll(\PDO::FETCH_ASSOC);
			if(!empty($_result->fetchAll(\PDO::FETCH_ASSOC))){
				return true;
			}else{
				return false;
			}
		}catch(\Exception $_e){
			$this->__queryErrorDebug($_sqlStmt, $_e);
		}finally{
			$_result->closeCursor();
		}
		/* this will free up the memory */
		//$result->closeCursor();
	}

	/*
	@description: update records @boolean
	*/
	public function __updateRecords():bool
	{
		try{
			$_sqlStmt	= 	'UPDATE '.$this->_tableName.' SET ' . implode($this->_arrSetColumns, ', ') . $this->_getWhereClauseParam;
			if($this->_displayAllQuries === true){
				$this->__printQueries($_sqlStmt);
			}
			$_result	= 	$this->_db->prepare($_sqlStmt);
			$_status 	= 	$_result->execute($this->_arrSetRowData);
			if($_result->rowCount() > 0){
				return true;
			}else{
				return false;
			}

		}catch(\Exception $_e){

			$this->__queryErrorDebug($_sqlStmt, $_e);

		}finally{
			//$_result->closeCursor();
		}
	}

	/*
	@description: delete records @boolean
	*/
	public function __deleteRecords():bool
	{
		try{
			$_sqlStmt	= 	'DELETE FROM '.$this->_tableName . $this->_getWhereClauseParam;
			if($this->_displayAllQuries === true){
				$this->__printQueries($_sqlStmt);
			}
			$_result	= 	$this->_db->prepare($_sqlStmt);
			$_status 	= 	$_result->execute($this->_arrSetRowData);
			if($_result->rowCount() > 0){
				return true;
			}else{
				return false;
			}
		}catch(\Exception $_e){
			$this->__queryErrorDebug($_sqlStmt, $_e);
			/* log query errors in a file according to month folder with per day */
		}finally{
			//$result->closeCursor();
		}
	}

	/*
	@description: custom query if required in @string
	*/
	public function __callCustomQuery(string $_sqlStmt, string $command="select")
	{
		try{
			if($_sqlStmt != ""){
				if($this->_displayAllQuries === true){
					$this->__printQueries($_sqlStmt);
				}

				$result	 	= 	$this->_db->prepare($_sqlStmt);
				$status 	= 	$result->execute();
				if($command="select"){
					return $result;
				} else {
					return true;
				}	
			}
		}catch(\Exception $_e){
			$this->__queryErrorDebug($_sqlStmt, $_e);
		}finally{
			//$result->closeCursor();
		}
	}

	public function __queryErrorDebug(string $_sqlStmt, object $_e)
	{
		//Something to write to txt log
		$_dbLog	= 	"USER	:	".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a"). PHP_EOL .
					"URL	: 	".$_SERVER['REQUEST_URI']. PHP_EOL .
					"User	: 	".json_encode($_SESSION). PHP_EOL .
					"Error	: 	". $_e->getMessage() . PHP_EOL .
					"File  	: 	".$_e->getFile() . PHP_EOL .
					"Line  	: 	".$_e->getLine() . PHP_EOL .
					"-------------------------".PHP_EOL;
		$_logFile	=	'logs/database_logs/'.date('F_Y');

		chmod("logs/database_logs/", 0777);

		//Save string to log, use FILE_APPEND to append.
		//file_put_contents('./logs/log_'.date('F_Y').'/log_'.date("j.n.Y").'.log', $_dbLog, FILE_APPEND);
		if (!file_exists($_logFile)) {
			mkdir($_logFile, 0777, true);
		}

		file_put_contents($_logFile.'/log_'.date("j.n.Y").'.log', $_dbLog, FILE_APPEND);

		if($this->_queryDebugingMode === true){
			echo '
				<h3 style="text-align: center; font-size: 20px; text-transform: uppercase;">Error Report</h3>

				<table style="background-color:#e1e1e1;margin: 0 auto;width: 70%;font-size: 13px;font-family: arial;">
					<tbody><tr>
						<td style="border: 1px solid #000;padding: 10px;" width="20%"><b>Reqeusted Query</b></td>
						<td style="border: 1px solid #000;padding: 10px;background-color:yellow">'.$_sqlStmt.'</td>
					</tr>
					<tr>
						<td style="border: 1px solid #000;padding: 10px;"  width="20%"><b>Passed Row Data</b></td>
						<td style="border: 1px solid #000;padding: 10px; background-color:#82c482;">'.var_export($this->_arrSetRowData, true).'</td>
					</tr>
					<tr>
						<td style="border: 1px solid #000;padding: 10px;"  width="20%"><b>Reqeusted Query Error</b></td>
						<td style="border: 1px solid #000;padding: 10px; background-color:#cf7272;" >'.$_e->getMessage().'</td>
					</tr>
					</tbody>
				</table>';
			exit;
		}else{
			if(!in_array($this->_serverName, array('10.16.70.70', 'localhost'))){
				header('location: '. $this->_basePath.'/error/404');
				exit;
			}
		}
	}

	/**
	 * A custom function that automatically constructs a multi insert statement.
	 *
	 * @param string $tableName Name of the table we are inserting into.
	 * @param array $data An "array of arrays" containing our row data.
	 * @param PDO $pdoObject Our PDO object.
	 * @return boolean TRUE on success. FALSE on failure.
	*/
	function __pdoMultiInsert(string $tableName, array $data)
	{
	    //Will contain SQL snippets.
	    $rowsSQL = array();

	    //Will contain the values that we need to bind.
	    $toBind = array();

	    //Get a list of column names to use in the SQL statement.
	    $columnNames = array_keys($data[0]);

	    //Loop through our $data array.
	    foreach($data as $arrayIndex => $row){
	        $params = array();
	        foreach($row as $columnName => $columnValue){
	            $param = ":" . $columnName . $arrayIndex;
	            $params[] = $param;
	            $toBind[$param] = $columnValue;
	        }
	        $rowsSQL[] = "(" . implode(", ", $params) . ")";
	    }

	    //Construct our SQL statement
	    $sql = "INSERT INTO ".$tableName." (" . implode(", ", $columnNames) . ") VALUES " . implode(", ", $rowsSQL);

	    //Prepare our PDO statement.
	    $pdoStatement = $this->_db->prepare($sql);

	    //Bind our values.
	    foreach($toBind as $param => $val){
	        $pdoStatement->bindValue($param, $val);
	    }

	    //Execute our statement (i.e. insert the data).
	    return $pdoStatement->execute();
	}
	
	/*
	@description: close db connection after process
	*/
	public function __destruct()
	{
		$this->_db = null;
	}
	
	public function __printQueries($_sqlStmt):void
	{
		echo '<div style="display:none" class="query_display">'.$_sqlStmt.'</div>';
	}


}
