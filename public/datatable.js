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
						
			this.$clone = this.$element.clone();
			this.$clone.find('tbody').remove();
			this.$clone.appendTo(this.$header);
			
			this.$cells = this.$element.find('tbody tr:first').children();
			this.$heads = this.$clone.find('thead tr:first').children();
			this.$heading = this.$clone.find('thead');
			
			this.$element.appendTo(this.$body);
			
			this.resize();
			
			$(window).on('resize', function(){
				self.resize();
			});
        },
		
		resize: function() {	

			var self = this,
				widths;
			
			widths = this.$cells.map(function() {
				return $(this).width();
			}).get();
			
			this.$heads.each(function(index, th) {
				$(th).append( $('<div></div>').width(widths[index]) );
			});  
			
			this.$element.css({marginTop: - this.$heading.outerHeight(true) + 'px'});
			
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
