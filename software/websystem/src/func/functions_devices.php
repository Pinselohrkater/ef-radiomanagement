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
        <b>//FIXME: Delete also devices with tplid and delete bindings!</b>
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
                    <td id="devicetpl_navi_<?=$row->{"devicetemplateid"}?>"><a href="#" onclick="editDeviceTemplate(<?=$row->{"devicetemplateid"}?>)" title="Edit"><img src="<?=domain.dir_img?>edit.png"/></a>&nbsp;<a href="#" onclick="deleteDeviceTemplate(<?=$row->{"devicetemplateid"}?>)" title="Delete"><img src="<?=domain.dir_img?>cross.png"/></a></td>
                </tr>
            <?php
            }
            ?></table>
            <i>DTID: DevicetemplateID</i>&nbsp;&nbsp;-&nbsp;&nbsp;<a href="#" onclick="spawnAddDeviceTemplateForm()">New Devicetemplate!</a><br/>
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

    /* generateRegisteredDevicesList()
     *
     * This function generates a list of all registered devices
     *
     * @param $search_field The field to perform a specific search in (If == false => no search is performed)
     * @param $search_value The value to search for
     * @param $availability (0=List all devices, 1=List only available devices, 2=List only bound devices)
     */
    public function generateRegisteredDevicesList($search_field, $search_value, $availability) {
        //Gain db access
        global $db;

        //Build search-argument if necessary
        if(!$search_value) { $search_field=false; }
        switch($search_field) {
            case "deviceid": $sqlSearch = "`deviceid` LIKE '%".$db->escape($search_value)."%'"; break;
            case "name": $sqlSearch = "`name` LIKE '%".$db->escape($search_value)."%'"; break;
            case "callsign": $sqlSearch = "`callsign` LIKE '%".$db->escape($search_value)."%'"; break;
            case "serialnumber": $sqlSearch = "`serialnumber` LIKE '%".$db->escape($search_value)."%'"; break;
            case "notes": $sqlSearch = "`notes` LIKE '%".$db->escape($search_value)."%'"; break;
            default: $sqlSearch = "1=1"; break;
        }

        //Query all registered devices
        $query_devices = $db->query("
            SELECT  devices.*, devicetemplates.`name`
            FROM `devices` devices, `devicetemplates` devicetemplates
            WHERE devices.`devicetemplateid` = devicetemplates.`devicetemplateid` AND ".$sqlSearch."
            ORDER BY `name`, `callsign`, `deviceid` ASC");
        if($db->isError()) { die($db->isError()); }

        //Querry database for bindings
        $query_bindings = $db->query("SELECT `deviceid` FROM `bindings`");
        if($db->isError()) { die($db->isError()); }

        //Generate array of bound devices
        $boundDevices = array();
        while($row = mysqli_fetch_object($query_bindings)) { $boundDevices[] = $row->{"deviceid"}; }

        //Output search-form
        if(!$search_field) { $optionSelected = "callsign"; }
        else { $optionSelected = $search_field; }
        ?>
        <form method="POST" id="deviceSearchForm" action="<?=domain?>index.php?p=devices" style="margin-bottom: 2px;">
            <input type="hidden" name="serachRegisteredDevices_reset" id=serachRegisteredDevices_reset" value=""/>
            <table>
                <tr>
                    <td style="text-align: right; font-size: 12px;">Search:</td>
                    <td style="text-align: left;">
                        <select name="serachRegisteredDevices_field" required>
                            <option value="deviceid" <?php if($optionSelected=="deviceid") echo "selected"; ?>>DeviceID</option>
                            <option value="name" <?php if($optionSelected=="name") echo "selected"; ?>>Name</option>
                            <option value="callsign" <?php if($optionSelected=="callsign") echo "selected"; ?>>Callsign</option>
                            <option value="serialnumber" <?php if($optionSelected=="serialnumber") echo "selected"; ?>>Serialnumber</option>
                            <option value="notes" <?php if($optionSelected=="notes") echo "selected"; ?>>Notes</option>
                        </select>
                        <input type="text" name="serachRegisteredDevices_value" value="<?=$search_value?>" placeholder="String to search for" style="width: 200px;"/>
                        <input type="submit" value="Submit"/>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; font-size: 12px;">Show:</td>
                    <td style="text-align: left;">
                        <select name="serachRegisteredDevices_availability">
                            <option value="0" <?php if($availability=="0") echo "selected"; ?>>All registered devices</option>
                            <option value="1" <?php if($availability=="1") echo "selected"; ?>>Only available devices</option>
                            <option value="2" <?php if($availability=="2") echo "selected"; ?>>Only bound devices</option>
                        </select>
                    </td>
                </tr>
            </table>
        </form>
        <?php

        //Generate output data array
        $outputDevices = array();
        switch($availability) {
            //Only available devices
            case 1:
                while($row = mysqli_fetch_object($query_devices)) {
                    if(!in_array($row->{"deviceid"}, $boundDevices)) {
                        $outputDevices[] = $row;
                    }
                }
                break;
            //Only bound devices
            case 2:
                while($row = mysqli_fetch_object($query_devices)) {
                    if(in_array($row->{"deviceid"}, $boundDevices)) {
                        $outputDevices[] = $row;
                    }
                }
                break;
            //All devices
            case 0:
            default: while($row = mysqli_fetch_object($query_devices)) { $outputDevices[] = $row; } break;
        }

        //Check if devices are present
        if(sizeof($outputDevices)<1) {
            ?><div>There are currently no matching devices registered in the database. <a href="#">Register a new device now!</a></div><?php
            return true;
        }

        //Generate output table head
        ?>
        <table class="devicelist" style="margin-left: 0px;">
            <tr>
                <td class="devicelist_head">DID</td>
                <td class="devicelist_head">Av</td>
                <td class="devicelist_head">Name (DTID)</td>
                <td class="devicelist_head">Callsign</td>
                <td class="devicelist_head">S/N</td>
                <td class="devicelist_head">Notes</td>
            </tr>
        <?php

        //Spit out devices
        $row_color = "even";
        foreach($outputDevices as $device) {
            if($row_color == "even") { $row_color = "odd"; }
            else { $row_color = "even"; }
            ?>
            <tr class="devicelist_<?=$row_color?>">
                <td><?=$device->{"deviceid"}?></td>
                <td style="text-align: center;"><?php if(in_array($device->{"deviceid"}, $boundDevices)) { echo "<img src=\"".domain.dir_img."cross.png\"/>"; } else { echo "<img src=\"".domain.dir_img."check.png\"/>"; } ?></td>
                <td><?=$device->{"name"}?>&nbsp;(<?=$device->{"devicetemplateid"}?>)</td>
                <td><?=$device->{"callsign"}?></td>
                <td><?=$device->{"serialnumber"}?></td>
                <td><?=$device->{"notes"}?></td>
            </tr>
        <?php
        }

        ?></table><i>Av: Available</i>&nbsp;&nbsp;-&nbsp;&nbsp;<i>DID: DeviceID</i>&nbsp;&nbsp;-&nbsp;&nbsp;<i>DTID: DevicetemplateID</i>&nbsp;&nbsp;-&nbsp;&nbsp;<a href="#" onclick="">New Device!</a><br/><?php

        return true;

    }

}