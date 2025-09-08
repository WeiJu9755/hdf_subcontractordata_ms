<?php

//error_reporting(E_ALL); 
//ini_set('display_errors', '1');

session_start();

$memberID = $_SESSION['memberID'];
$powerkey = $_SESSION['powerkey'];


require_once '/website/os/Mobile-Detect-2.8.34/Mobile_Detect.php';
$detect = new Mobile_Detect;

if (!($detect->isMobile() && !$detect->isTablet())) {
	$isMobile = "0";
} else {
	$isMobile = "1";
}

@include_once("/website/class/".$site_db."_info_class.php");

/* 使用xajax */
@include_once '/website/xajax/xajax_core/xajax.inc.php';
$xajax = new xajax();

$xajax->registerFunction("DeleteRow");
function DeleteRow($auto_seq){

	$objResponse = new xajaxResponse();
	
	$mDB = "";
	$mDB = new MywebDB();

	//刪除主資料
	$Qry="delete from CaseManagement where auto_seq = '$auto_seq'";
	$mDB->query($Qry);
	
	$mDB->remove();
	
    $objResponse->script("oTable = $('#db_table').dataTable();oTable.fnDraw(false)");
	$objResponse->script("autoclose('提示', '資料已刪除！', 1500);");

	return $objResponse;
	
}

$xajax->registerFunction("confirm");
function confirm($auto_seq,$check,$memberID){

	$objResponse = new xajaxResponse();

	$mDB = "";
	$mDB = new MywebDB();
	$Qry = "update CaseManagement set 
			confirm7 = '$check' 
			,makeby7 = '$memberID'
			,last_modify7 = now()
			where auto_seq = '$auto_seq'";
	$mDB->query($Qry);
	$mDB->remove();
	
    $objResponse->script("oTable = $('#db_table').dataTable();oTable.fnDraw(false)");

	return $objResponse;
	
}

$xajax->registerFunction("returnValue");
function returnValue($auto_seq){
	$objResponse = new xajaxResponse();

	$mDB = "";
	$mDB = new MywebDB();

	$mDB2 = "";
	$mDB2 = new MywebDB();
	
	$Qry="SELECT * FROM CaseManagement WHERE auto_seq = '$auto_seq'";
	$mDB->query($Qry);
	if ($mDB->rowCount() > 0) {
		while ($row=$mDB->fetchRow(2)) {
			$subcontractor_id1 = $row['subcontractor_id1'];
			$subcontractor_id2 = $row['subcontractor_id2'];
			$subcontractor_id3 = $row['subcontractor_id3'];
			$subcontractor_id4 = $row['subcontractor_id4'];

			$Qry2="SELECT subcontractor_name FROM subcontractor WHERE subcontractor_id = '$subcontractor_id1'";
			$mDB2->query($Qry2);
			if ($mDB2->rowCount() > 0) {
				$row2=$mDB2->fetchRow(2);
				$subcontractor_id1 = $row2['subcontractor_name'];
			}
			$Qry2="SELECT subcontractor_name FROM subcontractor WHERE subcontractor_id = '$subcontractor_id2'";
			$mDB2->query($Qry2);
			if ($mDB2->rowCount() > 0) {
				$row2=$mDB2->fetchRow(2);
				$subcontractor_id2 = $row2['subcontractor_name'];
			}
			$Qry2="SELECT subcontractor_name FROM subcontractor WHERE subcontractor_id = '$subcontractor_id3'";
			$mDB2->query($Qry2);
			if ($mDB2->rowCount() > 0) {
				$row2=$mDB2->fetchRow(2);
				$subcontractor_id3 = $row2['subcontractor_name'];
			}
			$Qry2="SELECT subcontractor_name FROM subcontractor WHERE subcontractor_id = '$subcontractor_id4'";
			$mDB2->query($Qry2);
			if ($mDB2->rowCount() > 0) {
				$row2=$mDB2->fetchRow(2);
				$subcontractor_id4 = $row2['subcontractor_name'];
			}


		}
	}
	
	$mDB2->remove();
	$mDB->remove();
	
	//$show_file_size_total = "<span style=\"white-space: pre;\">(".byteConvert($file_size_total).")</span>";
	
	$objResponse->assign("subcontractor_id1".$auto_seq,"innerHTML",$subcontractor_id1);	
	$objResponse->assign("subcontractor_id2".$auto_seq,"innerHTML",$subcontractor_id2);	
	$objResponse->assign("subcontractor_id3".$auto_seq,"innerHTML",$subcontractor_id3);	
	$objResponse->assign("subcontractor_id4".$auto_seq,"innerHTML",$subcontractor_id4);	
	
	
    return $objResponse;
	
}

