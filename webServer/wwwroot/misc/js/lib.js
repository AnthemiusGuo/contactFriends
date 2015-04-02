
/**
*
*  Base64 encode / decode
*  http://www.webtoolkit.info/
*
**/
var Base64 = {
    // private property
    _keyStr : "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",

    // public method for encoding
    encode : function (input) {
        var output = "";
        var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
        var i = 0;

        input = Base64._utf8_encode(input);

        while (i < input.length) {

            chr1 = input.charCodeAt(i++);
            chr2 = input.charCodeAt(i++);
            chr3 = input.charCodeAt(i++);

            enc1 = chr1 >> 2;
            enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
            enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
            enc4 = chr3 & 63;

            if (isNaN(chr2)) {
                enc3 = enc4 = 64;
            } else if (isNaN(chr3)) {
                enc4 = 64;
            }

            output = output +
            this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) +
            this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);

        }

        return output;
    },

    // public method for decoding
    decode : function (input) {
        var output = "";
        var chr1, chr2, chr3;
        var enc1, enc2, enc3, enc4;
        var i = 0;

        input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

        while (i < input.length) {

            enc1 = this._keyStr.indexOf(input.charAt(i++));
            enc2 = this._keyStr.indexOf(input.charAt(i++));
            enc3 = this._keyStr.indexOf(input.charAt(i++));
            enc4 = this._keyStr.indexOf(input.charAt(i++));

            chr1 = (enc1 << 2) | (enc2 >> 4);
            chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
            chr3 = ((enc3 & 3) << 6) | enc4;

            output = output + String.fromCharCode(chr1);

            if (enc3 != 64) {
                output = output + String.fromCharCode(chr2);
            }
            if (enc4 != 64) {
                output = output + String.fromCharCode(chr3);
            }

        }

        output = Base64._utf8_decode(output);

        return output;

    },

    // private method for UTF-8 encoding
    _utf8_encode : function (string) {
        string = string.replace(/\r\n/g,"\n");
        var utftext = "";

        for (var n = 0; n < string.length; n++) {

            var c = string.charCodeAt(n);

            if (c < 128) {
                utftext += String.fromCharCode(c);
            }
            else if((c > 127) && (c < 2048)) {
                utftext += String.fromCharCode((c >> 6) | 192);
                utftext += String.fromCharCode((c & 63) | 128);
            }
            else {
                utftext += String.fromCharCode((c >> 12) | 224);
                utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                utftext += String.fromCharCode((c & 63) | 128);
            }

        }

        return utftext;
    },

    // private method for UTF-8 decoding
    _utf8_decode : function (utftext) {
        var string = "";
        var i = 0;
        var c = c1 = c2 = 0;

        while ( i < utftext.length ) {

            c = utftext.charCodeAt(i);

            if (c < 128) {
                string += String.fromCharCode(c);
                i++;
            }
            else if((c > 191) && (c < 224)) {
                c2 = utftext.charCodeAt(i+1);
                string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                i += 2;
            }
            else {
                c2 = utftext.charCodeAt(i+1);
                c3 = utftext.charCodeAt(i+2);
                string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                i += 3;
            }

        }

        return string;
    }

}
if (!String.prototype.str_supplant) {
    String.prototype.str_supplant = function (o) {
        return this.replace(/{([^{}]*)}/g,
            function (a, b) {
                var r = o[b];
                return typeof r === 'string' || typeof r === 'number' ? r : a;
            }
        );
    };
}

if (!String.prototype.trim) {
    String.prototype.trim = function () {
        return this.replace(/^\s*(\S*(?:\s+\S+)*)\s*$/, "$1");
    };
}

(function($) {
  $.uuid = function() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            var r = Math.random()*16|0, v = c == 'x' ? r : (r&0x3|0x8);
            return v.toString(16);
        });
    };
})(jQuery);

