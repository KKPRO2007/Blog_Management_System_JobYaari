$(function () {
    var $results = $('#blog-results');
    var $meta = $('#results-meta');
    var $search = $('#search-input');
    var $category = $('#category-filter');
    var $date = $('#published-date');
    var $searchButton = $('#search-button');
    var $feedback = $('#filter-feedback');
    var timer = null;

    function params() {
        return {
            q: $.trim($search.val()),
            category: $.trim($category.val()),
            date: $.trim($date.val())
        };
    }

    function feedbackText(count, requestParams) {
        return '';
    }

    function loadBlogs(immediate) {
        clearTimeout(timer);
        timer = setTimeout(function () {
            var requestParams = params();
            $results.addClass('is-loading');
            $feedback.removeClass('is-error').text('');
            $.ajax({
                url: 'ajax/blogs.php',
                method: 'GET',
                dataType: 'json',
                data: requestParams,
                success: function (res) {
                    $results.html(res.html);
                    $meta.text(res.count + ' post' + (res.count !== 1 ? 's' : ''));
                    $feedback.removeClass('is-error').text(feedbackText(res.count, requestParams));
                },
                error: function () {
                    $results.html('<div class="empty-state">Something went wrong. Please try again.</div>');
                    $meta.text('0 posts');
                    $feedback.addClass('is-error').text('Search failed. Please try again.');
                },
                complete: function () {
                    $results.removeClass('is-loading');
                }
            });
        }, immediate ? 0 : 250);
    }

    $search.on('input', function () { loadBlogs(false); });
    $search.on('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            loadBlogs(true);
        }
    });
    $category.on('change', function () { loadBlogs(true); });
    $date.on('change', function () { loadBlogs(true); });
    $searchButton.on('click', function () { loadBlogs(true); });

    $('#reset-filters').on('click', function () {
        $search.val('');
        $category.val('');
        $date.val('');
        $feedback.removeClass('is-error').text('');
        loadBlogs(true);
    });

    window.showToast = function (msg) {
        var $t = $('#toast');
        $t.text(msg).addClass('show');
        setTimeout(function () { $t.removeClass('show'); }, 2600);
    };

});
