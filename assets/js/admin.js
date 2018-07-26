jQuery(document).ready(function($) {
    // Add slides
    $('.ilifautpl-add-slide').on('click', function() {
        if( $('.ilifautpl-input-slide-wrapper').length > 4 ) {
            alert('Es dürfen maximal 5 Slides eingefügt werden.');
            return;
        }
        
        if( $('.ilifautpl-input-slide:last').val() !== '' ) {
            $('.ilifautpl-input-slide-wrapper:last').clone().insertAfter('.ilifautpl-input-slide-wrapper:last');
        }
        
        $('.ilifautpl-input-slide:last').val('');
    });
    
    // Remove slides
    $(document).on('click', '.ilifautpl-remove-slide', function() {
        if( $('.ilifautpl-input-slide-wrapper').length < 2 ) {
            $('.ilifautpl-input-slide').val('');
            return;
        }

        $(this).closest('.ilifautpl-input-slide-wrapper').remove();
    });
    
    // Select media for slides
    $(document).on('click', '.ilifautpl-input-slide-media', function(e) {
        e.preventDefault();
        
        var _this = $(this).prev('.ilifautpl-input-slide');
        var image_frame;
        
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
});