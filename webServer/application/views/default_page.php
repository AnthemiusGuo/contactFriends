<?php include_once('common/header.php');
?>
<body>
    <nav class="navbar navbar-inverse">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?=site_url();?>">同学录</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <?
            if ($this->is_login){
            ?>
            <li><a href="<?=site_url('contact/my')?>"><?=$this->userInfo->field_list['name']->gen_show_value()?></a></li>
            <li><a href="<?=site_url('blog/write')?>"><span class="glyphicon glyphicon-edit"></span>  发文章</a></li>
            <li><a href="<?=site_url('contact/lists')?>"><span class="glyphicon glyphicon-list"></span> 查看同学录</a></li>
            <li><a href="<?=site_url('index/doLogout')?>">
                                <span class="glyphicon glyphicon-log-out"></span> 退出
                                </a></li>
            <?
            } else {
            ?>
            <li><a href="<?=site_url('index/login')?>">登录</a></li>
            <li><a href="<?=site_url('index/reg')?>">注册</a></li>
            <?
            }
            ?>
            
          </ul>
        </div>
      </div>
    </nav>
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-8 blog-main">
                <?php echo $contents; ?>
            </div><!-- /.blog-main -->

            <div class="col-sm-12 col-md-3 col-md-offset-1 blog-sidebar">
                <div class="sidebar-module sidebar-module-inset">
                    <h4>公告</h4>
                    <p>Etiam porta <em>sem malesuada magna</em> mollis euismod. Cras mattis consectetur purus sit amet fermentum. Aenean lacinia bibendum nulla sed consectetur.</p>
                </div>
                <div class="sidebar-module">
                    <h4>最近活跃用户</h4>
                    <ol class="list-unstyled">
                      <li><a href="#">GitHub</a></li>
                      <li><a href="#">Twitter</a></li>
                      <li><a href="#">Facebook</a></li>
                    </ol>
                </div>
            </div><!-- /.blog-sidebar -->

        </div>
    </div>
    <?php include_once('common/footer.php')?>
    <script>
        $('.wysiwyg_editor').wysihtml5({
    "font-styles": true, //Font styling, e.g. h1, h2, etc. Default true
    "emphasis": true, //Italics, bold, etc. Default true
    "lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
    "html": false, //Button which allows you to edit the generated HTML. Default false
    "link": true, //Button to insert a link. Default true
    
    "image": true, //Button to insert an image. Default true,
    "color": false, //Button to change color of font  
    "blockquote": true, //Blockquote  
    "fa": true,
    locale: "zh-CN",
    "size": 'sm' //default: none, other options are xs, sm, lg
});
    </script>
</body>
</html>
