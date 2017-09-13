<?php
/**
 * Created by PhpStorm.
 * User: brijesh.tiwari@c-metric.com
 * Date: 3/15/2016
 * Time: 7:28 PM
 */



if($this->input->get('data')){

    $urlData='email_client?data='.$this->input->get('data');
}else{
    $urlData='';

    if($this->input->get('admin')){

        $urlData='email_client?admin';
    }

}

?>

<div class="getHeader">
	<iframe src="<?php echo base_url()?><?php echo $urlData;?>"></iframe>
</div>

<style>
    .getHeader iframe {
        border: medium none;
        height: 650px;
        margin-left: -1%;
        width: 102%;
    }

</style>