(function($) {
    $.fn.quickPager = function(options) {
            var defaults = {
                pageSize: 10,
                currentPage: 1,
                holder: null,
                pagerLocation: "after",
                id:'',
                typ:'page',
                pageCounter:0,
                need_odd:0,
                need_compress:0,
                struct:null
            };
        options = $.extend(defaults, options);
            return this.each(function() {
                var selector = $(this);
                var pageCounter = 1;
                if (!selector.parent().hasClass('simplePagerContainer')){
                    selector.wrap("<div class='simplePagerContainer'></div>");
                }
                var real_counter=0;
                selector.children().each(function(i){

                    $(this).removeAttr('PagerPage');
                    if ($(this).attr('skipPager')==1){
                        $(this).attr('PagerPage',0);
                    } else {
                        if(real_counter < pageCounter*options.pageSize && real_counter >= (pageCounter-1)*options.pageSize) {
                            $(this).attr('PagerPage',pageCounter);
                        }
                        else {
                            $(this).attr('PagerPage',pageCounter+1);
                            pageCounter ++;
                        }
                        real_counter++;
                    }
                });
                options.pageCounter = pageCounter;
                // show/hide the appropriate regions
                selector.children().addClass('hide').removeClass('nposhowPage');

                if (options.need_odd==1){
                    selector.children("[PagerPage="+options.currentPage+"]").removeClass('hide').addClass('nposhowPage').each(function(index){
                        var odd = index % 2 + 1;
                        $(this).removeClass('odd1').removeClass('odd2').addClass('odd'+odd);
                    });
                } else {
                    selector.children("[PagerPage="+options.currentPage+"]").removeClass('hide').addClass('nposhowPage');
                }
                if(pageCounter <= 1) {
                    if(!options.holder) {
                        $("#"+options.id).remove();
                    } else {
                        $(options.holder).html('');
                    }
                    return;
                }
                if (pageCounter>10){
                    options.need_compress = 1;

                }
                if (options.typ=='page'){
                    //Build pager navigation
                    var pageNav = '';
                    pageNav = "<ul class='pagination center-block' id='"+options.id+"'>";
                    if (options.need_compress==1 && options.currentPage>10){
                        pageNav += "<li class='disabled'><a class='page_pre_compress>...</a></li>";
                    }
                    for (i=1;i<=pageCounter;i++){
                        if (i==options.currentPage) {
                            pageNav += "<li class='active simplePageNav"+i+"' rel='"+i+"'><a>"+i+"</a></li>";
                        }
                        else {
                            if (options.currentPage<5){
                                var temp_cur_page = 5;
                            } else {
                                var temp_cur_page = options.currentPage;
                            }
                            if (options.need_compress==1 && (i<temp_cur_page-5 || i>temp_cur_page+5)){
                                pageNav += "<li class='hide simplePageNav"+i+"' rel='"+i+"'><a >"+i+"</a></li>";
                            } else {
                                pageNav += "<li class='simplePageNav"+i+"' rel='"+i+"'><a>"+i+"</a></li>";
                            }

                        }
                    }
                    if (options.need_compress==1 && options.currentPage<10){
                        pageNav += "<li class='disabled'><a class='page_post_compress'>...</a></li>";
                    }
                    pageNav += "</ul>";

                } else {
                    //Build pager navigation
                    var pageNav = '';
                    pageNav = "<ul class='pagination center-block' id='"+options.id+"' cur_page='"+options.currentPage+"' >";

                    pageNav += "<li class='' rel_typ='pre'><a>&laquo;</a></li>";
                    pageNav += "<li class='' rel_typ='next'><a>&raquo;</a></li>";
                    pageNav += "</ul>";

                }

                if(!options.holder) {
                    $("#"+options.id).remove();
                    switch(options.pagerLocation)
                    {
                    case "before":
                        selector.before(pageNav);
                    break;
                    case "both":
                        selector.before(pageNav);
                        selector.after(pageNav);
                    break;
                    default:
                        selector.after(pageNav);
                    }
                    var click_target = selector.parent();
                }
                else {
                    var click_target = $(options.holder);
                    click_target.html(pageNav);
                }
                //pager navigation behaviour
                click_target.find(".pagination li").click(function() {
                    if (options.typ=='page'){
                        //reset checkbox false
                        $("#selectAll").prop("checked", false);
                        $(".nposhowPage input[name='check_target[]']").prop("checked", false);
                        //grab the REL attribute
                        var clickedLink = $(this).attr("rel");
                        options.currentPage = clickedLink;
                        var pageDom = $(this).parent("ul");
                        if(options.holder) {
                            $(this).parent("ul").parent(options.holder).find("li.active").removeClass("active");
                            $(this).parent("ul").parent(options.holder).find("li[rel='"+clickedLink+"']").addClass("active");
                        }
                        else {
                            //remove current current (!) page
                            pageDom.find(".active").removeClass("active");
                            //Add current page highlighting
                            pageDom.find("li[rel='"+clickedLink+"']").addClass("active");
                        }
                        //hide and show relevant links
                        selector.children().addClass('hide').removeClass('nposhowPage');
                        if (options.need_odd==1){
                            selector.find("[PagerPage="+clickedLink+']').removeClass('hide').addClass('nposhowPage').each(function(index){
                                var odd = index % 2 +1;
                                $(this).removeClass('odd1').removeClass('odd2').addClass('odd'+odd);
                            });
                        } else {
                            selector.find("[PagerPage="+clickedLink+']').removeClass('hide').addClass('nposhowPage');
                        }
                        if (options.need_compress==1){
                            pageDom.find("a").removeClass('hide');
                            if (options.currentPage<5){
                                var temp_cur_page = 5;
                            } else if (options.currentPage>options.pageCounter-5) {
                                var temp_cur_page = parseInt(options.pageCounter)-5;
                            } else {
                                var temp_cur_page = parseInt(options.currentPage);
                            }
                            //alert(temp_cur_page);
                            //alert(options.pageCounter);
                            for (var i=1;i<=options.pageCounter;i++){
                                if (i<temp_cur_page-5 || i>temp_cur_page+5){
                                    pageDom.find("a[rel='"+i+"']").addClass('hide');
                                    //alert(i);
                                }
                            }
                        }

                        return false;
                    } else {
                        if ($(this).attr("rel_typ")=='pre'){
                            options.currentPage--;
                            if (options.currentPage<=0){
                                options.currentPage = 1;
                            }
                        } else {
                            options.currentPage++;
                            if (options.currentPage>=options.pageCounter){
                                options.currentPage = options.pageCounter;
                            }
                        }
                        selector.children().addClass('hide').removeClass('nposhowPage');
                        if (options.need_odd==1){
                            selector.find("[PagerPage="+options.currentPage+']').removeClass('hide').addClass('nposhowPage').each(function(index){
                                var odd = index % 2 +1;
                                $(this).removeClass('odd1').removeClass('odd2').addClass('odd'+odd);
                            });
                        } else {
                            selector.find("[PagerPage="+options.currentPage+']').removeClass('hide').addClass('nposhowPage');
                        }

                        return false;
                    }
                });
            });
    }
})(jQuery);

