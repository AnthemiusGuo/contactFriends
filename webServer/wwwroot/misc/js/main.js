var g_uuid = '';
$(function() {
	g_uuid = $.uuid();
});

function addSubLine(editor_typ,table_id){
	// console.log(editor_typ,table_id,table_item_vars,table_item_template);
	var id_pre = 'creator_';
	if (editor_typ==1){
		id_pre = 'modify_';
	}
	var newId = 0 - new Date().getTime();
	var newData = {_id:newId};
	var checkErr = false;
	$.each(table_item_vars,function(k,v){
		var value = $("#"+id_pre+v).val();
		if (table_item_must_vars[v] && (value=="" || value.trim()=="-"|| parseFloat(value)==0)){
			checkErr = true;
			return;
		}
		newData[v] = value;
		$("#"+id_pre+v).val('');
	});
	if (checkErr){
		alert('请填写所有星号字段！');
		return;
	}
	table_all_data[newId] = newData;
	resetTable(table_id);
}

function removeSubLine(table_id,id){
	delete table_all_data[id];
	resetTable(table_id);
}

function resetTable(table_id){
	var _html = '';
	var totalGetting = 0;
	for(var k in table_all_data){
       	console.log(typeof(table_all_data[k]));
       	if(typeof(table_all_data[k])=="function"){

        }else{
            _html += table_item_template.str_supplant(table_all_data[k]);
            totalGetting += parseFloat(table_all_data[k].allPrice);
        }
    }
	$("#table_"+table_id).html(_html);
	console.log(table_all_data);
	$("#"+table_id).val(JSON.stringify(table_all_data));
	console.log($("#"+table_id).val());
	$("#creator_totalGetting").val(totalGetting);
	$("#modify_totalGetting").val(totalGetting);

}
//目前还不支持缓存，后面必须支持搜索缓存，或者打开页面拉取所有数据，本地查询
//还有就是做个多少毫秒的延迟，
var searchDataCache = {};

function addSearch(inputName,name){
    $("#"+inputName).val(name);
    $("#"+inputName+"_list_holder").addClass('hidden');
    $("#search_loading").addClass('hidden');
}

function searchbox_on_change(inputName,editorController,editorMethod){
    $("#"+inputName+"_list_holder").removeClass('hidden');
    var data_input = $("#"+inputName+"").val();
    var target_dom = $("#"+inputName+"_list");
    var _template = '<li class="list-group-item" onclick="addSearch(\''+inputName+'\',\'{name}\')"><span class="glyphicon glyphicon-plus"></span>{name}</li>';
    $("#search_loading").removeClass('hidden');
    //检查缓存
    if (typeof searchDataCache[inputName] === 'undefined') {
	    searchDataCache[inputName] = {};
	}

    if (typeof searchDataCache[inputName][data_input] !== 'undefined') {
        var _html = "";
        $.each(searchDataCache[inputName][data_input],function(k,v){
            _html += _template.str_supplant(v);
        });
        $("#"+inputName+"_list").html(_html);
        $("#search_loading").addClass('hidden');
        return;
    }
    ajax_post({m:editorController,a:editorMethod,data:{data:data_input},callback:function(json){
        if (json.rstno==1) {
            var _html = "";
            searchDataCache[inputName][data_input] = json.data;
            $.each(json.data,function(k,v){
                _html += _template.str_supplant(v);
            });
            $("#"+inputName+"_list").html(_html);
        } else {

        }
        $("#search_loading").addClass('hidden');

        }
    });
}

function phoneSearch(id){
	var phone = $("#"+id).val().trim();
	if (phone==""){
		return;
	}
	var url = req_url_template.str_supplant({ctrller:'phone',action:'call'})+'/'+phone;
	window.location.href=url;
}
