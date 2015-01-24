<?php
/**
 * Created by Niels Gandraß.
 * Copyright (C) 2015
 */

class devices {

    /* printDeviceTemplateList()
     *
     * This function prints a list of all available device-templates (editable)
     */
    public function printDeviceTemplateList() {
        //Gain db access
        global $db;

        //Query available templates
        $query = $db->query("SELECT * FROM `devicetemplates` ORDER BY `devicetemplateid` ASC LIMIT 100");
        if($db->isError()) { die($db->isError()); }

        //Check if there are templates in dataset
        if(mysqli_num_rows($query)<1) {
            ?><div>There are currently no devicetemplates listed in the database. <a href="#" onclick="spawnAddDeviceTemplateForm()">Create one!</a></div><?php
        }

        //Generate table from query
        ?>
        <form style="display: inline;" method="POST" action="<?=domain?>index.php?p=devices" id="devicetpl_edit_form">
            <input type="hidden" name="devicetpl_edit_form_submitted" value="true"/>
            <input type="hidden" name="devicetpl_edit_form_action" id="devicetpl_edit_form_action" value="0"/>
            <input type="hidden" name="devicetpl_edit_devicetemplateid" id="devicetpl_edit_devicetemplateid" value=""/>
            <table class="gptable" style="margin-left: 0px;">
            <tr>
                <td class="gptable_head">DTID</td>
                <td class="gptable_head">Name</td>
                <td class="gptable_head">Description</td>
                <td class="gptable_head"></td>
            </tr>
            <?php
            //Generate data rows
            $row_color = "even";
            while($row = mysqli_fetch_object($query)) {
                //Adjust row-color
                if($row_color == "even") { $row_color = "odd"; }
                else { $row_color = "even"; }
                ?>
                <tr class="gptable_<?=$row_color?>">
                    <td><?=$row->{"devicetemplateid"}?></td>
                    <td id="devicetpl_name_<?=$row->{"devicetemplateid"}?>"><?=$row->{"name"}?></td>
                    <td id="devicetpl_description_<?=$row->{"devicetemplateid"}?>"><?=$row->{"description"}?></td>
                    <td id="devicetpl_navi_<?=$row->{"devicetemplateid"}?>"><a href="#" onclick="editDeviceTemplate(<?=$row->{"devicetemplateid"}?>)" title="Edit"><img src="<?=domain.dir_img?>edit.png"/></a>&nbsp;<a href="#" onclick="deleteDeviceTemplate(<?=$row->{"devicetemplateid"}?>)" title="Delete"><img src="<?=domain.dir_img?>delete.png"/></a></td>
                </tr>
            <?php
            }
            ?></table>
            <i>DTID: DevicetemplateID</i>&nbsp;&nbsp;-&nbsp;&nbsp;<a href="#" onclick="spawnAddDeviceTemplateForm()">New Devicetemplate!</a>
        </form>
        <?php

    }

    /* deviceTemplateEditFormSubmitted()
     *
     * This function checks if the deviceTemplateEditForm was submitted
     *
     * @return TRUE Form submitted
     * @return FALSE Form NOT submitted
     */
    public function deviceTemplateEditFormSubmitted() {
        if($_POST["devicetpl_edit_form_submitted"]) { return true; }
        return false;
    }

    /* proccessDeviceTemplateEdit()
     *
     * This function proccesses the submitted proccessDeviceTemplateEdit-Form
     *
     * @return STRING The message report
     */
    public function proccessDeviceTemplateEdit() {
        //Check if form was submitted
        if(!self::deviceTemplateEditFormSubmitted()) { return false; }

        //Gain db acess
        global $db;

        //Check desired action (1=Edit, 2=Delete)
        if($_POST["devicetpl_edit_form_action"] == 1) {
            //Proccess edit

            //Check if all required datafields are present
            if(!$_POST["devicetpl_edit_devicetemplateid"] || !$_POST["devicetpl_edit_name"]) { return "<b style=\"color: #EE0000;\">Error: Name can't be empty!!</b>"; }

            //Update database
            $db->query("UPDATE `devicetemplates` SET `name`='".$db->escape($_POST["devicetpl_edit_name"])."', `description`='".$db->escape($_POST["devicetpl_edit_description"])."' WHERE `devicetemplateid`='".$db->escape($_POST["devicetpl_edit_devicetemplateid"])."' LIMIT 1");
            if($db->isError()) { die($db->isError()); }

            return "";

        } elseif($_POST["devicetpl_edit_form_action"] == 2) {
            //Proccess delete
            if(!$_POST["devicetpl_edit_devicetemplateid"]) { return "<b style=\"color: #EE0000;\">Error: DTID not passed!</b>"; }

            //Delete template from db
            $db->query("DELETE FROM `devicetemplates` WHERE `devicetemplateid`='".$db->escape($_POST["devicetpl_edit_devicetemplateid"])."' LIMIT 1");
            if($db->isError()) { die($db->isError()); }

            return "";
        }

        return false;

    }

    /* newDeviceTemplateFormSubmitted()
     *
     * This function checks if the newDeviceTemplateForm was submitted
     *
     * @return TRUE Form submitted
     * @return FALSE Form NOT submitted
     */
    public function newDeviceTemplateFormSubmitted() {
        if($_POST["newdevicetemplateform_submitted"]) { return true; }
        return false;
    }

    /* addNewDeviceTemplate()
     *
     * This function tries to add a new design template
     *
     * @param $new_name The new devicetemplate-name
     * @param $new_description The new devicetemplate-description
     *
     * @return TRUE Success
     * @return FALSE Error
     */
    public function addNewDeviceTemplate($new_name, $new_description) {
        //Check input
        if(!$new_name) { return false; }

        //Gain db access
        global $db;

        //Insert new template into device
        $db->query("INSERT INTO `devicetemplates` (`name`, `description`) VALUES ('".$db->escape($new_name)."', '".$db->escape($new_description)."')");
        if($db->isError()) { die($db->isError()); }

        return true;
    }

}