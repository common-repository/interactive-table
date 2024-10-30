var $ = jQuery.noConflict();
jQuery( document ).ready(function($) {
	"use strict";							  
	var extra_count = 0;
	if(	ajaxObj.display_mode == 'Filter' ) {
		extra_count = 1;
	}
	var cols = parseInt(jQuery('.heading.horizontal').attr('data-terms'))+extra_count;
	var hLiWidth = (100/cols);
	
	//jQuery('.heading.horizontal li').css('width', hLiWidth+'%');
	jQuery('.column_list .horizontal .cell-heading').css('width', hLiWidth+'%');
	jQuery('.column_list .cell').css('width', hLiWidth+'%');

	var v_cols = jQuery('.column_list.vertical').attr('data-vcols');
	var vLiWidth = (100/v_cols);

	jQuery('.column_list.vertical th').css('width', vLiWidth+'%');
	jQuery('.column_list.vertical .cell').css('width', vLiWidth+'%');
	

	var maxHeight = -1;
	jQuery('.heading.horizontal .cell-heading').each(function() {
		var height = jQuery(this).height();
		maxHeight = height > maxHeight ? height : maxHeight;
	});
	jQuery( '.heading.horizontal .cell-heading' ).height( maxHeight );

	jQuery('.column_list .it_row').each(function() {
		//var rowHeight = jQuery(this).height();
		var maxRowHeight = -1;

        jQuery('.cell', this).each(function(){
            if( jQuery(this).outerHeight() > maxRowHeight ) 
               maxRowHeight = jQuery(this).outerHeight();
        });

        jQuery('.cell', this).height(maxRowHeight);
		
		if( (ajaxObj.heading_position == 'Left') || (ajaxObj.heading_position == 'Right') ) {
        	var row_num = jQuery(this).attr('data-row');
			var spanHeight = jQuery('.heading_col .cell-heading').eq(row_num-1).children("span").height();
			var paddingTop = (maxRowHeight - spanHeight)/2;
			jQuery('.heading_col .cell-heading').eq(row_num-1).height(maxRowHeight);
			jQuery('.heading_col .cell-heading').eq(row_num-1).children("span").css('padding-top', paddingTop+'px');
		}
	});
	
	var maxHeadingTopHeight = -1;
	jQuery('.pricing-table-style-top .pricing-col-wrap').each(function() {		
        jQuery('.cell-heading', this).each(function(){
            if( jQuery(this).height() > maxHeadingTopHeight ) 
               maxHeadingTopHeight = jQuery(this).height();
        });
	});	
	var widthWhenTopHead = jQuery(window).width();
	if ((widthWhenTopHead > 768)) {
		jQuery('.pricing-table-style-top .pricing-col-wrap .cell-heading .cellwrap').height(maxHeadingTopHeight);
	}	
	
	jQuery('.pricing-table-style-left .pricing-col-wrap').each(function() {
		var maxRowHeight = -1;
        jQuery('.cell', this).each(function(){
            if( jQuery(this).outerHeight() > maxRowHeight ) 
               maxRowHeight = jQuery(this).outerHeight();
        });
		var width = jQuery(window).width();
		if ((width > 768)) {
        	jQuery('.cell .cellwrap', this).height(maxRowHeight);
		}
	});
	
	jQuery( ".filter_all > a" ).on( "click", function() {
		jQuery( ".column_list tr:not(.heading)" ).show();
		jQuery( ".column_list tr.appended" ).remove();
		jQuery( ".column_list th" ).removeClass('blur');
		jQuery( ".column_list th" ).removeClass('highlight');

		jQuery( ".column_list td .v-col-content" ).show();
		jQuery( ".column_list tr .v-filtered-content" ).remove();
		jQuery( ".column_list td" ).removeClass("v-filtered");
	});
	
	jQuery( ".column_list .cell-heading" ).mouseover(function() {
		if( (ajaxObj.display_mode == 'Highlight') && (ajaxObj.highlight_mode == 'On Hover') ) {
			var taxonomy = jQuery(this).attr('data-tax');
			
			jQuery( ".column_list .cell" ).each(function( index ) {
				//var postTax = jQuery( ".column_list li" ).attr('post-tax');
				//var postTaxArray = postTax.split(",");
				var postTax = jQuery( this ).attr('data-posttax');
				var postTaxArray = postTax.split(",");
				if(jQuery.inArray(taxonomy, postTaxArray) !== -1) {
					jQuery( this ).addClass('highlight');
					jQuery( this ).css('background', termObj[taxonomy]);
				}
			});
			jQuery( this ).addClass('highlight');
			jQuery( this ).css('background', termObj[taxonomy]);
		} else if( (ajaxObj.display_mode == 'Filter') || (ajaxObj.highlight_mode == 'On Click') ) {
			jQuery( this ).css('cursor', 'pointer');
		}
	});
	
	jQuery( ".column_list .cell-heading" ).mouseout(function() {
		if( (ajaxObj.display_mode == 'Highlight') && (ajaxObj.highlight_mode == 'On Hover') ) {
			jQuery( ".column_list .cell" ).removeClass('highlight');
			jQuery( ".column_list .cell" ).css('background', '');

			jQuery( this ).removeClass('highlight');
			jQuery( this ).css('background', '');
		}
	});
	
	jQuery( ".column_list .cell-heading" ).click(function() {
		var heading_position = ajaxObj.heading_position;
		var highlight_mode = ajaxObj.highlight_mode;
		var v_posts_per_row = ajaxObj.v_posts_per_row;

		if( (ajaxObj.display_mode == 'Filter') && (jQuery(this).attr('data-tax') == 'all') ) {
			jQuery( ".column_list .it_row" ).show();
			jQuery( ".column_list .appended" ).remove();
			jQuery( ".column_list .cell-heading" ).removeClass('blur');
			jQuery( ".column_list .cell-heading" ).removeClass('highlight');
	
			jQuery( ".column_list .cell .v-col-content" ).show();
			jQuery( ".column_list .it_row .v-filtered-content" ).remove();
			jQuery( ".column_list .cell" ).removeClass("v-filtered");
			if( (heading_position == 'Left') || (heading_position == 'Right') ) {
				jQuery( ".column_list .cell" ).addClass("v-filtered");
				jQuery(".v-filtered .v-col-content").removeAttr('style');
				setTimeout(function(){ jQuery(".v-filtered .v-col-content").css('transform', 'scale(1)'); }, 50);
			}
			
			if( (heading_position == 'Top') || (heading_position == 'Bottom') ) {
				jQuery( ".column_list .cell" ).addClass("horz-filtered");
				setTimeout(function(){ jQuery(".horz-filtered .horizontal_td_wrap").css('transform', 'scale(1)'); }, 50);
			}
		}else if( (ajaxObj.display_mode == 'Filter') && (heading_position == 'Top' || heading_position == 'Bottom') ) {
			jQuery( ".column_list .cell-heading" ).removeClass('highlight');
			jQuery( ".column_list .cell-heading" ).addClass('blur');
			jQuery( this ).removeClass('blur');
			jQuery( this ).addClass('highlight');
			var h_post_per_row = parseInt(jQuery( ".column_list" ).attr("data-terms"))+1; // +1 for All taxonomy filter
			//var v_posts_per_row = ajaxObj.v_posts_per_row;

			var horzRow = 1;
	
			var tax_posts = jQuery(this).attr('data-taxposts');
			var total_rows = Math.ceil(tax_posts/h_post_per_row);
			var extra_posts = (tax_posts % h_post_per_row);
			var extra_cols = 0;
			if(extra_posts > 0) {
				extra_cols = h_post_per_row - extra_posts;
			}
			
			var taxonomy = jQuery(this).attr('data-tax');

			jQuery( ".column_list .appended" ).remove();
			jQuery( ".column_list .cell" ).removeClass("horz-filtered");
			jQuery('.column_list .cell .horizontal_td_wrap').removeAttr('style');

			var filterTable = '';
			var counter = 0;
			var total_printed_posts = 0;
			var rowCellHeight = 0;

			jQuery( ".column_list .cell" ).each(function( index ) {
				var postTax = jQuery( this ).attr('data-posttax');
				var postTaxArray = postTax.split(",");
				if(jQuery.inArray(taxonomy, postTaxArray) !== -1) {
					if(counter == 0) filterTable += '<div class="it_row appended">';
					var col_styles = jQuery( this ).attr('style');
					var colStylesArray = col_styles.split(";");
					var colHeight = colStylesArray[1].replace ( /[^\d.]/g, '' );
					if(colHeight > rowCellHeight) {
						rowCellHeight = colHeight;
					}
					//jQuery( this ).toggleClass('highlight');
					filterTable += '<div data-posttax="'+postTax+'" class="cell '+postTax+'" style="width: '+hLiWidth+'%;"><div class="filter_td_wrap">'+jQuery( this ).html()+'</div></div>';
					counter++;
					total_printed_posts++;

					if( (total_printed_posts == tax_posts) && (extra_cols > 0) && (total_rows == horzRow) && ( counter >= extra_posts) ) {
						for(var j=0; j<extra_cols; j++) {
							filterTable += '<div data-posttax="" class="cell '+postTax+'" style="width: '+hLiWidth+'%;">&nbsp;</div>';
							counter++;
							total_printed_posts++;
						}
					}
					if(counter == h_post_per_row) {
						filterTable += '</div>';
						counter = 0;
						horzRow++;
					}
				} else {
					//jQuery( this ).removeClass('highlight');
				}
			});

			jQuery( ".column_list .it_row" ).hide();
			if( heading_position == 'Bottom' ) {
				jQuery( filterTable ).insertBefore( jQuery( ".column_list .heading" ) );
				jQuery( ".column_list .appended .cell" ).height(rowCellHeight);
				setTimeout(function(){ jQuery(".appended .cell .filter_td_wrap").css('transform', 'scale(1)'); }, 50);
			} else {
				jQuery( filterTable ).insertAfter( jQuery( ".column_list .heading" ) );
				jQuery( ".column_list .appended .cell" ).height(rowCellHeight);
				setTimeout(function(){ jQuery(".appended .cell .filter_td_wrap").css('transform', 'scale(1)'); }, 50);
			}
		} else if( (ajaxObj.display_mode == 'Filter') && (heading_position == 'Left' || heading_position == 'Right') ) {
			var total_rows = parseInt(jQuery( ".column_list" ).attr("data-terms"))+1;
			var num_of_posts = jQuery(this).attr('data-taxposts');
			var total_cols = 1;
			if( num_of_posts > total_rows ) {
				total_cols = Math.ceil(num_of_posts/total_rows);
			}

			jQuery( ".column_list .cell-heading" ).removeClass('highlight');
			jQuery( ".column_list .cell-heading" ).addClass('blur');
			jQuery( this ).removeClass('blur');
			jQuery( this ).addClass('highlight');
			
			jQuery('.column_list .v-filtered .v-col-content').removeAttr('style');
			jQuery( ".column_list .cell" ).removeClass("v-filtered");

			jQuery( ".column_list .cell .v-col-content" ).hide();
			jQuery( ".column_list .cell .v-filtered-content" ).remove();
			var taxonomy = jQuery(this).attr('data-tax');

			//var filterTable = '';
			var counter = 0;
			var row_switcher = 1; //first row

			jQuery( ".column_list .cell" ).each(function( index ) {
				//jQuery( this ).children(".v-filtered-content").remove();
				var postTax = jQuery( this ).attr('data-posttax');
				var postTaxArray = postTax.split(",");

				if(jQuery.inArray(taxonomy, postTaxArray) !== -1) {
					//filterTable += '<div class="v-filtered">'+jQuery( this ).html()+'</div>';
					//var theRow = jQuery(".column_list tbody tr:nth-child(" + (counter+1) + ") td:nth-child(" + column_switcher + ")");
					jQuery(".column_list .it_row:nth-child(" + row_switcher + ") .cell:nth-child(" + (counter+1) + ")").addClass("v-filtered");
					jQuery(".column_list .it_row:nth-child(" + row_switcher + ") .cell:nth-child(" + (counter+1) + ")").append('<div class="v-filtered-content">'+jQuery( this ).children(".v-col-content").html()+'</div>');
					counter++;

					if( counter == v_cols ) {
						counter = 0;
						row_switcher++;
					}

				}
			});
			setTimeout(function(){ jQuery(".v-filtered .v-filtered-content").css('transform', 'scale(1)'); }, 50);
			setTimeout(function(){ jQuery(".v-filtered .v-col-content").css('transform', 'scale(1)'); }, 50);
			//alert(counter);
		} else if( (ajaxObj.display_mode == 'Highlight') && (highlight_mode == 'On Click') ) {
			var taxonomy = jQuery(this).attr('data-tax');
			jQuery( ".column_list .cell" ).removeClass('highlight');
			
			jQuery( ".column_list .cell" ).each(function( index ) {
				var postTax = jQuery( this ).attr('data-posttax');
				var postTaxArray = postTax.split(",");
				jQuery( this ).css('background', '');

				if(jQuery.inArray(taxonomy, postTaxArray) !== -1) {
					jQuery( this ).toggleClass('highlight');
					jQuery( this ).css('background', termObj[taxonomy]);
				}/* else {
					jQuery( this ).removeClass('highlight');
				}*/
			});
			jQuery( ".column_list .cell-heading" ).css('background', '');
			jQuery( this ).css('background', termObj[taxonomy]);
		}
	});

	jQuery( ".column_list .cell" ).mouseover(function() {
		if( (ajaxObj.display_mode == 'Highlight') && (ajaxObj.highlight_mode == 'On Hover') && (ajaxObj.cell_hover == 'enable') ) {
			var taxonomy = jQuery(this).attr('data-posttax');
			var taxArray = taxonomy.split(",");
			
			if( (ajaxObj.display_mode == 'Highlight') && (ajaxObj.highlight_mode == 'On Hover') && (ajaxObj.title_hover == 'enable') ) {
				for( var j=0; j<taxArray.length; j++) {
					jQuery( ".column_list .cell-heading" ).each(function( index ) {
						var postTax = jQuery( this ).attr('data-tax');
						if(postTax == taxArray[j]) {
							jQuery( this ).addClass('highlight');
							//jQuery( this ).css('background', termObj[postTax]);
							jQuery( this ).css('background', termObj[taxArray[0]]);
						}
					});
				}
			}
			
			for( var i=0; i<taxArray.length; i++) {
				jQuery( ".column_list .cell" ).each(function( index ) {
					var postTax = jQuery( this ).attr('data-posttax');
					var postTaxArray = postTax.split(",");
					if(jQuery.inArray(taxArray[i], postTaxArray) !== -1) {
						jQuery( this ).addClass('highlight');
						jQuery( this ).css('background', termObj[taxArray[0]]);
					}
				});
			}
		}
	});
	
	jQuery( ".column_list .cell" ).mouseout(function() {
		if( (ajaxObj.display_mode == 'Highlight') && (ajaxObj.highlight_mode == 'On Hover') && (ajaxObj.cell_hover == 'enable') ) {
			jQuery( ".column_list .cell" ).removeClass('highlight');
			jQuery( ".column_list .cell" ).css('background', '');
		}
		if( (ajaxObj.display_mode == 'Highlight') && (ajaxObj.highlight_mode == 'On Hover') && (ajaxObj.title_hover == 'enable') ) {
			jQuery( ".column_list .cell-heading" ).removeClass('highlight');
			jQuery( ".column_list .cell-heading" ).css('background', '');
		}
	});
});