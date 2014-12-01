// hide column name text fields when timstamp behavior option is disabled
$('form #generator-includetimestampbehavior').change(function () {
    $('form .field-generator-createdcolumnname').toggle($(this).is(':checked'));
    $('form .field-generator-updatedcolumnname').toggle($(this).is(':checked'));
}).change();

// hide namespace and base class text fields when query class generation is disabled
$('form #generator-generatequeryclass').change(function () {
    $('form .field-generator-queryns').toggle($(this).is(':checked'));
    $('form .field-generator-basequeryclass').toggle($(this).is(':checked'));
}).change();
