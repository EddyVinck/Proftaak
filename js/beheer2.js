$(document).ready(function() {
    $('.colorpicker2').simpleColor({
        boxHeight: 40,
        cellWidth: 20,
        cellHeight: 20,
        displayColorCode: true,
        onSelect: function(hex, element, id) {
            var name = getColorNameOrKey("hash",hex);
            $(element).val(name);
            $(element).attr('value', name);
        },
    });
    $('.modal').modal();
    renderSchoolSelect();
    reloadEverything();
    $('#schoolSelect').on('change', function() {
        reloadEverything();
    });
});

function reloadEverything(){
    getColleges();
    getDocenten();
    getKlassen();
    getStudenten();
    reloadModalSelect()
}
function getStudenten(){
    var schoolSelect = $('#schoolSelect').val();
    if (!schoolSelect) {schoolSelect = 3;}
    $.post("ajaxInclude/getStudenten.php",{
        school:schoolSelect
    }, function(response,status){
        var data = JSON.parse(response);
        var template = $("#studentenTemplate").html();
        var renderTemplate = Mustache.render(template, data);
        $("#studenten-container").html(renderTemplate);
        $(data).each(function(index, el) {
            var template = $("#studentKlasSelectTemplate").html();
            var renderTemplate = Mustache.render(template, el.klassen);
            $('#studentKlasSelect' + el.user_id).html(renderTemplate);
        });
        initializeSelectElements();
        var options = {enterDelay: 1};
        $('.tooltipped').tooltip(options);
    });
}

$('body').on('click', '.js-save-studenten', function(){
    var saveVals = [];
    var er = 0;
    $('#studenten-container .student').each(function(index, el) {
        var id = $(this).data('id');
        var selectVal = $('#studentKlasSelect' + id).val();
        saveVals.push({
            klas: selectVal,
            id: id
        });
    });
    console.log(saveVals);
    $.post("ajaxInclude/saveStudenten.php",{
        data: saveVals
    }, function(response,status){
        reloadEverything();
    });
});

$('body').on('click', '.js-student-verificatie', function(){
    var id = $(this).data('id');
    var rol = $(this).data('rol');
    var er = 0;
    if (rol == "stu") {
        rol = "ost";
    }
    else if (rol == "ost") {
        rol = "stu"
    }
    else {
        er = 1;
    }
    if (!er) {
        $.post("ajaxInclude/updateStudentVerification.php",{
            rol: rol,
            id: id
        }, function(response,status){
            $('.tooltipped').tooltip('remove');
            reloadEverything();
        });

    }
});

$('.student-college-select').on('change', function() {
    var userid = $(this).closest('.student').data('id');
    var newColId = $(this).val();
});

$('body').on('change', '.student-college-select', function(event) {
    var userid = $(this).closest('.student').data('id');
    var newColId = $('#studentCollegeSelect' + userid).val();
    $.post("ajaxInclude/getKlassenBasedOnCollege.php",{
        user: userid,
        col: newColId
    }, function(response,status){
        var data = JSON.parse(response);
        var template = $("#studentKlasSelectTemplate").html();
        var renderTemplate = Mustache.render(template, data);
        $('#studentKlasSelect' + userid).removeClass('initialized').html(renderTemplate);
        initializeSelectElements();
    });
});

function reloadModalSelect(){
    var schoolSelect = $('#schoolSelect').val();
    if (!schoolSelect) {schoolSelect = 3;}
    $.post("ajaxInclude/getColleges.php",{
        school: schoolSelect
    }, function(response,status){
        var data = JSON.parse(response);
        $('#newKlasSelect').html('');
        var txt = "";
        $(data).each(function(index, el) {
            txt+= '<option value="' + el.id + '">' + el.naam + '</option>';
        });
        $('#newKlasSelect').html(txt);
        initializeSelectElements();
    });
}

