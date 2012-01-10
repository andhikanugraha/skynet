
// Form validation logic

recheckActivated = false;

(function ($) {
	$.fn.getVal = function(fieldname) {
		var b;
		$(this.serializeArray()).each(function() {
			if (!b && this.name == fieldname)
				b = $.trim(this.value);
		});
		
		return b;
	}
	$.fn.recheck = function() {
		l = $('label[for=' + this.attr('id') + ']');
		f = $('#appform');
		s = this.parents('fieldset');
		n = $("a[href='#" + s.attr('id') + "']");

		if (!f.getVal(this.attr('name')) && l.hasClass('required')) {
			l.addClass('recheck');
			n.addClass('recheck');
			this.addClass('invalid');
		}
		this.change(function() {
			t = $(this);
			l = $('label[for=' + t.attr('id') + ']');
			s = t.parents('fieldset');
			n = $("a[href='#" + s.attr('id') + "']");

			if (f.getVal(t.attr('name'))) {
				t.removeClass('invalid');
				l.removeClass('recheck');

				if ($('.invalid', s).length == 0) {
					n.removeClass('recheck');
				}
			}
			else {
				l.addClass('recheck');
				n.addClass('recheck');
				t.addClass('invalid');
			}
		});

		return this;	
	}
	
	activateRecheck = function() {
		if (!recheckActivated) {
			$('label.required').each(function() {
				e = $('#' + $(this).attr('for'));
				e.recheck();
			});
		}

		recheckActivated = true;
	}
})(jQuery);

$(document).ready(function(){
	$('span.phone-number input, span.number input')
		.focus(function(){$(this.parentNode).addClass('focus')})
		.blur(function(){$(this.parentNode).removeClass('focus')});
	
	function switchToTab(activeTab, direct) {
		if (!activeTab)
			activeTab = '#pribadi';
		if ($(activeTab).hasClass('pane')) {
			$('fieldset.pane').hide();
			
			$(".form-nav li a.active").each(function() {
				t = $(this);
				t.removeClass('active');
				$(t.attr('href')).removeClass('active').hide().trigger('deactivate');
			})

			$(".form-nav li a.active").removeClass('active');

			$(".form-nav li a[href='" + activeTab + "']").addClass("active"); //Add "active" class to selected tab
	
			$("#lastpane").val(activeTab);

			$(activeTab).trigger('activate');
			
			if (direct) {
				$(document).scrollTop(0);
				$(activeTab).addClass('active').show();
			}
			else
				$(activeTab).addClass('active').fadeIn('medium', function() { $('.form-page-nav').focus() }); //Fade in the active ID content

		}
	}

	function getNextTab() {
		return $(".form-nav a.active").parent().closest('li').next().children().first().attr('href');
	}
	function getPrevTab() {
		return	$(".form-nav a.active").parent().closest('li').prev().children().first().attr('href') ? 
				$(".form-nav a.active").parent().closest('li').prev().children().first().attr('href') :
				$(".form-nav a.active").parent().siblings().last().children().first().attr('href');
	}

	$("a[href='#_next']").click(function(e) {
		e.preventDefault();
		switchToTab(getNextTab());
	})

	$("a[href='#_prev']").click(function(e) {
		e.preventDefault();
		switchToTab(getPrevTab());
	})

	//On Click Event
	$(".form-nav li a").click(function(e) {
		var activeTab = $(this).attr("href"); //Find the href attribute value to identify the active tab + content

		switchToTab(activeTab);
		
		if (history.pushState)
			history.pushState(activeTab, $(this).text(), activeTab);

		e.preventDefault();
	});

	if (history.pushState) {
		window.onpopstate = function(e) {
			if (e.state)
				switchToTab(e.state);
			else if (window.location.hash)
				switchToTab(window.location.hash, true);
			else
				switchToTab(last_pane);
		}
	}

	window.location.hash = last_pane;
	$(document).scrollTop(0);
	if (!history.pushState)
		switchToTab(last_pane, true)

	if (firstTime) {
		$('.message').hide();
		$('.global-nav').slideDown('slow', function() {
			$(document).scrollTop(0);
			$('.content').fadeIn('slow', function() {
				$('.message').slideDown()
			})
		});
	}
	else {
		$('.global-nav, .content').fadeIn('fast', function() { $('.message').slideDown() })
	}

	toggleFinalizeButton = function() {
		if ($('#finalize').attr('checked')) {
			$('#finalize-button:parent').fadeIn('fast').focus();
		}
		else
			$('#finalize-button:parent').hide();
	}
	$('#finalize').change(toggleFinalizeButton)

	$('#finalisasi')
		.bind('activate', function() {
			$(this).show();
			$('p.save button').css('visibility', 'hidden');
			$('.form-page-nav.below').hide();

			activateRecheck();

			if ($('.form-nav li a.active').length) {
				// Invalid elements still exist
				$('.recheck', this).show();
				$('.finalize-checkbox', this).hide();
			}
			else {
				$('.recheck', this).hide();
				$('.finalize-checkbox', this).show();
			}

			toggleFinalizeButton();
		})
		.bind('deactivate', function() {
			$('p.save button').css('visibility', 'visible');
			$('.form-page-nav.below').show();
			$('#finalize').removeAttr('checked');
			toggleFinalizeButton();
		});

	$('#foto')
		.bind('activate', function() {
			$('.form-page-nav.below').hide();
		})
		.bind('deactivate', function() {
			$('.form-page-nav.below').show();
		});

	if (incomplete) {
		activateRecheck();
	}

});