$xajax->processRequest();


$fm = $_GET['fm'];
//$pjt = $_GET['pjt'];
//$project_id = $_GET['project_id'];
//$auth_id = $_GET['auth_id'];

$project_id = "202412090001";
$auth_id = "DS003";
if (isset($_GET['pjt']))
	$pjt = $_GET['pjt'];
else
	$pjt = "下包資料";

$tb = "CaseManagement";

$m_t = urlencode($_GET['pjt']);

$mess_title = $pjt;


$today = date("Y-m-d");

$dataTable_de = getDataTable_de();
$Prompt = getlang("提示訊息");
$Confirm = getlang("確認");
$Cancel = getlang("取消");

$pubweburl = "//".$domainname;



//網頁標題
$page_title = $pjt;
$page_description = trim(strip_tags($pjt));
$page_description = utf8_substr($page_description,0,1024);
$page_keywords = $pjt;

//載入上方索引列模組
@include $m_location."/sub_modal/base/project_index.php";


$m_pjt = urlencode($_GET['pjt']);

$mk = $_GET['mk'];
$start_date = $_GET['start_date'];
$end_date = $_GET['end_date'];


$today = date("Y-m-d");


$pubweburl = "//".$domainname;

//載入功能選單模組
@include $m_location."/sub_modal/base/project_menu.php";

$fellow_count = 0;
//取得指定管理人數
$pjmyfellow_row = getkeyvalue2($site_db."_info","pjmyfellow","web_id = '$web_id' and project_id = '$project_id' and auth_id = '$auth_id' and pro_id = 'subcontractordata'","count(*) as fellow_count");
$fellow_count =$pjmyfellow_row['fellow_count'];
if ($fellow_count == 0)
	$fellow_count = "";

/*
$warning_count = 0;
//取得指定管理人數(警訊通知對象)
$pjmyfellow_row = getkeyvalue2($site_db."_info","pjmyfellow","web_id = '$web_id' and project_id = '$project_id' and auth_id = '$auth_id' and pro_id = 'alertlist'","count(*) as warning_count");
$warning_count =$pjmyfellow_row['warning_count'];
if ($warning_count == 0)
	$warning_count = "";
*/

$pjItemManager = false;
//檢查是否為指定管理人
$pjmyfellow_row = getkeyvalue2($site_db."_info","pjmyfellow","web_id = '$web_id' and project_id = '$project_id' and auth_id = '$auth_id' and pro_id = 'subcontractordata' and member_no = '$memberID'","count(*) as enable_count");
$enable_count =$pjmyfellow_row['enable_count'];
if ($enable_count > 0)
	$pjItemManager = true;


//設定權限
$cando = "N";
if (($powerkey=="A") || ($super_admin=="Y") || ($pjItemManager == true)) {
	$cando = "Y";
}


//取得使用者員工身份
$member_picture = getmemberpict160($memberID);

$member_row = getkeyvalue2("memberinfo","member","member_no = '$memberID'","member_name");
$member_name = $member_row['member_name'];

$employee_row = getkeyvalue2($site_db."_info","employee","member_no = '$memberID'","count(*) as manager_count,employee_name,employee_type,team_id");
$manager_count =$employee_row['manager_count'];
$team_id = $employee_row['team_id'];
if ($manager_count > 0) {
	$employee_name = $employee_row['employee_name'];
	$employee_type = $employee_row['employee_type'];

	$team_row = getkeyvalue2($site_db."_info","team","team_id = '$team_id'","team_name");
	$team_name = $team_row['team_name'];
} else {
	$employee_name = $member_name;
	$team_name = "未在員工名單";
}


$member_logo=<<<EOT
<div class="mytable bg-white m-auto rounded">
	<div class="myrow">
		<div class="mycell" style="text-align:center;width:73px;padding: 5px 0;">
			<img src="$member_picture" height="75" class="rounded">
		</div>
		<div class="mycell text-start p-2 vmiddle" style="width:107px;">
			<div class="size14 blue02 weight mb-1 text-nowrap">$employee_name</div>
			<div class="size12 weight text-nowrap">$team_name</div>
			<div class="size12 weight text-nowrap">$employee_type</div>
		</div>
	</div>