function ajax_get(opts){
    var dft_opt = {
        m: 'index',
        a: 'index',
        id: '',
        data: {},
        error_alert:1,
        callback: function(json){
            alert(json);
        }
    };
    opts = $.extend({},dft_opt,opts);
    $.blockUI();
    var url = req_url_template.str_supplant({ctrller:opts.m,action:opts.a});
    url = url + '/'+opts.id;
    $.each(opts.data,function(k,v){
        url = url + '&'+k+'='+v;
    });
    $.ajax(
        {type: "GET",
        url: url,
        dataType:"json"}
    ).done(function(json) {
        opts.callback(json);
    }).fail(function() {
        alert( "error" );
    }).always(function() {
        $.unblockUI();
    });
}
function ajax_post(opts){
    var dft_opt = {
        m: 'index',
        a: 'index',
        plus: '',
        data: {},
        error_alert:1,
        callback: function(json){
            alert(json);
        }
    };
    opts = $.extend({},dft_opt,opts);
    $.blockUI();
    var url = req_url_template.str_supplant({ctrller:opts.m,action:opts.a})+'/'+opts.plus;
    $.ajax(
        {type: "POST",
        url: url,
        dataType:"json",
        data: opts.data}
    ).done(function(json) {
        opts.callback(json);
    }).fail(function() {
        alert( "error" );
    }).always(function() {
        $.unblockUI();
    });
}

function ajax_load(selector,url) {
    $(selector).load(url);
}
function info_load(module,menu){
    $("#nav-"+module+" li").removeClass("active");
    $("#nav-"+module+"-"+menu).addClass("active");

    $(".info-"+module).addClass("hidden");
    $("#info-"+module+"-"+menu).removeClass("hidden");
    $("."+module+"-"+menu+"-table-paged").quickPager({pageSize:10,holder:'#'+module+"-"+menu+'-list_pager',struct:'tbody'});
}

function project_info_load(menu,url){
    $("#nav-project li").removeClass("active");
    $("#nav-project-"+menu).addClass("active");
    ajax_load("#project_info",url);
}

function nav_sidebar_collapse(nav_id) {
    $("#nav-sidebar .sub-nav").removeClass("show").hide();
    $("#nav-side-list-"+nav_id).removeClass("hidden").show('fast');
    $("#nav-sidebar .main-nav .showing_icon").addClass("hidden");
    $("#nav-side-title-"+nav_id+" .showing_icon").removeClass("hidden");
    //$('#nav-side-title-'+nav_id).collapse({parent:"#nav-sidebar",toggle:true});
}

function lightbox(opts) {
    var default_opts = {
        size:'m',
        url:''
    };

    var width = 720;
    if (opts.size=="l"){
        width = 960;
    } else if (opts.size=="s"){
        width=600;
    }
    opts = $.extend(default_opts,opts);
    $.fancybox.open({href : opts.url,type:'ajax',autoSize:false,autoHeight:false,autoWidth:false,width:width,height:500});
    return;
    if ($("#lightbox").data('bs.modal')){
        $("#lightbox").modal('hide').one('hidden.bs.modal', function (e) {
            $("#lightbox").removeClass("lightbox_l lightbox_m lightbox_s").addClass("lightbox_"+opts.size).modal({remote:opts.url}).on('hidden.bs.modal', function (e) {
                hide_relate_box();
                $(this).removeData('bs.modal');
            });
        });
    } else {
        $("#lightbox").removeClass("lightbox_l lightbox_m lightbox_s").addClass("lightbox_"+opts.size).modal({remote:opts.url}).on('hidden.bs.modal', function (e) {
            hide_relate_box();
            $(this).removeData('bs.modal');
        });
    }


}

