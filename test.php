<?php
// 取得上包-建商清單，從資料表 subcontractor 取出代號與名稱
$Qry = "SELECT subcontractor_id, subcontractor_name FROM subcontractor ORDER BY auto_seq";
$mDB->query($Qry);
$subcontractor_id_list = "";

// 若查詢有資料，逐筆建立 <option> 下拉選項
if ($mDB->rowCount() > 0) {
    while ($row = $mDB->fetchRow(2)) {
        $ch_subcontractor = $row['subcontractor_id'];       // 下包代號
        $ch_subcontractor_name = $row['subcontractor_name']; // 下包名稱
        // 將代號與名稱組成選單項目
        $subcontractor_id_list .= "<option value=\"$ch_subcontractor\">$ch_subcontractor $ch_subcontractor_name</option>";
    }
}


$show = <<<EOT
<script>
// 綁定輸入框查詢功能
function bindSubcontractorLookup(inputId, infoId) {

    // 內部函式：根據輸入的下包代號查詢資料
    function fetchSubcontractorInfo(subcontractor_id) {
        if (subcontractor_id !== '') {
            // 發送 AJAX 請求到後端查詢建商資訊
            $.ajax({
                url: '$ajax_get_subcontractor',       // 查詢的後端 API
                method: 'POST',                       // 使用 POST 傳送
                data: { 
                    site_db : '$site_db',             // 傳送目前站台資料庫
                    subcontractor_id: subcontractor_id // 傳送輸入的下包代號
                },
                dataType: 'json',
                success: function (response) {
                    // 若回傳 success=true，顯示對應的名稱
                    if (response.success) {
                        $('#' + infoId).text(response.subcontractor_name);
                    } else {
                        // 若查無資料，清空顯示欄
                        $('#' + infoId).text('');
                    }
                },
                error: function () {
                    // 若 AJAX 請求失敗，也清空顯示欄
                    $('#' + infoId).text('');
                }
            });
        } else {
            // 若輸入框為空，清空顯示欄
            $('#' + infoId).text('');
        }
    }

    // 綁定輸入事件：當使用者輸入代號時自動查詢
    $('#' + inputId).on('input', function() {
        fetchSubcontractorInfo($(this).val());
    });

    // 若頁面載入時該欄位已有值，自動查詢一次
    var initialVal = $('#' + inputId).val();
    if (initialVal !== '') {
        fetchSubcontractorInfo(initialVal);
    }
}

// 頁面載入完成後，對四組輸入框進行綁定
$(document).ready(function(){
    bindSubcontractorLookup('subcontractor_id1', 'subcontractor_info1');
    bindSubcontractorLookup('subcontractor_id2', 'subcontractor_info2');
    bindSubcontractorLookup('subcontractor_id3', 'subcontractor_info3');
    bindSubcontractorLookup('subcontractor_id4', 'subcontractor_info4');
});
</script>

<!-- 四組輸入框區塊 -->
<div>
    <!-- 下包代工1 -->
    <div class="field_div1">下包代工1:</div>
    <div class="field_div2">
        <!-- 輸入框 + datalist 下拉選單 -->
        <input list="subcontractor_id_list" type="text" class="inputtext w-100" 
               id="subcontractor_id1" name="subcontractor_id1" autocomplete="off" value="$subcontractor_id1" 
               style="width:100%;max-width:250px;"/>
        <!-- 建商選單清單 -->
        <datalist id="subcontractor_id_list">
            $subcontractor_id_list
        </datalist>
        <!-- 顯示查詢結果 (建商名稱) -->
        <div id="subcontractor_info1"></div>
    </div>

    <!-- 下包代工2 -->
    <div class="field_div1">下包代工2:</div>
    <div class="field_div2">
        <input list="subcontractor_id_list" type="text" class="inputtext w-100" 
               id="subcontractor_id2" name="subcontractor_id2" autocomplete="off" value="$subcontractor_id2"
               style="width:100%;max-width:250px;"/>
        <div id="subcontractor_info2"></div>
    </div>

    <!-- 下包代工3 -->
    <div class="field_div1">下包代工3:</div>
    <div class="field_div2">
        <input list="subcontractor_id_list" type="text" class="inputtext w-100" 
               id="subcontractor_id3" name="subcontractor_id3" autocomplete="off" value="$subcontractor_id3"
               style="width:100%;max-width:250px;"/>
        <div id="subcontractor_info3"></div>
    </div>

    <!-- 下包代工4 -->
    <div class="field_div1">下包代工4:</div>
    <div class="field_div2">
        <input list="subcontractor_id_list" type="text" class="inputtext w-100" 
               id="subcontractor_id4" name="subcontractor_id4" autocomplete="off" value="$subcontractor_id4"
               style="width:100%;max-width:250px;"/>
        <div id="subcontractor_info4"></div>
    </div>
</div>
EOT;




// 後端資料
