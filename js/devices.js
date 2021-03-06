/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2016 Niels Gandraß <ngandrass@squacu.de>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

/* This file contains javascript-functions used on devices-page */

var lastDeviceTemplateId;
var lastDeviceTemplateName;
var lastDeviceTemplateDescription;
var lastDeviceTemplateAllowQuickadd;
var lastDeviceTemplateNavi;

/* spawnAddDeviceTemplateForm()
 *
 * This function spawns a form to add a new device-template
 */
function spawnAddDeviceTemplateForm() {
    document.getElementById("newdevicetemplate").style.display = "inline-block";
}

/* despawnAddDeviceTemplateForm()
 *
 * This function despawns a form to add a new device-template
 */
function despawnAddDeviceTemplateForm() {
    document.getElementById("newdevicetemplate").style.display = "none";
}

/* resetLastDeviceTemplateEdit()
 *
 * Removes input-form from edit and restores old content
 */
function resetLastDeviceTemplateEdit() {
    //Check if lastId is present
    if(!lastDeviceTemplateId) { return false; }

    //Revert Changes
    document.getElementById("devicetpl_name_"+lastDeviceTemplateId).innerHTML = lastDeviceTemplateName;
    document.getElementById("devicetpl_description_"+lastDeviceTemplateId).innerHTML = lastDeviceTemplateDescription;
    document.getElementById("devicetpl_allow_quickadd_"+lastDeviceTemplateId).innerHTML = lastDeviceTemplateAllowQuickadd;
    document.getElementById("devicetpl_navi_"+lastDeviceTemplateId).innerHTML = lastDeviceTemplateNavi;

    return true;
}

/* editDeviceTemplateSubmit()
 *
 * Submits the editDeviceTemplate-Form
 */
function editDeviceTemplateSubmit() {
    document.getElementById("devicetpl_edit_form").submit();
}

/* deleteDeviceTemplate()
 *
 * Submits delete request for devicetemplate
 *
 * @param devicetemplateid The ID of the desired template
 */
function deleteDeviceTemplate(devicetemplateid) {
    //Check input
    if(!devicetemplateid) { return false; }

    if(confirm("Do you really want to delete the devicetemplate with the ID "+devicetemplateid+" and ALL ASSOCIATED BINDINGS AND DEVICES?!?")) {
        //Set form values
        document.getElementById("devicetpl_edit_devicetemplateid").value = devicetemplateid;
        document.getElementById("devicetpl_edit_form_action").value = 2;

        //Submit form
        document.getElementById("devicetpl_edit_form").submit();
    } else {
        return false;
    }
}

/* editDeviceTemplate()
 *
 * Spawns form-inputs in desired table column
 *
 * @param devicetemplateid The ID of the desired template
 */
function editDeviceTemplate(devicetemplateid) {
    //Check input
    if(!devicetemplateid) { return false; }

    //Remove form from old field if not allready
    if(lastDeviceTemplateId) {
        document.getElementById("devicetpl_name_"+lastDeviceTemplateId).innerHTML = lastDeviceTemplateName;
        document.getElementById("devicetpl_description_"+lastDeviceTemplateId).innerHTML = lastDeviceTemplateDescription;
        document.getElementById("devicetpl_allow_quickadd_"+lastDeviceTemplateId).innerHTML = lastDeviceTemplateAllowQuickadd;
        document.getElementById("devicetpl_navi_"+lastDeviceTemplateId).innerHTML = lastDeviceTemplateNavi;
    }

    //Get elements
    var devicetpl_name = document.getElementById("devicetpl_name_"+devicetemplateid);
    var devicetpl_description = document.getElementById("devicetpl_description_"+devicetemplateid);
    var devicetpl_allow_quickadd = document.getElementById("devicetpl_allow_quickadd_"+devicetemplateid);
    var devicetpl_navi = document.getElementById("devicetpl_navi_"+devicetemplateid);

    //Get old field contents
    var old_name = devicetpl_name.innerHTML;
    var old_description = devicetpl_description.innerHTML;
    var old_allow_quickadd = devicetpl_allow_quickadd.innerHTML;
    var old_navi = devicetpl_navi.innerHTML;

    //Set new lastDeviceTemplateId and co.
    lastDeviceTemplateId = devicetemplateid;
    lastDeviceTemplateName = old_name;
    lastDeviceTemplateDescription = old_description;
    lastDeviceTemplateAllowQuickadd = old_allow_quickadd;
    lastDeviceTemplateNavi = old_navi;

    //Set current edit-id in form and set action-type to 1 (Edit)
    document.getElementById("devicetpl_edit_devicetemplateid").value = devicetemplateid;
    document.getElementById("devicetpl_edit_form_action").value = 1;

    //Insert form-fields into table and update navi
    devicetpl_name.innerHTML = '<input type="text" style="width: 200px;" name="devicetpl_edit_name" value=\''+old_name+'\' required />';
    devicetpl_description.innerHTML = '<input type="text" style="width: 300px;" name="devicetpl_edit_description" value=\''+old_description+'\' />';
    devicetpl_allow_quickadd.innerHTML = '<input type="checkbox" name="devicetpl_edit_allow_quickadd" value="true" '+((devicetpl_allow_quickadd.getAttribute('data-allow-quickadd')=='1')?'checked':'')+' />'
    devicetpl_navi.innerHTML = '<a href="#" onclick="editDeviceTemplateSubmit()" title="Save Changes"><img src="img/check.png"/></a>&nbsp;<a href="#" onclick="resetLastDeviceTemplateEdit()" title="Revert Changes"><img src="img/return.png"/></a>';

    return true;
}

function despawnNewDeviceSingleForm() {
    //Set display to none
    document.getElementById("newDeviceSingleForm_wrapper").style.display = "none";

    return true;
}

function despawnNewDeviceMultiForm() {
    //Set display to none
    document.getElementById("newDeviceMultiForm_wrapper").style.display = "none";

    return true;
}

function spawnNewDeviceSingleForm() {
    //Despawn other form if still visible
    despawnNewDeviceMultiForm();

    //Display wrapper
    document.getElementById("newDeviceSingleForm_wrapper").style.display = "inline-block";

    return true;
}



function spawnNewDeviceMultiForm() {
    //Despawn other form if still visible
    despawnNewDeviceSingleForm();

    //Display wrapper
    document.getElementById("newDeviceMultiForm_wrapper").style.display = "inline-block";

    return true;
}

/* deleteDevice()
 *
 * This function submits the deleteDevice-Form
 *
 * @param deviceid The desired device to delete
 */
function deleteDevice(deviceid) {
    //Ask user is he is sure
    if(!confirm("Do you really want to delete the device with the ID "+deviceid+" and ALL bindings associated with it?")) { return false; }

    //Set deviceid-field in form
    document.getElementById("deleteDeviceForm_deviceid").value = deviceid;

    //Submit Form
    document.getElementById("deleteDeviceForm").submit();

    return true;
}