function lightbox_close(){
    hide_relate_box();
    $("#lightbox").modal('hide').on('hidden.bs.modal', function (e) {
        $(this).removeData('bs.modal');

    });
    $(".modal-backdrop").remove();
}

function create_crm_step_2(typ,id){
    var field_crm_typ = $("#field_crm_typ").val()*1;
    if (typ==0){
        lightbox({size:'l',url:base_url+'?crm/create_crm_typ/'+field_crm_typ});

    } else {
        lightbox({size:'l',url:base_url+'?crm/edit_crm_typ/'+field_crm_typ+'/'+id});

    }

}

function toggle_search_box(){
    $("#search-box-main").toggleClass('hidden');
}

function build_relate_id(){
    $("#relate_box_choosed").html('');
    $(".list-search-item").removeClass('selected');
    $.each(relate_datas_result,function(k,v){
        if (v.id==-1) {
            var this_class = 'label-success';
        } else {
            var this_class = 'label-primary';
        }
        $("#relate_box_choosed").append(
            '<li class="list-search-item" onclick="miniInputBoxRemove({id})"><span class="label {this_class}">{name}</span><span class="pull-right glyphicon glyphicon-minus"></span></li>'.str_supplant({id:k,name:v.name,this_class:this_class})
                    );
        $("#list_relate_li_"+v.id).addClass("selected");
    })
}

function relatedDirectAdd(){
    var sm = $("#editor-related-id").attr('data-sm');
    var name = $("#related-search").val();
    if (name==''){
        return;
    }
    if (sm=='single'){

        relate_datas_result = [];
        relate_datas_result.push({id:-1,name:name});
        build_relate_id();
    } else {
        relate_datas_result.push({id:-1,name:name});
        build_relate_id();
    }
    $("#related-search").val('');
}

function miniInputBoxRemove(id){
    var sm = $("#editor-related-id").attr('data-sm');
    if (sm=='single'){
        relate_datas_result = [];
        build_relate_id();
    } else {
        relate_datas_result.splice(id,1);
        build_relate_id();
    }

}

function miniInputBoxAdd(id){
    if ($("#list_relate_li_"+id).hasClass('selected')){
        return;
    }
    var sm = $("#editor-related-id").attr('data-sm');
    if (sm=='single'){

        if (relate_datas[id]!=undefined){
            relate_datas_result = [];
            relate_datas_result.push(relate_datas[id]);
        }
        build_relate_id();
    } else {
        if (relate_datas[id]!=undefined){
            relate_datas_result.push(relate_datas[id]);
        }
        build_relate_id();
    }
}

function buildRelateResult(){
    var sm = $("#editor-related-id").attr('data-sm');
    var cmsTypName = $("#editor-related-id").attr('data-cms');
    var tag = $("#editor-related-id").attr('data-tag');
    var holder_text = '';
    $.each(relate_datas_result,function(k,v){
        if (v.id!=0) {
            holder_text += ' <span class="label label-primary">'+v.name+'</span> ';
        } else {
            holder_text += ' <span class="label label-danger">'+v.name+'</span> ';
        }
    });
    $("#"+cmsTypName+tag).val(JSON.stringify(relate_datas_result));
    $("#holder_"+cmsTypName+tag).html(holder_text);
    hide_relate_box();
}
function relatedSearch(){
    var moduleUrl = $("#editor-related-id").attr('data-moduleUrl');
    var cmsTypName = $("#editor-related-id").attr('data-cms');
    var tag = $("#editor-related-id").attr('data-tag');

    var quicksearch = $("#related-search").val();
    if (quicksearch==""){
        var searchInfo = {t:'no'};
    } else {
        var searchInfo = {t:'quick',i:quicksearch};
    }


    var url = moduleUrl + '/'+Base64.encode(JSON.stringify(searchInfo));

    $("#editor-related-id")
    .load(url,function(){
        relate_datas_result = JSON.parse($("#"+cmsTypName+tag).val());
        build_relate_id();

    }).removeClass('hidden');
}

function build_relate_box(tag,smTyp,cmsTyp,moduleUrl){
    var cmsTypName = '';
    switch (cmsTyp) {
        case 0:
            cmsTypName = 'creator_';
            break;
        case 1:
            cmsTypName = "modify_";
            break;
        case 2:
            cmsTypName = "search_";
            break;
    }
    var now_tag = $("#editor-related-id").attr('data-tag');
    var now_cmsTypName = $("#editor-related-id").attr('data-cms');

    if (now_tag!=tag || now_cmsTypName!=cmsTyp){
        //reset js var
        relate_datas_result = [];
    }
    $("#editor-related-id").attr('data-tag',tag)
    .attr('data-cms',cmsTypName)
    .attr('data-sm',smTyp)
    .attr('data-moduleUrl',moduleUrl)
    .load(moduleUrl,function(){
        relate_datas_result = JSON.parse($("#"+cmsTypName+tag).val());
        build_relate_id();

    }).removeClass('hidden');

}
function hide_relate_box(){
    $("#editor-related-id")
    .attr('data-tag','')
    .attr('data-cms','')
    .attr('data-sm','')
    .attr('data-moduleUrl','')
    .html('').addClass('hidden');
}