$('body').on('click', '.js-save-docenten', function(){
    var saveVals = [];
    var er = 0;
    $('#docenten-container .docent').each(function(index, el) {
        var id = $(this).data('id');
        var selectVal = $('#docentCollegeSelect' + id).val();
        saveVals.push({
            college: selectVal,
            id: id
        });
    });
    $.post("ajaxInclude/saveDocenten.php",{
        data: saveVals
    }, function(response,status){
        reloadEverything();
    });
});

$('body').on('click', '.js-docent-verificatie', function(){
    var id = $(this).data('id');
    var rol = $(this).data('rol');
    var er = 0;
    if (rol == "doc") {
        rol = "odo";
    }
    else if (rol == "odo") {
        rol = "doc"
    }
    else {
        er = 1;
    }
    if (!er) {
        $.post("ajaxInclude/updateDocentVerification.php",{
            rol: rol,
            id: id
        }, function(response,status){
            $('.tooltipped').tooltip('remove');
            reloadEverything();
        });

    }
});

function getDocenten(){
    var schoolSelect = $('#schoolSelect').val();
    if (!schoolSelect) {schoolSelect = 3;}
    $.post("ajaxInclude/getDocenten.php",{
        school:schoolSelect
    }, function(response,status){
        var data = JSON.parse(response);
        var template = $("#docentTemplate").html();
        var renderTemplate = Mustache.render(template, data);
        $("#docenten-container").html(renderTemplate);
        initializeSelectElements();
        var options = {enterDelay: 1};
        $('.tooltipped').tooltip(options);
    });
}

function getKlassen(){
    var schoolSelect = $('#schoolSelect').val();
    if (!schoolSelect) {schoolSelect = 3;}
    $.post("ajaxInclude/getKlassen.php",{
        school:schoolSelect
    }, function(response,status){
        var data = JSON.parse(response);
        var template = $("#klassenTemplate").html();
        var renderTemplate = Mustache.render(template, data);
        $("#klassen-container").html(renderTemplate);
        initializeSelectElements();
    });
}

function deleteKlas(id){
    $.post("ajaxInclude/deleteKlas.php",{
        id:id
    }, function(response,status){
        var data = JSON.parse(response);
        if (data.length == 0) {
            $('#modalDeleteKlas').modal('close');
            reloadEverything();
        }
        else {
            // Error handling
            if ($.inArray("users", data) != -1) {
                $('.js-modal-errors-klas').append('<p class="red-text">Er zijn nog studenten aan deze klas verbonden, verplaats deze studenten eerst</p>');
            }
        }
    });
}

$('body').on('click', '.js-new-klas', function(){
    $('#modalNewKlas').modal('open');
});

$('body').on('click', '.js-delete-klas-confirm', function(){
    var id = $(this).closest('.modal').data('delid');
    deleteKlas(id);
});

$('body').on('click', '.js-save-new-klas', function(){
    var college = $('#newKlasSelect').val();
    var name = $('#new-klas-naam').val();
    $.post("ajaxInclude/newKlas.php",{
        college: college,
        name: name
    }, function(response,status){
        if (response == "") {
            reloadEverything();
        }
    });
});

$('body').on('click', '.js-save-klassen', function(){
    var saveVals = [];
    var er = 0;
    $('#klassen-container .klas').each(function(index, el) {
        var id = $(this).data('id');
        var nameElem = $(this).find('.js-klas-name');
        if ($(nameElem).val() == "") {
            er = 1;
            $(nameElem).next().data('error', 'De naam kan niet leeg zijn');
            $(nameElem).next().attr('data-error', 'De naam kan niet leeg zijn');
            $(nameElem).addClass('invalid');
        }
        var selectVal = $('#klasSelect' + id).val();
        saveVals.push({
            naam: $(nameElem).val(),
            college: selectVal,
            id: $(this).data('id')
        });
    });
    if (!er) {
        $.post("ajaxInclude/saveKlassen.php",{
            data: saveVals
        }, function(response,status){
            reloadEverything();
        });
    }
});

$('body').on('click', '.js-delete-klas', function(){
    var id = $(this).closest('.klas').data('id')
    $('#modalDeleteKlas').data('delid', id).attr('data-delid', id);
    $('.js-modal-errors-klas').html('');
    $('#modalDeleteKlas').modal('open');
});