</div>
EOT;


$show_disabled = "";
$show_disabled_warning = "";
/*
//if ((empty($team_id)) || ((($super_admin=="Y") && ($admin_readonly == "Y")) || (($super_advanced=="Y") && ($advanced_readonly == "Y")))) {
if (((($super_admin=="Y") && ($admin_readonly == "Y")) || (($super_advanced=="Y") && ($advanced_readonly == "Y")))) {
	if ($pjItemManager <> "Y") {
		$show_disabled = "disabled";
		$show_disabled_warning = "<div class=\"size12 red weight text-center p-2\">此區為管理人專區，非經授權請勿進行任何處理</div>";
	}
}
*/

//if ($cando == "Y") {
	if (($super_admin == "Y") && ($admin_readonly == "Y")) {
		$show_disabled = "disabled";
		$show_disabled_warning = "<div class=\"size12 red weight text-center p-2\">此區為管理人專區，非經授權請勿進行任何處理</div>";
	}
//}


$show_admin_list = "";


if ($cando == "Y") {

	$show_modify_btn = "";
	//$show_ConfirmSending_btn = "";

//	if ($fm == "case") {

		if (($powerkey == "A") || (($super_admin=="Y") && ($admin_readonly <> "Y"))) {
$show_admin_list=<<<EOT
<div class="text-center">
	<div class="btn-group me-2 mb-2" role="group">
		<a role="button" class="btn btn-light" href="javascript:void(0);" onclick="openfancybox_edit('/index.php?ch=fellowlist&project_id=$project_id&auth_id=$auth_id&pro_id=subcontractordata&t=指定管理人&fm=base',850,'96%',true);" title="指定管理人"><i class="bi bi-shield-fill-check size14 red inline me-2 vmiddle"></i><div class="inline size12 me-2">指定管理人</div><div class="inline red weight vmiddle">$fellow_count</div></a>
		<!--
		<a role="button" class="btn btn-light" href="javascript:void(0);" onclick="openfancybox_edit('/index.php?ch=fellowlist&project_id=$project_id&auth_id=$auth_id&pro_id=alertlist&t=警訊通知對象&fm=base',850,'96%',true);" title="警訊通知對象"><i class="bi bi-bell-fill size14 red inline me-2 vmiddle"></i><div class="inline size12 me-2">警訊通知對象</div><div class="inline red weight vmiddle">$warning_count</div></a>
		-->
	</div>
</div>
EOT;
		}

$show_modify_btn=<<<EOT
<div class="text-center my-2">
	<div class="btn-group me-2 mb-2" role="group">
		<button type="button" class="btn btn-success text-nowrap" onclick="myDraw();"><i class="bi bi-arrow-repeat"></i>&nbsp;重整</button>
		<button type="button" class="btn btn-warning text-nowrap" onclick="add_shortcuts('$site_db','$web_id','$templates','$project_id','$auth_id','$pjcaption','$i_caption','$fm','$memberID');"><i class="bi bi-lightning-fill red"></i>&nbsp;加入至快捷列</button>
	</div>
</div>
$show_admin_list
EOT;



$list_view=<<<EOT
<div class="w-100 m-auto p-1 mb-5 bg-white">
	<div style="width:auto;padding: 5px;">
		<div class="inline float-start me-1 mb-2">$left_menu</div>
		<a role="button" class="btn btn-light px-2 py-1 float-start inline me-3 mb-2" href="javascript:void(0);" onClick="parent.history.back();"><i class="bi bi-chevron-left"></i>&nbsp;回上頁</a>
		<a role="button" class="btn btn-light p-1" href="/">回首頁</a>$mess_title
	</div>
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-2 col-sm-12 col-md-12 p-1 d-flex flex-column justify-content-center align-items-center">
				$member_logo
			</div> 
			<div class="col-lg-8 col-sm-12 col-md-12 p-1">
				<div class="size20 pt-1 text-center">$pjt</div>
				$show_modify_btn
				$show_disabled_warning
			</div> 
			<div class="col-lg-2 col-sm-12 col-md-12">
			</div> 
		</div>
	</div>
	$show_ConfirmSending_btn
	<table class="table table-bordered border-dark w-100" id="db_table" style="min-width:1200px;">
		<thead class="table-light border-dark">
			<tr style="border-bottom: 1px solid #000;">
				<th class="text-center text-nowrap vmiddle" style="width:3%;padding: 10px;background-color: #CBF3FC;">狀態(1)</th>
				<th class="text-center text-nowrap vmiddle" style="width:3%;padding: 10px;background-color: #CBF3FC;">狀態(2)</th>
				<th class="text-center text-nowrap vmiddle" style="width:3%;padding: 10px;background-color: #CBF3FC;">區域</th>
				<th class="text-center text-nowrap vmiddle" style="width:3%;padding: 10px;background-color: #CBF3FC;">案件編號</th>
				<th class="text-center text-nowrap vmiddle" style="width:10%;padding: 10px;background-color: #CBF3FC;">工程名稱</th>
				<th class="text-center text-nowrap vmiddle" style="width:7%;padding: 10px;background-color: #CBF3FC;">下包發包進度</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">下包代工1</th>
				<th class="text-center text-nowrap vmiddle" style="width:4%;padding: 10px;background-color: #CBF3FC;">施作樓層1</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">合約總價1<br>(含稅)</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">下包代工2</th>
				<th class="text-center text-nowrap vmiddle" style="width:4%;padding: 10px;background-color: #CBF3FC;">施作樓層2</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">合約總價2<br>(含稅)</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">下包代工3</th>
				<th class="text-center text-nowrap vmiddle" style="width:4%;padding: 10px;background-color: #CBF3FC;">施作樓層3</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">合約總價3<br>(含稅)</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">下包代工4</th>
				<th class="text-center text-nowrap vmiddle" style="width:4%;padding: 10px;background-color: #CBF3FC;">施作樓層4</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">合約總價4<br>(含稅)</th>
				<th class="text-center text-nowrap vmiddle" style="width:3%;padding: 10px;background-color: #CBF3FC;">處理</th>
				<th class="text-center text-nowrap vmiddle" style="width:4%;padding: 10px;background-color: #CBF3FC;">最後修改</th>
			</tr>
		</thead>
		<tbody class="table-group-divider">
			<tr>
				<td colspan="20" class="dataTables_empty">資料載入中...</td>
			</tr>
		</tbody>
	</table>
</div>
EOT;



$scroll = true;
if (!($detect->isMobile() && !$detect->isTablet())) {
	$scroll = false;
}
	
	
$show_view=<<<EOT

<style type="text/css">
#db_table {
	width: 100% !Important;
	margin: 5px 0 0 0 !Important;
}