function reqEdit(url_m,url_a,fields,validator){
    if (validator.form()==false) {
        return;
    };
    var data = {};
    $.each(fields,function( key, value,array){
        if (value.type=="Field_tag") {
            data[value.name+'[]'] = new Array();
            $("input."+"modify_"+value.name+":checked").each(function() {
               data[value.name+'[]'].push($(this).val());
            });
        } else {
            data[value.name] = $("#modify_"+value.name).val();
        }
    });
    var id = $("#modify_id").val();
    if (typeof now_page != "undefined") {
        data['now_page'] = now_page;
    }
    ajax_post({m:url_m,a:url_a,plus:id,data:data,callback:function(json){
            if (json.rstno>0){
                lightbox_close();
                if (json.data.goto_url!=undefined) {
                    window.location.href=json.data.goto_url;
                }
                lightbox_close();
            } else {
                var showErr = {};
                if (json.data.err.id) {
                    showErr[json.data.err.id] = json.data.err.msg ;
                    validator.showErrors(showErr);
                } else {
                    alert(json.data.err.msg);
                }

            }
        }
    });
}
function reqCreate(url_m,url_a,fields,validator){
    if (validator.form()==false) {
        return;
    };
    var data = {};
    $.each(fields,function( key, value,array){
        console.log("type:"+value.type+':'+value.name);
        if (value.type=="Field_tag") {
            data[value.name+'[]'] = new Array();
            $("input."+"creator_"+value.name+":checked").each(function() {
               data[value.name+'[]'].push($(this).val());
            });
        } else {
            data[value.name] = $("#creator_"+value.name).val();
            console.log("value:"+$("#creator_"+value.name).val());
        }


    });

    if (typeof now_page != "undefined") {
        data['now_page'] = now_page;
    }

    ajax_post({m:url_m,a:url_a,data:data,callback:function(json){
            if (json.rstno>0){
                if (json.data.reload_comment){
                    $(comment_id).html('载入评论中').load(comments_url);
                    return;
                }
                lightbox_close();
                if (json.data.goto_url!=undefined) {
                    if (json.data.goto_url == 'refer'){
                        window.location.href=window.location.href;
                    } else {
                        window.location.href=json.data.goto_url;
                    }

                } else if (json.data.lightbox!=undefined) {
                    lightbox({size:json.data.lightbox.size,url:json.data.lightbox.url});
                }
                if (json.data.succ!=undefined && json.data.succ.msg!=undefined){
                    alert(json.data.succ.msg);
                }
            } else {
                if ((json.data.err.id!=undefined)){
                    var showErr = {};
                    showErr[json.data.err.id] = json.data.err.msg ;
                    validator.showErrors(showErr);
                    alert(json.data.err.msg);
                } else {
                    alert(json.data.err.msg);
                }
            }
        }
    });
}
function reqDelete(url_m,url_a,id){
    var _id = 0;
    if(id == 0) {
        var ids='';
        $(".nposhowPage input[name='check_target[]']").each(function(){
            var val = $(this).val();
            if($(this).prop("checked")){
               ids += val+'-';
            }
        });
        if(ids != '') {
            _id = ids.substr(0,ids.length-1)
        }
        if(_id == 0) {
            alert("请选择要删除的数据");
            return;
        }
        id = _id;
    }
    // var r = confirm("一旦删除数据将无法恢复。所有相关数据均将删除，请确认确实该数据无用再删除!");
    var r = true;
    if (!r)
    {
        return;
    }
    var data = {};
    if (typeof now_page != "undefined") {
        data['now_page'] = now_page;
    }
    ajax_get({m:url_m,a:url_a,id:id,data:data,callback:function(json){
            if (json.rstno>0){
                if (json.data.reload_comment){
                    $(comment_id).html('载入评论中').load(comments_url);
                    return;
                }
                lightbox_close();
                alert("删除成功");
                if (json.data.goto_url!=undefined) {
                    window.location.href=json.data.goto_url;
                } else if (json.data.lightbox!=undefined) {
                    lightbox({size:json.data.lightbox.size,url:json.data.lightbox.url});
                } else {
                    window.location.href=window.location.href;
                }
            } else {

                alert(json.data.err.msg);
            }
        }
    });
}
function reqOperator(url_m,url_a,id){
    var data = {};
    if (typeof now_page != "undefined") {
        data['now_page'] = now_page;
    }
    ajax_get({m:url_m,a:url_a,id:id,data:data,callback:function(json){
            if (json.rstno>0){
                lightbox_close();
                alert("操作成功! ");
                if (json.data.goto_url!=undefined) {
                    window.location.href=json.data.goto_url;
                } else if (json.data.lightbox!=undefined) {
                    lightbox({size:json.data.lightbox.size,url:json.data.lightbox.url});
                }

            } else {

                alert(json.data.err.msg);
            }
        }
    });
}

