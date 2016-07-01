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

/* This file contains javascript-functions used on bindings-page */

/* deleteBinding()
 *
 * This function submits the deleteBinding-Form
 *
 * @param bindingid The desired binding to delete
 */
function deleteBinding(bindingid) {
    //Ask user is he is sure
    if(!confirm("Do you really want to delete the binding with the ID "+bindingid+"?")) { return false; }

    //Set deviceid-field in form
    document.getElementById("deleteBindingForm_bindingid").value = bindingid;

    //Append search parameter
    /*var newAction = document.getElementById("deleteBindingForm").action +
                    "&sfield=" + document.getElementById("sfield").options[document.getElementById("sfield").selectedIndex].value +
                    "&svalue=" + document.getElementById("svalue").value;
    document.getElementById("deleteBindingForm").action = newAction;*/

    //Submit Form
    document.getElementById("deleteBindingForm").submit();

    return true;
}