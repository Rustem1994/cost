<?php

if (!defined('included')){die("Ошибка доступа!");}

$change=$_GET["change"];
$action=$_GET["action"];
if ($change=="typecosts"){
	if ($_GET["typecosts"]==0){
		die("Данные пустые");
	}
	$sql=$this->str_replace_db_query(6, "objects");
	$result=[];
	while ($item=$sql->fetch_assoc())
		$result[]=array("id"=>$item['id'],"name"=>$item['name']);
	echo json_encode($result);
}//typecosts
elseif ($change=="object"){
	if ($_GET["object"]==0){
		die("Данные пустые");
	}
	$object=$_GET["object"];
	$sql=$this->str_replace_db_query(21, $object);
	$result=[];
	
	while ($item=$sql->fetch_assoc())
		$result[]=array("id"=>$item['id'],"name"=>$item['name']);
	echo json_encode($result);
} //if (object)
elseif ($change=="place_ZET_direction"){
	if ($_GET["direction"]==0){
		die("Данные пустые");
	}
	$direction=$_GET["direction"];
	if ($_GET["typecosts"]==0){
		die("Данные пустые");
	}
	$typecosts=$_GET["typecosts"];
	echo json_encode(["place"=>$this->str_replace_db_query_res(22, [$direction,$typecosts])]);
}//place_ZET_direction
elseif ($change=="place_ZET_object"){
	if ($_GET["object"]==0){
		die("Данные пустые");
	}
	$object=$_GET["object"];
	if ($_GET["typecosts"]==0){
		die("Данные пустые");
	}
	$typecosts=$_GET["typecosts"];
	$sql=$this->str_replace_db_query(23, [$object,$typecosts]);
	$result=[];
	while ($item=$sql->fetch_assoc()){
		$result[]=$item["name"]." ".$item["sum"];
	}
	echo json_encode($result);
}//place_ZET_object
elseif ($change=="get_header_object_ZET"){
	if ($_GET["object"]==0){
		die("Данные пустые");
	}
	$object=$_GET["object"];
	if ($_GET["typecosts"]==0){
		die("Данные пустые");
	}
	$typecosts=$_GET["typecosts"];
	echo json_encode(["Наименование направления","Курс","Форма обучения", "Количество обучающихся на бюджетной основе","Стоимость обучения по МОН методики", "Коэффицент П для бюждет", "Количество обучающихся на платной основе", "Стоимость обучения для платной основы", "Коэффицент П для платной основы"]);
}//get_header_object
elseif ($change=="element_object_ZET"){
	if ($_GET["object"]==0){
		die("Данные пустые");
	}
	$object=$_GET["object"];
	if ($_GET["typecosts"]==0){
		die("Данные пустые");
	}
	$typecosts=$_GET["typecosts"];
	$sql=$this->str_replace_db_query(24, [$object,$typecosts]);
	$result=[];
	while ($item=$sql->fetch_assoc()){
		$arr=[];
		foreach ($item AS $value)
			$arr[]=$value;
		$result[]=$arr;
	}
	echo json_encode($result);
}//element_object
elseif ($change=="place_type_cost"){
	if ($_GET["object"]==0){
		die("Данные пустые");
	}
	$object=$_GET["object"];
	if ($_GET["typecosts"]==0){
		die("Данные пустые");
	}
	$typecosts=$_GET["typecosts"];
	echo json_encode(["place"=>$this->str_replace_db_query_res(37, [$object,$typecosts])]);
}//place_type_cost
elseif ($change=="get_header_object"){
	if ($_GET["object"]==0){
		die("Данные пустые");
	}
	$object=$_GET["object"];
	if ($_GET["typecosts"]==0){
		die("Данные пустые");
	}
	$typecosts=$_GET["typecosts"];
	echo json_encode(["Наименование направления","Наименование кафедры","Семестр", "Наименование дисциплины", "ЗЕТ", "Вид контроля","Количество обучающихся", "Средняя стоимость ЗЕТ по подраздлению", "Стоимости дисциплины учебного плана направления подготовки/специальности института/факультета в части расходов на ФОТ ППС и УВП с учетом стоимости ЗЕТ"]);
}//get_header_object
elseif ($change=="element_object"){
	if ($_GET["object"]==0){
		die("Данные пустые");
	}
	$object=$_GET["object"];
	if ($_GET["typecosts"]==0){
		die("Данные пустые");
	}
	$typecosts=$_GET["typecosts"];
	$sql=$this->str_replace_db_query(38, [$object,$typecosts]);
	$result=[];
	while ($item=$sql->fetch_assoc()){
		$arr=[];
		foreach ($item AS $value)
			$arr[]=$value;
		$result[]=$arr;
	}
	echo json_encode($result);
}//element_object
elseif ($change=="search"){
	if (!isset($_GET["search"])){
		die("Данные пустые");
	}
	$search=$_GET["search"];
	if (!isset($_GET["id_table"])){
		die("Данные пустые");
	}
	$id_table=$_GET["id_table"];
	if (!isset($_GET["limit"])){
		die("Данные пустые");
	}
	$limit=$_GET["limit"];
	$this->link="index.php?com=table&action=%action%";
	$sql=$this->str_replace_db_query(2, $id_table);
	$table=$sql->fetch_array();
	$fields_name=explode(";",$table['field_name']);
	$fields_table=explode(";",$table['field_table']);
	$query="";
	if (isset($_GET["filter_table"])){
		$arr=explode(",",$_GET["filter_table"]);
		foreach ($arr AS $value){
			$v=explode("_",$value);
			$query.=" {$fields_name[array_search($v[0],$fields_table)]}={$v[1]} AND ";
		}
	}
	$items=$this->str_replace_db_query($table['query_search'],[$query, $search,"0,{$limit}"]);
	if ($items->num_rows){
		$flag=true;
		$l=$limit+1;
		$c=$this->str_replace_db_query($table['query_search'],[$query, $search,"0,{$l}"]);
		if ($c->num_rows<=$limit)
			$flag=false;
		$result=[];
		$index=1;
		while ($item=$items->fetch_assoc()){
			$arr_val=[];
			foreach ($item AS $value){
				$arr_val[]=$value;
			}
			$arr_val[0]=$index++;
			$arr_val[]=$this->get_link_with_text($this->user_can_edit, "edit&id_table={$id_table}&id_item={$item['id']}",'<img src="img/b_edit.png">');
			$arr_val[]=$this->get_link_with_text($this->user_can_delete,"delete&id_table={$id_table}&id_item={$item['id']}",'<img src="img/b_drop.png">');
			$result[]=$arr_val;
		}// while($item)
		echo json_encode([$result,$flag]);
	}
}//search
elseif ($change=="get_header_search"){
	if (!isset($_GET["id_table"])){
		die("Данные пустые");
	}
	$id_table=$_GET["id_table"];
	$sql=$this->str_replace_db_query(2, $id_table);
	$table=$sql->fetch_array();
	$items=$this->str_replace_db_query($table['query_view'],array($table['table_name'],"0,100"));
	if ($items->num_rows){
		$fields_text=explode(";",$table['field_text']);
		echo json_encode($this->get_header_table($fields_text,$this->user_can_edit,$this->user_can_delete));
	}
}//get_header_object
elseif ($change=="export_excel"){
	header("Content-Type: application/force-download");
	header("Content-Type: application/octet-stream");
	header("Content-Type: application/download");
/*	header('Content-Type: application/vnd.ms-excel; charset=utf-8');
	header("Content-Disposition: attachment;filename=".date("d-m-Y")."-export.xls");
	header("Content-Transfer-Encoding: binary ");*/
	echo '
	   <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	   <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	 <head>
	 <meta http-equiv="content-type" content="text/html; charset=utf-8" />
	 <meta name="author" content="zabey" />
	 <title>Demo</title>
	 </head>
	 <body>
	 </body></html>';
}//
elseif ($change==="C_educ_form"){ // хотим получить список форм обучений по типу затрат
	if (!isset($_GET["type_cost"])){
		die("Данные пустые");
	}
	$type_cost=$_GET["type_cost"];
	
	$sql=$this->str_replace_db_query(51, ["t5.id, t5.name", " AND t1.id_type_cost={$type_cost}", "t5.id"]);
	if ($sql->num_rows){
		$result=[];
		while ($item=$sql->fetch_assoc())
			$result[]=["id"=>$item['id'],"name"=>$item['name']];
		echo json_encode($result);
	}
}
elseif ($change==="C_cost_group"){ // хотим получить список стоимостных групп  по типу затрат и форме обучения 
	if (!isset($_GET["type_cost"])){
		die("Данные пустые");
	}
	$type_cost=$_GET["type_cost"];
	if (!isset($_GET["educ_form"])){
		die("Данные пустые");
	}
	$educ_form=$_GET["educ_form"];
	$sql=$this->str_replace_db_query(51, ["t4.id, t4.name ", " AND t1.id_type_cost={$type_cost}  AND t5.id={$educ_form}", "t4.id"]);
	if ($sql->num_rows){
		$result=[];
		while ($item=$sql->fetch_assoc())
			$result[]=["id"=>$item['id'],"name"=>$item['name']];
		echo json_encode($result);
	}
}
elseif ($change==="C_degree"){ // хотим получить список стоимостных групп  по типу затрат и форме обучения 
	if (!isset($_GET["type_cost"])){
		die("Данные пустые");
	}
	$type_cost=$_GET["type_cost"];
	if (!isset($_GET["educ_form"])){
		die("Данные пустые");
	}
	$educ_form=$_GET["educ_form"];
	if (!isset($_GET["cost_group"])){
		die("Данные пустые");
	}
	$cost_group=$_GET["cost_group"];
	$sql=$this->str_replace_db_query(51, ["t6.id, t6.name", " AND t1.id_type_cost={$type_cost}  AND t5.id={$educ_form} AND t4.id={$cost_group} ", "t6.id"]);
	if ($sql->num_rows){
		$result=[];
		while ($item=$sql->fetch_assoc())
			$result[]=["id"=>$item['id'],"name"=>$item['name']];
		echo json_encode($result);
	}
}
elseif ($change=="factor_PI"){ // изменияем значения 
	$id_type_cost="";
	if (isset($_GET["type_cost"])){
		$id_type_cost=" id_type_cost={$_GET["type_cost"]} ";
	}
	$id_educ_form=" 1 ";
	if (isset($_GET["educ_form"])){
		$id_educ_form=" id_educ_form={$_GET["educ_form"]} ";
	}
	$id_cost_group=" 1 ";
	if (isset($_GET["cost_group"])){
		$id_cost_group=" id_cost_group={$_GET["cost_group"]} ";
	}
	$id_degree=" 1 ";
	if (isset($_GET["degree"])){
		$id_degree=" id_degree={$_GET["degree"]} ";
	}
	if (!isset($_GET["factor_PI_budjet"])){
		die("Данные пустые");
	}
	$factor_PI_budjet=$_GET["factor_PI_budjet"];
	if (!isset($_GET["factor_PI_place"])){
		die("Данные пустые");
	}
	$factor_PI_place=$_GET["factor_PI_place"];
	echo json_encode([$this->str_replace_db_query_empty(52, [$factor_PI_budjet, $factor_PI_place, $id_type_cost, $id_educ_form,$id_cost_group, $id_degree])]);
}
elseif ($change=="view_factor_PI"){ // просмотр
	$id_type_cost=" 1 ";
	if (isset($_GET["type_cost"])){
		$id_type_cost=" id_type_cost={$_GET["type_cost"]} ";
	}
	$id_educ_form=" 1 ";
	if (isset($_GET["educ_form"])){
		$id_educ_form=" id_educ_form={$_GET["educ_form"]} ";
	}
	$id_cost_group=" 1 ";
	if (isset($_GET["cost_group"])){
		$id_cost_group=" id_cost_group={$_GET["cost_group"]} ";
	}
	$id_degree=" 1 ";
	if (isset($_GET["degree"])){
		$id_degree=" t6.id={$_GET["degree"]} ";
	}
	$sql=$this->str_replace_db_query(53, [$id_type_cost, $id_educ_form, $id_cost_group, $id_degree]);
	$table="<table><tr><th>Стоимостная группа</th><th>Тип затрат</th><th>Коэффицент для бюджет</th><th>Коэффицент для платной основы</th><th>Форма обучения</th></tr>";
	while ($item=$sql->fetch_assoc()){
		$table.="<tr>";
		foreach ($item AS $value)
			$table.="<td>{$value}</td>";
		$table.="</tr>";
	}//while
	$table.="</table>";
	echo $table;
}
elseif ($change=="form_add"){
	if ($action==="view"){
		echo "<form href=\"#\" id=\"control_add_factor_PI_cost\">
		<label>Форма добавляения коэффицента</label><br>
		<label for=\"C_add_typecosts\">{$this->get_text("__INPUT_C_1__")}</label><br>
		{$this->make_select_replace_query(6, "sprav_type_costs","C_add_typecosts", -1, $this->get_text("__INPUT_C_2__"))}
		<label for=\"C_add_educ_form\" >{$this->get_text("__INPUT_C_3__")}</label>
		{$this->make_select_replace_query(6, "sprav_educ_forms","C_add_educ_form", -1, $this->get_text("__INPUT_C_4__"))}
		<label for=\"C_add_cost_group\" >{$this->get_text("__INPUT_C_12__")}</label>
		{$this->make_select_replace_query(6, "sprav_cost_groups","C_add_cost_group", -1, $this->get_text("__INPUT_C_11__"))}
		<label for=\"C_add_degree\">{$this->get_text("__INPUT_C_19__")}</label>
		{$this->make_select_replace_query(6, "sprav_degrees","C_add_degree", -1, $this->get_text("__INPUT_C_18__"))}
		<label for=\"C_add_factor_PI_budjet\" >{$this->get_text("__INPUT_C_9__")}</label>
		<input type=\"text\" id=\"C_add_factor_PI_budjet\" name=\"C_add_factor_PI_budjet\"  style=\"width: 10%; display: contents; margin-top: 2%;\" size=\"6\" onchange=\"onChange_C_add_factor_PI()\"><br>
		<label for=\"C_add_factor_PI_place\" >{$this->get_text("__INPUT_C_17__")}</label>
		<input type=\"text\" id=\"C_add_factor_PI_place\" name=\"C_add_factor_PI_place\"  style=\"width: 10%; display: contents; margin-top: 2%;\" size=\"6\" onchange=\"onChange_C_add_factor_PI()\">
		<input type=\"button\" value=\"{$this->get_text("__ADD__")}\" id=\"C_add_add\" name=\"C_add_add\" onclick=\"onClick_C_add_add()\"> 
	</form>";
	}elseif ($action==="add"){
		$id_type_cost=" 1 ";
		$type_cost=0;
		if (isset($_GET["type_cost"])){
			$type_cost=$_GET["type_cost"];
			$id_type_cost=" id_type_cost={$_GET["type_cost"]} ";
		}
		$id_educ_form=" 1 ";
		$educ_form=0;
		if (isset($_GET["educ_form"])){
			$educ_form=$_GET["educ_form"];
			$id_educ_form=" id_educ_form={$_GET["educ_form"]} ";
		}
		$id_cost_group=" 1 ";
		if (isset($_GET["cost_group"])){
			$id_cost_group=" id_cost_group={$_GET["cost_group"]} ";
		}
		$id_degree=" 1 ";
		if (isset($_GET["degree"])){
			$id_degree=" id_degree={$_GET["degree"]} ";
		}
		$factor_PI_budjet=0;
		if (isset($_GET["factor_PI_budjet"])){
			$factor_PI_budjet=$_GET["factor_PI_budjet"];
		}
		$factor_PI_place=0;
		if (isset($_GET["factor_PI_place"])){
			$factor_PI_place=$_GET["factor_PI_place"];
		}
		if ($sql=$this->str_replace_db_query(55, [" `mon_methods` ", " {$id_cost_group} AND {$id_degree} AND id NOT IN (SELECT id_mom_method FROM mon_methods_and_sprav_type_costs WHERE id_educ_form={$id_educ_form} AND id_type_cost={$id_type_cost} ) ","id"])){
			if ($sql->num_rows!==0){
				$count=$sql->num_rows-1;
				$str_query="INSERT INTO `mon_methods_and_sprav_type_costs`(`id_mom_method`, `id_type_cost`, `factor_PI_budjet`, `factor_PI_place`, `id_educ_form`) VALUES";
				for ($i=0; $i<$count; ++$i){
					$item=$sql->fetch_assoc();
					$str_query.="({$item["id"]}, {$type_cost}, {$factor_PI_budjet}, {$factor_PI_place}, {$educ_form}) ,";
				}
				$item=$sql->fetch_assoc();
				$str_query.="({$item["id"]}, {$type_cost}, {$factor_PI_budjet}, {$factor_PI_place}, {$educ_form})";
				echo json_encode([$this->db_query($str_query)]);
			}
		}
	//	echo json_encode([false]);
	}
}
elseif ($change==="C1_educ_form"){ 
	if (!isset($_GET["type_cost"])){
		die("Данные пустые");
	}
	$type_cost=$_GET["type_cost"];
	$sql=$this->str_replace_db_query(57, [" t1.id_type_cost={$type_cost} ", "t3.id"]);
	if ($sql->num_rows){
		$result=[];
		while ($item=$sql->fetch_assoc())
			$result[]=["id"=>$item['id'],"name"=>$item['name']];
		echo json_encode($result);
	}
}
elseif ($change=="C1_view_factor_PI"){ 
	$id_type_cost=" 1 ";
	if (isset($_GET["type_cost"])){
		$id_type_cost=" t1.id_type_cost={$_GET["type_cost"]} ";
	}
	$id_educ_form=" 1 ";
	if (isset($_GET["educ_form"])){
		$id_educ_form=" t1.id_educ_form={$_GET["educ_form"]} ";
	}
	$sql=$this->str_replace_db_query(58, [$id_type_cost, $id_educ_form]);
	$table="<table><tr><th>Форма обучения</th><th>Тип затрат</th><th>Коэффицент для бюджет</th><th>Коэффицент для платной основы</th></tr>";
	while ($item=$sql->fetch_assoc()){
		$table.="<tr>";
		foreach ($item AS $value)
			$table.="<td>{$value}</td>";
		$table.="</tr>";
	}//while
	$table.="</table>";
	echo $table;
}
elseif ($change=="C1_form_add"){
	if ($action=="view"){
		echo "
		<form href=\"#\" id=\"control_factor_PI_cost_educ_form\">
		<label>{$this->get_text("__INPUT_C_15__")}</label><br>
		<label for=\"C1_add_typecosts\">{$this->get_text("__INPUT_C_1__")}</label><br>
		{$this->make_select_replace_query(6, "sprav_type_costs","C1_add_typecosts", -1, $this->get_text("__INPUT_C_2__"))}
		<label for=\"C1_add_educ_form\" >{$this->get_text("__INPUT_C_3__")}</label>
		{$this->make_select_replace_query(6, "sprav_educ_forms","C1_add_educ_form", -1, $this->get_text("__INPUT_C_4__"))}
		<label for=\"C1_add_factor_PI_budjet\" >{$this->get_text("__INPUT_C_9__")}</label>
		<input type=\"text\" id=\"C1_add_factor_PI_budjet\" name=\"C1_add_factor_PI_budjet\"  style=\"width: 10%; display: contents; margin-top: 2%;\" size=\"6\"><br>
		<label for=\"C1_add_factor_PI_place\" >{$this->get_text("__INPUT_C_17__")}</label>
		<input type=\"text\" id=\"C1_add_factor_PI_place\" name=\"C1_add_factor_PI_place\"  style=\"width: 10%; display: contents; margin-top: 2%;\" size=\"6\">
		<input type=\"button\" value=\"{$this->get_text("__ADD__")}\" id=\"C1_add\" name=\"C1_add_add\" onclick=\"onClick_C1_add_add()\"> 
	</form>";
	}elseif ($action=="add"){
		if (!isset($_GET["type_cost"])){
			die("Данные пустые");
		}
		$type_cost=$_GET["type_cost"];
		if (!isset($_GET["educ_form"])){
			die("Данные пустые");
		}
		$educ_form=$_GET["educ_form"];
		$factor_PI_budjet=0;
		if (isset($_GET["factor_PI_budjet"])){
			$factor_PI_budjet=$_GET["factor_PI_budjet"];
		}
		$factor_PI_place=0;
		if (isset($_GET["factor_PI_place"])){
			$factor_PI_place=$_GET["factor_PI_place"];
		}
		if ($sql=$this->str_replace_db_query(55, [" `sprav_type_costs_and_sprav_educ_forms` ", " id_educ_form={$educ_form} AND id_type_cost={$type_cost} ","id"])){
			if ($sql->num_rows===0){
				$str_query="INSERT INTO `sprav_type_costs_and_sprav_educ_forms`( `id_educ_form`, `id_type_cost`, `factor_PI_form_budjet`, `factor_PI_form_place`) VALUES ({$educ_form}, {$type_cost},{$factor_PI_budjet}, {$factor_PI_place})";
				echo json_encode([$this->db_query($str_query)]);
			}
		}
	//	echo json_encode([false]);
	}
}
?>