$(document).ready(function(offsetHandled, offsetUnhandled) {

    function getCommonElementHtml(record) {
        return `
            <div class="record" data-id="${record.id}">
                <p><span>ID:</span> ${record.id}</p>
                <p><span>Oprettet:</span> ${record.dateCreated}</p>
                <p><span>Name:</span> ${record.fname} ${record.lname}</p>
                <p><span>Telefon:</span> ${record.phone}</p>
                <p><span>Email:</span> ${record.email}</p>
                <p><span>Uddannelse:</span> ${record.qualification}</p>
                <p><span>Antal års erfaring:</span> ${record.experience}</p>
                <p><span>Reference kontakt:</span> ${record.namePhoneReference}</p>
                {{actions}}
            </div>
        `;
    }

    function getUnhandledHtmlElement(record) {
        return getCommonElementHtml(record).replace (`{{actions}}`,
        `<form method="post" class="mark-handled-form" data-id="${record.id}">
                <div class="form-actions">
                    <button class="save" type="submit" name="mark_handled">Marker som behandlet</button>
                </div>
            </form>
            <a class="create-account" target=_blank href="`+encodeURI(`../um/admin/users.php?create=1&fname=${record.fname}&lname=${record.lname}&email=${record.email}&phone=${record.phone}`)+`">+ Opret brugerkonto</a>`);
    }

    function getHandledHtmlElement(record) {
        return getCommonElementHtml(record).replace (`{{actions}}`, ``);
    }
    
    function refreshUnhandledApplications(data) {
        $('#unhandled-container').empty();
        if (data.length == 0) {
            $('#unhandled-container').append(`<p>Ingen ansøgninger</p>`);
        }
        data.forEach(record => {
            $('#unhandled-container').append(getUnhandledHtmlElement(record));
        });

        // if (!response.unhandled_list.length || !response.has_more_unhandled) {
        //     $('#show-more-unhandled').hide();
        // } else {
        //     $('#show-more-unhandled').show();
        // }
    }

    function refreshHandledApplications(data) {
        $('#handled-container').empty();
        if (data.length == 0) {
            $('#handled-container').append(`<p>Ingen ansøgninger</p>`);
        }
        data.forEach(record => {
            $('#handled-container').append(getHandledHtmlElement(record));
        });

        // if (!response.unhandled_list.length || !response.has_more_unhandled) {
        //     $('#show-more-unhandled').hide();
        // } else {
        //     $('#show-more-unhandled').show();
        // }
    }

    function refreshData(data) {

        refreshUnhandledApplications(data.unhandled_list);
        refreshHandledApplications(data.handled_list);
    }

    
    const limit = 10;

    function loadMore(handled) {
        let offset = handled ? offsetHandled : offsetUnhandled;
        $.get('actions.php', { ajax: true, handled: handled, offset: offset + limit }, function(data) {
            const records = JSON.parse(data);
            const container = handled ? '#handled-container' : '#unhandled-container';
            
            records.forEach(record => {
                const recordHtml = handled ? getHandledHtmlElement(record) : getUnhandledHtmlElement(record);
                $(container).append(recordHtml);
            });
            // if (handled) {
            //     offsetHandled += limit;
            //     if (!response.has_more) {
            //         $('#show-more-handled').hide();
            //     }
            // } else {
            //     offsetUnhandled += limit;
            //     if (!response.has_more) {
            //         $('#show-more-unhandled').hide();
            //     }
            // }
        });
    }

    $('#show-more-handled').click(function() {
        loadMore(true);
    });

    $('#show-more-unhandled').click(function() {
        loadMore(false);
    });

    $(document).on('submit', '.mark-handled-form', function(e) {
        e.preventDefault();
        const form = $(this);
        const id = form.data('id');
        $.post('actions.php', { mark_handled: true, id: id }, function(data) {
            refreshData(JSON.parse(data));
        });
    });

    $.get('actions.php', { ajax: true, initial: 1 }, function(data) {
        refreshData(JSON.parse(data));
    });
});