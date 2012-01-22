
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
		n = $(".form-nav a[href='#" + s.attr('id') + "']");

		v = f.getVal(this.attr('name'));

		if (!l.length && (!v || v == '0')) {
			l.addClass('recheck');
			n.addClass('recheck');
			this.addClass('invalid');
			if (this.css('border-width') == 0)
				this.parents('span').addClass('invalid');
		}
		if ((!v || v == '0') && (!l.length || l.hasClass('required'))) {
			l.addClass('recheck');
			n.addClass('recheck');
			this.addClass('invalid');
			if (this.css('border-width') == 0)
				this.parents('span').addClass('invalid');
		}
		this.change(function() {
			t = $(this);
			l = $('label[for=' + t.attr('id') + ']');
			s = t.parents('fieldset');
			n = $(".form-nav a[href='#" + s.attr('id') + "']");

			v = f.getVal(t.attr('name'));
			if (v && v != '0') {
				t.removeClass('invalid');
				if (t.css('border-width') == 0)
					t.parents('span').removeClass('invalid');
				l.removeClass('recheck');

				if ($('.invalid', s).length == 0) {
					n.removeClass('recheck');
				}
			}
			else {
				l.addClass('recheck');
				n.addClass('recheck');
				t.addClass('invalid');
				if (t.css('border-width') == 0)
					t.parents('span').addClass('invalid');
			}
		});

		return this;	
	}
	
	activateRecheck = function() {
		if (!recheckActivated) {
			$('label.required').each(function() {
				e = $('#' + $(this).attr('for'));
				if (!e.length) {
					$(this).addClass('recheck');
					$('input[name=' + $(this).attr('for') + ']').change(function() {
						$('label[for=' + $(this).attr('name') + ']').removeClass('recheck');
					});
				}
				else
					e.recheck();
			});
			
			// Program check
			afs = $('#program_afs');
			yes = $('#program_yes');
			$('#program_afs, #program_yes').each(function() {
				if (!afs.attr('checked') && !yes.attr('checked')) {
					afs.parents('tr').children('th.label').addClass('recheck');
					$(".form-nav a[href='#program']").addClass('recheck');
				}
			}).change(function() {
				if (!afs.attr('checked') && !yes.attr('checked')) {
					$(this).parents('tr').children('th.label').addClass('recheck');
					$(".form-nav a[href='#program']").addClass('recheck');
				}
				else {
					afs.parents('tr').children('th.label').removeClass('recheck');
					$(".form-nav a[href='#program']").removeClass('recheck');
				}
			});
			
			// Grades check
			for (i=1; i<=8; i++) {
				if (i != 6) {
					$('#grades_y' + i + 't1_rank').recheck();
					$('#grades_y' + i + 't1_total').recheck();
					$('#grades_y' + i + 't2_rank').recheck();
					$('#grades_y' + i + 't2_total').recheck();
				}
			}
			$('#grades_y10t1_rank').recheck();
			$('#grades_y10t1_total').recheck();
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
		else {
			e.preventDefault();
		}
	});

	if (history.pushState) {
		window.onpopstate = function(e) {
			if (e.state)
				switchToTab(e.state);
			else if (window.location.hash)
				switchToTab(window.location.hash, true);
		}
	}

	if (last_pane) {
		window.onhashchange = function(e) { e.preventDefault(); $(document).scrollTop(0); return false; }
		window.location.hash = last_pane;
		$(document).scrollTop(0);
		$(document).load(function() {
			$(this).scrollTop(0);
		});
		$(document).scrollTop(0);
		if (!history.pushState)
			switchToTab(last_pane, true);
	}
	else if (!window.location.hash) {
		$(document).scrollTop(0);
		window.onhashchange = function(e) { e.preventDefault(); $(document).scrollTop(0); return false; }
		window.location.replace('#pribadi');
		switchToTab('#pribadi', true);
		$(document).load(function() {
			$(this).scrollTop(0);
		});
		$(document).scrollTop(0);
	}

	if (firstTime) {
		$('.message').hide();
		$('.global-header').slideDown('slow', function() {
			$(document).scrollTop(0);
			$('.content').fadeIn('slow', function() {
				$('.message').slideDown()
			})
		});
	}
	else {
		$('.global-header, .content').fadeIn('fast', function() { $('.message').slideDown() })
	}

	toggleFinalizeButton = function(e) {

		if ($('#finalize').attr('checked')) {
			activateRecheck();
			if ($('.form-nav li a.recheck').length) {
				// Invalid elements still exist
				$('.recheck', '#finalisasi').show();
				$('.finalize-checkbox').hide();
				e.preventDefault();
				$('#finalize').removeAttr('checked');
			}
			else {
				$('.recheck', '#finalisasi').hide();
				$('.finalize-checkbox').show();
				$('#finalize-button:parent').fadeIn('fast').focus();
			}
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

			if (!$('.form-nav li a.recheck').length) {
				$('.recheck', '#finalisasi').hide();
				$('.finalize-checkbox').show();
				$('#finalize-button:parent').fadeIn('fast').focus();
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
	
	// Siblings

	$.fn.replaceKey = function(rand) {
		this.attr('name', this.attr('name').replace('[#]', '[' + rand + ']'));
	}
	fac = function() {
		$('td.sibling-name input').each(function() {
			t = $(this);
			if (!t.parent().parent().hasClass('prototype')) {
				if (t.val())
					t.parent().parent().removeClass('engineered').addClass('notempty');
				else
					t.parent().parent().addClass('engineered').removeClass('notempty');
			}
		})

		v = parseInt($(this).val());
		o = $('.siblings-table tbody tr').length - 1;
		if (v > o) {
			d = v - o - 1;
			for (i=0; i<d; i++) {
				cl = $('.prototype').clone().removeClass('prototype');
				rand = Math.ceil(Math.random() * 1000).toString();
				$('input, select', cl).each(function() { $(this).replaceKey(rand); } );
				$('.siblings-table tbody').append(cl);
			}
		}
		if (v <= o) {
			d = o - v + 1;
			for (i=0; i<d; i++) {
				$('tr.engineered').first().detach();
			}
		}
	}
	$('#number_of_children_in_family').click(fac);
	$('#number_of_children_in_family').change(fac);
	$('#number_of_children_in_family').keyup(fac);

	$('#keluarga input[type=number]').attr('min', 1);

	previously_selected_yes = $('#program_yes').attr('checked')
	checkAcc = function() {
		if ($('#in_acceleration_class').is(':checked')) {								
			previously_selected_yes = $('#program_yes').attr('checked');
			$('#program_yes').removeAttr('checked')
			$('.programs-table .yes').hide();
		}
		else {
			if (previously_selected_yes)
				$('#program_yes').attr('checked', 'checked');
			else
				$('#program_yes').removeAttr('checked');

			$('.programs-table .yes').show();
		}
	}
	checkAcc();
	$('#in_acceleration_class').click(checkAcc);
	
	
	$('input[type=file]').change(function() { $(this).parents('form').submit() })
});