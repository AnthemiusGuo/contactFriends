<ul class='breadcrumb breadcrumb-with-search'>
    
    <?=(isset($this->searchInfo) && $this->searchInfo['t']=="quick")?'<li class="avtive"><span class="glyphicon glyphicon-search"></span> 快捷搜索</li>':'';?>
    <?=(isset($this->searchInfo) && $this->searchInfo['t']=="full")?'<li class="avtive"><span class="glyphicon glyphicon-search"></span> 高级搜索</li>':'';?>
    <li class="pull-right search-aera-inline">
        <div class="">
        <form class="form-inline">
            <div class="input-group input-group-sm">
                <span class="input-group-addon">快捷搜索</span>
                <input type="text" id="quick_search" class="form-control input-sm" placeholder="请输入<?=(isset($this->quickSearchName)?$this->quickSearchName:'名称/编号');?>" value="<?=(isset($this->quickSearchValue)?$this->quickSearchValue:'');?>">
                <div class="input-group-btn">
                    <a class="btn btn-primary btn-sm" id="btnQuickSearch" onclick="quicksearch('<?=$this->controller_name?>','<?=$this->method_name?>')"><span class="glyphicon glyphicon-search"></span></a>
                    
                    </a>
                </div>

            </div>
            <div class="clearfix"></div>
            
        </form>
    </div>
    </li>
    <li><div class="clear"></div></li>
</ul>
<script type="text/javascript">
    $("#quick_search").keyup(function(event){
        if(event.keyCode == 13){
            $("#btnQuickSearch").click();
        }
    });
    var searchFormValidator = $("#searchForm").validate();
    var reqSearchFields = [];
    <?php
    foreach ($this->listInfo->build_search_infos() as $key_names) {
        echo 'reqSearchFields.push({name:"'.$key_names.'",type:"'.$this->listInfo->dataModel[$key_names]->typ.'"});';
    }
    ?>
</script>