function showPie(id,data){
    $.plot($(id), data, {
        colors: ["#FF8700", "#009bdf", "#FF3100", "#00a600", "#D00000","#D05E00","#007D7D","#81C200","#00A600","#A7005E","#5C078B","#870F4E"],
        series: {
            pie: {
                show: true,
                innerRadius:10,
                combine: {
                    threshold: 0.05,  // percentage at which to combine little slices into one larger slice
                    color: null,    // color to give the new slice (auto-generated if null)
                    label: "其他"  // label to give the new slice
                },
                label:{
                    formatter: function(label, slice) {
                        return "<div style='font-size:x-small;text-align:center;padding:2px;color:" + slice.color + ";'>" + label + " : " + Math.round(slice.value) +"<br/>" + Math.round(slice.percent) + "%</div>";
                    },
                }
            }
        },
        legend: {
            show: false
        },

    });
}

function show_chart(id,data){
    $.plot($(id), data, {
        series: {
            lines: {
                show: true,
                lineWidth: 2,
                fill: true,
                fillColor: {
                    colors: [{
                            opacity: 0.05
                        }, {
                            opacity: 0.01
                        }
                    ]
                }
            },
            points: {
                show: true
            },
            shadowSize: 2
        },
        grid: {
            hoverable: true,
            clickable: true,
            tickColor: "#eee",
            borderWidth: 0
        },
        colors: ["#d12610", "#37b7f3", "#52e136"],
        xaxis: {
            ticks: 11,
            tickDecimals: 0
        },
        yaxis: {
            ticks: 11,
            tickDecimals: 0
        },
        legend: {
            show: false
        }
    });
}

function show_input_holder(typ){
    $("#input_holder").toggleClass("hidden").attr("data-typ",typ);
    $(".input_apply_detail").val('').removeClass('has-error');

}

function change_input_detail(){
    var typ = $("#input_holder").attr("data-typ");
    $(".input_apply_detail").removeClass('has-error');
    var input_apply_detail_detail = $("#input_apply_detail_detail").val();
    var input_apply_detail_money = $("#input_apply_detail_money").val();
    var input_apply_detail_comment = $("#input_apply_detail_comment").val();
    if (input_apply_detail_detail == ''){
        $("#input_apply_detail_detail").addClass('has-error');
        return;
    }
    if (parseInt(input_apply_detail_money) == 0 || parseInt(input_apply_detail_money)+""!=input_apply_detail_money){
        $("#input_apply_detail_money").addClass('has-error');
        return;
    }
    if (typ=='new'){
        apply_detail_info.push({detail:input_apply_detail_detail,
            money:input_apply_detail_money,
            comment:input_apply_detail_comment}
        );
    } else {
        apply_detail_info[$("#input_holder").attr("data-index")] = {detail:input_apply_detail_detail,
            money:input_apply_detail_money,
            comment:input_apply_detail_comment};
    }
    redraw_input_detail();
    $("#input_holder").toggleClass("hidden");
}

function redraw_input_detail(){
    var _html = '';
    var total_outgoing = 0;
    $.each(apply_detail_info,function(k,v){
        _html += '<tr>';
        _html += '<td>'+v.detail+'</td>';
        _html += '<td>'+v.money+'</td>';
        _html += '<td>'+v.comment+'</td>';
        _html += '<td>'+
            '<a class="list_op tooltips" onclick="edit_input_detail('+k+')">'+
            '<span class="glyphicon glyphicon-edit"></span></a>'+
            '<a href="javascript:void(0)" class="list_op tooltips" onclick="del_input_detail('+k+')">'+
            '<span class="glyphicon glyphicon-trash"></span>'+
            '</a></td>';
        _html += '</tr>';
        total_outgoing += parseFloat(v.money);
    });
    $("#apply_detail_table tbody").html(_html);
    $("#total_outgoing").html(total_outgoing);
    if (editor_typ==0){
        $("#creator_outgoing").val(total_outgoing);
        $("#creator_detail").val(JSON.stringify(apply_detail_info));
    } else {
        $("#modify_outgoing").val(total_outgoing);
        $("#modify_detail").val(JSON.stringify(apply_detail_info));
    }
}

function edit_input_detail(index){
    $("#input_holder").toggleClass("hidden").attr("data-typ",'edit').attr('data-index',index);
    $("#input_apply_detail_detail").val(apply_detail_info[index].detail);
    $("#input_apply_detail_money").val(apply_detail_info[index].money);
    $("#input_apply_detail_comment").val(apply_detail_info[index].comment);
}

function del_input_detail(index){
    apply_detail_info.splice(index,1);
    redraw_input_detail();
    $("#input_holder").addClass('hidden');
}