$('body').on('click', '.js-new-college', function(){
    $('#modalNewCollege').modal('open');
});

$('body').on('click', '.js-save-new-college', function(){
    var schoolSelect = $('#schoolSelect').val();
    if (!schoolSelect) {schoolSelect = 3;}
    var newName = $('#new-college-naam').val();
    var newCol = $(this).closest('.modal').find('.simpleColorDisplay').html();
    var col = getColorNameOrKey("hash", newCol);
    $.post("ajaxInclude/newCollege.php",{
        col: col,
        name: newName,
        school: schoolSelect
    }, function(response,status){
        reloadEverything();
    });
});

$('body').on('click', '.js-save-colleges', function(){
    var saveVals = [];
    var er = 0;
    $('#colleges-container .college').each(function(index, el) {
        var nameElem = $(this).find('.js-college-name');
        var id = $(this).data('id');
        if ($(nameElem).val() == "") {
            er = 1;
            $(nameElem).next().data('error', 'De naam kan niet leeg zijn');
            $(nameElem).next().attr('data-error', 'De naam kan niet leeg zijn');
            $(nameElem).addClass('invalid');
        }
        var col = getColorNameOrKey("hash", $(this).find('.simpleColorDisplay').html());
        saveVals.push({
            naam: $(this).find('.js-college-name').val(),
            kleur: col,
            id: $(this).data('id')
        });
    });
    if (!er) {
        $.post("ajaxInclude/saveColleges.php",{
            data: saveVals
        }, function(response,status){
            reloadEverything();
        });
    }
});

$('body').on('click', '.js-delete-college-confirm', function(){
    var id = $(this).closest('.modal').data('delid');
    deleteCollege(id);
});

$('body').on('click', '.js-delete-college', function(){
    var id = $(this).closest('.college').data('id')
    $('#modalDeleteCollege').data('delid', id).attr('data-delid', id);
    $('.js-modal-errors').html('');
    $('#modalDeleteCollege').modal('open');
});

function deleteCollege(id){
    $.post("ajaxInclude/deleteCollege.php",{
        id:id
    }, function(response,status){
        var data = JSON.parse(response);
        if (data.length == 0) {
            $('#modalDeleteCollege').modal('close');
            reloadEverything();
        }
        else {
            // Error handling
            if ($.inArray("schooladmin", data) != -1) {
                $('.js-modal-errors').append('<p class="red-text">Een schooladmin is nog verbonden aan dit college, vraag hem/haar zichzelf eerst te verplaatsen</p>');
            }
            if ($.inArray("docenten", data) != -1) {
                $('.js-modal-errors').append('<p class="red-text">Er zijn nog docenten verbonden aan dit college, verplaats of verwijder hen eerst</p>');
            }
            if ($.inArray("studentenklassen", data) != -1) {
                $('.js-modal-errors').append('<p class="red-text">Er zijn nog studenten klassen verbonden aan dit college, verplaats of verwijder deze eerst</p>');
            }
        }

    });
}
function getColleges(){
    var schoolSelect = $('#schoolSelect').val();
    if (!schoolSelect) {schoolSelect = 3;}
    $.post("ajaxInclude/getColleges.php",{
        school: schoolSelect
    }, function(response,status){
        var data = JSON.parse(response);
        var template = $("#collegeTemplate").html();
        data.map(function(index, elem) {
            index.kleur = "#" + getColorNameOrKey("name",index.kleur);
        })
        var renderTemplate = Mustache.render(template, data);
        $("#colleges-container").html(renderTemplate);
        initColorPicker();
    });
}
function renderSchoolSelect(){
    $.post("ajaxInclude/getScholen.php",{}, function(response,status){
        if (response) {
            var data = JSON.parse(response);
            var template = $("#schoolselectTtemplate").html();
            var renderTemplate = Mustache.render(template, data);
            $("#schoolSelect").html(renderTemplate);
            reloadEverything();
            initializeSelectElements();
        }
    });
}
