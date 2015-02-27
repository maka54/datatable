/*!
 * jQuery lightweight plugin boilerplate
 * Licensed under the MIT license
 */


;(function ( $, window, document, undefined ) {

	
    // Create the defaults once
    var datatable = "datatable",
        defaults = {

        };

    // The actual plugin constructor
    function Datatable( element, options ) {
        this.element = element;
        this.$element = $(element);
        this.options = $.extend( {}, defaults, options) ;

        this._defaults = defaults;
        this._name = datatable;

        this.init();
    }

    Datatable.prototype = {

        init: function() {
			
			var self = this;
			
			this.$datatable = $('<div class="datatable-wrapper"></div>').insertBefore(this.$element);
			
			this.$header = $('<div class="datatable-thead"></div>').appendTo(this.$datatable);
			this.$body = $('<div class="datatable-tbody"></div>').appendTo(this.$datatable);
			
			
			this.replace();
			
			$(window).on('resize', function(){
				self.reset();
			});
        },
		
		reset: function() {
			this.$header.empty();
			this.$body.empty();	
			this.replace();
		},
		
		replace: function() {	

			this.$clone = this.$element.clone();
			this.$clone.find('tbody').remove();
			this.$clone.appendTo(this.$header);
			
			this.$element.appendTo(this.$body);
			this.$element.css({marginTop: - this.$clone.outerHeight(true) + 'px'});
			
			this.setup();
		},
		
		setup: function() {
			var self = this,
				$cells = this.$element.find('tbody tr:first').children(),
				$heads = this.$header.find('thead tr:first').children(),
				widths;
			
			widths = $cells.map(function() {
				return $(this).width();
			}).get();
			
			$heads.each(function(index, th) {
				$(th).append( $('<div></div>').width(widths[index]) );
			});  
			
		}

    };


    $.fn[datatable] = function ( options ) {
		
        return this.each(function () {
			
			var $this = $(this),
				data = $this.data("datatable"),
				fn;

			if (!data) {
				$this.data("datatable", (data = new Datatable(this, options)));
			}

			if (typeof options === "string" && $.isFunction((fn = data[options]))) {
				fn.apply(data, args);
			}
			
        });
				
    };

})( jQuery, window, document );