function syncDescInvestigation(input_id_prefix){
    var crmIds = $(input_id_prefix+"orgRequirement").val();
    if (crmIds=="" || crmIds=="[]") {
        $("#syncDataRst").addClass("has-error").html("没有输入需求方");
        return;
    };

    $("#syncDataRst").removeClass("has-error").html("检查中...");
    ajax_post({m:'project',a:'syncDescInvestigation',data:{crmIds:crmIds},callback:function(json){
        if (json.rstno==1) {
            _html = "";
            $.each(json.data.userData,function(k,v){
                _html += v.contactTS+" : "+v.contactMethod+"方式联系 "+v.crmName+"\n";
                _html += v.desc+"\n";
            });
            $(input_id_prefix+"descInvestigation").html(_html);
            $("#syncDataRst").html("已完成同步");
        } else {
            $("#syncDataRst").addClass("has-error").html(json.data.err.msg);
        }

        }
    });
}

function syncData(input_id_prefix){
    var email = $(input_id_prefix+"email").val();
    if (email=="") {
        $("#syncDataRst").addClass("has-error").html("没有输入邮箱地址");
        return;
    };
    $("#syncDataRst").removeClass("has-error").html("检查中...");
    ajax_post({m:'hr',a:'syncData',data:{email:email},callback:function(json){
        if (json.rstno==1) {
            $.each(json.data.userData,function(k,v){
                $(input_id_prefix+k).val(v);
            });
            $("#syncDataRst").html("已完成同步");
        } else {
            $("#syncDataRst").addClass("has-error").html(json.data.err.msg);
        }

        }
    });
}

function quicksearchFromTo(c,a){
    var quicksearch_from = $("#quick_search_from").val();
    var quicksearch_to = $("#quick_search_to").val();
    if (quicksearch==""){
        var searchInfo = {t:'no'};
    } else {
        var searchInfo = {t:'quick',ifrom:quicksearch_from,ito:quicksearch_to};
    }


    var url = req_url_template.str_supplant({ctrller:c,action:a});
    url = url + '/'+Base64.encode(JSON.stringify(searchInfo));

    window.location.href=url;
}

function quicksearch(c,a){
    var quicksearch = $("#quick_search").val();
    if (quicksearch==""){
        var searchInfo = {t:'no'};
    } else {
        var searchInfo = {t:'quick',i:quicksearch};
    }


    var url = req_url_template.str_supplant({ctrller:c,action:a});
    url = url + '/'+Base64.encode(JSON.stringify(searchInfo));

    window.location.href=url;
}

function fullsearch(c,a,fields,validator){

    if (validator!=undefined && validator.form()==false) {
        return;
    };
    toggle_search_box();
    var data = {};
    var flag = true;
    $.each(fields,function( key, value,array){
        var chked = $("#searchChk_"+value.name).prop('checked');
        if (chked) {
            data[value.name] = {};
            data[value.name].v = $("#search_"+value.name).val();
            data[value.name].e = $("#searchEle_"+value.name).val();
            flag = false;
        }
    });

    if (flag){
        var searchInfo = {t:'no'};
    } else {
        var searchInfo = {t:'full',i:data};
    }


    var url = req_url_template.str_supplant({ctrller:c,action:a});
    url = url + '/'+encodeURIComponent(Base64.encode(JSON.stringify(searchInfo)));

    window.location.href=url;
}

$(function() {
    $("#selectAll").click(function () {
        if ($(this).prop("checked")) { // 全选
            $(".nposhowPage input[name='check_target[]']").each(function () {
                $(this).prop("checked", true);
            });
        } else { // 取消全选
            $(".nposhowPage input[name='check_target[]']").each(function () {
                $(this).prop("checked", false);
            });
        }
    });
    $(".nposhowPage input[name='check_target[]']").each(function () {
        $(this).click(function(){
            if (!$(this).prop("checked")) {
                $("#selectAll").prop("checked", false);
            }
        })
    });
    $(".selectAll").click(function () {
        var tag = $(this).attr("data-select");

        if ($(this).prop("checked")) { // 全选
            $("#"+tag+" .nposhowPage input[name='check_target[]']").each(function () {
                $(this).prop("checked", true);
            });
        } else { // 取消全选
            $("#"+tag+" .nposhowPage input[name='check_target[]']").each(function () {
                $(this).prop("checked", false);
            });
        }
    });
    $("input[name='check_target[]']").each(function () {
        $(this).click(function(){
            var tag = $(this).attr("data-select");
            if (!$(this).prop("checked")) {
                $("#"+tag+"-selectall").prop("checked", false);
            }
        })
    });
})

function finance_anylytic(typ){
    var url = req_url_template.str_supplant({ctrller:"finance",action:"analytics"});
    url = url + '/'+typ+'/'+$("#filter_beginTS").val()+"/"+$("#filter_endTS").val();
    window.location.href=url;
}

