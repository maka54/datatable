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
			
			this.$container = this.$element.parent();
			this.$datatable = $('<div class="datatable-wrapper"></div>').insertBefore(this.$element);
			
			this.$header = $('<div class="datatable-thead"></div>').appendTo(this.$datatable);
			this.$body = $('<div class="datatable-tbody"></div>').appendTo(this.$datatable);
						
			this.$clone = this.$element.clone();
			this.$clone.find('tbody').remove();
			this.$clone.appendTo(this.$header);
			
			this.$cells = this.$element.find('tbody tr:first').children();
			
			this.$heads = this.$clone.find('thead tr:first').children().map(function() {
				return $('<div></div>').appendTo( $(this) );
			});  
						
			this.$heading = this.$clone.find('thead');
			
			this.$element.appendTo(this.$body);
			
			this.resize();
			
			$(window).on('resize scroll', function(){
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
				$(this).width( widths[index] );
			});  
			
			this.$header.css({marginBottom: - this.$heading.outerHeight(true) + 'px'});
			this.$header.width( this.$container.width() );
			
			if( $(window).scrollTop() > this.$datatable.offset().top ){
				this.$header.addClass('affix');
			} else {
				this.$header.removeClass('affix');
			}
			
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
				fn.apply(data, []);
			}
			
        });
				
    };

})( jQuery, window, document );
