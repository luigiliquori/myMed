/**
 * @author Bastien BLANCHARD
 */
(function($)
{
	jQuery.fn.mobilePopup = function (mainwindow)
	{
		var popupStack	= new Array(mainwindow);
		return this.each(function()
		{
			this.open	= function()
			{
				if(popupStack[popupStack.length-1] == this)
					return;
				$(this).addClass('mobile_open');
				popupStack.push(this);
				function waitForClose()
				{
					$(popupStack[popupStack.length-2]).removeClass('mobile_open');
					$(this).unbind("transitionend", waitForClose);
					$(this).unbind("webkitTransitionEnd", waitForClose);
					$(this).unbind("oTransitionEnd", waitForClose);
					$(this).unbind("transitionEnd", waitForClose);
				}
				$(this).bind("transitionend", waitForClose);
				$(this).bind("webkitTransitionEnd", waitForClose);
				$(this).bind("oTransitionEnd", waitForClose);
				$(this).bind("transitionEnd", waitForClose);
				window.scrollTo(0, 1);
			};
			this.close	= function()
			{
				if(!$.inArray(this, popupStack))
					return;
				var elem=popupStack.pop();
				$(elem).removeClass('mobile_open');
				while(elem!=this){elem=popupStack.pop()};
				$(popupStack[popupStack.length-1]).addClass('mobile_open');
				window.scrollTo(0, 1);
			};
		});
	}
})(jQuery);