function turnoverInputTypShow(typ){
    if (typ==0){
        //新建
        var prefix = "creator_";
    } else {
        var prefix = "modify_";
    }
    var nowValue = valueTyps[$("#"+prefix+"typ").val()];
    if (nowValue==0) {
        //收入
        $("#"+prefix+"incoming").prop('disabled', false);
        $("#"+prefix+"outgoing").prop('disabled', true).val(0);
    } else {
        //支出
        $("#"+prefix+"outgoing").prop('disabled', false);
        $("#"+prefix+"incoming").prop('disabled', true).val(0);
    }

}
function ajaxFileUpload(url,hidden_input)
{
    $("#loading")
    .ajaxStart(function(){
        $(this).show();
    })
    .ajaxComplete(function(){
        $(this).hide();
    });
    $.ajaxFileUpload
    (
        {
            url:url,
            secureuri:false,
            fileElementId:'fileToUpload',
            dataType: 'json',
            success: function (data, status)
            {
                if (data.rstno<=0){
                    //error
                    $("#upload_info").html(data.data.errors)

                } else {
                    var msg="上传文件 "+data.data.client_name+" 成功！"+
                        "下载地址 <a href='"+data.data.url+"' target='_blank' >"+data.data.url+"</a>";

                    if (data.data.is_image==true) {
                        msg = msg+'<div><img src="'+data.data.url+'" class="img-thumbnail" width="100%"></div>';
                    }
                    $("#upload_info").html(msg);
                    $("#"+hidden_input).val(JSON.stringify(data.data));
                }
            },
            error: function (data, status, e)
            {
                $("#upload_info").html(data.data.errors);
            }
        }
    )
    return false;
}

function switchOrg(orgId){
    reqOperator('org','doSwitchOrg',orgId);
}

function typ_in_all_changed(id){
    //3．[issue117]新建人事资料中如果【身份】（原先叫做【身份类型】）中选择了【志愿者】之后，【角色】（原先叫做【身份】）默认会选中【志愿者】，【部门】【职位】【汇报给】禁用（或隐藏）。如果【身份】中选择了【员工】，角色默认会选中【员工】，【部门】【职位】要求必填。
    var now_typ = $("#"+id).val();
    if (id=='creator_typInAll'){
        var prefix = 'creator_';
    } else if (id=='modify_typInAll') {
        var prefix = 'modify_'
    } else {
        return;
    }
    //modify_departmentId,modify_titleId,modify_reportToUserId,
    //id=\"holder-editor-{$inputName}\"
    if (now_typ==0){
        //未设置
        $("#"+prefix+'departmentId').val(0).prop('disabled', true).prop('required',false).removeClass('has-error');
        $("#"+prefix+'titleId').val(0).prop('disabled', true).prop('required',false).removeClass('has-error');;
        $("span.has-error").remove();
        $("#td_name_holder_titleId em").hide();
        $("#td_name_holder_departmentId em").hide();
        $("#"+prefix+'reportToUserId').val("[]");
        $("#"+prefix+'roleId').val(0);
        var folder_input = $("#holder-editor-"+prefix+"reportToUserId");
        folder_input.data('_html',folder_input.html());
        folder_input.html('无');

    } else if (now_typ==2){
        $("#"+prefix+'departmentId').val(0).prop('disabled', true).prop('required',false).removeClass('has-error');;
        $("#"+prefix+'titleId').val(0).prop('disabled', true).prop('required',false).removeClass('has-error');;
        $("span.has-error").remove();
        $("#"+prefix+'reportToUserId').val("[]");
        $("#"+prefix+'roleId').val(1);
        $("#td_name_holder_titleId em").hide();
        $("#td_name_holder_departmentId em").hide();
        var folder_input = $("#holder-editor-"+prefix+"reportToUserId");
        folder_input.data('_html',folder_input.html());
        folder_input.html('无');


    }  else if (now_typ==1){
        $("#td_name_holder_titleId em").show();
        $("#td_name_holder_departmentId em").show();
        $("#"+prefix+'departmentId').prop('disabled', false).prop('required',true);;
        $("#"+prefix+'titleId').prop('disabled', false).prop('required',true);;
        $("#"+prefix+'departmentId').prop('disabled', false);
        $("#"+prefix+'roleId').val(2);

        var folder_input = $("#holder-editor-"+prefix+"reportToUserId");
        folder_input.html(folder_input.data('_html'));
    }

}

function importFileUpload(url,id)
{
    $("#loading")
    .ajaxStart(function(){
        $(this).show();
    })
    .ajaxComplete(function(){
        $(this).hide();
    });
    $.ajaxFileUpload
    (
        {
            url:url,
            secureuri:false,
            fileElementId:id,
            dataType: 'json',
            success: function (data, status)
            {
                if (data.rstno<=0){
                    //error
                    $("#upload_info"+id).html(data.data.errors)

                } else {
                    lightbox({size:'xl',url:req_url_template.str_supplant({ctrller:'management',action:'importData/'+data.data.typ+"/"+data.data.file_name})});
                }
            },
            error: function (data, status, e)
            {
                $("#upload_info"+id).html(data.data.errors);
            }
        }
    )
    return false;
}
