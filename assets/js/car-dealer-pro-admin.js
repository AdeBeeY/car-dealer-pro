jQuery(document).ready(function($) {
    // Media uploader for Car Image Gallery
    var galleryFrame;

    $('#add_car_gallery_images').on('click', function(event) {
        event.preventDefault();

        // If the media frame already exists, reopen it.
        if (galleryFrame) {
            galleryFrame.open();
            return;
        }

        // Create a new media frame
        galleryFrame = wp.media({
            title: 'Select or Upload Car Images',
            button: {
                text: 'Use these images'
            },
            multiple: true // Allow multiple image selection
        });

        // When an image is selected in the media frame...
        galleryFrame.on('select', function() {
            var attachments = galleryFrame.state().get('selection').toJSON();
            var galleryHtml = '';
            var galleryIds = [];

            // Get existing IDs
            var existingIds = $('#car_gallery_ids').val();
            if (existingIds) {
                galleryIds = existingIds.split(',').map(Number); // Convert to array of numbers
            }

            // Append new images and update IDs
            $.each(attachments, function(index, attachment) {
                if (attachment.type === 'image') {
                    // Check if image is already in gallery to prevent duplicates
                    if ($.inArray(attachment.id, galleryIds) === -1) {
                        galleryIds.push(attachment.id);
                        galleryHtml += '<li data-id="' + attachment.id + '">';
                        galleryHtml += '<img src="' + attachment.sizes.thumbnail.url + '" />';
                        galleryHtml += '<a href="#" class="car-gallery-remove-image dashicons dashicons-no-alt"></a>';
                        galleryHtml += '</li>';
                    }
                }
            });

            // Update the gallery display
            $('.car-gallery-images').append(galleryHtml);

            // Update the hidden input field with comma-separated IDs
            $('#car_gallery_ids').val(galleryIds.join(','));
        });

        // Open the media frame
        galleryFrame.open();
    });

    // Remove image from gallery
    $('#car_gallery_container').on('click', '.car-gallery-remove-image', function(event) {
        event.preventDefault();
        var $li = $(this).closest('li');
        var removedId = $li.data('id');

        // Remove from display
        $li.remove();

        // Update hidden input
        var existingIds = $('#car_gallery_ids').val();
        var galleryIds = existingIds.split(',').map(Number);
        galleryIds = $.grep(galleryIds, function(value) {
            return value != removedId;
        });
        $('#car_gallery_ids').val(galleryIds.join(','));
    });

    // Make gallery images sortable
    $('.car-gallery-images').sortable({
        items: 'li',
        cursor: 'move',
        scrollSensitivity: 40,
        forcePlaceholderSize: true,
        forceHelperSize: false,
        helper: 'clone',
        opacity: 0.65,
        placeholder: 'sortable-placeholder',
        start: function(event, ui) {
            ui.item.css('background-color', '#fcfcfc');
        },
        stop: function(event, ui) {
            ui.item.removeAttr('style');
            var galleryIds = [];
            $('.car-gallery-images li').each(function() {
                galleryIds.push($(this).data('id'));
            });
            $('#car_gallery_ids').val(galleryIds.join(','));
        }
    });

});