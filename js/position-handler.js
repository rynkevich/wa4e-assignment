$(document).ready(function() {
    posEntryCounter = $('#hint_initial_pos_entries_count').val();
    maxPositionEntries = $('#hint_max_pos_entries').val();
});

function addPositionField() {
    if (posEntryCounter < maxPositionEntries) {
        posEntryCounter++;
        var form = $('#blck_positions');

        entryPosition = getEntryPosition();
        form.append('<div id="blck_year' + entryPosition + '"><p><label for="edt_year' +
            entryPosition + '">Year:&nbsp;</label>' +
            '<input type="text" name="year' + entryPosition +
            '" id="edt_year' + entryPosition + '">' +
            '<input type="button" value="-" onclick="deletePositionField(' +
            entryPosition + ');"></p></div>');
        form.append('<div id="blck_desc' + entryPosition +
            '"><p><textarea name="desc' + entryPosition +
            '" id="txt_desc' + entryPosition +
            '" rows="8" cols="80"></textarea></p></div>');
    } else {
        alert('Maximum of position entries exceeded');
    }
}

function getEntryPosition() {
    for (pos = 1; pos <= maxPositionEntries; pos++) {
        isExistingElement = $('#blck_year' + pos).length;
        console.log(isExistingElement);
        if (!isExistingElement) {
            return pos;
        }
    }
    return null;
}

function deletePositionField(position) {
    posEntryCounter--;
    $('#blck_year' + position).remove();
    $('#blck_desc' + position).remove();
}
