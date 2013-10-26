/*
 Copyright (c) 2013 Cas Leentfaar, http://github.com/cleentfaar

 Permission is hereby granted, free of charge, to any person obtaining
 a copy of this software and associated documentation files (the
 "Software"), to deal in the Software without restriction, including
 without limitation the rights to use, copy, modify, merge, publish,
 distribute, sublicense, and/or sell copies of the Software, and to
 permit persons to whom the Software is furnished to do so, subject to
 the following conditions:

 The above copyright notice and this permission notice shall be
 included in all copies or substantial portions of the Software.

 THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
;(function ($) {
    $.sudoku = function (element, options) {
        var defaults = {
            setup: {},
            debug: false,
            log: null,
            inputSelector: "select",
            onCreateActionUrl: null,
            actionsContainer: "#SudokuActions",
            actions: ["solve", "generate", "hint", "zoom"],
            startingClues: 40
        }
        var plugin = this;
        var $grid = null;
        var $element = $(element);
        plugin.settings = {};

        plugin.init = function () {
            plugin.settings = $.extend({}, defaults, options);
            writeLog('Initiating grid...');
            if (plugin.settings.setup === null) {
                $grid = generateTable();
                $element.html($grid);
            } else {
                $grid = $element;
            }
            fillPossibleValues();
            bindTriggers();
            checkGrid();
            writeLog('Successfully initiated grid!');
        }

        /**
         * Fills the possible-values box inside each cell with numbers that are allowed to be used in that cell,
         * depending on the values in other cells
         * @param table
         */
        var fillPossibleValues = function ()
        {
            $('.possible-values', $grid).each(function(){
                var cellKey = $(this).data('cell-key');
                var possibleValues = getPossibleValuesForCell(cellKey);
                var valuesString = '<span class="possible-value">'+possibleValues.join('</span>, <span class="possible-value">')+'</span>';
                $(this).append(valuesString);
            });
            writeLog('Successfully filled possible values for all cells');
        }

        /**
         *
         * @param cellKey
         * @returns {Array}
         */
        var getPossibleValuesForCell = function (cellKey)
        {
            var parts = cellKey.split('-');
            var rowValues = getValuesBySelector('.row-'+parts[0]+' .input');
            var columnValues = getValuesBySelector('.column-'+parts[1]+' .input');
            var boxValues = getValuesBySelector('.box-'+parts[2]+' .input');
            var possibleValues = [];
            for (var x = 1; x <= 9; x++) {
                if ($.inArray(x,columnValues) === -1 &&
                    $.inArray(x,rowValues) === -1 &&
                    $.inArray(x,boxValues) === -1) {
                    possibleValues.push(x);
                }
            }
            return possibleValues;
        }

        /**
         * @param selector
         * @returns {Array}
         */
        var getValuesBySelector = function (selector)
        {
            var values = [];
            $(selector).each(function() {
                if ($(this).val() > 0) {
                    values.push($(this).val());
                }
            });
            writeLog("Used values for selector "+selector+": ");
            writeLog(values);
            return values;
        }

        /**
         * @returns {*|HTMLElement}
         */
        var generateTable = function () {
            var tableHtml = '<table class="sudoku"><tbody>';
            for (var rowNumber = 1; rowNumber <= 9; rowNumber++) {
                tableHtml += '<tr>';
                for (var columnNumber = 1; columnNumber <= 9; columnNumber++) {
                    var boxNumber = getBoxNumberFromColumnAndRow(columnNumber, rowNumber);
                    var cellKey = columnNumber + '-' + rowNumber + '-' + boxNumber;
                    tableHtml += '<td class="cell column-'+columnNumber+' row-'+rowNumber+' box-'+boxNumber+'">';
                    tableHtml += generateCellInput(cellKey);
                    tableHtml += '<div class="possible-values" data-cell-key="'+cellKey+'"></div>';
                    tableHtml += '</td>';
                }
                tableHtml += '</tr>'
            }
            tableHtml += '</tbody></table>';
            return $(tableHtml);
        }

        var clearLog = function (message) {
            if (plugin.settings.log !== null) {
                $(plugin.settings.log).html('');
            }
        }

        var writeLog = function (message) {
            if (plugin.settings.debug == true) {
                if (plugin.settings.log !== null) {
                    $(plugin.settings.log).append('<div class="entry">'+message+'</div>');
                    $(plugin.settings.log)[0].scrollTop = $(plugin.settings.log)[0].scrollHeight;
                } else {
                    console.log(message);
                }
            }
        }

        /**
         * Returns all the cells that have conflicts in their row
         * @returns {*}
         */
        var getConflictingRowCells = function ()
        {
            return getConflictingCells("row");
        }

        /**
         * Returns all the cells that have conflicts in their column
         * @returns {*}
         */
        var getConflictingColumnCells = function ()
        {
            return getConflictingCells("column");
        }

        /**
         * Returns all the cells that have conflicts in their box
         * @returns {*}
         */
        var getConflictingBoxCells = function ()
        {
            return getConflictingCells("box");
        }

        /**
         * Abstracted method to return conflicting cells based on a given data-attribute
         * This attribute can be column, row or box
         * @see getConflictingRowCells()
         * @see getConflictingColumnCells()
         * @see getConflictingBoxCells()
         * @returns {*}
         */
        var getConflictingCells = function (classPrefix)
        {
            var conflicting = [];
            for (var x = 1; x <= 9; x++) {
                var selector = "."+classPrefix+"-"+x+" select";
                var found = {};
                var duplicateValues = [];
                $(selector).each(function(){
                    var $this = $(this);
                    var value = $this.val();
                    if (value != '') {
                        if(found[value]){
                            duplicateValues.push(value);
                        }
                        found[value] = true;
                    }
                });
                for (var y = 0; y < duplicateValues.length; y++) {
                    var duplicateSelector = selector+" option[value="+duplicateValues[y]+"]:checked";
                    $(duplicateSelector).each(function() {
                        conflicting.push($(this).parent().parent());
                    });
                }
            }
            return conflicting;
        }

        /**
         * Removes any warnings from the grid, making it ready for the user to solve again
         */
        var cleanGrid = function ()
        {
            $(".warning", $grid).removeClass('warning');
            writeLog('Successfully cleaned the grid (removed warnings)');
        }

        /**
         * Main method that checks for any conflicts in the current grid
         * Empty cells are not seen as conflicts, but merely as an unfinished state
         * Therefore, the empty cells are counted in a separate method
         *
         * @param currentCell
         * @returns {Array} The conflicting cells
         */
        var checkGrid = function ()
        {
            writeLog('&nbsp;');
            writeLog('Checking grid...');
            cleanGrid();
            var conflictingRowCells = getConflictingRowCells();
            var conflictingColumnCells = getConflictingColumnCells();
            var conflictingBoxCells = getConflictingBoxCells();
            var conflicts = [];
            for (var x = 0; x < conflictingColumnCells.length; x++) {
                conflictingColumnCells[x].addClass('warning');
                var inputName = conflictingColumnCells[x].find('select').attr('name');
                if ($.inArray(inputName, conflicts) === -1) {
                    conflicts.push(inputName);
                }
            }
            writeLog('Conflicting cells found in columns: '+conflictingColumnCells.length);
            for (var x = 0; x < conflictingRowCells.length; x++) {
                conflictingRowCells[x].addClass('warning');
                var inputName = conflictingRowCells[x].find('select').attr('name');
                if ($.inArray(inputName, conflicts) === -1) {
                    conflicts.push(inputName);
                }
            }
            writeLog('Conflicting cells found in rows: '+conflictingRowCells.length);
            for (var x = 0; x < conflictingBoxCells.length; x++) {
                conflictingBoxCells[x].addClass('warning');
                var inputName = conflictingBoxCells[x].find('select').attr('name');
                if ($.inArray(inputName, conflicts) === -1) {
                    conflicts.push(inputName);
                }
            }
            writeLog('Conflicting cells found in boxes: '+conflictingBoxCells.length);
            writeLog('Grid checks have finished...');
            return conflicts;
        }

        /**
         *
         * @param cellKey
         * @param value
         */
        var disablePossibleValue = function (cellKey, value) {
            var possibleValuesElement = $(".input[id="+cellKey+"]").parent().find('.possible-values');
            possibleValuesElement.find('span.possible-value:contains('+value+')').addClass('disabled').siblings().removeClass('disabled');
        }

        /**
         * Binds triggers to generated elements to make the game interactive
         */
        var bindTriggers = function () {
            /**
             * Trigger for changing a value in the grid
             */
            $(plugin.settings.inputSelector, $element).change(function() {
                var cellKey = $(this).attr('id');
                var value = $(this).val();
                disablePossibleValue(cellKey, value);
                checkGrid();
            });
            writeLog('Successfully bound triggers to elements');
        }

        /**
         * Generates the necessary input element for the given cell's key,
         * additionally pre-selecting the current value as defined in the setup
         *
         * @param cellKey
         * @returns {*|HTMLElement}
         */
        var generateCellInput = function (cellKey) {
            var value = '';
            if (plugin.settings.setup[cellKey] !== undefined) {
                value = plugin.settings.setup[cellKey];
            }
            var cellInput = '<select name="grid['+cellKey+']" id="'+cellKey+'" id="'+cellKey+'" class="input">';
            cellInput += '<option value=""></option>';
            for (var x = 1; x <= 9; x++) {
                cellInput += '<option value="'+x+'"'+(value == x ? ' selected="selected"' : '')+'>'+x+'</option>';
            }
            cellInput += '</select>';
            return cellInput;
        }

        /**
         *
         * @param columnNumber
         * @param rowNumber
         * @returns {number}
         */
        var getBoxNumberFromColumnAndRow = function (columnNumber, rowNumber) {
            var box = 0;
            if (rowNumber < 4) {
                if (columnNumber < 4) {
                    box = 1;
                } else if (columnNumber < 7) {
                    box = 2;
                } else {
                    box = 3;
                }
            } else if (rowNumber < 7) {
                if (columnNumber < 4) {
                    box = 4;
                } else if (columnNumber < 7) {
                    box = 5;
                } else {
                    box = 6;
                }
            } else {
                if (columnNumber < 4) {
                    box = 7;
                } else if (columnNumber < 7) {
                    box = 8;
                } else {
                    box = 9;
                }
            }
            return box;
        }


        plugin.foo_public_method = function () {
            // code goes here
        }

        plugin.init();

    }

    $.fn.sudoku = function (options) {
        return this.each(function () {
            if (undefined == $(this).data('sudoku')) {
                var plugin = new $.sudoku(this, options);
                $(this).data('sudoku', plugin);
            }
        });
    }
})(jQuery);