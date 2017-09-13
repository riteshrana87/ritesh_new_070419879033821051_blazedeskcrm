<?php
/*
 * edit section
 */
if (isset($editRecord) && !empty($editRecord)) {
	if($editRecord[0]['text_paragraph']!='') {
		$paraTexts = (array)json_decode($editRecord[0]['text_paragraph']);
		$lastParagraphId =1;
		if (count($paraTexts['text_paragraph']) > 0) {
			for ($i = 0; $i < count($paraTexts['text_paragraph']); $i++) {
				?>
				<div id="textparagraph-<?php echo $i; ?>" style="display: block;" class="row mb15">
					<div class="bd-error-control col-lg-1 pull-right col-xs-1 col-md-1"><div class="bd-error"><a title="Remove Textbox?" onclick="removeItem('#textparagraph-<?php echo $i; ?>')"><i class="fa fa-trash redcol"></i></a></div></div>
					<div class="col-lg-11 col-xs-11 col-md-11"><textarea placeholder="<?php echo lang('EST_ENTER_TEXT_PARAGRAPH');?>" id="text_paragraph" name="text_paragraph[]" class="form-control" ><?php echo $paraTexts['text_paragraph'][$i]; ?></textarea></div>
					<div class="clr"></div>
				</div>
				<?php
				$lastParagraphId=$i;
			}
		}
	}
} else {
    /*
     * Add Section
     */
    ?>
    <div id="textparagraph-<?php echo $incId; ?>" style="display: block;" class="row mb15">
        <div class="bd-error-control col-lg-1 pull-right col-xs-1 col-md-1"><div class="bd-error"><a  title="<?php echo lang('EST_REMOVE_TXTBOX');?>" onclick="removeItem('#textparagraph-<?php echo $incId; ?>')"><i class="fa fa-trash redcol"></i></a></div></div>
        <div class="col-lg-11 col-xs-11 col-md-11"><textarea placeholder="<?php echo lang('EST_ENTER_TEXT_PARAGRAPH');?>" id="text_paragraph<?php echo '_'.$incId; ?>" name="text_paragraph[]" class="form-control" ></textarea></div>
        <div class="clr"></div>
    </div>
<?php } ?>
<div class="clr"></div>