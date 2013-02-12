$(function() {

    $('.button-tooltip').click(function() {
        $(this).toggleClass('button-active');
        $('.button-tooltip').not(this).toggleClass('button-active', false);
        var tooltip =  $(this).next('.tooltip');
        tooltip.toggleClass('active');
        $('.tooltip').not(tooltip).toggleClass('active', false);
        return false;
    });

    $('.tooltip .button.negative').click(function() {
        var tt_container = $(this).parents('.tooltip-container');
        var tooltip = $(this).parents('.tooltip');
        tooltip.toggleClass('active', false);
        tooltip.prev('.button-tooltip').toggleClass('button-active', false);
        return false;
    });

    $('.tooltip .button.positive').click(function() {
        $(this).parents('form').submit();
        return false;
    });

});

