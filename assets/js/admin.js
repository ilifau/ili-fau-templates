jQuery(document).ready(function($) {
    var options = ilifautpl_options_admin;
    
    // Add slides button
    $('.ilifautpl-add-slide').on('click', function() {
        if( $('.ilifautpl-input-slide-wrapper').length >= ilifautpl_options_admin.max_num_slides ) {
            alert('Es dürfen maximal ' + ilifautpl_options_admin.max_num_slides + ' Slides pro Seite angezeigt werden.');
            return;
        }

        if( $('.ilifautpl-input-slide:last').val() !== '' ) {
            var new_wrapper = $('.ilifautpl-input-slide-wrapper:last').clone().insertAfter('.ilifautpl-input-slide-wrapper:last');
            var new_id = $('.ilifautpl-input-slide-wrapper:last').data('id') + 1;

            new_wrapper.attr('data-id', new_id);
            new_wrapper.find('.ilifautpl-label').text('Slide ' + new_id);
            new_wrapper.find('input').val('');
            new_wrapper.find('textarea').val('');
        }
    });
    
    // Remove slides button
    $(document).on('click', '.ilifautpl-remove-slide', function() {
        if( ! confirm('Sind Sie sicher? Die Aktion kann nicht rückgängig gemacht werden.') )
            return;
        
        if( $('.ilifautpl-input-slide-wrapper').length < 2 ) {
            $('.ilifautpl-input-slide').val('');
            return;
        }

        $(this).closest('.ilifautpl-input-slide-wrapper').remove();
        
        $('.ilifautpl-input-slide-wrapper').each(function(index, slide) {
            var id = index + 1;
            $(slide).attr('data-id', id);
            $(slide).find('.ilifautpl-label').text('Slide ' + id);
        });
    });
    
    // Select media button
    $(document).on('click', '.ilifautpl-input-select-media', function(e) {
        e.preventDefault();

        var image_frame;
        var id = $(this).data('id');
        var _this = $(this).closest('.ilifautpl-input-select-wrapper').find('.ilifautpl-input-select');

        if(image_frame) {
            image_frame.open();
        }

        image_frame = wp.media({
            title: 'Select Media',
            multiple : false,
            library : {
                type : 'image',
            }
        });

        image_frame.on('close',function() {
            var selection =  image_frame.state().get('selection');
            var gallery_ids = new Array();
            var index = 0;

            selection.each(function(attachment) {
                gallery_ids[index] = attachment.attributes.url;
                index++;
            });

            var ids = gallery_ids.join(',');

            _this.val(ids);

            // refreshImage(ids);
        });

        image_frame.on('open', function() {
            var selection =  image_frame.state().get('selection');
            
            ids = _this.val().split(',');
            
            ids.forEach(function(id) {
                attachment = wp.media.attachment(id);
                attachment.fetch();
                selection.add( attachment ? [ attachment ] : [] );
            });

        });

        image_frame.open();
    });

    // Multiselect
    $('.ilifautpl-multi-select').multiSelect({
        keepOrder: true,
        sortable: true,
    });
});