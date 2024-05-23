$(document).ready(function(offsetHandled, offsetUnhandled) {
    const limit = 10;

    function loadMore(handled) {
        let offset = handled ? offsetHandled : offsetUnhandled;
        $.get('applications.php', { ajax: true, handled: handled, offset: offset + limit }, function(data) {
            const records = JSON.parse(data);
            const container = handled ? '#handled-container' : '#unhandled-container';
            records.forEach(record => {
                const recordHTML = `
                    <div class="record">
                        <p>ID: ${record.id}</p>
                        <p>Name: ${record.fname} ${record.lname}</p>
                        <p>Date Created: ${record.dateCreated}</p>
                        ${handled ? `<a href="new_account.php?id=${record.id}&fname=${record.fname}&lname=${record.lname}&email=${record.email}&phone=${record.phone}&qualification_id=${record.qualification_id}&experience=${record.experience}&namePhoneReference=${record.namePhoneReference}">Transform to Account</a>` : `
                        <form method="post" class="mark-handled-form" data-id="${record.id}">
                            <button type="submit" name="mark_handled">Mark as Handled</button>
                        </form>`}
                    </div>
                `;
                $(container).append(recordHTML);
            });
            if (handled) {
                offsetHandled += limit;
                if (!response.has_more) {
                    $('#show-more-handled').hide();
                }
            } else {
                offsetUnhandled += limit;
                if (!response.has_more) {
                    $('#show-more-unhandled').hide();
                }
            }
        });
    }

    $('#show-more-handled').click(function() {
        loadMore(true);
    });

    $('#show-more-unhandled').click(function() {
        loadMore(false);
    });

    function getCommonElementHtml(record) {
        return `
            <div class="record" data-id="${record.id}">
                <p><span>ID:</span> ${record.id}</p>
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
            <a class="create-account" target=_blank href="um/admin/users.php"?create=1&fname=${record.fname}&lname=${record.lname}&email=${record.email}&phone=${record.phone}">Opret brugerkonto</a>`);
    }

    function getHandledHtmlElement(record) {
        return getCommonElementHtml(record).replace (`{{actions}}`, ``);
    }

    $(document).on('submit', '.mark-handled-form', function(e) {
        e.preventDefault();
        const form = $(this);
        const id = form.data('id');
        $.post('list.php', { mark_handled: true, id: id }, function(data) {
            const response = JSON.parse(data);

            $('#unhandled-container').empty();
            response.unhandled_list.forEach(record => {
                $('#unhandled-container').append(getUnhandledHtmlElement(record));
            });

            $('#handled-container').empty();
            response.handled_list.forEach(record => {
                $('#handled-container').append(getHandledHtmlElement(record));
            });

            // if (!response.unhandled_list.length || !response.has_more_unhandled) {
            //     $('#show-more-unhandled').hide();
            // } else {
            //     $('#show-more-unhandled').show();
            // }

            // if (!response.handled_list.length || !response.has_more_handled) {
            //     $('#show-more-handled').hide();
            // } else {
            //     $('#show-more-handled').show();
            // }
        });
    });
});