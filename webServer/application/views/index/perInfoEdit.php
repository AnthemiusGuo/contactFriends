    <div class="col-lg-12">
        <table class="table">
                <tr>
                    <td class="td_title"><?php echo $this->userInfo->field_list['name']->gen_editor_show_name(); ?></td>
                    <td class="td_data"><?php echo $this->userInfo->field_list['name']->gen_editor($this->editor_typ) ?></td>
                    <td class="td_title"><?php echo $this->userInfo->field_list['sex']->gen_editor_show_name(); ?></td>
                    <td class="td_data"><?php echo $this->userInfo->field_list['sex']->gen_editor($this->editor_typ) ?></td>
                </tr>
                <tr>
                    <td class="td_title"><?php echo $this->userInfo->field_list['nickname']->gen_editor_show_name(); ?></td>
                    <td><?php echo $this->userInfo->field_list['nickname']->gen_editor($this->editor_typ) ?></td>
                    <td class="td_title"><?php echo $this->userInfo->field_list['usenick']->gen_editor_show_name(); ?></td>
                    <td><?php echo $this->userInfo->field_list['usenick']->gen_editor($this->editor_typ) ?></td>
                </tr>
                <tr>
                    <td class="td_title"><?php echo $this->userInfo->field_list['email']->gen_editor_show_name(); ?></td>
                    <td><?php echo $this->userInfo->field_list['email']->gen_show_html() ?></td>
                    <td class="td_title"></td>
                    <td></td>
                        
                </tr>
                
                <tr>
                    <td class="td_title"><?php echo $this->userInfo->field_list['regTS']->gen_editor_show_name(); ?></td>
                    <td><?php echo $this->userInfo->field_list['regTS']->gen_show_html() ?></td>
                    <td class="td_title"><?php echo $this->userInfo->field_list['beginNGOTS']->gen_editor_show_name(); ?></td>
                    <td><?php echo $this->userInfo->field_list['beginNGOTS']->gen_editor($this->editor_typ) ?></td>
                </tr>
                <tr>
                    <td class="td_title"><?php echo $this->userInfo->field_list['birthTS']->gen_editor_show_name(); ?></td>
                    <td colspan="3"><?php echo $this->userInfo->field_list['birthTS']->gen_editor($this->editor_typ) ?></td>
                </tr>
                <tr>
                    <td class="td_title"><?php echo $this->userInfo->field_list['idType']->gen_editor_show_name(); ?></td>
                    <td><?php echo $this->userInfo->field_list['idType']->gen_editor($this->editor_typ) ?></td>
                    <td class="td_title"><?php echo $this->userInfo->field_list['idNumber']->gen_editor_show_name(); ?></td>
                    <td><?php echo $this->userInfo->field_list['idNumber']->gen_editor($this->editor_typ) ?></td>
                </tr>
                <tr>
                    <td class="td_title"><?php echo $this->userInfo->field_list['nationId']->gen_editor_show_name(); ?></td>
                    <td><?php echo $this->userInfo->field_list['nationId']->gen_editor($this->editor_typ) ?></td>
                    <td class="td_title"><?php echo $this->userInfo->field_list['provinceId']->gen_editor_show_name(); ?></td>
                    <td><?php echo $this->userInfo->field_list['provinceId']->gen_editor($this->editor_typ) ?></td>
                </tr>
                <tr>
                    <td class="td_title"><?php echo $this->userInfo->field_list['addresses']->gen_editor_show_name(); ?></td>
                    <td><?php echo $this->userInfo->field_list['addresses']->gen_editor($this->editor_typ) ?></td>
                    <td class="td_title"><?php echo $this->userInfo->field_list['zipCode']->gen_editor_show_name(); ?></td>
                    <td><?php echo $this->userInfo->field_list['zipCode']->gen_editor($this->editor_typ) ?></td>
                </tr>
                <tr>
                    <td class="td_title"><?php echo $this->userInfo->field_list['phoneNumber']->gen_editor_show_name(); ?></td>
                    <td><?php echo $this->userInfo->field_list['phoneNumber']->gen_editor($this->editor_typ) ?></td>
                    <td class="td_title"><?php echo $this->userInfo->field_list['qqNumber']->gen_editor_show_name(); ?></td>
                    <td><?php echo $this->userInfo->field_list['qqNumber']->gen_editor($this->editor_typ) ?></td>
                </tr>
                <tr>
                    <td class="td_title"><?php echo $this->userInfo->field_list['wechatNumber']->gen_editor_show_name(); ?></td>
                    <td><?php echo $this->userInfo->field_list['wechatNumber']->gen_editor($this->editor_typ) ?></td>
                    <td class="td_title"><?php echo $this->userInfo->field_list['weiboNumber']->gen_editor_show_name(); ?></td>
                    <td><?php echo $this->userInfo->field_list['weiboNumber']->gen_editor($this->editor_typ) ?></td>
                </tr>
                <tr>
                    <td class="td_title"><?php echo $this->userInfo->field_list['otherContact']->gen_editor_show_name(); ?></td>
                    <td colspan="3"><?php echo $this->userInfo->field_list['otherContact']->gen_editor($this->editor_typ) ?></td>
                </tr>
                <tr>
                    <td class="td_title"><?php echo $this->userInfo->field_list['education']->gen_editor_show_name(); ?></td>
                    <td><?php echo $this->userInfo->field_list['education']->gen_editor($this->editor_typ) ?></td>
                    <td class="td_title"><?php echo $this->userInfo->field_list['school']->gen_editor_show_name(); ?></td>
                    <td><?php echo $this->userInfo->field_list['school']->gen_editor($this->editor_typ) ?></td>
                </tr>
                <tr>
                    <td class="td_title"><?php echo $this->userInfo->field_list['major']->gen_editor_show_name(); ?></td>
                    <td colspan="3"><?php echo $this->userInfo->field_list['major']->gen_editor($this->editor_typ) ?></td>
                </tr>
                <tr>
                    <td class="td_title"><?php echo $this->userInfo->field_list['outcomming']->gen_editor_show_name(); ?></td>
                    <td colspan="3"><?php echo $this->userInfo->field_list['outcomming']->gen_editor($this->editor_typ) ?></td>
                    
                </tr>
                

        </table>
    </div>