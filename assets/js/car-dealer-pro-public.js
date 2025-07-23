jQuery(document).ready(function($) {
    // AJAX Filtering for Car Listings
    var filterForm = $('#car-dealer-pro-filter-form');
    var listingsResults = $('#car-listings-results');
    var ajaxUrl = carDealerPro.ajax_url;
    var nonce = carDealerPro.nonce;

    function applyFilters() {
        listingsResults.addClass('loading'); // Add a loading state visually
        var filters = {};
        filterForm.find('input, select').each(function() {
            var name = $(this).attr('name');
            var value = $(this).val();

            // Sanitize and include only if value is not empty or "any" for selects
            if (name && value && value !== 'any') {
                filters[name] = value;
            }
        });

        // Ensure the nonce field is correctly included in the data sent
        var formData = {
            action: 'car_dealer_pro_filter_cars',
            nonce: nonce, // Use the localized nonce
            filters: filters,
            search_query: $('#car_search').val() // Get search query directly
        };

        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: formData, // Send the consolidated formData
            success: function(response) {
                if (response.success) {
                    listingsResults.html(response.data.html);
                } else {
                    listingsResults.html('<p>' + 'No cars found matching your criteria or an error occurred.' + '</p>');
                    console.error('AJAX Error Response:', response.data); // Log error for debugging
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                listingsResults.html('<p>' + 'Failed to connect to the server. Please try again. Error: ' + textStatus + '</p>');
                console.error('AJAX request failed:', textStatus, errorThrown, jqXHR); // More detailed error logging
            },
            complete: function() {
                listingsResults.removeClass('loading');
            }
        });
    }

    // Trigger filter on form submission
    filterForm.on('submit', function(e) {
        e.preventDefault();
        applyFilters();
    });

    // Trigger filter on change for select fields
    filterForm.find('select').on('change', function() {
        applyFilters();
    });

    // Trigger filter after a short delay for text/number inputs (search, min/max)
    var searchTimeout;
    filterForm.find('input[type="text"], input[type="number"]').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            applyFilters();
        }, 500); // Wait 500ms after last keypress
    });

    // Reset Filters button
    $('.car-dealer-pro-reset-button').on('click', function() {
        filterForm.trigger('reset'); // Resets form fields to their initial state
        // Manually trigger change on selects if you use them to ensure filters are re-applied
        filterForm.find('select').val('any').trigger('change');
        // Clear search input manually
        $('#car_search').val('');
        applyFilters(); // Re-apply filters to show all default available cars
    });

    // Single Car Page Gallery Slider
    var mainImage = $('.single-car-gallery .main-image img');
    var thumbnails = $('.single-car-gallery .thumbnails img');

    thumbnails.on('click', function() {
        var newSrc = $(this).data('full-src'); // Assuming full-size URL is in data-full-src
        if (newSrc) {
            mainImage.attr('src', newSrc);
            thumbnails.removeClass('active');
            $(this).addClass('active');
        }
    });

    // Set initial active thumbnail
    if (thumbnails.length > 0) {
        thumbnails.first().addClass('active');
    }
});