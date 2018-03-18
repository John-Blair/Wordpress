/* *Bootstrap: bootstap-accordion.js v3.0.3
 * Accordion-folding functionality.
 *
 * Markup with the appropriate classes will be automatically hidden,
 * with one section opening at a time when its title is clicked.
 * Use the following markup structure for accordion behavior:
 *
<div class="panel-group fec-accordion" id="accordion">
    <div class="panel panel-default">
        //OPEN
        <div class="panel-heading">
            <h4 class="panel-title">
                CLICK HOOK: 
                <a class="accordion-toggle [FIND] open" data-toggle="collapse"  data-parent="accordion" href="#one"> TOGGLE open
                    Title
                </a>
            </h4>
        </div>
        <div id="one" class="[FIND] panel-collapse collapse in">
            <div class="panel-body">
                content
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        //CLOSED
        <div class="panel-heading">
            <h4 class="panel-title">
                CLICK HOOK: 
                <a class="accordion-toggle collapsed" data-toggle="collapse"  data-parent="accordion" href="#one"> TOGGLE open
                    Title
                </a>
            </h4>
        </div>
        <div id="one" class="panel-collapse collapse">
            <div class="panel-body">
                content
            </div>
        </div>
    </div>
</div>

 *
 * Note that any appropriate tags may be used, as long as the above classes are present.
 */

(function ($) {

    $(document).ready(function () {

        // Expand/Collapse accordion sections on click.
        $('.panel-group.fec-accordion').on('click keydown', 'a.accordion-toggle', function (e) {
            if (e.type === 'keydown' && 13 !== e.which) { // "return" key
                return;
            }
            // Allow bootstrap to handle default behaviour.
            if (e.type === 'keydown') {
                // default action is the link will be clicked - resulting in a second call to this routine.
                return;
            }

            accordionSwitch($(this));
        });

    });

    /**
	 * If this accordion is opening then close any other open accordion.
	 *
	 * @param {Object} el Title element of the accordion selected.
	 */
    function accordionSwitch(el) {
        // Find entire accordion container.
        var container = el.closest('.panel-group.fec-accordion');

        // Find the currently open accordion headers (anchor tags)
        // These will need to be closed i.e. remove "open" class and add "collapsed".
        // If the current accordion is closed it will NOT be in this set.
        var openSiblings = container.find('.open');

        // Find the currently open accordion content panels - should only be a max of 1.
        // These will need to be closed i.e. remove "in" class.
        var opensSiblingsContentControls = openSiblings.closest('.panel-heading').next();


        // If the current panel is open - then it is closing - just remove its open status and let bootstrap default behaviour take care of the rest.
        if (el.hasClass('open')) {
            // Flag panel as closed.
            el.toggleClass('open');
            return;
        }

        // Current panel is opening - close all other open panels.
        // Process accordion headers first.
        openSiblings.removeClass('open');
        openSiblings.addClass('collapsed');

        // Close content panels.
        opensSiblingsContentControls.removeClass('in');

        // Mark accordion panel as open - so it gets closed on next open.
        el.toggleClass('open');

    }

})(jQuery);
