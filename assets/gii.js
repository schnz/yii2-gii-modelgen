// hide column name text fields when timstamp behavior option is disabled
$('form #generator-includetimestampbehavior').change(function () {
    $('form .field-generator-createdcolumnname').toggle($(this).is(':checked'));
    $('form .field-generator-updatedcolumnname').toggle($(this).is(':checked'));
}).change();
