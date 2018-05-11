var window_width = $(window).width();
var window_height = $(window).height();
var right_width = 0; //右侧宽度
var table_grid_height = window_height - 78 - 77;  //easyui表格高度

//easyui datebox 新加按钮
var datebox_buttons = $.extend([], $.fn.datebox.defaults.buttons);
datebox_buttons.splice(1, 0, {
    text: '清除',
    handler: function(target){
    	$(target).datebox("setValue",""); 
    }
});

//提示信息
function showTip(msg)
{
	$.messager.show({
		title: '提示',
		msg: msg,
		showType: 'fade',
		style:{
			right:'',
			bottom:'',
			top:document.body.scrollTop+document.documentElement.scrollTop
		}
	});
}

//输出对象内容
function printObject(obj)
{
    var desc = "<table cellspacing='0' cellpadding='0' border='0' width='100%'>";
    for (var i in obj) {
        desc += "<tr>";
        desc += "<td>" + i + "</td>" + "<td>" + obj[i] + "</td>";
        desc += "</tr>";
    }
    desc += "</table>";

    return desc;
}

//验证框方法扩展
$.extend($.fn.validatebox.defaults.rules, {
    md: {
        validator: function(value, param){
            var DATE_FORMAT = /^[0-9]{4}-[0-1]?[0-9]{1}-[0-3]?[0-9]{1}$/;
            return DATE_FORMAT.test(value);
        },
        message: '日期格式有误'
    },
});

//表格方法扩展
$.extend($.fn.datagrid.methods, {
    autoMergeCells: function (jq, fields) { //行合并
        return jq.each(function () {
            var target = $(this);
            if (!fields) {
                fields = target.datagrid("getColumnFields");
            }

            var rows = target.datagrid("getRows");
            var i = 0,
            j = 0,
            temp = {};

            for (i; i < rows.length; i++) {
                var row = rows[i];
                j = 0;
                for (j; j < fields.length; j++) {
                    var field = fields[j];
                    var tf = temp[field];

                    if (!tf) {
                        tf = temp[field] = {};
                        tf[row[field]] = [i];
                    } else {
                        var tfv = tf[row[field]];
                        if (tfv) {
                            tfv.push(i);
                        } else {
                            tfv = tf[row[field]] = [i];
                        }
                    }
                }
            }

            $.each(temp, function (field, colunm) {
                $.each(colunm, function () {
                    var group = this;

                    if (group.length > 1) {
                        var before,
                        after,
                        megerIndex = group[0];

                        for (var i = 0; i < group.length; i++) {
                            before = group[i];
                            after = group[i + 1];
                            if (after && (after - before) == 1) {
                                continue;
                            }

                            var rowspan = before - megerIndex + 1;
                            if (rowspan > 1) {
                                target.datagrid('mergeCells', {
                                    index: megerIndex,
                                    field: field,
                                    rowspan: rowspan
                                });
                            }

                            if (after && (after - before) != 1) {
                                megerIndex = after;
                            }
                        }
                    }
                });
            });
        });
    }
});

$(function(){

	right_width = window_width - $('.left_bar').width(); 
	$('.right_container').css('width', right_width + 'px');

	$('.left_bar, .right_container').css('height', window_height + 'px');
	$('#container').css('height', (window_height - 78) + 'px');
	
});