</style>

$list_view

<script type="text/javascript" charset="utf-8">
	var oTable;
	$(document).ready(function() {
		$('#db_table').dataTable( {
			"processing": true,
			"serverSide": true,
			"responsive":  {
				details: true
			},//RWD響應式
			"scrollX": '$scroll',
			/*"scrollY": 600,*/
			"paging": true,
			"pageLength": 50,
			"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
			"pagingType": "full_numbers",  //分页样式： simple,simple_numbers,full,full_numbers
			"searching": true,  //禁用原生搜索
			"ordering": false,
			"ajaxSource": "/smarty/templates/$site_db/$templates/sub_modal/project/func02/subcontractordata_ms/server_subcontractordata.php?site_db=$site_db&fm=$fm",
			"language": {
						"sUrl": "$dataTable_de"
						/*"sUrl": '//cdn.datatables.net/plug-ins/1.12.1/i18n/zh-HANT.json'*/
					},
			"fixedHeader": true,
			"fixedColumns": {
        		left: 1,
    		},
			"fnRowCallback": function( nRow, aData, iDisplayIndex ) { 

				//狀態(1)
				var status1 = "";
				if (aData[0] != null && aData[0] != "")
					status1 = aData[0];

				$('td:eq(0)', nRow).html( '<div class="d-flex justify-content-center align-items-center text-center size12 text-nowrap" style="height:auto;min-height:32px;">'+status1+'</div>' );

				//狀態(2)
				var status2 = "";
				if (aData[1] != null && aData[1] != "")
					status2 = aData[1];

				$('td:eq(1)', nRow).html( '<div class="d-flex justify-content-center align-items-center text-center size12 text-nowrap" style="height:auto;min-height:32px;">'+status2+'</div>' );

				//區域
				var region = "";
				if (aData[2] != null && aData[2] != "")
					region = aData[2];

				$('td:eq(2)', nRow).html( '<div class="d-flex justify-content-center align-items-center text-center size12 text-nowrap" style="height:auto;min-height:32px;">'+region+'</div>' );

				//案件編號
				var case_id = "";
				if (aData[3] != null && aData[3] != "")
					case_id = aData[3];

				$('td:eq(3)', nRow).html( '<div class="d-flex justify-content-center align-items-center text-center size12 weight text-nowrap" style="height:auto;min-height:32px;">'+case_id+'</div>' );

				//工程名稱
				var construction_id = "";
				if (aData[4] != null && aData[4] != "")
					construction_id = aData[4];

				$('td:eq(4)', nRow).html( '<div class="d-flex justify-content-center align-items-center size12 text-center" style="height:auto;min-height:32px;">'+construction_id+'</div>' );


				var subcontractor_name1 = '<div id="subcontractor_id1'+aData[11]+'"></div>';
				var subcontractor_name2 = '<div id="subcontractor_id2'+aData[11]+'"></div>';
				var subcontractor_name3 = '<div id="subcontractor_id3'+aData[11]+'"></div>';
				var subcontractor_name4 = '<div id="subcontractor_id4'+aData[11]+'"></div>';
				xajax_returnValue(aData[11]);


				//下包發包進度
				var subcontracting_progress = "";
				if (aData[23] != null && aData[23] != "")
					subcontracting_progress = aData[23];

				$('td:eq(5)', nRow).html( '<div class="d-flex justify-content-center align-items-center text-center size12" style="height:auto;min-height:32px;">'+subcontracting_progress+'</div>' );


				//下包代工1
				var subcontractor_id1 = "";
				if (aData[6] != null && aData[6] != "")
					subcontractor_id1 = aData[6];

				$('td:eq(6)', nRow).html( '<div class="d-flex justify-content-center align-items-center text-center size12" style="height:auto;min-height:32px;">'+subcontractor_name1+'</div>' );

				//施作樓層1
				var construction_floor1 = "";
				if (aData[7] != null && aData[7] != "")
					construction_floor1 = aData[7];

				$('td:eq(7)', nRow).html( '<div class="d-flex justify-content-center align-items-center text-center size12" style="height:auto;min-height:32px;">'+construction_floor1+'</div>' );

				//合約總價1(含稅)
				var total_contract_amt1 = "";
				if (aData[8] != null && aData[8] != "")
					total_contract_amt1 = number_format(aData[8]);

				$('td:eq(8)', nRow).html( '<div class="d-flex justify-content-center align-items-center text-center size12 text-nowrap" style="height:auto;min-height:32px;">'+total_contract_amt1+'</div>' );

				//下包代工2
				var subcontractor_id2 = "";
				if (aData[14] != null && aData[14] != "")
					subcontractor_id2 = aData[14];

				$('td:eq(9)', nRow).html( '<div class="d-flex justify-content-center align-items-center text-center size12" style="height:auto;min-height:32px;">'+subcontractor_name2+'</div>' );

				//施作樓層2
				var construction_floor2 = "";
				if (aData[15] != null && aData[15] != "")
					construction_floor2 = aData[15];

				$('td:eq(10)', nRow).html( '<div class="d-flex justify-content-center align-items-center text-center size12" style="height:auto;min-height:32px;">'+construction_floor2+'</div>' );

				//合約總價2(含稅)
				var total_contract_amt2 = "";
				if (aData[16] != null && aData[16] != "")
					total_contract_amt2 = number_format(aData[16]);

				$('td:eq(11)', nRow).html( '<div class="d-flex justify-content-center align-items-center text-center size12 text-nowrap" style="height:auto;min-height:32px;">'+total_contract_amt2+'</div>' );

				//下包代工3
				var subcontractor_id3 = "";
				if (aData[17] != null && aData[17] != "")
					subcontractor_id3 = aData[17];

				$('td:eq(12)', nRow).html( '<div class="d-flex justify-content-center align-items-center text-center size12" style="height:auto;min-height:32px;">'+subcontractor_name3+'</div>' );

				//施作樓層3
				var construction_floor3 = "";
				if (aData[18] != null && aData[18] != "")
					construction_floor3 = aData[18];

				$('td:eq(13)', nRow).html( '<div class="d-flex justify-content-center align-items-center text-center size12" style="height:auto;min-height:32px;">'+construction_floor3+'</div>' );

				//合約總價3(含稅)
				var total_contract_amt3 = "";
				if (aData[19] != null && aData[19] != "")
					total_contract_amt3 = number_format(aData[19]);

				$('td:eq(14)', nRow).html( '<div class="d-flex justify-content-center align-items-center text-center size12 text-nowrap" style="height:auto;min-height:32px;">'+total_contract_amt3+'</div>' );

				//下包代工4
				var subcontractor_id4 = "";
				if (aData[20] != null && aData[20] != "")
					subcontractor_id4 = aData[20];

				$('td:eq(15)', nRow).html( '<div class="d-flex justify-content-center align-items-center text-center size12" style="height:auto;min-height:32px;">'+subcontractor_name4+'</div>' );

				//施作樓層4
				var construction_floor4 = "";
				if (aData[21] != null && aData[21] != "")
					construction_floor4 = aData[21];

				$('td:eq(16)', nRow).html( '<div class="d-flex justify-content-center align-items-center text-center size12" style="height:auto;min-height:32px;">'+construction_floor4+'</div>' );

				//合約總價4(含稅)
				var total_contract_amt4 = "";
				if (aData[22] != null && aData[22] != "")
					total_contract_amt4 = number_format(aData[22]);

				$('td:eq(17)', nRow).html( '<div class="d-flex justify-content-center align-items-center text-center size12 text-nowrap" style="height:auto;min-height:32px;">'+total_contract_amt4+'</div>' );

				/*
				//確認
				if ( aData[13] == "Y" ) {
					var mcheck = "xajax_confirm("+aData[11]+",'N','$memberID');";
					var img_check = '<a href="javascript:void(0);" onclick="'+mcheck+'"><i class="bi bi-check-circle size16 green weight"></i></a>';
				} else {
					var mcheck = "xajax_confirm("+aData[11]+",'Y','$memberID');";
					var img_check = '<a href="javascript:void(0);" onclick="'+mcheck+'"><i class="bi bi-circle size16 gray"></i></a>';
				}
				$('td:eq(17)', nRow).html( '<div class="text-center">'+img_check+'</div>' );
				*/
				var url1 = "openfancybox_edit('/index.php?ch=edit&auto_seq="+aData[11]+"&fm=$fm',800,'96%','');";
				var mdel = "myDel("+aData[11]+");";

				var show_btn = '';
				if (('$powerkey'=="A") || ('$super_admin'=="Y")) {
					show_btn = '<div class="btn-group text-nowrap">'
						+'<button type="button" class="btn btn-light" onclick="'+url1+'" title="修改"><i class="bi bi-pencil-square"></i></button>'
						+'<button type="button" class="btn btn-light" onclick="'+mdel+'" title="刪除"><i class="bi bi-trash"></i></button>'
						+'</div>';
				} else {
					show_btn = '<div class="btn-group text-nowrap">'
						+'<button type="button" class="btn btn-light" onclick="'+url1+'" title="修改"><i class="bi bi-pencil-square"></i></button>'
						+'</div>';
				}

				$('td:eq(18)', nRow).html( '<div class="d-flex justify-content-center align-items-center text-center" style="height:auto;min-height:32px;">'+show_btn+'</div>' );

				//最後修改
				var last_modify7 = "";
				if (aData[10] != null && aData[10] != "")
					last_modify7 = '<div class="text-nowrap">'+moment(aData[10]).format('YYYY-MM-DD HH:mm')+'</div>';
				
				//編輯人員
				var member_name = "";
				if (aData[12] != null && aData[12] != "")
					member_name = '<div class="text-nowrap">'+aData[12]+'</div>';

				$('td:eq(19)', nRow).html( '<div class="text-center" style="height:auto;min-height:32px;">'+last_modify7+member_name+'</div>' );


				return nRow;
			
			}
			
		});
	
		/* Init the table */
		oTable = $('#db_table').dataTable();
		
	} );

var myDel = function(auto_seq) {

	Swal.fire({
	title: "您確定要刪除此筆資料嗎?",
	text: "此項作業會刪除所有與此筆案件記錄有關的資料",
	icon: "question",
	showCancelButton: true,
	confirmButtonColor: "#3085d6",
	cancelButtonColor: "#d33",
	cancelButtonText: "取消",
	confirmButtonText: "刪除"
	}).then((result) => {
		if (result.isConfirmed) {
			xajax_DeleteRow(auto_seq);
		}
	});

};

var myDraw = function(){
	var oTable;
	oTable = $('#db_table').dataTable();
	oTable.fnDraw(false);
}

	
</script>

EOT;

} else {

	$sid = "mbwarning";
	$show_view = mywarning("很抱歉! 目前此功能只開放給本站特定會員，或是您目前的權限無法存取此頁面。");

}

?>