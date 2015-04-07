<?php
$editorData = $this->editorData;
$inputName = $editorData->build_input_name($editorData->editor_typ);
?>
<textarea id="<?=$inputName?>" rows="6" name="<?=$inputName?>" class="wysiwyg_editor"><?=$this->editorData->default?></textarea>