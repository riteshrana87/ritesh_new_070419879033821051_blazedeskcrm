<select multiple class="selectpicker form-control chosen-select" name="responsible_employee_id[]" id="responsible_employee_id" data-placeholder="<?=$this->lang->line('RESPONSIBLE_EMPLOYEE')?>">
    <?php
    foreach($responsible_employee_data as $row){
        if (in_array($row['login_id'], $responsible_user_data)){
            ?>
            <option selected value="<?php echo $row['login_id'];?>"><?php echo $row['firstname'].' '.$row['lastname'];?></option>
        <?php }else{?>
            <option value="<?php echo $row['login_id'];?>"><?php echo $row['firstname'].' '.$row['lastname'];?></option>

        <?php }}?>
</select>