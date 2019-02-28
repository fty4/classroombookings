
/**
 * yii is the root module for all Yii JavaScript modules.
 * It implements a mechanism of organizing JavaScript code in modules through the function "yii.initModule()".
 *
 * Each module should be named as "x.y.z", where "x" stands for the root module (for the Yii core code, this is "yii").
 *
 * A module may be structured as follows:
 *
 * ```javascript
 * window.yii.sample = (function($) {
 *     var pub = {
 *         // whether this module is currently active. If false, init() will not be called for this module
 *         // it will also not be called for all its child modules. If this property is undefined, it means true.
 *         isActive: true,
 *         init: function() {
 *             // ... module initialization code goes here ...
 *         },
 *
 *         // ... other public functions and properties go here ...
 *     };
 *
 *     // ... private functions and properties go here ...
 *
 *     return pub;
 * })(window.jQuery);
 * ```
 *
 * Using this structure, you can define public and private functions/properties for a module.
 * Private functions/properties are only visible within the module, while public functions/properties
 * may be accessed outside of the module. For example, you can access "yii.sample.isActive".
 *
 * You must call "yii.initModule()" once for the root module of all your modules.
 */
window.crbs = (function($) {

	var pub = {

		/**
         * The selector for clickable elements that need to support confirmation and form submission.
         */
        clickableSelector: 'a, button, input[type="submit"], input[type="button"], input[type="reset"], input[type="image"]',

        /**
         * The selector for changeable elements that need to support confirmation and form submission.
         */
        changeableSelector: 'select, input, textarea',


		/**
		 * @return string|undefined the CSRF parameter name. Undefined is returned if CSRF validation is not enabled.
		 */
		getCsrfParam: function () {
			return $('meta[name=csrf_token_name]').attr('content');
		},


		/**
		 * @return string|undefined the CSRF token. Undefined is returned if CSRF validation is not enabled.
		 */
		getCsrfToken: function () {
			return $('meta[name=csrf_token_value]').attr('content');
		},


		/**
		 * Displays a confirmation dialog.
		 * The default implementation simply displays a js confirmation dialog.
		 * You may override this by setting `yii.confirm`.
		 * @param message the confirmation message.
		 * @param ok a callback to be called when the user confirms the message
		 * @param cancel a callback to be called when the user cancels the confirmation
		 */
		confirm: function (message, ok, cancel) {
			if (window.confirm(message)) {
				!ok || ok();
			} else {
				!cancel || cancel();
			}
		},


		/**
		 * Handles the action triggered by user.
		 * This method recognizes the `data-method` attribute of the element. If the attribute exists,
		 * the method will submit the form containing this element. If there is no containing form, a form
		 * will be created and submitted using the method given by this attribute value (e.g. "post", "put").
		 * For hyperlinks, the form action will take the value of the "href" attribute of the link.
		 * For other elements, either the containing form action or the current page URL will be used
		 * as the form action URL.
		 *
		 * If the `data-method` attribute is not defined, the `href` attribute (if any) of the element
		 * will be assigned to `window.location`.
		 *
		 * Starting from version 2.0.3, the `data-params` attribute is also recognized when you specify
		 * `data-method`. The value of `data-params` should be a JSON representation of the data (name-value pairs)
		 * that should be submitted as hidden inputs. For example, you may use the following code to generate
		 * such a link:
		 *
		 * ```php
		 * use yii\helpers\Html;
		 * use yii\helpers\Json;
		 *
		 * echo Html::a('submit', ['site/foobar'], [
		 *     'data' => [
		 *         'method' => 'post',
		 *         'params' => [
		 *             'name1' => 'value1',
		 *             'name2' => 'value2',
		 *         ],
		 *     ],
		 * ]);
		 * ```
		 *
		 * @param $e the jQuery representation of the element
		 * @param event Related event
		 */
		handleAction: function ($e, event) {
			var href = $e.attr("href"),
				parts = href.split('?'),
				url = parts[0],
				params = parts.length === 2 ? parts[1].split('&') : [],
				pp,
				inputs = '';

			$form = $("<form/>", { action: url, method: "post" });

			for (var i = 0, n = params.length; i < n; i++) {
				pp = params[i].split('=');
				$form.append($("<input/>", { name: pp[0], value: pp[1], type: "hidden" }));
			}

			var csrfParam = pub.getCsrfParam();
			if (csrfParam) {
				$form.append($('<input/>', { name: csrfParam, value: pub.getCsrfToken(), type: 'hidden' }));
			}

			$form.hide().appendTo("body");
			$form.trigger("submit");

		},


		getQueryParams: function (url) {
			var pos = url.indexOf('?');
			if (pos < 0) {
				return {};
			}

			var pairs = $.grep(url.substring(pos + 1).split('#')[0].split('&'), function (value) {
				return value !== '';
			});
			var params = {};

			for (var i = 0, len = pairs.length; i < len; i++) {
				var pair = pairs[i].split('=');
				var name = decodeURIComponent(pair[0].replace(/\+/g, '%20'));
				var value = decodeURIComponent(pair[1].replace(/\+/g, '%20'));
				if (!name.length) {
					continue;
				}
				if (params[name] === undefined) {
					params[name] = value || '';
				} else {
					if (!$.isArray(params[name])) {
						params[name] = [params[name]];
					}
					params[name].push(value || '');
				}
			}

			return params;
		},


		initModule: function (module) {
			if (module.isActive !== undefined && !module.isActive) {
				return;
			}
			if ($.isFunction(module.init)) {
				module.init();
			}
			$.each(module, function() {
				if ($.isPlainObject(this)) {
					pub.initModule(this);
				}
			});
		},

		init: function() {
			// initCsrfHandler();
			initDataMethods();
		},

		/**
		 * Returns the URL of the current page without params and trailing slash. Separated and made public for testing.
		 * @returns {string}
		 */
		getBaseCurrentUrl: function () {
			return window.location.protocol + '//' + window.location.host;
		},

		/**
		 * Returns the URL of the current page. Used for testing, you can always call `window.location.href` manually
		 * instead.
		 * @returns {string}
		 */
		getCurrentUrl: function () {
			return window.location.href;
		}

	};

	/*function initCsrfHandler() {
		// automatically send CSRF token for all AJAX requests
		$.ajaxPrefilter(function (options, originalOptions, xhr) {
			if (!options.crossDomain && pub.getCsrfParam()) {
				xhr.setRequestHeader('X-CSRF-Token', pub.getCsrfToken());
			}
		});
		pub.refreshCsrfToken();
	}*/


	function initDataMethods() {
		var handler = function (event) {
			console.log("handle");
			var $this = $(this),
				method = $this.data('method'),
				message = $this.data('confirm');

			if (method === undefined && message === undefined) {
				return true;
			}

			if (message !== undefined) {
				$.proxy(pub.confirm, this)(message, function() {
					pub.handleAction($this, event);
				});
			} else {
				pub.handleAction($this, event);
			}
			event.stopImmediatePropagation();
			return false;
		};

		// handle data-confirm and data-method for clickable and changeable elements
		$(document)
			.on('click', pub.clickableSelector, handler)
			.on('change', pub.changeableSelector, handler);
	}


	/**
	 * Returns absolute URL based on the given URL
	 * @param {string} url Initial URL
	 * @returns {string}
	 */
	function getAbsoluteUrl(url) {
		return url.charAt(0) === '/' ? pub.getBaseCurrentUrl() + url : url;
	}


	return pub;


})(window.jQuery);

window.jQuery(function() {
	window.crbs.initModule(window.crbs);
});
