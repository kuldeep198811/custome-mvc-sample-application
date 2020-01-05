<?php
require "Model.php";
/* create record */
$arrData	=	$this	->__setTable('cid_stores_list')
						->__setData(['sgc_group_id'=>1, 'store_name'=>'store_name4', 'store_code'=>'code4'])
						->__createRecords();





						
/* select all rows */
$arrData	=	$this	->__setTable('sgc_companies')
						->__setArrSelectColumns(['sgc_group_id', 'group_name'])
						->__readRecords();
echo '<pre>'; print_r($arrData); echo '</pre><br />==============<br />';




/* selet rows with limit */
$arrData	=	$this	->__setTable('sgc_companies')
						->__setArrSelectColumns(['sgc_group_id', 'group_name'])
						->__setLimitOffset(2)
						->__readRecords();
echo '<pre>'; print_r($arrData); echo '</pre><br />==============<br />';




/* selet with join table */
$arrData	=	$this	->__setTable('sgc_companies as sc')
						->__setArrSelectColumns(['sc.sgc_group_id', 'sc.group_name'])
						->__setJoin('cid_stores_list as csl', 'left', ['csl.sgc_group_id=sc.sgc_group_id'])
						->__readRecords();
echo '<pre>'; print_r($arrData); echo '</pre><br />==============<br />';
/*
1. a & b & c & d
array(
	'data'=>array(a,b,c,d),
	'condition'=>'AND'
);

2. a || b || c || d
array(
	'data'	=>array(
				array(a),
				array(b),
				array(c),
				array(d)
			),
	'condition'	=>	'OR'
);

3. (a & b) || (c & d)
array(
	'data'	=>array(
				array(a,b),
				array(c,d)
			),
	'condition'	=>	'OR'
);

4. (a || b) && (c & d)
array(
	'data'	=>	array(
					array(a,b),
					array(c,d)
				),
	'condition'=>'OR'
);

5. not like
6. like
7. rlike for regular expression
8. 
*/



/* select records using join table with AND condition */
$arrData	=	$this	->__setTable('sgc_companies as sc')
						->__setArrSelectColumns(['sc.sgc_group_id', 'sc.group_name'])
						->__setJoin('cid_stores_list as csl', 'left', ['csl.sgc_group_id=sc.sgc_group_id'])
						->__setArrWhereClauseUsingAnd(['sc.group_name' => 'HPI'])
						->__readRecords();
echo '<pre>'; print_r($arrData); echo '</pre><br />==============<br />';




/* select records using join table with OR condition */
$arrData	=	$this	->__setTable('sgc_companies as sc')
						->__setArrSelectColumns(['sc.sgc_group_id', 'sc.group_name'])
						->__setJoin('cid_stores_list as csl', 'left', ['csl.sgc_group_id=sc.sgc_group_id'])
						->__setArrWhereClauseUsingOr(['sc.group_name' => ['HPI','CID'], 'sc.sgc_group_id' => ['HPI','CID']])
						->__readRecords();
								

/* select records using IN aggregate */
$arrData	=	$this	->__setTable('cid_stores_list')
						->__setData(['sgc_group_id'=>1, 'store_name'=>'store_name4', 'store_code'=>'code4_update'])
						->__setArrWhereClauseUsingIN(['sc.group_name' => ['HPI','CID'],])
						->__readRecords();
						
								
/* update records */
$arrData	=	$this	->__setTable('cid_stores_list')
						->__setData(['sgc_group_id'=>1, 'store_name'=>'store_name4', 'store_code'=>'code4_update'])
						->__setArrWhereClauseUsingAnd(['sgc_group_id' => 1])
						->__updateRecords();

						
						
/* delete records */
$arrData	=	$this	->__setTable('cid_stores_list')
						->__setArrWhereClauseUsingAnd(['sgc_group_id' => 1])
						->__deleteRecords();
								
/* select record using comparison operator */
$arrData	=	$this	->__setTable('sgc_companies')
						->__setArrSelectColumns(['sgc_group_id', 'group_name'])
						->__setArrComparisonOperator(' > ')
						->__setArrWhereClauseUsingAnd(['sgc_group_id' => 1])
						->__readRecords();
echo '<pre>'; print_r($arrData); echo '</pre><br />==============<br />';

/* this is to handle LIKE and NULL statements

WHERE CustomerName LIKE 'a%'	Finds any values that start with "a"
WHERE CustomerName LIKE '%a'	Finds any values that end with "a"
WHERE CustomerName LIKE '%or%'	Finds any values that have "or" in any position
WHERE CustomerName LIKE '_r%'	Finds any values that have "r" in the second position
WHERE CustomerName LIKE 'a_%_%'	Finds any values that start with "a" and are at least 3 characters in length


 */
$arrData	=	$model	->__setTable('sgc_companies')
						->__setArrSelectColumns(['sgc_group_id', 'group_name'])
						->__setArrComparisonOperator(['%like%'/* , 'IS not NULL' */])
						->__setArrWhereClauseUsingAnd([/* 'sgc_group_id' => 1  , */'master_table' => 'p'])
						->__readRecords();
						
						
$arrData	=	$model	->__setTable('sgc_companies')
						->__setArrSelectColumns(['sgc_group_id', 'group_name', 'master_table'])
						->__setArrComparisonOperator(['like_%_%'/* , 'IS not NULL' */])
						->__setArrWhereClauseUsingAnd([/* 'sgc_group_id' => 1  , */'master_table' => 's'])
						->__readRecords();
							
echo '<pre>'; print_r($arrData); echo '</pre><br />==============<br />';

?>