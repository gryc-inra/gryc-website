/*
 *    Copyright 2015-2018 Mathieu Piot
 *
 *    Licensed under the Apache License, Version 2.0 (the "License");
 *    you may not use this file except in compliance with the License.
 *    You may obtain a copy of the License at
 *
 *        http://www.apache.org/licenses/LICENSE-2.0
 *
 *    Unless required by applicable law or agreed to in writing, software
 *    distributed under the License is distributed on an "AS IS" BASIS,
 *    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *    See the License for the specific language governing permissions and
 *    limitations under the License.
 */

module.exports = function(container, buttonText, buttonId, fieldStart, functions) {
    // If the container is not a div, exit the function
    if (!container.length > 0) {
        return;
    }

    if (buttonId === undefined) {
        buttonId = null;
    }

    if (fieldStart === undefined) {
        fieldStart = false;
    }

    if (functions === undefined) {
        functions = [];
    }

    // Delete the first label (the number of the field), and the required class
    container.children('div').find('label:first').text('');
    container.children('div').find('label:first').removeClass('required');
    container.children('div').find('label:first').removeClass('required');

    // Create and add a button to add new field
    if (buttonId) {
        var id = "id='" + buttonId + "'";
        var $addButton = $('<a href="#" ' + id + 'class="btn btn-outline-secondary btn-sm"><span class="fas fa-plus"></span> '+buttonText+'</a>');
    } else {
        var $addButton = $('<a href="#" class="btn btn-outline-secondary btn-sm"><span class="fas fa-plus"></span> '+buttonText+'</a>');
    }

    container.append($addButton);

    // Add a click event on the add button
    $addButton.click(function(e) {
        e.preventDefault();
        // Call the addField method
        addField(container);
        return false;
    });

    // Define an index to count the number of added field (used to give name to fields)
    var index = container.children('div').length;

    // If the index is > 0, fields already exists, then, add a deleteButton to this fields
    if (index > 0) {
        container.children('div').each(function() {
            addDeleteButton($(this));
            addFunctions($(this));
        });
    }

    // If we want to have a field at start
    if (true === fieldStart && 0 === index) {
        addField(container);
    }

    // The addField function
    function addField(container) {
        // Replace some value in the « data-prototype »
        // - "__name__label__" by the name we want to use, here nothing
        // - "__name__" by the name of the field, here the index number
        var $prototype = $(container.attr('data-prototype')
            .replace(/class="col-sm-2 control-label required"/, 'class="col-sm-2 control-label"')
            .replace(/__name__label__/g, '')
            .replace(/__name__/g, index));

        // Add a delete button to the new field
        addDeleteButton($prototype);

        // If there are supplementary functions
        addFunctions($prototype);

        // Add the field in the form
        $addButton.before($prototype);

        // Increment the counter
        index++;
    }

    // A function called to add deleteButton
    function addDeleteButton(prototype) {
        // First, create the button
        var $deleteButton = $('<div class="col-sm-1"><a href="#" class="btn btn-danger btn-sm"><span class="fas fa-trash-alt"></span></a></div>');

        // Add the button on the field
        $('.col-sm-10', prototype).removeClass('col-sm-10').addClass('col-sm-9');
        prototype.append($deleteButton);

        // Create a listener on the click event
        $deleteButton.click(function(e) {
            e.preventDefault();
            // Remove the field
            prototype.remove();
            return false;
        });
    }

    function addFunctions(prototype) {
        // If there are supplementary functions
        if (functions.length > 0) {
            // Do a while on functions, and apply them to the prototype
            for (var i = 0; functions.length > i; i++) {
                functions[i](prototype);
            }
        }
    }
};
