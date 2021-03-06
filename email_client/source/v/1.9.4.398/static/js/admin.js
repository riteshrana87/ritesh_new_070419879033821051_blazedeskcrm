/* RainLoop Webmail (c) RainLoop Team | Licensed under RainLoop Software License */
/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId])
/******/ 			return installedModules[moduleId].exports;
/******/
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			exports: {},
/******/ 			id: moduleId,
/******/ 			loaded: false
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.loaded = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "source/v/0.0.0/static/js/";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/*!***********************!*\
  !*** ./dev/admin.jsx ***!
  \***********************/
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	var _bootstrap = __webpack_require__(/*! bootstrap */ 79);

	var _bootstrap2 = _interopRequireDefault(_bootstrap);

	var _Admin = __webpack_require__(/*! App/Admin */ 20);

	var _Admin2 = _interopRequireDefault(_Admin);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	(0, _bootstrap2.default)(_Admin2.default);

/***/ },
/* 1 */
/*!*****************************!*\
  !*** ./dev/Common/Utils.js ***!
  \*****************************/
/***/ function(module, exports, __webpack_require__) {

	
	(function () {

		'use strict';

		var
			oEncryptObject = null,

			Utils = {},

			window = __webpack_require__(/*! window */ 13),
			_ = __webpack_require__(/*! _ */ 3),
			$ = __webpack_require__(/*! $ */ 14),
			ko = __webpack_require__(/*! ko */ 2),
			Autolinker = __webpack_require__(/*! Autolinker */ 80),
			JSEncrypt = __webpack_require__(/*! JSEncrypt */ 81),

			Mime = __webpack_require__(/*! Common/Mime */ 68),

			Enums = __webpack_require__(/*! Common/Enums */ 4),
			Globals = __webpack_require__(/*! Common/Globals */ 8)
		;

		Utils.trim = $.trim;
		Utils.inArray = $.inArray;
		Utils.isArray = _.isArray;
		Utils.isObject = _.isObject;
		Utils.isFunc = _.isFunction;
		Utils.isUnd = _.isUndefined;
		Utils.isNull = _.isNull;
		Utils.emptyFunction = Utils.noop = function () {};

		/**
		 * @param {*} oValue
		 * @return {boolean}
		 */
		Utils.isNormal = function (oValue)
		{
			return !Utils.isUnd(oValue) && !Utils.isNull(oValue);
		};

		Utils.windowResize = _.debounce(function (iTimeout) {
			if (Utils.isUnd(iTimeout))
			{
				Globals.$win.resize();
			}
			else
			{
				window.setTimeout(function () {
					Globals.$win.resize();
				}, iTimeout);
			}
		}, 50);

		Utils.windowResizeCallback = function () {
			Utils.windowResize();
		};

		/**
		 * @param {(string|number)} mValue
		 * @param {boolean=} bIncludeZero
		 * @return {boolean}
		 */
		Utils.isPosNumeric = function (mValue, bIncludeZero)
		{
			return Utils.isNormal(mValue) ?
				((Utils.isUnd(bIncludeZero) ? true : !!bIncludeZero) ?
					(/^[0-9]*$/).test(mValue.toString()) :
					(/^[1-9]+[0-9]*$/).test(mValue.toString())) :
				false;
		};

		/**
		 * @param {*} iValue
		 * @param {number=} iDefault = 0
		 * @return {number}
		 */
		Utils.pInt = function (iValue, iDefault)
		{
			var iResult = Utils.isNormal(iValue) && '' !== iValue ? window.parseInt(iValue, 10) : (iDefault || 0);
			return window.isNaN(iResult) ? (iDefault || 0) : iResult;
		};

		/**
		 * @param {*} mValue
		 * @return {string}
		 */
		Utils.pString = function (mValue)
		{
			return Utils.isNormal(mValue) ? '' + mValue : '';
		};

		/**
		 * @param {*} mValue
		 * @return {boolean}
		 */
		Utils.pBool = function (mValue)
		{
			return !!mValue;
		};

		/**
		 * @param {string} sComponent
		 * @return {string}
		 */
		Utils.encodeURIComponent = function (sComponent)
		{
			return window.encodeURIComponent(sComponent);
		};

		/**
		 * @param {*} aValue
		 * @return {boolean}
		 */
		Utils.isNonEmptyArray = function (aValue)
		{
			return Utils.isArray(aValue) && 0 < aValue.length;
		};

		/**
		 * @param {string} sQueryString
		 * @return {Object}
		 */
		Utils.simpleQueryParser = function (sQueryString)
		{
			var
				oParams = {},
				aQueries = [],
				aTemp = [],
				iIndex = 0,
				iLen = 0
			;

			aQueries = sQueryString.split('&');
			for (iIndex = 0, iLen = aQueries.length; iIndex < iLen; iIndex++)
			{
				aTemp = aQueries[iIndex].split('=');
				oParams[window.decodeURIComponent(aTemp[0])] = window.decodeURIComponent(aTemp[1]);
			}

			return oParams;
		};

		/**
		 * @param {string} sMailToUrl
		 * @param {Function} PopupComposeVoreModel
		 * @return {boolean}
		 */
		Utils.mailToHelper = function (sMailToUrl, PopupComposeVoreModel)
		{
			if (sMailToUrl && 'mailto:' === sMailToUrl.toString().substr(0, 7).toLowerCase())
			{
				if (!PopupComposeVoreModel)
				{
					return true;
				}

				sMailToUrl = sMailToUrl.toString().substr(7);

				var
					aTo = [],
					aCc = null,
					aBcc = null,
					oParams = {},
					EmailModel = __webpack_require__(/*! Model/Email */ 29),
					sEmail = sMailToUrl.replace(/\?.+$/, ''),
					sQueryString = sMailToUrl.replace(/^[^\?]*\?/, ''),
					fParseEmailLine = function (sLine) {
						return sLine ? _.compact(_.map(window.decodeURIComponent(sLine).split(/[,]/), function (sItem) {
							var oEmailModel = new EmailModel();
							oEmailModel.mailsoParse(sItem);
							return '' !== oEmailModel.email ? oEmailModel : null;
						})) : null;
					}
				;

				aTo = fParseEmailLine(sEmail);

				oParams = Utils.simpleQueryParser(sQueryString);

				if (!Utils.isUnd(oParams.cc))
				{
					aCc = fParseEmailLine(window.decodeURIComponent(oParams.cc));
				}

				if (!Utils.isUnd(oParams.bcc))
				{
					aBcc = fParseEmailLine(window.decodeURIComponent(oParams.bcc));
				}

				__webpack_require__(/*! Knoin/Knoin */ 5).showScreenPopup(PopupComposeVoreModel, [Enums.ComposeType.Empty, null,
					aTo, aCc, aBcc,
					Utils.isUnd(oParams.subject) ? null :
						Utils.pString(window.decodeURIComponent(oParams.subject)),
					Utils.isUnd(oParams.body) ? null :
						Utils.plainToHtml(Utils.pString(window.decodeURIComponent(oParams.body)))
				]);

				return true;
			}

			return false;
		};

		/**
		 * @param {string} sPublicKey
		 * @return {JSEncrypt}
		 */
		Utils.rsaObject = function (sPublicKey)
		{
			if (JSEncrypt && sPublicKey && (null === oEncryptObject || (oEncryptObject && oEncryptObject.__sPublicKey !== sPublicKey)) &&
				window.crypto && window.crypto.getRandomValues)
			{
				oEncryptObject = new JSEncrypt();
				oEncryptObject.setPublicKey(sPublicKey);
				oEncryptObject.__sPublicKey = sPublicKey;
			}
			else
			{
				oEncryptObject = false;
			}

			return oEncryptObject;
		};

		/**
		 * @param {string} sValue
		 * @param {string} sPublicKey
		 * @return {string}
		 */
		Utils.rsaEncode = function (sValue, sPublicKey)
		{
			if (window.crypto && window.crypto.getRandomValues && sPublicKey)
			{
				var
					sResultValue = false,
					oEncrypt = Utils.rsaObject(sPublicKey)
				;

				if (oEncrypt)
				{
					sResultValue = oEncrypt.encrypt(Utils.fakeMd5() + ':' + sValue + ':' + Utils.fakeMd5());
					if (false !== sResultValue && Utils.isNormal(sResultValue))
					{
						return 'rsa:xxx:' + sResultValue;
					}
				}
			}

			return sValue;
		};

		Utils.rsaEncode.supported = !!(window.crypto && window.crypto.getRandomValues && false && JSEncrypt);

		/**
		 * @param {string} sText
		 * @return {string}
		 */
		Utils.encodeHtml = function (sText)
		{
			return Utils.isNormal(sText) ? _.escape(sText.toString()) : '';
		};

		/**
		 * @param {string} sText
		 * @param {number=} iLen
		 * @return {string}
		 */
		Utils.splitPlainText = function (sText, iLen)
		{
			var
				sPrefix = '',
				sSubText = '',
				sResult = sText,
				iSpacePos = 0,
				iNewLinePos = 0
			;

			iLen = Utils.isUnd(iLen) ? 100 : iLen;

			while (sResult.length > iLen)
			{
				sSubText = sResult.substring(0, iLen);
				iSpacePos = sSubText.lastIndexOf(' ');
				iNewLinePos = sSubText.lastIndexOf('\n');

				if (-1 !== iNewLinePos)
				{
					iSpacePos = iNewLinePos;
				}

				if (-1 === iSpacePos)
				{
					iSpacePos = iLen;
				}

				sPrefix += sSubText.substring(0, iSpacePos) + '\n';
				sResult = sResult.substring(iSpacePos + 1);
			}

			return sPrefix + sResult;
		};

		Utils.timeOutAction = (function () {

			var
				oTimeOuts = {}
			;

			return function (sAction, fFunction, iTimeOut)
			{
				if (Utils.isUnd(oTimeOuts[sAction]))
				{
					oTimeOuts[sAction] = 0;
				}

				window.clearTimeout(oTimeOuts[sAction]);
				oTimeOuts[sAction] = window.setTimeout(fFunction, iTimeOut);
			};
		}());

		Utils.timeOutActionSecond = (function () {

			var
				oTimeOuts = {}
			;

			return function (sAction, fFunction, iTimeOut)
			{
				if (!oTimeOuts[sAction])
				{
					oTimeOuts[sAction] = window.setTimeout(function () {
						fFunction();
						oTimeOuts[sAction] = 0;
					}, iTimeOut);
				}
			};
		}());

		/**
		 * @param {(Object|null|undefined)} oObject
		 * @param {string} sProp
		 * @return {boolean}
		 */
		Utils.hos = function (oObject, sProp)
		{
			return oObject && window.Object && window.Object.hasOwnProperty ? window.Object.hasOwnProperty.call(oObject, sProp) : false;
		};

		/**
		 * @return {boolean}
		 */
		Utils.inFocus = function ()
		{
			if (window.document.activeElement)
			{
				if (Utils.isUnd(window.document.activeElement.__inFocusCache))
				{
					window.document.activeElement.__inFocusCache = $(window.document.activeElement).is('input,textarea,iframe,.cke_editable');
				}

				return !!window.document.activeElement.__inFocusCache;
			}

			return false;
		};

		Utils.removeInFocus = function (force)
		{
			if (window.document && window.document.activeElement && window.document.activeElement.blur)
			{
				var oA = $(window.document.activeElement);
				if (oA.is('input,textarea'))
				{
					window.document.activeElement.blur();
				}
				else if (force)
				{
					try {
						window.document.activeElement.blur();
					} catch (e) {}
				}
			}
		};

		Utils.removeSelection = function ()
		{
			if (window && window.getSelection)
			{
				var oSel = window.getSelection();
				if (oSel && oSel.removeAllRanges)
				{
					oSel.removeAllRanges();
				}
			}
			else if (window.document && window.document.selection && window.document.selection.empty)
			{
				window.document.selection.empty();
			}
		};

		/**
		 * @param {string} sPrefix
		 * @param {string} sSubject
		 * @return {string}
		 */
		Utils.replySubjectAdd = function (sPrefix, sSubject)
		{
			sPrefix = Utils.trim(sPrefix.toUpperCase());
			sSubject = Utils.trim(sSubject.replace(/[\s]+/g, ' '));

			var
				bDrop = false,
				aSubject = [],
				bRe = 'RE' === sPrefix,
				bFwd = 'FWD' === sPrefix,
				bPrefixIsRe = !bFwd
			;

			if ('' !== sSubject)
			{
				_.each(sSubject.split(':'), function (sPart) {
					var sTrimmedPart = Utils.trim(sPart);
					if (!bDrop && (/^(RE|FWD)$/i.test(sTrimmedPart) || /^(RE|FWD)[\[\(][\d]+[\]\)]$/i.test(sTrimmedPart)))
					{
						if (!bRe)
						{
							bRe = !!(/^RE/i.test(sTrimmedPart));
						}

						if (!bFwd)
						{
							bFwd = !!(/^FWD/i.test(sTrimmedPart));
						}
					}
					else
					{
						aSubject.push(sPart);
						bDrop = true;
					}
				});
			}

			if (bPrefixIsRe)
			{
				bRe = false;
			}
			else
			{
				bFwd = false;
			}

			return Utils.trim(
				(bPrefixIsRe ? 'Re: ' : 'Fwd: ') +
				(bRe ? 'Re: ' : '') +
				(bFwd ? 'Fwd: ' : '') +
				Utils.trim(aSubject.join(':'))
			);
		};

		/**
		 * @param {number} iNum
		 * @param {number} iDec
		 * @return {number}
		 */
		Utils.roundNumber = function (iNum, iDec)
		{
			return window.Math.round(iNum * window.Math.pow(10, iDec)) / window.Math.pow(10, iDec);
		};

		/**
		 * @param {(number|string)} iSizeInBytes
		 * @return {string}
		 */
		Utils.friendlySize = function (iSizeInBytes)
		{
			iSizeInBytes = Utils.pInt(iSizeInBytes);

			if (iSizeInBytes >= 1073741824)
			{
				return Utils.roundNumber(iSizeInBytes / 1073741824, 1) + 'GB';
			}
			else if (iSizeInBytes >= 1048576)
			{
				return Utils.roundNumber(iSizeInBytes / 1048576, 1) + 'MB';
			}
			else if (iSizeInBytes >= 1024)
			{
				return Utils.roundNumber(iSizeInBytes / 1024, 0) + 'KB';
			}

			return iSizeInBytes + 'B';
		};

		/**
		 * @param {string} sDesc
		 */
		Utils.log = function (sDesc)
		{
			if (window.console && window.console.log)
			{
				window.console.log(sDesc);
			}
		};

		/**
		 * @param {?} oObject
		 * @param {string} sMethodName
		 * @param {Array=} aParameters
		 * @param {number=} nDelay
		 */
		Utils.delegateRun = function (oObject, sMethodName, aParameters, nDelay)
		{
			if (oObject && oObject[sMethodName])
			{
				nDelay = Utils.pInt(nDelay);
				if (0 >= nDelay)
				{
					oObject[sMethodName].apply(oObject, Utils.isArray(aParameters) ? aParameters : []);
				}
				else
				{
					_.delay(function () {
						oObject[sMethodName].apply(oObject, Utils.isArray(aParameters) ? aParameters : []);
					}, nDelay);
				}
			}
		};

		/**
		 * @param {?} oEvent
		 */
		Utils.kill_CtrlA_CtrlS = function (oEvent)
		{
			oEvent = oEvent || window.event;
			if (oEvent && oEvent.ctrlKey && !oEvent.shiftKey && !oEvent.altKey)
			{
				var
					oSender = oEvent.target || oEvent.srcElement,
					iKey = oEvent.keyCode || oEvent.which
				;

				if (iKey === Enums.EventKeyCode.S)
				{
					oEvent.preventDefault();
					return;
				}
				else if (iKey === Enums.EventKeyCode.A)
				{
					if (oSender && ('true' === '' + oSender.contentEditable ||
						(oSender.tagName && oSender.tagName.match(/INPUT|TEXTAREA/i))))
					{
						return;
					}

					if (window.getSelection)
					{
						window.getSelection().removeAllRanges();
					}
					else if (window.document.selection && window.document.selection.clear)
					{
						window.document.selection.clear();
					}

					oEvent.preventDefault();
				}
			}
		};

		/**
		 * @param {(Object|null|undefined)} oContext
		 * @param {Function} fExecute
		 * @param {(Function|boolean|null)=} fCanExecute
		 * @return {Function}
		 */
		Utils.createCommand = function (oContext, fExecute, fCanExecute)
		{
			var
				fNonEmpty = function () {
					if (fResult && fResult.canExecute && fResult.canExecute())
					{
						fExecute.apply(oContext, Array.prototype.slice.call(arguments));
					}
					return false;
				},
				fResult = fExecute ? fNonEmpty : Utils.emptyFunction
			;

			fResult.enabled = ko.observable(true);

			fCanExecute = Utils.isUnd(fCanExecute) ? true : fCanExecute;
			if (Utils.isFunc(fCanExecute))
			{
				fResult.canExecute = ko.computed(function () {
					return fResult.enabled() && fCanExecute.call(oContext);
				});
			}
			else
			{
				fResult.canExecute = ko.computed(function () {
					return fResult.enabled() && !!fCanExecute;
				});
			}

			return fResult;
		};

		/**
		 * @param {string} sTheme
		 * @return {string}
		 */
		Utils.convertThemeName = _.memoize(function (sTheme)
		{
			if ('@custom' === sTheme.substr(-7))
			{
				sTheme = Utils.trim(sTheme.substring(0, sTheme.length - 7));
			}

			return Utils.trim(sTheme.replace(/[^a-zA-Z0-9]+/g, ' ').replace(/([A-Z])/g, ' $1').replace(/[\s]+/g, ' '));
		});

		/**
		 * @param {string} sName
		 * @return {string}
		 */
		Utils.quoteName = function (sName)
		{
			return sName.replace(/["]/g, '\\"');
		};

		/**
		 * @return {number}
		 */
		Utils.microtime = function ()
		{
			return (new window.Date()).getTime();
		};

		/**
		 * @return {number}
		 */
		Utils.timestamp = function ()
		{
			return window.Math.round(Utils.microtime() / 1000);
		};

		/**
		 *
		 * @param {string} sLanguage
		 * @param {boolean=} bEng = false
		 * @return {string}
		 */
		Utils.convertLangName = function (sLanguage, bEng)
		{
			return __webpack_require__(/*! Common/Translator */ 6).i18n('LANGS_NAMES' + (true === bEng ? '_EN' : '') + '/LANG_' +
				sLanguage.toUpperCase().replace(/[^a-zA-Z0-9]+/g, '_'), null, sLanguage);
		};

		/**
		 * @param {number=} iLen
		 * @return {string}
		 */
		Utils.fakeMd5 = function(iLen)
		{
			var
				sResult = '',
				sLine = '0123456789abcdefghijklmnopqrstuvwxyz'
			;

			iLen = Utils.isUnd(iLen) ? 32 : Utils.pInt(iLen);

			while (sResult.length < iLen)
			{
				sResult += sLine.substr(window.Math.round(window.Math.random() * sLine.length), 1);
			}

			return sResult;
		};

		Utils.draggablePlace = function ()
		{
			return $('<div class="draggablePlace">' +
				'<span class="text"></span>&nbsp;' +
				'<i class="icon-copy icon-white visible-on-ctrl"></i><i class="icon-mail icon-white hidden-on-ctrl"></i></div>').appendTo('#rl-hidden');
		};

		Utils.defautOptionsAfterRender = function (oDomOption, oItem)
		{
			if (oItem && !Utils.isUnd(oItem.disabled) && oDomOption)
			{
				$(oDomOption)
					.toggleClass('disabled', oItem.disabled)
					.prop('disabled', oItem.disabled)
				;
			}
		};

		/**
		 * @param {Object} oViewModel
		 * @param {string} sTemplateID
		 * @param {string} sTitle
		 * @param {Function=} fCallback
		 */
		Utils.windowPopupKnockout = function (oViewModel, sTemplateID, sTitle, fCallback)
		{
			var
				oScript = null,
				oWin = window.open(''),
				sFunc = '__OpenerApplyBindingsUid' + Utils.fakeMd5() + '__',
				oTemplate = $('#' + sTemplateID)
			;

			window[sFunc] = function () {

				if (oWin && oWin.document.body && oTemplate && oTemplate[0])
				{
					var oBody = $(oWin.document.body);

					$('#rl-content', oBody).html(oTemplate.html());
					$('html', oWin.document).addClass('external ' + $('html').attr('class'));

					__webpack_require__(/*! Common/Translator */ 6).i18nToNodes(oBody);

					if (oViewModel && $('#rl-content', oBody)[0])
					{
						ko.applyBindings(oViewModel, $('#rl-content', oBody)[0]);
					}

					window[sFunc] = null;

					fCallback(oWin);
				}
			};

			oWin.document.open();
			oWin.document.write('<html><head>' +
	'<meta charset="utf-8" />' +
	'<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />' +
	'<meta name="viewport" content="user-scalable=no" />' +
	'<meta name="apple-mobile-web-app-capable" content="yes" />' +
	'<meta name="robots" content="noindex, nofollow, noodp" />' +
	'<title>' + Utils.encodeHtml(sTitle) + '</title>' +
	'</head><body><div id="rl-content"></div></body></html>');
			oWin.document.close();

			oScript = oWin.document.createElement('script');
			oScript.type = 'text/javascript';
			oScript.innerHTML = 'if(window&&window.opener&&window.opener[\'' + sFunc + '\']){window.opener[\'' + sFunc + '\']();window.opener[\'' + sFunc + '\']=null}';
			oWin.document.getElementsByTagName('head')[0].appendChild(oScript);
		};

		/**
		 * @param {Function} fCallback
		 * @param {?} koTrigger
		 * @param {?} oContext = null
		 * @param {number=} iTimer = 1000
		 * @return {Function}
		 */
		Utils.settingsSaveHelperFunction = function (fCallback, koTrigger, oContext, iTimer)
		{
			oContext = oContext || null;
			iTimer = Utils.isUnd(iTimer) ? 1000 : Utils.pInt(iTimer);

			return function (sType, mData, bCached, sRequestAction, oRequestParameters) {
				koTrigger.call(oContext, mData && mData['Result'] ? Enums.SaveSettingsStep.TrueResult : Enums.SaveSettingsStep.FalseResult);
				if (fCallback)
				{
					fCallback.call(oContext, sType, mData, bCached, sRequestAction, oRequestParameters);
				}
				_.delay(function () {
					koTrigger.call(oContext, Enums.SaveSettingsStep.Idle);
				}, iTimer);
			};
		};

		Utils.settingsSaveHelperSimpleFunction = function (koTrigger, oContext)
		{
			return Utils.settingsSaveHelperFunction(null, koTrigger, oContext, 1000);
		};

		Utils.settingsSaveHelperSubscribeFunction = function (oRemote, sSettingName, sType, fTriggerFunction)
		{
			return function (mValue) {

				if (oRemote)
				{
					switch (sType)
					{
						default:
							mValue = Utils.pString(mValue);
							break;
						case 'bool':
						case 'boolean':
							mValue = mValue ? '1' : '0';
							break;
						case 'int':
						case 'integer':
						case 'number':
							mValue = Utils.pInt(mValue);
							break;
						case 'trim':
							mValue = Utils.trim(mValue);
							break;
					}

					var oData = {};
					oData[sSettingName] = mValue;

					if (oRemote.saveAdminConfig)
					{
						oRemote.saveAdminConfig(fTriggerFunction || null, oData);
					}
					else if (oRemote.saveSettings)
					{
						oRemote.saveSettings(fTriggerFunction || null, oData);
					}
				}
			};
		};

		/**
		 * @param {string} sHtml
		 * @return {string}
		 */
		Utils.htmlToPlain = function (sHtml)
		{
			var
				iPos = 0,
				iP1 = 0,
				iP2 = 0,
				iP3 = 0,
				iLimit = 0,

				sText = '',

				convertBlockquote = function (sText) {
					sText = Utils.trim(sText);
					sText = '> ' + sText.replace(/\n/gm, '\n> ');
					return sText.replace(/(^|\n)([> ]+)/gm, function () {
						return (arguments && 2 < arguments.length) ? arguments[1] + $.trim(arguments[2].replace(/[\s]/g, '')) + ' ' : '';
					});
				},

				convertDivs = function () {
					if (arguments && 1 < arguments.length)
					{
						var sText = $.trim(arguments[1]);
						if (0 < sText.length)
						{
							sText = sText.replace(/<div[^>]*>([\s\S\r\n]*)<\/div>/gmi, convertDivs);
							sText = '\n' + $.trim(sText) + '\n';
						}

						return sText;
					}

					return '';
				},

				convertPre = function () {
					return (arguments && 1 < arguments.length) ?
						arguments[1].toString()
							.replace(/[\n]/gm, '<br />')
							.replace(/[\r]/gm, '')
						: '';
				},

				fixAttibuteValue = function () {
					return (arguments && 1 < arguments.length) ?
						'' + arguments[1] + _.escape(arguments[2]) : '';
				},

				convertLinks = function () {
					return (arguments && 1 < arguments.length) ? $.trim(arguments[1]) : '';
				}
			;

			sText = sHtml
				.replace(/\u0002([\s\S]*)\u0002/gm, '\u200C$1\u200C')
				.replace(/<p[^>]*><\/p>/gi, '')
				.replace(/<pre[^>]*>([\s\S\r\n\t]*)<\/pre>/gmi, convertPre)
				.replace(/[\s]+/gm, ' ')
				.replace(/((?:href|data)\s?=\s?)("[^"]+?"|'[^']+?')/gmi, fixAttibuteValue)
				.replace(/<br[^>]*>/gmi, '\n')
				.replace(/<\/h[\d]>/gi, '\n')
				.replace(/<\/p>/gi, '\n\n')
				.replace(/<ul[^>]*>/gmi, '\n')
				.replace(/<\/ul>/gi, '\n')
				.replace(/<li[^>]*>/gmi, ' * ')
				.replace(/<\/li>/gi, '\n')
				.replace(/<\/td>/gi, '\n')
				.replace(/<\/tr>/gi, '\n')
				.replace(/<hr[^>]*>/gmi, '\n_______________________________\n\n')
				.replace(/<div[^>]*>([\s\S\r\n]*)<\/div>/gmi, convertDivs)
				.replace(/<blockquote[^>]*>/gmi, '\n__bq__start__\n')
				.replace(/<\/blockquote>/gmi, '\n__bq__end__\n')
				.replace(/<a [^>]*>([\s\S\r\n]*?)<\/a>/gmi, convertLinks)
				.replace(/<\/div>/gi, '\n')
				.replace(/&nbsp;/gi, ' ')
				.replace(/&quot;/gi, '"')
				.replace(/<[^>]*>/gm, '')
			;

			sText = Globals.$div.html(sText).text();

			sText = sText
				.replace(/\n[ \t]+/gm, '\n')
				.replace(/[\n]{3,}/gm, '\n\n')
				.replace(/&gt;/gi, '>')
				.replace(/&lt;/gi, '<')
				.replace(/&amp;/gi, '&')
			;

			sText = Utils.splitPlainText(Utils.trim(sText));

			iPos = 0;
			iLimit = 800;

			while (0 < iLimit)
			{
				iLimit--;
				iP1 = sText.indexOf('__bq__start__', iPos);
				if (-1 < iP1)
				{
					iP2 = sText.indexOf('__bq__start__', iP1 + 5);
					iP3 = sText.indexOf('__bq__end__', iP1 + 5);

					if ((-1 === iP2 || iP3 < iP2) && iP1 < iP3)
					{
						sText = sText.substring(0, iP1) +
							convertBlockquote(sText.substring(iP1 + 13, iP3)) +
							sText.substring(iP3 + 11);

						iPos = 0;
					}
					else if (-1 < iP2 && iP2 < iP3)
					{
						iPos = iP2 - 1;
					}
					else
					{
						iPos = 0;
					}
				}
				else
				{
					break;
				}
			}

			sText = sText
				.replace(/__bq__start__/gm, '')
				.replace(/__bq__end__/gm, '')
			;

			return sText;
		};

		/**
		 * @param {string} sPlain
		 * @param {boolean} bFindEmailAndLinks = false
		 * @return {string}
		 */
		Utils.plainToHtml = function (sPlain, bFindEmailAndLinks)
		{
			sPlain = sPlain.toString().replace(/\r/g, '');

			bFindEmailAndLinks = Utils.isUnd(bFindEmailAndLinks) ? false : !!bFindEmailAndLinks;

			var
				bIn = false,
				bDo = true,
				bStart = true,
				aNextText = [],
				sLine = '',
				iIndex = 0,
				aText = sPlain.split("\n")
			;

			do
			{
				bDo = false;
				aNextText = [];
				for (iIndex = 0; iIndex < aText.length; iIndex++)
				{
					sLine = aText[iIndex];
					bStart = '>' === sLine.substr(0, 1);
					if (bStart && !bIn)
					{
						bDo = true;
						bIn = true;
						aNextText.push('~~~blockquote~~~');
						aNextText.push(sLine.substr(1));
					}
					else if (!bStart && bIn)
					{
						if ('' !== sLine)
						{
							bIn = false;
							aNextText.push('~~~/blockquote~~~');
							aNextText.push(sLine);
						}
						else
						{
							aNextText.push(sLine);
						}
					}
					else if (bStart && bIn)
					{
						aNextText.push(sLine.substr(1));
					}
					else
					{
						aNextText.push(sLine);
					}
				}

				if (bIn)
				{
					bIn = false;
					aNextText.push('~~~/blockquote~~~');
				}

				aText = aNextText;
			}
			while (bDo);

			sPlain = aText.join("\n");

			sPlain = sPlain
	//			.replace(/~~~\/blockquote~~~\n~~~blockquote~~~/g, '\n')
				.replace(/&/g, '&amp;')
				.replace(/>/g, '&gt;').replace(/</g, '&lt;')
				.replace(/~~~blockquote~~~[\s]*/g, '<blockquote>')
				.replace(/[\s]*~~~\/blockquote~~~/g, '</blockquote>')
				.replace(/\u200C([\s\S]*)\u200C/g, '\u0002$1\u0002')
				.replace(/\n/g, '<br />')
			;

			return bFindEmailAndLinks ? Utils.findEmailAndLinks(sPlain) : sPlain;
		};

		window.rainloop_Utils_htmlToPlain = Utils.htmlToPlain;
		window.rainloop_Utils_plainToHtml = Utils.plainToHtml;

		/**
		 * @param {string} sHtml
		 * @return {string}
		 */
		Utils.findEmailAndLinks = function (sHtml)
		{
	//		return sHtml;
			sHtml = Autolinker.link(sHtml, {
				'newWindow': true,
				'stripPrefix': false,
				'urls': true,
				'email': true,
				'twitter': false,
				'replaceFn': function (autolinker, match) {
					return !(autolinker && match && 'url' === match.getType() && match.matchedText && 0 !== match.matchedText.indexOf('http'));
				}
			});

			return sHtml;
		};

		/**
		 * @param {string} sUrl
		 * @param {number} iValue
		 * @param {Function} fCallback
		 */
		Utils.resizeAndCrop = function (sUrl, iValue, fCallback)
		{
			var oTempImg = new window.Image();
			oTempImg.onload = function() {

				var
					aDiff = [0, 0],
					oCanvas = window.document.createElement('canvas'),
					oCtx = oCanvas.getContext('2d')
				;

				oCanvas.width = iValue;
				oCanvas.height = iValue;

				if (this.width > this.height)
				{
					aDiff = [this.width - this.height, 0];
				}
				else
				{
					aDiff = [0, this.height - this.width];
				}

				oCtx.fillStyle = '#fff';
				oCtx.fillRect(0, 0, iValue, iValue);
				oCtx.drawImage(this, aDiff[0] / 2, aDiff[1] / 2, this.width - aDiff[0], this.height - aDiff[1], 0, 0, iValue, iValue);

				fCallback(oCanvas.toDataURL('image/jpeg'));
			};

			oTempImg.src = sUrl;
		};

		/**
		 * @param {Array} aSystem
		 * @param {Array} aList
		 * @param {Array=} aDisabled
		 * @param {Array=} aHeaderLines
		 * @param {?number=} iUnDeep
		 * @param {Function=} fDisableCallback
		 * @param {Function=} fVisibleCallback
		 * @param {Function=} fRenameCallback
		 * @param {boolean=} bSystem
		 * @param {boolean=} bBuildUnvisible
		 * @return {Array}
		 */
		Utils.folderListOptionsBuilder = function (aSystem, aList, aDisabled, aHeaderLines,
			iUnDeep, fDisableCallback, fVisibleCallback, fRenameCallback, bSystem, bBuildUnvisible)
		{
			var
				/**
				 * @type {?FolderModel}
				 */
				oItem = null,
				bSep = false,
				iIndex = 0,
				iLen = 0,
				sDeepPrefix = '\u00A0\u00A0\u00A0',
				aResult = []
			;

			bBuildUnvisible = Utils.isUnd(bBuildUnvisible) ? false : !!bBuildUnvisible;
			bSystem = !Utils.isNormal(bSystem) ? 0 < aSystem.length : bSystem;
			iUnDeep = !Utils.isNormal(iUnDeep) ? 0 : iUnDeep;
			fDisableCallback = Utils.isNormal(fDisableCallback) ? fDisableCallback : null;
			fVisibleCallback = Utils.isNormal(fVisibleCallback) ? fVisibleCallback : null;
			fRenameCallback = Utils.isNormal(fRenameCallback) ? fRenameCallback : null;

			if (!Utils.isArray(aDisabled))
			{
				aDisabled = [];
			}

			if (!Utils.isArray(aHeaderLines))
			{
				aHeaderLines = [];
			}

			for (iIndex = 0, iLen = aHeaderLines.length; iIndex < iLen; iIndex++)
			{
				aResult.push({
					'id': aHeaderLines[iIndex][0],
					'name': aHeaderLines[iIndex][1],
					'system': false,
					'seporator': false,
					'disabled': false
				});
			}

			bSep = true;
			for (iIndex = 0, iLen = aSystem.length; iIndex < iLen; iIndex++)
			{
				oItem = aSystem[iIndex];
				if (fVisibleCallback ? fVisibleCallback.call(null, oItem) : true)
				{
					if (bSep && 0 < aResult.length)
					{
						aResult.push({
							'id': '---',
							'name': '---',
							'system': false,
							'seporator': true,
							'disabled': true
						});
					}

					bSep = false;
					aResult.push({
						'id': oItem.fullNameRaw,
						'name': fRenameCallback ? fRenameCallback.call(null, oItem) : oItem.name(),
						'system': true,
						'seporator': false,
						'disabled': !oItem.selectable || -1 < Utils.inArray(oItem.fullNameRaw, aDisabled) ||
							(fDisableCallback ? fDisableCallback.call(null, oItem) : false)
					});
				}
			}

			bSep = true;
			for (iIndex = 0, iLen = aList.length; iIndex < iLen; iIndex++)
			{
				oItem = aList[iIndex];
	//			if (oItem.subScribed() || !oItem.existen || bBuildUnvisible)
				if ((oItem.subScribed() || !oItem.existen || bBuildUnvisible) && (oItem.selectable || oItem.hasSubScribedSubfolders()))
				{
					if (fVisibleCallback ? fVisibleCallback.call(null, oItem) : true)
					{
						if (Enums.FolderType.User === oItem.type() || !bSystem || oItem.hasSubScribedSubfolders())
						{
							if (bSep && 0 < aResult.length)
							{
								aResult.push({
									'id': '---',
									'name': '---',
									'system': false,
									'seporator': true,
									'disabled': true
								});
							}

							bSep = false;
							aResult.push({
								'id': oItem.fullNameRaw,
								'name': (new window.Array(oItem.deep + 1 - iUnDeep)).join(sDeepPrefix) +
									(fRenameCallback ? fRenameCallback.call(null, oItem) : oItem.name()),
								'system': false,
								'seporator': false,
								'disabled': !oItem.selectable || -1 < Utils.inArray(oItem.fullNameRaw, aDisabled) ||
									(fDisableCallback ? fDisableCallback.call(null, oItem) : false)
							});
						}
					}
				}

				if (oItem.subScribed() && 0 < oItem.subFolders().length)
				{
					aResult = aResult.concat(Utils.folderListOptionsBuilder([], oItem.subFolders(), aDisabled, [],
						iUnDeep, fDisableCallback, fVisibleCallback, fRenameCallback, bSystem, bBuildUnvisible));
				}
			}

			return aResult;
		};

		Utils.computedPagenatorHelper = function (koCurrentPage, koPageCount)
		{
			return function() {

				var
					iPrev = 0,
					iNext = 0,
					iLimit = 2,
					aResult = [],
					iCurrentPage = koCurrentPage(),
					iPageCount = koPageCount(),

					/**
					 * @param {number} iIndex
					 * @param {boolean=} bPush = true
					 * @param {string=} sCustomName = ''
					 */
					fAdd = function (iIndex, bPush, sCustomName) {

						var oData = {
							'current': iIndex === iCurrentPage,
							'name': Utils.isUnd(sCustomName) ? iIndex.toString() : sCustomName.toString(),
							'custom': Utils.isUnd(sCustomName) ? false : true,
							'title': Utils.isUnd(sCustomName) ? '' : iIndex.toString(),
							'value': iIndex.toString()
						};

						if (Utils.isUnd(bPush) ? true : !!bPush)
						{
							aResult.push(oData);
						}
						else
						{
							aResult.unshift(oData);
						}
					}
				;

				if (1 < iPageCount || (0 < iPageCount && iPageCount < iCurrentPage))
		//		if (0 < iPageCount && 0 < iCurrentPage)
				{
					if (iPageCount < iCurrentPage)
					{
						fAdd(iPageCount);
						iPrev = iPageCount;
						iNext = iPageCount;
					}
					else
					{
						if (3 >= iCurrentPage || iPageCount - 2 <= iCurrentPage)
						{
							iLimit += 2;
						}

						fAdd(iCurrentPage);
						iPrev = iCurrentPage;
						iNext = iCurrentPage;
					}

					while (0 < iLimit) {

						iPrev -= 1;
						iNext += 1;

						if (0 < iPrev)
						{
							fAdd(iPrev, false);
							iLimit--;
						}

						if (iPageCount >= iNext)
						{
							fAdd(iNext, true);
							iLimit--;
						}
						else if (0 >= iPrev)
						{
							break;
						}
					}

					if (3 === iPrev)
					{
						fAdd(2, false);
					}
					else if (3 < iPrev)
					{
						fAdd(window.Math.round((iPrev - 1) / 2), false, '...');
					}

					if (iPageCount - 2 === iNext)
					{
						fAdd(iPageCount - 1, true);
					}
					else if (iPageCount - 2 > iNext)
					{
						fAdd(window.Math.round((iPageCount + iNext) / 2), true, '...');
					}

					// first and last
					if (1 < iPrev)
					{
						fAdd(1, false);
					}

					if (iPageCount > iNext)
					{
						fAdd(iPageCount, true);
					}
				}

				return aResult;
			};
		};

		Utils.selectElement = function (element)
		{
			var sel, range;
			if (window.getSelection)
			{
				sel = window.getSelection();
				sel.removeAllRanges();
				range = window.document.createRange();
				range.selectNodeContents(element);
				sel.addRange(range);
			}
			else if (window.document.selection)
			{
				range = window.document.body.createTextRange();
				range.moveToElementText(element);
				range.select();
			}
		};

		Utils.detectDropdownVisibility = _.debounce(function () {
			Globals.dropdownVisibility(!!_.find(Globals.aBootstrapDropdowns, function (oItem) {
				return oItem.hasClass('open');
			}));
		}, 50);

		/**
		 * @param {boolean=} bDelay = false
		 */
		Utils.triggerAutocompleteInputChange = function (bDelay) {

			var fFunc = function () {
				$('.checkAutocomplete').trigger('change');
			};

			if (Utils.isUnd(bDelay) ? false : !!bDelay)
			{
				_.delay(fFunc, 100);
			}
			else
			{
				fFunc();
			}
		};

		/**
		 * @param {Object} oParams
		 */
		Utils.setHeadViewport = function (oParams)
		{
			var aContent = [];
			_.each(oParams, function (sKey, sValue) {
				aContent.push('' + sKey + '=' + sValue);
			});

			$('#rl-head-viewport').attr('content', aContent.join(', '));
		};

		/**
		 * @param {string} sFileName
		 * @return {string}
		 */
		Utils.getFileExtension = function (sFileName)
		{
			sFileName = Utils.trim(sFileName).toLowerCase();

			var sResult = sFileName.split('.').pop();
			return (sResult === sFileName) ? '' : sResult;
		};

		/**
		 * @param {string} sFileName
		 * @return {string}
		 */
		Utils.mimeContentType = function (sFileName)
		{
			var
				sExt = '',
				sResult = 'application/octet-stream'
			;

			sFileName = Utils.trim(sFileName).toLowerCase();

			if ('winmail.dat' === sFileName)
			{
				return 'application/ms-tnef';
			}

			sExt = Utils.getFileExtension(sFileName);
			if (sExt && 0 < sExt.length && !Utils.isUnd(Mime[sExt]))
			{
				sResult = Mime[sExt];
			}

			return sResult;
		};

		/**
		 * @param {mixed} mPropOrValue
		 * @param {mixed} mValue
		 */
		Utils.disposeOne = function (mPropOrValue, mValue)
		{
			var mDisposable = mValue || mPropOrValue;
	        if (mDisposable && typeof mDisposable.dispose === 'function')
			{
	            mDisposable.dispose();
	        }
		};

		/**
		 * @param {Object} oObject
		 */
		Utils.disposeObject = function (oObject)
		{
			if (oObject)
			{
				if (Utils.isArray(oObject.disposables))
				{
					_.each(oObject.disposables, Utils.disposeOne);
				}

				ko.utils.objectForEach(oObject, Utils.disposeOne);
			}
		};

		/**
		 * @param {Object|Array} mObjectOrObjects
		 */
		Utils.delegateRunOnDestroy = function (mObjectOrObjects)
		{
			if (mObjectOrObjects)
			{
				if (Utils.isArray(mObjectOrObjects))
				{
					_.each(mObjectOrObjects, function (oItem) {
						Utils.delegateRunOnDestroy(oItem);
					});
				}
				else if (mObjectOrObjects && mObjectOrObjects.onDestroy)
				{
					mObjectOrObjects.onDestroy();
				}
			}
		};

		Utils.__themeTimer = 0;
		Utils.__themeAjax = null;

		Utils.changeTheme = function (sValue, themeTrigger)
		{
			var
				oThemeLink = $('#rlThemeLink'),
				oThemeStyle = $('#rlThemeStyle'),
				sUrl = oThemeLink.attr('href')
			;

			if (!sUrl)
			{
				sUrl = oThemeStyle.attr('data-href');
			}

			if (sUrl)
			{
				sUrl = sUrl.toString().replace(/\/-\/[^\/]+\/\-\//, '/-/' + sValue + '/-/');
				sUrl = sUrl.toString().replace(/\/Css\/[^\/]+\/User\//, '/Css/0/User/');
				sUrl = sUrl.toString().replace(/\/Hash\/[^\/]+\//, '/Hash/-/');

				if ('Json/' !== sUrl.substring(sUrl.length - 5, sUrl.length))
				{
					sUrl += 'Json/';
				}

				window.clearTimeout(Utils.__themeTimer);
				themeTrigger(Enums.SaveSettingsStep.Animate);

				if (Utils.__themeAjax && Utils.__themeAjax.abort)
				{
					Utils.__themeAjax.abort();
				}

				Utils.__themeAjax = $.ajax({
					'url': sUrl,
					'dataType': 'json'
				}).done(function(aData) {

					if (aData && Utils.isArray(aData) && 2 === aData.length)
					{
						if (oThemeLink && oThemeLink[0] && (!oThemeStyle || !oThemeStyle[0]))
						{
							oThemeStyle = $('<style id="rlThemeStyle"></style>');
							oThemeLink.after(oThemeStyle);
							oThemeLink.remove();
						}

						if (oThemeStyle && oThemeStyle[0])
						{
							oThemeStyle.attr('data-href', sUrl).attr('data-theme', aData[0]);
							if (oThemeStyle[0].styleSheet && !Utils.isUnd(oThemeStyle[0].styleSheet.cssText))
							{
								oThemeStyle[0].styleSheet.cssText = aData[1];
							}
							else
							{
								oThemeStyle.text(aData[1]);
							}
						}

						themeTrigger(Enums.SaveSettingsStep.TrueResult);
					}

				}).always(function() {

					Utils.__themeTimer = window.setTimeout(function () {
						themeTrigger(Enums.SaveSettingsStep.Idle);
					}, 1000);

					Utils.__themeAjax = null;
				});
			}
		};

		Utils.substr = window.String.substr;
		if ('ab'.substr(-1) !== 'b')
		{
			Utils.substr = function(sStr, iStart, iLength)
			{
				if (iStart < 0)
				{
					iStart = sStr.length + iStart;
				}

				return sStr.substr(iStart, iLength);
			};
		}

		module.exports = Utils;

	}());

/***/ },
/* 2 */
/*!****************************!*\
  !*** ./dev/External/ko.js ***!
  \****************************/
/***/ function(module, exports, __webpack_require__) {

	
	(function (ko) {

		'use strict';

		var
			window = __webpack_require__(/*! window */ 13),
			_ = __webpack_require__(/*! _ */ 3),
			$ = __webpack_require__(/*! $ */ 14),
			JSON = __webpack_require__(/*! JSON */ 40),
			Opentip = __webpack_require__(/*! Opentip */ 55),

			fDisposalTooltipHelper = function (oElement) {
				ko.utils.domNodeDisposal.addDisposeCallback(oElement, function () {
					if (oElement && oElement.__opentip)
					{
						oElement.__opentip.deactivate();
					}
				});
			}
		;

		ko.bindingHandlers.editor = {
			'init': function (oElement, fValueAccessor) {

				var
					oEditor  = null,
					fValue = fValueAccessor(),

					fUpdateEditorValue = function () {
						if (fValue && fValue.__editor)
						{
							fValue.__editor.setHtmlOrPlain(fValue());
						}
					},

					fUpdateKoValue = function () {
						if (fValue && fValue.__editor)
						{
							fValue(fValue.__editor.getDataWithHtmlMark());
						}
					},

					fOnReady = function () {
						fValue.__editor = oEditor;
						fUpdateEditorValue();
					},

					HtmlEditor = __webpack_require__(/*! Common/HtmlEditor */ 45)
				;

				if (ko.isObservable(fValue) && HtmlEditor)
				{
					oEditor = new HtmlEditor(oElement, fUpdateKoValue, fOnReady, fUpdateKoValue);

					fValue.__fetchEditorValue = fUpdateKoValue;

					fValue.subscribe(fUpdateEditorValue);

	//				ko.utils.domNodeDisposal.addDisposeCallback(oElement, function () {
	//				});
				}
			}
		};

		ko.bindingHandlers.json = {
			'init': function (oElement, fValueAccessor) {
				$(oElement).text(JSON.stringify(ko.unwrap(fValueAccessor())));
			},
			'update': function (oElement, fValueAccessor) {
				$(oElement).text(JSON.stringify(ko.unwrap(fValueAccessor())));
			}
		};

		ko.bindingHandlers.scrollerShadows = {
			'init': function (oElement) {

				var
					iLimit = 8,
					$oEl = $(oElement),
					$win = $(window),
					oCont = $oEl.find('[data-scroller-shadows-content]')[0] || null,
					fFunc = _.throttle(function () {
						$oEl
							.toggleClass('scroller-shadow-top', iLimit < oCont.scrollTop)
							.toggleClass('scroller-shadow-bottom', oCont.scrollTop + iLimit < oCont.scrollHeight - oCont.clientHeight)
						;
					}, 100)
				;

				if (oCont)
				{
					$(oCont).on('scroll resize', fFunc);
					$win.on('resize', fFunc);

					ko.utils.domNodeDisposal.addDisposeCallback(oCont, function () {
						$(oCont).off();
						$win.off('resize', fFunc);
					});
				}
			}
		};

		ko.bindingHandlers.tooltip = {
			'init': function (oElement, fValueAccessor) {

				var
					bi18n = true,
					sValue = '',
					Translator = null,
					$oEl = $(oElement),
					fValue = fValueAccessor(),
					bMobile = 'on' === ($oEl.data('tooltip-mobile') || 'off'),
					Globals = __webpack_require__(/*! Common/Globals */ 8)
				;

				if (!Globals.bMobileDevice || bMobile)
				{
					bi18n = 'on' === ($oEl.data('tooltip-i18n') || 'on');
					sValue = !ko.isObservable(fValue) && _.isFunction(fValue) ? fValue() : ko.unwrap(fValue);

					oElement.__opentip = new Opentip(oElement, {
						'style': 'rainloopTip',
						'element': oElement,
						'tipJoint': $oEl.data('tooltip-join') || 'bottom'
					});

					Globals.dropdownVisibility.subscribe(function (bV) {
						if (bV) {
							oElement.__opentip.hide();
						}
					});

					if ('' === sValue)
					{
						oElement.__opentip.hide();
						oElement.__opentip.deactivate();
						oElement.__opentip.setContent('');
					}
					else
					{
						oElement.__opentip.activate();
					}

					if (bi18n)
					{
						Translator = __webpack_require__(/*! Common/Translator */ 6);

						oElement.__opentip.setContent(Translator.i18n(sValue));

						Translator.trigger.subscribe(function () {
							oElement.__opentip.setContent(Translator.i18n(sValue));
						});

						Globals.dropdownVisibility.subscribe(function () {
							if (oElement && oElement.__opentip)
							{
								oElement.__opentip.setContent(__webpack_require__(/*! Common/Translator */ 6).i18n(sValue));
							}
						});
					}
					else
					{
						oElement.__opentip.setContent(sValue);
					}
				}
			},
			'update': function (oElement, fValueAccessor) {

				var
					bi18n = true,
					sValue = '',
					$oEl = $(oElement),
					fValue = fValueAccessor(),
					bMobile = 'on' === ($oEl.data('tooltip-mobile') || 'off'),
					Globals = __webpack_require__(/*! Common/Globals */ 8)
				;

				if ((!Globals.bMobileDevice || bMobile) && oElement.__opentip)
				{
					bi18n = 'on' === ($oEl.data('tooltip-i18n') || 'on');
					sValue = !ko.isObservable(fValue) && _.isFunction(fValue) ? fValue() : ko.unwrap(fValue);

					if (sValue)
					{
						oElement.__opentip.setContent(
							bi18n ? __webpack_require__(/*! Common/Translator */ 6).i18n(sValue) : sValue);
						oElement.__opentip.activate();
					}
					else
					{
						oElement.__opentip.hide();
						oElement.__opentip.deactivate();
						oElement.__opentip.setContent('');
					}
				}
			}
		};

		ko.bindingHandlers.tooltipErrorTip = {
			'init': function (oElement) {

				var $oEl = $(oElement);

				oElement.__opentip = new Opentip(oElement, {
					'style': 'rainloopErrorTip',
					'hideOn': 'mouseout click',
					'element': oElement,
					'tipJoint': $oEl.data('tooltip-join') || 'top'
				});

				oElement.__opentip.deactivate();

				$(window.document).on('click', function () {
					if (oElement && oElement.__opentip)
					{
						oElement.__opentip.hide();
					}
				});

				fDisposalTooltipHelper(oElement);
			},
			'update': function (oElement, fValueAccessor) {

				var
					$oEl = $(oElement),
					fValue = fValueAccessor(),
					sValue = !ko.isObservable(fValue) && _.isFunction(fValue) ? fValue() : ko.unwrap(fValue),
					oOpenTips = oElement.__opentip
				;

				if (oOpenTips)
				{
					if ('' === sValue)
					{
						oOpenTips.hide();
						oOpenTips.deactivate();
						oOpenTips.setContent('');
					}
					else
					{
						_.delay(function () {
							if ($oEl.is(':visible'))
							{
								oOpenTips.setContent(sValue);
								oOpenTips.activate();
								oOpenTips.show();
							}
							else
							{
								oOpenTips.hide();
								oOpenTips.deactivate();
								oOpenTips.setContent('');
							}
						}, 100);
					}
				}
			}
		};

		ko.bindingHandlers.registrateBootstrapDropdown = {
			'init': function (oElement) {
				var Globals = __webpack_require__(/*! Common/Globals */ 8);
				if (Globals && Globals.aBootstrapDropdowns)
				{
					Globals.aBootstrapDropdowns.push($(oElement));

					$(oElement).click(function () {
						__webpack_require__(/*! Common/Utils */ 1).detectDropdownVisibility();
					});

	//				ko.utils.domNodeDisposal.addDisposeCallback(oElement, function () {
	//				});
				}
			}
		};

		ko.bindingHandlers.openDropdownTrigger = {
			'update': function (oElement, fValueAccessor) {
				if (ko.unwrap(fValueAccessor()))
				{
					var $oEl = $(oElement);
					if (!$oEl.hasClass('open'))
					{
						$oEl.find('.dropdown-toggle').dropdown('toggle');
					}

					$oEl.find('.dropdown-toggle').focus();

					__webpack_require__(/*! Common/Utils */ 1).detectDropdownVisibility();
					fValueAccessor()(false);
				}
			}
		};

		ko.bindingHandlers.dropdownCloser = {
			'init': function (oElement) {
				$(oElement).closest('.dropdown').on('click', '.e-item', function () {
					$(oElement).dropdown('toggle');
				});
			}
		};

		ko.bindingHandlers.popover = {
			'init': function (oElement, fValueAccessor) {
				$(oElement).popover(ko.unwrap(fValueAccessor()));

				ko.utils.domNodeDisposal.addDisposeCallback(oElement, function () {
					$(oElement).popover('destroy');
				});
			}
		};

		ko.bindingHandlers.csstext = {
			'init': function (oElement, fValueAccessor) {
				if (oElement && oElement.styleSheet && undefined !== oElement.styleSheet.cssText)
				{
					oElement.styleSheet.cssText = ko.unwrap(fValueAccessor());
				}
				else
				{
					$(oElement).text(ko.unwrap(fValueAccessor()));
				}
			},
			'update': function (oElement, fValueAccessor) {
				if (oElement && oElement.styleSheet && undefined !== oElement.styleSheet.cssText)
				{
					oElement.styleSheet.cssText = ko.unwrap(fValueAccessor());
				}
				else
				{
					$(oElement).text(ko.unwrap(fValueAccessor()));
				}
			}
		};

		ko.bindingHandlers.resizecrop = {
			'init': function (oElement) {
				$(oElement).addClass('resizecrop').resizecrop({
					'width': '100',
					'height': '100',
					'wrapperCSS': {
						'border-radius': '10px'
					}
				});
			},
			'update': function (oElement, fValueAccessor) {
				fValueAccessor()();
				$(oElement).resizecrop({
					'width': '100',
					'height': '100'
				});
			}
		};

		ko.bindingHandlers.onEnter = {
			'init': function (oElement, fValueAccessor, fAllBindingsAccessor, oViewModel) {
				$(oElement).on('keypress.koOnEnter', function (oEvent) {
					if (oEvent && 13 === window.parseInt(oEvent.keyCode, 10))
					{
						$(oElement).trigger('change');
						fValueAccessor().call(oViewModel);
					}
				});

				ko.utils.domNodeDisposal.addDisposeCallback(oElement, function () {
					$(oElement).off('keypress.koOnEnter');
				});
			}
		};

		ko.bindingHandlers.onSpace = {
			'init': function (oElement, fValueAccessor, fAllBindingsAccessor, oViewModel) {
				$(oElement).on('keyup.koOnSpace', function (oEvent) {
					if (oEvent && 32 === window.parseInt(oEvent.keyCode, 10))
					{
						fValueAccessor().call(oViewModel, oEvent);
					}
				});

				ko.utils.domNodeDisposal.addDisposeCallback(oElement, function () {
					$(oElement).off('keyup.koOnSpace');
				});
			}
		};

		ko.bindingHandlers.onTab = {
			'init': function (oElement, fValueAccessor, fAllBindingsAccessor, oViewModel) {
				$(oElement).on('keydown.koOnTab', function (oEvent) {
					if (oEvent && 9 === window.parseInt(oEvent.keyCode, 10))
					{
						return fValueAccessor().call(oViewModel, !!oEvent.shiftKey);
					}
				});

				ko.utils.domNodeDisposal.addDisposeCallback(oElement, function () {
					$(oElement).off('keydown.koOnTab');
				});
			}
		};

		ko.bindingHandlers.onEsc = {
			'init': function (oElement, fValueAccessor, fAllBindingsAccessor, oViewModel) {
				$(oElement).on('keypress.koOnEsc', function (oEvent) {
					if (oEvent && 27 === window.parseInt(oEvent.keyCode, 10))
					{
						$(oElement).trigger('change');
						fValueAccessor().call(oViewModel);
					}
				});

				ko.utils.domNodeDisposal.addDisposeCallback(oElement, function () {
					$(oElement).off('keypress.koOnEsc');
				});
			}
		};

		ko.bindingHandlers.clickOnTrue = {
			'update': function (oElement, fValueAccessor) {
				if (ko.unwrap(fValueAccessor()))
				{
					$(oElement).click();
				}
			}
		};

		ko.bindingHandlers.modal = {
			'init': function (oElement, fValueAccessor) {

				var
					Globals = __webpack_require__(/*! Common/Globals */ 8),
					Utils = __webpack_require__(/*! Common/Utils */ 1)
				;

				$(oElement).toggleClass('fade', !Globals.bMobileDevice).modal({
					'keyboard': false,
					'show': ko.unwrap(fValueAccessor())
				})
				.on('shown.koModal', Utils.windowResizeCallback)
				.find('.close').on('click.koModal', function () {
					fValueAccessor()(false);
				});

				ko.utils.domNodeDisposal.addDisposeCallback(oElement, function () {
					$(oElement)
						.off('shown.koModal')
						.find('.close')
						.off('click.koModal')
					;
				});
			},
			'update': function (oElement, fValueAccessor) {

				var Globals = __webpack_require__(/*! Common/Globals */ 8);

				$(oElement).modal(!!ko.unwrap(fValueAccessor()) ? 'show' : 'hide');

				if (Globals.$html.hasClass('rl-anim'))
				{
					Globals.$html.addClass('rl-modal-animation');
					_.delay(function () {
						Globals.$html.removeClass('rl-modal-animation');
					}, 400);
				}

			}
		};

		ko.bindingHandlers.moment = {
			'init': function (oElement, fValueAccessor) {
				__webpack_require__(/*! Common/Momentor */ 26).momentToNode(
					$(oElement).addClass('moment').data('moment-time', ko.unwrap(fValueAccessor()))
				);
			},
			'update': function (oElement, fValueAccessor) {
				__webpack_require__(/*! Common/Momentor */ 26).momentToNode(
					$(oElement).data('moment-time', ko.unwrap(fValueAccessor()))
				);
			}
		};

		ko.bindingHandlers.i18nInit = {
			'init': function (oElement) {
				__webpack_require__(/*! Common/Translator */ 6).i18nToNodes(oElement);
			}
		};

		ko.bindingHandlers.translatorInit = {
			'init': function (oElement) {
				__webpack_require__(/*! Common/Translator */ 6).i18nToNodes(oElement);
			}
		};

		ko.bindingHandlers.i18nUpdate = {
			'update': function (oElement, fValueAccessor) {
				ko.unwrap(fValueAccessor());
				__webpack_require__(/*! Common/Translator */ 6).i18nToNodes(oElement);
			}
		};

		ko.bindingHandlers.link = {
			'update': function (oElement, fValueAccessor) {
				$(oElement).attr('href', ko.unwrap(fValueAccessor()));
			}
		};

		ko.bindingHandlers.title = {
			'update': function (oElement, fValueAccessor) {
				$(oElement).attr('title', ko.unwrap(fValueAccessor()));
			}
		};

		ko.bindingHandlers.textF = {
			'init': function (oElement, fValueAccessor) {
				$(oElement).text(ko.unwrap(fValueAccessor()));
			}
		};

		ko.bindingHandlers.initDom = {
			'init': function (oElement, fValueAccessor) {
				fValueAccessor()(oElement);
			}
		};

		ko.bindingHandlers.initFixedTrigger = {
			'init': function (oElement, fValueAccessor) {
				var
					aValues = ko.unwrap(fValueAccessor()),
					$oContainer = null,
					$oElement = $(oElement),
					oOffset = null,

					iTop = aValues[1] || 0
				;

				$oContainer = $(aValues[0] || null);
				$oContainer = $oContainer[0] ? $oContainer : null;

				if ($oContainer)
				{
					$(window).resize(function () {
						oOffset = $oContainer.offset();
						if (oOffset && oOffset.top)
						{
							$oElement.css('top', oOffset.top + iTop);
						}
					});
				}
			}
		};

		ko.bindingHandlers.initResizeTrigger = {
			'init': function (oElement, fValueAccessor) {
				var aValues = ko.unwrap(fValueAccessor());
				$(oElement).css({
					'height': aValues[1],
					'min-height': aValues[1]
				});
			},
			'update': function (oElement, fValueAccessor) {

				var
					Utils = __webpack_require__(/*! Common/Utils */ 1),
					Globals = __webpack_require__(/*! Common/Globals */ 8),
					aValues = ko.unwrap(fValueAccessor()),
					iValue = Utils.pInt(aValues[1]),
					iSize = 0,
					iOffset = $(oElement).offset().top
				;

				if (0 < iOffset)
				{
					iOffset += Utils.pInt(aValues[2]);
					iSize = Globals.$win.height() - iOffset;

					if (iValue < iSize)
					{
						iValue = iSize;
					}

					$(oElement).css({
						'height': iValue,
						'min-height': iValue
					});
				}
			}
		};

		ko.bindingHandlers.appendDom = {
			'update': function (oElement, fValueAccessor) {
				$(oElement).hide().empty().append(ko.unwrap(fValueAccessor())).show();
			}
		};

		ko.bindingHandlers.draggable = {
			'init': function (oElement, fValueAccessor, fAllBindingsAccessor) {

				var
					Globals = __webpack_require__(/*! Common/Globals */ 8),
					Utils = __webpack_require__(/*! Common/Utils */ 1)
				;

				if (!Globals.bMobileDevice)
				{
					var
						iTriggerZone = 100,
						iScrollSpeed = 3,
						fAllValueFunc = fAllBindingsAccessor(),
						sDroppableSelector = fAllValueFunc && fAllValueFunc['droppableSelector'] ? fAllValueFunc['droppableSelector'] : '',
						oConf = {
							'distance': 20,
							'handle': '.dragHandle',
							'cursorAt': {'top': 22, 'left': 3},
							'refreshPositions': true,
							'scroll': true
						}
					;

					if (sDroppableSelector)
					{
						oConf['drag'] = function (oEvent) {

							$(sDroppableSelector).each(function () {
								var
									moveUp = null,
									moveDown = null,
									$this = $(this),
									oOffset = $this.offset(),
									bottomPos = oOffset.top + $this.height()
								;

								window.clearInterval($this.data('timerScroll'));
								$this.data('timerScroll', false);

								if (oEvent.pageX >= oOffset.left && oEvent.pageX <= oOffset.left + $this.width())
								{
									if (oEvent.pageY >= bottomPos - iTriggerZone && oEvent.pageY <= bottomPos)
									{
										moveUp = function() {
											$this.scrollTop($this.scrollTop() + iScrollSpeed);
											Utils.windowResize();
										};

										$this.data('timerScroll', window.setInterval(moveUp, 10));
										moveUp();
									}

									if (oEvent.pageY >= oOffset.top && oEvent.pageY <= oOffset.top + iTriggerZone)
									{
										moveDown = function() {
											$this.scrollTop($this.scrollTop() - iScrollSpeed);
											Utils.windowResize();
										};

										$this.data('timerScroll', window.setInterval(moveDown, 10));
										moveDown();
									}
								}
							});
						};

						oConf['stop'] =	function() {
							$(sDroppableSelector).each(function () {
								window.clearInterval($(this).data('timerScroll'));
								$(this).data('timerScroll', false);
							});
						};
					}

					oConf['helper'] = function (oEvent) {
						return fValueAccessor()(oEvent && oEvent.target ? ko.dataFor(oEvent.target) : null);
					};

					$(oElement).draggable(oConf).on('mousedown.koDraggable', function () {
						Utils.removeInFocus();
					});

					ko.utils.domNodeDisposal.addDisposeCallback(oElement, function () {
						$(oElement)
							.off('mousedown.koDraggable')
							.draggable('destroy')
						;
					});
				}
			}
		};

		ko.bindingHandlers.droppable = {
			'init': function (oElement, fValueAccessor, fAllBindingsAccessor) {
				var Globals = __webpack_require__(/*! Common/Globals */ 8);
				if (!Globals.bMobileDevice)
				{
					var
						fValueFunc = fValueAccessor(),
						fAllValueFunc = fAllBindingsAccessor(),
						fOverCallback = fAllValueFunc && fAllValueFunc['droppableOver'] ? fAllValueFunc['droppableOver'] : null,
						fOutCallback = fAllValueFunc && fAllValueFunc['droppableOut'] ? fAllValueFunc['droppableOut'] : null,
						oConf = {
							'tolerance': 'pointer',
							'hoverClass': 'droppableHover'
						}
					;

					if (fValueFunc)
					{
						oConf['drop'] = function (oEvent, oUi) {
							fValueFunc(oEvent, oUi);
						};

						if (fOverCallback)
						{
							oConf['over'] = function (oEvent, oUi) {
								fOverCallback(oEvent, oUi);
							};
						}

						if (fOutCallback)
						{
							oConf['out'] = function (oEvent, oUi) {
								fOutCallback(oEvent, oUi);
							};
						}

						$(oElement).droppable(oConf);

						ko.utils.domNodeDisposal.addDisposeCallback(oElement, function () {
							$(oElement).droppable('destroy');
						});
					}
				}
			}
		};

		ko.bindingHandlers.nano = {
			'init': function (oElement) {
				var Globals = __webpack_require__(/*! Common/Globals */ 8);
				if (!Globals.bDisableNanoScroll)
				{
					$(oElement)
						.addClass('nano')
						.nanoScroller({
							'iOSNativeScrolling': false,
							'preventPageScrolling': true
						})
					;
				}
			}
		};

		ko.bindingHandlers.saveTrigger = {
			'init': function (oElement) {

				var $oEl = $(oElement);

				$oEl.data('save-trigger-type', $oEl.is('input[type=text],input[type=email],input[type=password],select,textarea') ? 'input' : 'custom');

				if ('custom' === $oEl.data('save-trigger-type'))
				{
					$oEl.append(
						'&nbsp;&nbsp;<i class="icon-spinner animated"></i><i class="icon-remove error"></i><i class="icon-ok success"></i>'
					).addClass('settings-saved-trigger');
				}
				else
				{
					$oEl.addClass('settings-saved-trigger-input');
				}
			},
			'update': function (oElement, fValueAccessor) {
				var
					mValue = ko.unwrap(fValueAccessor()),
					$oEl = $(oElement)
				;

				if ('custom' === $oEl.data('save-trigger-type'))
				{
					switch (mValue.toString())
					{
						case '1':
							$oEl
								.find('.animated,.error').hide().removeClass('visible')
								.end()
								.find('.success').show().addClass('visible')
							;
							break;
						case '0':
							$oEl
								.find('.animated,.success').hide().removeClass('visible')
								.end()
								.find('.error').show().addClass('visible')
							;
							break;
						case '-2':
							$oEl
								.find('.error,.success').hide().removeClass('visible')
								.end()
								.find('.animated').show().addClass('visible')
							;
							break;
						default:
							$oEl
								.find('.animated').hide()
								.end()
								.find('.error,.success').removeClass('visible')
							;
							break;
					}
				}
				else
				{
					switch (mValue.toString())
					{
						case '1':
							$oEl.addClass('success').removeClass('error');
							break;
						case '0':
							$oEl.addClass('error').removeClass('success');
							break;
						case '-2':
		//					$oEl;
							break;
						default:
							$oEl.removeClass('error success');
							break;
					}
				}
			}
		};

		ko.bindingHandlers.emailsTags = {
			'init': function(oElement, fValueAccessor, fAllBindingsAccessor) {

				var
					Utils = __webpack_require__(/*! Common/Utils */ 1),
					EmailModel = __webpack_require__(/*! Model/Email */ 29),

					$oEl = $(oElement),
					fValue = fValueAccessor(),
					fAllBindings = fAllBindingsAccessor(),
					fAutoCompleteSource = fAllBindings['autoCompleteSource'] || null,
					fFocusCallback = function (bValue) {
						if (fValue && fValue.focused)
						{
							fValue.focused(!!bValue);
						}
					}
				;

				$oEl.inputosaurus({
					'parseOnBlur': true,
					'allowDragAndDrop': true,
					'focusCallback': fFocusCallback,
					'inputDelimiters': [',', ';', "\n"],
					'autoCompleteSource': fAutoCompleteSource,
	//				'elementHook': function (oEl, oItem) {
	//					if (oEl && oItem)
	//					{
	//						oEl.addClass('pgp');
	//						window.console.log(arguments);
	//					}
	//				},
					'parseHook': function (aInput) {

						return _.map(aInput, function (sInputValue) {

							var
								sValue = Utils.trim(sInputValue),
								oEmail = null
							;

							if ('' !== sValue)
							{
								oEmail = new EmailModel();
								oEmail.mailsoParse(sValue);
								return [oEmail.toLine(false), oEmail];
							}

							return [sValue, null];

						});

	//					var aResult = [];
	//
	//					_.each(aInput, function (sInputValue) {
	//
	//						var
	//							aM = null,
	//							aValues = [],
	//							sValue = Utils.trim(sInputValue),
	//							oEmail = null
	//						;
	//
	//						if ('' !== sValue)
	//						{
	//							aM = sValue.match(/[@]/g);
	//							if (aM && 0 < aM.length)
	//							{
	//								sValue = sValue.replace(/[\r\n]+/g, '; ').replace(/[\s]+/g, ' ');
	//								aValues = EmailModel.splitHelper(sValue, ';');
	//
	//								_.each(aValues, function (sV) {
	//
	//									oEmail = new EmailModel();
	//									oEmail.mailsoParse(sV);
	//
	//									if (oEmail.email)
	//									{
	//										aResult.push([oEmail.toLine(false), oEmail]);
	//									}
	//									else
	//									{
	//										aResult.push(['', null]);
	//									}
	//								});
	//							}
	//							else
	//							{
	//								aResult.push([sInputValue, null]);
	//							}
	//						}
	//						else
	//						{
	//							aResult.push([sInputValue, null]);
	//						}
	//					});
	//
	//					return aResult;
					},
					'change': _.bind(function (oEvent) {
						$oEl.data('EmailsTagsValue', oEvent.target.value);
						fValue(oEvent.target.value);
					}, this)
				});

				if (fValue && fValue.focused && fValue.focused.subscribe)
				{
					fValue.focused.subscribe(function (bValue) {
						$oEl.inputosaurus(!!bValue ? 'focus' : 'blur');
					});
				}
			},
			'update': function (oElement, fValueAccessor) {

				var
					$oEl = $(oElement),
					fValue = fValueAccessor(),
					sValue = ko.unwrap(fValue)
				;

				if ($oEl.data('EmailsTagsValue') !== sValue)
				{
					$oEl.val(sValue);
					$oEl.data('EmailsTagsValue', sValue);
					$oEl.inputosaurus('refresh');
				}
			}
		};

		ko.bindingHandlers.command = {
			'init': function (oElement, fValueAccessor, fAllBindingsAccessor, oViewModel) {
				var
					jqElement = $(oElement),
					oCommand = fValueAccessor()
				;

				if (!oCommand || !oCommand.enabled || !oCommand.canExecute)
				{
					throw new Error('You are not using command function');
				}

				jqElement.addClass('command');
				ko.bindingHandlers[jqElement.is('form') ? 'submit' : 'click'].init.apply(oViewModel, arguments);
			},

			'update': function (oElement, fValueAccessor) {

				var
					bResult = true,
					jqElement = $(oElement),
					oCommand = fValueAccessor()
				;

				bResult = oCommand.enabled();
				jqElement.toggleClass('command-not-enabled', !bResult);

				if (bResult)
				{
					bResult = oCommand.canExecute();
					jqElement.toggleClass('command-can-not-be-execute', !bResult);
				}

				jqElement.toggleClass('command-disabled disable disabled', !bResult).toggleClass('no-disabled', !!bResult);

				if (jqElement.is('input') || jqElement.is('button'))
				{
					jqElement.prop('disabled', !bResult);
				}
			}
		};

		// extenders

		ko.extenders.trimmer = function (oTarget)
		{
			var
				Utils = __webpack_require__(/*! Common/Utils */ 1),
				oResult = ko.computed({
					'read': oTarget,
					'write': function (sNewValue) {
						oTarget(Utils.trim(sNewValue.toString()));
					},
					'owner': this
				})
			;

			oResult(oTarget());
			return oResult;
		};

		ko.extenders.posInterer = function (oTarget, iDefault)
		{
			var
				Utils = __webpack_require__(/*! Common/Utils */ 1),
				oResult = ko.computed({
					'read': oTarget,
					'write': function (sNewValue) {
						var iNew = Utils.pInt(sNewValue.toString(), iDefault);
						if (0 >= iNew)
						{
							iNew = iDefault;
						}

						if (iNew === oTarget() && '' + iNew !== '' + sNewValue)
						{
							oTarget(iNew + 1);
						}

						oTarget(iNew);
					}
				})
			;

			oResult(oTarget());
			return oResult;
		};

		ko.extenders.limitedList = function (oTarget, mList)
		{
			var
				Utils = __webpack_require__(/*! Common/Utils */ 1),
				oResult = ko.computed({
					'read': oTarget,
					'write': function (sNewValue) {

						var
							sCurrentValue = ko.unwrap(oTarget),
							aList = ko.unwrap(mList)
						;

						if (Utils.isNonEmptyArray(aList))
						{
							if (-1 < Utils.inArray(sNewValue, aList))
							{
								oTarget(sNewValue);
							}
							else if (-1 < Utils.inArray(sCurrentValue, aList))
							{
								oTarget(sCurrentValue + ' ');
								oTarget(sCurrentValue);
							}
							else
							{
								oTarget(aList[0] + ' ');
								oTarget(aList[0]);
							}
						}
						else
						{
							oTarget('');
						}
					}
				}).extend({'notify': 'always'})
			;

			oResult(oTarget());

			if (!oResult.valueHasMutated)
			{
				oResult.valueHasMutated = function () {
					oTarget.valueHasMutated();
				};
			}

			return oResult;
		};

		ko.extenders.reversible = function (oTarget)
		{
			var mValue = oTarget();

			oTarget.commit = function ()
			{
				mValue = oTarget();
			};

			oTarget.reverse = function ()
			{
				oTarget(mValue);
			};

			oTarget.commitedValue = function ()
			{
				return mValue;
			};

			return oTarget;
		};

		ko.extenders.toggleSubscribe = function (oTarget, oOptions)
		{
			oTarget.subscribe(oOptions[1], oOptions[0], 'beforeChange');
			oTarget.subscribe(oOptions[2], oOptions[0]);

			return oTarget;
		};

		ko.extenders.toggleSubscribeProperty = function (oTarget, oOptions)
		{
			var sProp = oOptions[1];

			if (sProp)
			{
				oTarget.subscribe(function (oPrev) {
					if (oPrev && oPrev[sProp])
					{
						oPrev[sProp](false);
					}
				}, oOptions[0], 'beforeChange');

				oTarget.subscribe(function (oNext) {
					if (oNext && oNext[sProp])
					{
						oNext[sProp](true);
					}
				}, oOptions[0]);
			}

			return oTarget;
		};

		ko.extenders.falseTimeout = function (oTarget, iOption)
		{
			oTarget.iFalseTimeoutTimeout = 0;
			oTarget.subscribe(function (bValue) {
				if (bValue)
				{
					window.clearTimeout(oTarget.iFalseTimeoutTimeout);
					oTarget.iFalseTimeoutTimeout = window.setTimeout(function () {
						oTarget(false);
						oTarget.iFalseTimeoutTimeout = 0;
					}, __webpack_require__(/*! Common/Utils */ 1).pInt(iOption));
				}
			});

			return oTarget;
		};

		ko.extenders.specialThrottle = function (oTarget, iOption)
		{
			oTarget.iSpecialThrottleTimeoutValue = __webpack_require__(/*! Common/Utils */ 1).pInt(iOption);
			if (0 < oTarget.iSpecialThrottleTimeoutValue)
			{
				oTarget.iSpecialThrottleTimeout = 0;
				oTarget.valueForRead = ko.observable(!!oTarget()).extend({'throttle': 10});

				return ko.computed({
					'read': oTarget.valueForRead,
					'write': function (bValue) {

						if (bValue)
						{
							oTarget.valueForRead(bValue);
						}
						else
						{
							if (oTarget.valueForRead())
							{
								window.clearTimeout(oTarget.iSpecialThrottleTimeout);
								oTarget.iSpecialThrottleTimeout = window.setTimeout(function () {
									oTarget.valueForRead(false);
									oTarget.iSpecialThrottleTimeout = 0;
								}, oTarget.iSpecialThrottleTimeoutValue);
							}
							else
							{
								oTarget.valueForRead(bValue);
							}
						}
					}
				});
			}

			return oTarget;
		};

		// functions

		ko.observable.fn.validateNone = function ()
		{
			this.hasError = ko.observable(false);
			return this;
		};

		ko.observable.fn.validateEmail = function ()
		{
			var Utils = __webpack_require__(/*! Common/Utils */ 1);

			this.hasError = ko.observable(false);

			this.subscribe(function (sValue) {
				sValue = Utils.trim(sValue);
				this.hasError('' !== sValue && !(/^[^@\s]+@[^@\s]+$/.test(sValue)));
			}, this);

			this.valueHasMutated();
			return this;
		};

		ko.observable.fn.validateSimpleEmail = function ()
		{
			var Utils = __webpack_require__(/*! Common/Utils */ 1);

			this.hasError = ko.observable(false);

			this.subscribe(function (sValue) {
				sValue = Utils.trim(sValue);
				this.hasError('' !== sValue && !(/^.+@.+$/.test(sValue)));
			}, this);

			this.valueHasMutated();
			return this;
		};

		ko.observable.fn.deleteAccessHelper = function ()
		{
			this.extend({'falseTimeout': 3000}).extend({'toggleSubscribe': [null,
				function (oPrev) {
					if (oPrev && oPrev.deleteAccess)
					{
						oPrev.deleteAccess(false);
					}
				}, function (oNext) {
					if (oNext && oNext.deleteAccess)
					{
						oNext.deleteAccess(true);
					}
				}
			]});

			return this;
		};

		ko.observable.fn.validateFunc = function (fFunc)
		{
			var Utils = __webpack_require__(/*! Common/Utils */ 1);

			this.hasFuncError = ko.observable(false);

			if (Utils.isFunc(fFunc))
			{
				this.subscribe(function (sValue) {
					this.hasFuncError(!fFunc(sValue));
				}, this);

				this.valueHasMutated();
			}

			return this;
		};

		module.exports = ko;

	}(ko));


/***/ },
/* 3 */
/*!***************************!*\
  !*** external "window._" ***!
  \***************************/
/***/ function(module, exports, __webpack_require__) {

	module.exports = window._;

/***/ },
/* 4 */
/*!******************************!*\
  !*** ./dev/Common/Enums.jsx ***!
  \******************************/
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	Object.defineProperty(exports, "__esModule", {
		value: true
	});

	/**
	 * @enum {string}
	 */
	var FileType = exports.FileType = {
		'Unknown': 'unknown',
		'Text': 'text',
		'Html': 'html',
		'Code': 'code',
		'Eml': 'eml',
		'WordText': 'word-text',
		'Pdf': 'pdf',
		'Image': 'image',
		'Audio': 'audio',
		'Video': 'video',
		'Sheet': 'sheet',
		'Presentation': 'presentation',
		'Certificate': 'certificate',
		'CertificateBin': 'certificate-bin',
		'Archive': 'archive'
	};

	/**
	 * @enum {string}
	 */
	var StorageResultType = exports.StorageResultType = {
		'Success': 'success',
		'Abort': 'abort',
		'Error': 'error',
		'Unload': 'unload'
	};

	/**
	 * @enum {string}
	 */
	var Focused = exports.Focused = {
		'None': 'none',
		'MessageList': 'message-list',
		'MessageView': 'message-view',
		'FolderList': 'folder-list'
	};

	/**
	 * @enum {number}
	 */
	var State = exports.State = {
		'Empty': 10,
		'Login': 20,
		'Auth': 30
	};

	/**
	 * @enum {number}
	 */
	var StateType = exports.StateType = {
		'Webmail': 0,
		'Admin': 1
	};

	/**
	 * @enum {string}
	 */
	var Capa = exports.Capa = {
		'TwoFactor': 'TWO_FACTOR',
		'TwoFactorForce': 'TWO_FACTOR_FORCE',
		'OpenPGP': 'OPEN_PGP',
		'Prefetch': 'PREFETCH',
		'Gravatar': 'GRAVATAR',
		'Folders': 'FOLDERS',
		'Composer': 'COMPOSER',
		'Contacts': 'CONTACTS',
		'Reload': 'RELOAD',
		'Search': 'SEARCH',
		'SearchAdv': 'SEARCH_ADV',
		'MessageActions': 'MESSAGE_ACTIONS',
		'MessageListActions': 'MESSAGELIST_ACTIONS',
		'AttachmentsActions': 'ATTACHMENTS_ACTIONS',
		'DangerousActions': 'DANGEROUS_ACTIONS',
		'Settings': 'SETTINGS',
		'Help': 'HELP',
		'Themes': 'THEMES',
		'UserBackground': 'USER_BACKGROUND',
		'Sieve': 'SIEVE',
		'Filters': 'FILTERS',
		'AttachmentThumbnails': 'ATTACHMENT_THUMBNAILS',
		'Templates': 'TEMPLATES',
		'AutoLogout': 'AUTOLOGOUT',
		'AdditionalAccounts': 'ADDITIONAL_ACCOUNTS',
		'Identities': 'IDENTITIES'
	};

	/**
	 * @enum {string}
	 */
	var KeyState = exports.KeyState = {
		'All': 'all',
		'None': 'none',
		'ContactList': 'contact-list',
		'MessageList': 'message-list',
		'FolderList': 'folder-list',
		'MessageView': 'message-view',
		'Compose': 'compose',
		'Settings': 'settings',
		'Menu': 'menu',
		'PopupComposeOpenPGP': 'compose-open-pgp',
		'PopupMessageOpenPGP': 'message-open-pgp',
		'PopupViewOpenPGP': 'view-open-pgp',
		'PopupKeyboardShortcutsHelp': 'popup-keyboard-shortcuts-help',
		'PopupAsk': 'popup-ask'
	};

	/**
	 * @enum {number}
	 */
	var FolderType = exports.FolderType = {
		'Inbox': 10,
		'SentItems': 11,
		'Draft': 12,
		'Trash': 13,
		'Spam': 14,
		'Archive': 15,
		'NotSpam': 80,
		'User': 99
	};

	/**
	 * @enum {number}
	 */
	var ServerFolderType = exports.ServerFolderType = {
		'USER': 0,
		'INBOX': 1,
		'SENT': 2,
		'DRAFTS': 3,
		'JUNK': 4,
		'TRASH': 5,
		'IMPORTANT': 10,
		'FLAGGED': 11,
		'ALL': 12
	};

	/**
	 * @enum {string}
	 */
	var LoginSignMeTypeAsString = exports.LoginSignMeTypeAsString = {
		'DefaultOff': 'defaultoff',
		'DefaultOn': 'defaulton',
		'Unused': 'unused'
	};

	/**
	 * @enum {number}
	 */
	var LoginSignMeType = exports.LoginSignMeType = {
		'DefaultOff': 0,
		'DefaultOn': 1,
		'Unused': 2
	};

	/**
	 * @enum {string}
	 */
	var ComposeType = exports.ComposeType = {
		'Empty': 'empty',
		'Reply': 'reply',
		'ReplyAll': 'replyall',
		'Forward': 'forward',
		'ForwardAsAttachment': 'forward-as-attachment',
		'Draft': 'draft',
		'EditAsNew': 'editasnew'
	};

	/**
	 * @enum {number}
	 */
	var UploadErrorCode = exports.UploadErrorCode = {
		'Normal': 0,
		'FileIsTooBig': 1,
		'FilePartiallyUploaded': 2,
		'FileNoUploaded': 3,
		'MissingTempFolder': 4,
		'FileOnSaveingError': 5,
		'FileType': 98,
		'Unknown': 99
	};

	/**
	 * @enum {number}
	 */
	var SetSystemFoldersNotification = exports.SetSystemFoldersNotification = {
		'None': 0,
		'Sent': 1,
		'Draft': 2,
		'Spam': 3,
		'Trash': 4,
		'Archive': 5
	};

	/**
	 * @enum {number}
	 */
	var ClientSideKeyName = exports.ClientSideKeyName = {
		'FoldersLashHash': 0,
		'MessagesInboxLastHash': 1,
		'MailBoxListSize': 2,
		'ExpandedFolders': 3,
		'FolderListSize': 4,
		'MessageListSize': 5,
		'LastReplyAction': 6,
		'LastSignMe': 7,
		'ComposeLastIdentityID': 8
	};

	/**
	 * @enum {number}
	 */
	var EventKeyCode = exports.EventKeyCode = {
		'Backspace': 8,
		'Tab': 9,
		'Enter': 13,
		'Esc': 27,
		'PageUp': 33,
		'PageDown': 34,
		'Left': 37,
		'Right': 39,
		'Up': 38,
		'Down': 40,
		'End': 35,
		'Home': 36,
		'Space': 32,
		'Insert': 45,
		'Delete': 46,
		'A': 65,
		'S': 83
	};

	/**
	 * @enum {number}
	 */
	var MessageSetAction = exports.MessageSetAction = {
		'SetSeen': 0,
		'UnsetSeen': 1,
		'SetFlag': 2,
		'UnsetFlag': 3
	};

	/**
	 * @enum {number}
	 */
	var MessageSelectAction = exports.MessageSelectAction = {
		'All': 0,
		'None': 1,
		'Invert': 2,
		'Unseen': 3,
		'Seen': 4,
		'Flagged': 5,
		'Unflagged': 6
	};

	/**
	 * @enum {number}
	 */
	var DesktopNotification = exports.DesktopNotification = {
		'Allowed': 0,
		'NotAllowed': 1,
		'Denied': 2,
		'NotSupported': 9
	};

	/**
	 * @enum {number}
	 */
	var MessagePriority = exports.MessagePriority = {
		'Low': 5,
		'Normal': 3,
		'High': 1
	};

	/**
	 * @enum {string}
	 */
	var EditorDefaultType = exports.EditorDefaultType = {
		'Html': 'Html',
		'Plain': 'Plain',
		'HtmlForced': 'HtmlForced',
		'PlainForced': 'PlainForced'
	};

	/**
	 * @enum {number}
	 */
	var ServerSecure = exports.ServerSecure = {
		'None': 0,
		'SSL': 1,
		'TLS': 2
	};

	/**
	 * @enum {number}
	 */
	var SearchDateType = exports.SearchDateType = {
		'All': -1,
		'Days3': 3,
		'Days7': 7,
		'Month': 30
	};

	/**
	 * @enum {number}
	 */
	var SaveSettingsStep = exports.SaveSettingsStep = {
		'Animate': -2,
		'Idle': -1,
		'TrueResult': 1,
		'FalseResult': 0
	};

	/**
	 * @enum {number}
	 */
	var Layout = exports.Layout = {
		'NoPreview': 0,
		'SidePreview': 1,
		'BottomPreview': 2,
		'Mobile': 3
	};

	/**
	 * @enum {string}
	 */
	var FilterConditionField = exports.FilterConditionField = {
		'From': 'From',
		'Recipient': 'Recipient',
		'Subject': 'Subject',
		'Header': 'Header',
		'Size': 'Size'
	};

	/**
	 * @enum {string}
	 */
	var FilterConditionType = exports.FilterConditionType = {
		'Contains': 'Contains',
		'NotContains': 'NotContains',
		'EqualTo': 'EqualTo',
		'NotEqualTo': 'NotEqualTo',
		'Over': 'Over',
		'Under': 'Under'
	};

	/**
	 * @enum {string}
	 */
	var FiltersAction = exports.FiltersAction = {
		'None': 'None',
		'MoveTo': 'MoveTo',
		'Discard': 'Discard',
		'Vacation': 'Vacation',
		'Reject': 'Reject',
		'Forward': 'Forward'
	};

	/**
	 * @enum {string}
	 */
	var FilterRulesType = exports.FilterRulesType = {
		'All': 'All',
		'Any': 'Any'
	};

	/**
	 * @enum {number}
	 */
	var SignedVerifyStatus = exports.SignedVerifyStatus = {
		'UnknownPublicKeys': -4,
		'UnknownPrivateKey': -3,
		'Unverified': -2,
		'Error': -1,
		'None': 0,
		'Success': 1
	};

	/**
	 * @enum {number}
	 */
	var ContactPropertyType = exports.ContactPropertyType = {

		'Unknown': 0,

		'FullName': 10,

		'FirstName': 15,
		'LastName': 16,
		'MiddleName': 16,
		'Nick': 18,

		'NamePrefix': 20,
		'NameSuffix': 21,

		'Email': 30,
		'Phone': 31,
		'Web': 32,

		'Birthday': 40,

		'Facebook': 90,
		'Skype': 91,
		'GitHub': 92,

		'Note': 110,

		'Custom': 250
	};

	/**
	 * @enum {number}
	 */
	var Notification = exports.Notification = {
		'InvalidToken': 101,
		'AuthError': 102,
		'AccessError': 103,
		'ConnectionError': 104,
		'CaptchaError': 105,
		'SocialFacebookLoginAccessDisable': 106,
		'SocialTwitterLoginAccessDisable': 107,
		'SocialGoogleLoginAccessDisable': 108,
		'DomainNotAllowed': 109,
		'AccountNotAllowed': 110,

		'AccountTwoFactorAuthRequired': 120,
		'AccountTwoFactorAuthError': 121,

		'CouldNotSaveNewPassword': 130,
		'CurrentPasswordIncorrect': 131,
		'NewPasswordShort': 132,
		'NewPasswordWeak': 133,
		'NewPasswordForbidden': 134,

		'ContactsSyncError': 140,

		'CantGetMessageList': 201,
		'CantGetMessage': 202,
		'CantDeleteMessage': 203,
		'CantMoveMessage': 204,
		'CantCopyMessage': 205,

		'CantSaveMessage': 301,
		'CantSendMessage': 302,
		'InvalidRecipients': 303,

		'CantSaveFilters': 351,
		'CantGetFilters': 352,
		'FiltersAreNotCorrect': 355,

		'CantCreateFolder': 400,
		'CantRenameFolder': 401,
		'CantDeleteFolder': 402,
		'CantSubscribeFolder': 403,
		'CantUnsubscribeFolder': 404,
		'CantDeleteNonEmptyFolder': 405,

		'CantSaveSettings': 501,
		'CantSavePluginSettings': 502,

		'DomainAlreadyExists': 601,

		'CantInstallPackage': 701,
		'CantDeletePackage': 702,
		'InvalidPluginPackage': 703,
		'UnsupportedPluginPackage': 704,

		'LicensingServerIsUnavailable': 710,
		'LicensingExpired': 711,
		'LicensingBanned': 712,

		'DemoSendMessageError': 750,
		'DemoAccountError': 751,

		'AccountAlreadyExists': 801,
		'AccountDoesNotExist': 802,

		'MailServerError': 901,
		'ClientViewError': 902,
		'InvalidInputArgument': 903,

		'AjaxFalse': 950,
		'AjaxAbort': 951,
		'AjaxParse': 952,
		'AjaxTimeout': 953,

		'UnknownNotification': 999,
		'UnknownError': 999
	};

/***/ },
/* 5 */
/*!****************************!*\
  !*** ./dev/Knoin/Knoin.js ***!
  \****************************/
/***/ function(module, exports, __webpack_require__) {

	
	(function () {

		'use strict';

		var
			_ = __webpack_require__(/*! _ */ 3),
			$ = __webpack_require__(/*! $ */ 14),
			ko = __webpack_require__(/*! ko */ 2),
			hasher = __webpack_require__(/*! hasher */ 83),
			crossroads = __webpack_require__(/*! crossroads */ 49),

			Globals = __webpack_require__(/*! Common/Globals */ 8),
			Plugins = __webpack_require__(/*! Common/Plugins */ 23),
			Utils = __webpack_require__(/*! Common/Utils */ 1)
		;

		/**
		 * @constructor
		 */
		function Knoin()
		{
			this.oScreens = {};
			this.sDefaultScreenName = '';
			this.oCurrentScreen = null;
		}

		Knoin.prototype.oScreens = {};
		Knoin.prototype.sDefaultScreenName = '';
		Knoin.prototype.oCurrentScreen = null;

		Knoin.prototype.hideLoading = function ()
		{
			$('#rl-content').show();
			$('#rl-loading').hide().remove();
		};

		/**
		 * @param {Object} thisObject
		 */
		Knoin.prototype.constructorEnd = function (thisObject)
		{
			if (Utils.isFunc(thisObject['__constructor_end']))
			{
				thisObject['__constructor_end'].call(thisObject);
			}
		};

		/**
		 * @param {string|Array} mName
		 * @param {Function} ViewModelClass
		 */
		Knoin.prototype.extendAsViewModel = function (mName, ViewModelClass)
		{
			if (ViewModelClass)
			{
				if (Utils.isArray(mName))
				{
					ViewModelClass.__names = mName;
				}
				else
				{
					ViewModelClass.__names = [mName];
				}

				ViewModelClass.__name = ViewModelClass.__names[0];
			}
		};

		/**
		 * @param {Function} SettingsViewModelClass
		 * @param {string} sLabelName
		 * @param {string} sTemplate
		 * @param {string} sRoute
		 * @param {boolean=} bDefault
		 */
		Knoin.prototype.addSettingsViewModel = function (SettingsViewModelClass, sTemplate, sLabelName, sRoute, bDefault)
		{
			SettingsViewModelClass.__rlSettingsData = {
				'Label':  sLabelName,
				'Template':  sTemplate,
				'Route':  sRoute,
				'IsDefault':  !!bDefault
			};

			Globals.aViewModels['settings'].push(SettingsViewModelClass);
		};

		/**
		 * @param {Function} SettingsViewModelClass
		 */
		Knoin.prototype.removeSettingsViewModel = function (SettingsViewModelClass)
		{
			Globals.aViewModels['settings-removed'].push(SettingsViewModelClass);
		};

		/**
		 * @param {Function} SettingsViewModelClass
		 */
		Knoin.prototype.disableSettingsViewModel = function (SettingsViewModelClass)
		{
			Globals.aViewModels['settings-disabled'].push(SettingsViewModelClass);
		};

		Knoin.prototype.routeOff = function ()
		{
			hasher.changed.active = false;
		};

		Knoin.prototype.routeOn = function ()
		{
			hasher.changed.active = true;
		};

		/**
		 * @param {string} sScreenName
		 * @return {?Object}
		 */
		Knoin.prototype.screen = function (sScreenName)
		{
			return ('' !== sScreenName && !Utils.isUnd(this.oScreens[sScreenName])) ? this.oScreens[sScreenName] : null;
		};

		/**
		 * @param {Function} ViewModelClass
		 * @param {Object=} oScreen
		 */
		Knoin.prototype.buildViewModel = function (ViewModelClass, oScreen)
		{
			if (ViewModelClass && !ViewModelClass.__builded)
			{
				var
					kn = this,
					oViewModel = new ViewModelClass(oScreen),
					sPosition = oViewModel.viewModelPosition(),
					oViewModelPlace = $('#rl-content #rl-' + sPosition.toLowerCase()),
					oViewModelDom = null
				;

				ViewModelClass.__builded = true;
				ViewModelClass.__vm = oViewModel;

				oViewModel.onShowTrigger = ko.observable(false);
				oViewModel.onHideTrigger = ko.observable(false);

				oViewModel.viewModelName = ViewModelClass.__name;
				oViewModel.viewModelNames = ViewModelClass.__names;

				if (oViewModelPlace && 1 === oViewModelPlace.length)
				{
					oViewModelDom = $('<div></div>').addClass('rl-view-model').addClass('RL-' + oViewModel.viewModelTemplate()).hide();
					oViewModelDom.appendTo(oViewModelPlace);

					oViewModel.viewModelDom = oViewModelDom;
					ViewModelClass.__dom = oViewModelDom;

					if ('Popups' === sPosition)
					{
						oViewModel.cancelCommand = oViewModel.closeCommand = Utils.createCommand(oViewModel, function () {
							kn.hideScreenPopup(ViewModelClass);
						});

						oViewModel.modalVisibility.subscribe(function (bValue) {

							var self = this;
							if (bValue)
							{
								this.viewModelDom.show();
								this.storeAndSetKeyScope();

								Globals.popupVisibilityNames.push(this.viewModelName);
								oViewModel.viewModelDom.css('z-index', 3000 + Globals.popupVisibilityNames().length + 10);

								if (this.onShowTrigger)
								{
									this.onShowTrigger(!this.onShowTrigger());
								}

								Utils.delegateRun(this, 'onShowWithDelay', [], 500);
							}
							else
							{
								Utils.delegateRun(this, 'onHide');
								Utils.delegateRun(this, 'onHideWithDelay', [], 500);

								if (this.onHideTrigger)
								{
									this.onHideTrigger(!this.onHideTrigger());
								}

								this.restoreKeyScope();

								_.each(this.viewModelNames, function (sName) {
									Plugins.runHook('view-model-on-hide', [sName, self]);
								});

								Globals.popupVisibilityNames.remove(this.viewModelName);
								oViewModel.viewModelDom.css('z-index', 2000);

								_.delay(function () {
									self.viewModelDom.hide();
								}, 300);
							}

						}, oViewModel);
					}

					_.each(ViewModelClass.__names, function (sName) {
						Plugins.runHook('view-model-pre-build', [sName, oViewModel, oViewModelDom]);
					});

					ko.applyBindingAccessorsToNode(oViewModelDom[0], {
						'translatorInit': true,
						'template': function () { return {'name': oViewModel.viewModelTemplate()};}
					}, oViewModel);

					Utils.delegateRun(oViewModel, 'onBuild', [oViewModelDom]);
					if (oViewModel && 'Popups' === sPosition)
					{
						oViewModel.registerPopupKeyDown();
					}

					_.each(ViewModelClass.__names, function (sName) {
						Plugins.runHook('view-model-post-build', [sName, oViewModel, oViewModelDom]);
					});
				}
				else
				{
					Utils.log('Cannot find view model position: ' + sPosition);
				}
			}

			return ViewModelClass ? ViewModelClass.__vm : null;
		};

		/**
		 * @param {Function} ViewModelClassToHide
		 */
		Knoin.prototype.hideScreenPopup = function (ViewModelClassToHide)
		{
			if (ViewModelClassToHide && ViewModelClassToHide.__vm && ViewModelClassToHide.__dom)
			{
				ViewModelClassToHide.__vm.modalVisibility(false);
			}
		};

		/**
		 * @param {Function} ViewModelClassToShow
		 * @param {Array=} aParameters
		 */
		Knoin.prototype.showScreenPopup = function (ViewModelClassToShow, aParameters)
		{
			if (ViewModelClassToShow)
			{
				this.buildViewModel(ViewModelClassToShow);

				if (ViewModelClassToShow.__vm && ViewModelClassToShow.__dom)
				{
					Utils.delegateRun(ViewModelClassToShow.__vm, 'onBeforeShow', aParameters || []);

					ViewModelClassToShow.__vm.modalVisibility(true);

					Utils.delegateRun(ViewModelClassToShow.__vm, 'onShow', aParameters || []);

					_.each(ViewModelClassToShow.__names, function (sName) {
						Plugins.runHook('view-model-on-show', [sName, ViewModelClassToShow.__vm, aParameters || []]);
					});
				}
			}
		};

		/**
		 * @param {Function} ViewModelClassToShow
		 * @return {boolean}
		 */
		Knoin.prototype.isPopupVisible = function (ViewModelClassToShow)
		{
			return ViewModelClassToShow && ViewModelClassToShow.__vm ? ViewModelClassToShow.__vm.modalVisibility() : false;
		};

		/**
		 * @param {string} sScreenName
		 * @param {string} sSubPart
		 */
		Knoin.prototype.screenOnRoute = function (sScreenName, sSubPart)
		{
			var
				self = this,
				oScreen = null,
				bSameScreen= false,
				oCross = null
			;

			if ('' === Utils.pString(sScreenName))
			{
				sScreenName = this.sDefaultScreenName;
			}

			if ('' !== sScreenName)
			{
				oScreen = this.screen(sScreenName);
				if (!oScreen)
				{
					oScreen = this.screen(this.sDefaultScreenName);
					if (oScreen)
					{
						sSubPart = sScreenName + '/' + sSubPart;
						sScreenName = this.sDefaultScreenName;
					}
				}

				if (oScreen && oScreen.__started)
				{
					bSameScreen = this.oCurrentScreen && oScreen === this.oCurrentScreen;

					if (!oScreen.__builded)
					{
						oScreen.__builded = true;

						if (Utils.isNonEmptyArray(oScreen.viewModels()))
						{
							_.each(oScreen.viewModels(), function (ViewModelClass) {
								this.buildViewModel(ViewModelClass, oScreen);
							}, this);
						}

						Utils.delegateRun(oScreen, 'onBuild');
					}

					_.defer(function () {

						// hide screen
						if (self.oCurrentScreen && !bSameScreen)
						{
							Utils.delegateRun(self.oCurrentScreen, 'onHide');
							Utils.delegateRun(self.oCurrentScreen, 'onHideWithDelay', [], 500);

							if (self.oCurrentScreen.onHideTrigger)
							{
								self.oCurrentScreen.onHideTrigger(!self.oCurrentScreen.onHideTrigger());
							}

							if (Utils.isNonEmptyArray(self.oCurrentScreen.viewModels()))
							{
								_.each(self.oCurrentScreen.viewModels(), function (ViewModelClass) {

									if (ViewModelClass.__vm && ViewModelClass.__dom &&
										'Popups' !== ViewModelClass.__vm.viewModelPosition())
									{
										ViewModelClass.__dom.hide();
										ViewModelClass.__vm.viewModelVisibility(false);

										Utils.delegateRun(ViewModelClass.__vm, 'onHide');
										Utils.delegateRun(ViewModelClass.__vm, 'onHideWithDelay', [], 500);

										if (ViewModelClass.__vm.onHideTrigger)
										{
											ViewModelClass.__vm.onHideTrigger(!ViewModelClass.__vm.onHideTrigger());
										}
									}

								});
							}
						}
						// --

						self.oCurrentScreen = oScreen;

						// show screen
						if (self.oCurrentScreen && !bSameScreen)
						{
							Utils.delegateRun(self.oCurrentScreen, 'onShow');
							if (self.oCurrentScreen.onShowTrigger)
							{
								self.oCurrentScreen.onShowTrigger(!self.oCurrentScreen.onShowTrigger());
							}

							Plugins.runHook('screen-on-show', [self.oCurrentScreen.screenName(), self.oCurrentScreen]);

							if (Utils.isNonEmptyArray(self.oCurrentScreen.viewModels()))
							{
								_.each(self.oCurrentScreen.viewModels(), function (ViewModelClass) {

									if (ViewModelClass.__vm && ViewModelClass.__dom &&
										'Popups' !== ViewModelClass.__vm.viewModelPosition())
									{
										Utils.delegateRun(ViewModelClass.__vm, 'onBeforeShow');

										ViewModelClass.__dom.show();
										ViewModelClass.__vm.viewModelVisibility(true);

										Utils.delegateRun(ViewModelClass.__vm, 'onShow');
										if (ViewModelClass.__vm.onShowTrigger)
										{
											ViewModelClass.__vm.onShowTrigger(!ViewModelClass.__vm.onShowTrigger());
										}

										Utils.delegateRun(ViewModelClass.__vm, 'onShowWithDelay', [], 200);

										_.each(ViewModelClass.__names, function (sName) {
											Plugins.runHook('view-model-on-show', [sName, ViewModelClass.__vm]);
										});
									}

								}, self);
							}
						}
						// --

						oCross = oScreen.__cross ? oScreen.__cross() : null;
						if (oCross)
						{
							oCross.parse(sSubPart);
						}
					});
				}
			}
		};

		/**
		 * @param {Array} aScreensClasses
		 */
		Knoin.prototype.startScreens = function (aScreensClasses)
		{
			$('#rl-content').css({
				'visibility': 'hidden'
			});

			_.each(aScreensClasses, function (CScreen) {

					if (CScreen)
					{
						var
							oScreen = new CScreen(),
							sScreenName = oScreen ? oScreen.screenName() : ''
						;

						if (oScreen && '' !== sScreenName)
						{
							if ('' === this.sDefaultScreenName)
							{
								this.sDefaultScreenName = sScreenName;
							}

							this.oScreens[sScreenName] = oScreen;
						}
					}

				}, this);


			_.each(this.oScreens, function (oScreen) {
				if (oScreen && !oScreen.__started && oScreen.__start)
				{
					oScreen.__started = true;
					oScreen.__start();

					Plugins.runHook('screen-pre-start', [oScreen.screenName(), oScreen]);
					Utils.delegateRun(oScreen, 'onStart');
					Plugins.runHook('screen-post-start', [oScreen.screenName(), oScreen]);
				}
			}, this);

			var oCross = crossroads.create();
			oCross.addRoute(/^([a-zA-Z0-9\-]*)\/?(.*)$/, _.bind(this.screenOnRoute, this));

			hasher.initialized.add(oCross.parse, oCross);
			hasher.changed.add(oCross.parse, oCross);
			hasher.init();

			$('#rl-content').css({
				'visibility': 'visible'
			});

			_.delay(function () {
				Globals.$html.removeClass('rl-started-trigger').addClass('rl-started');
			}, 100);

			_.delay(function () {
				Globals.$html.addClass('rl-started-delay');
			}, 200);
		};

		/**
		 * @param {string} sHash
		 * @param {boolean=} bSilence = false
		 * @param {boolean=} bReplace = false
		 */
		Knoin.prototype.setHash = function (sHash, bSilence, bReplace)
		{
			sHash = '#' === sHash.substr(0, 1) ? sHash.substr(1) : sHash;
			sHash = '/' === sHash.substr(0, 1) ? sHash.substr(1) : sHash;

			bReplace = Utils.isUnd(bReplace) ? false : !!bReplace;

			if (Utils.isUnd(bSilence) ? false : !!bSilence)
			{
				hasher.changed.active = false;
				hasher[bReplace ? 'replaceHash' : 'setHash'](sHash);
				hasher.changed.active = true;
			}
			else
			{
				hasher.changed.active = true;
				hasher[bReplace ? 'replaceHash' : 'setHash'](sHash);
				hasher.setHash(sHash);
			}
		};

		module.exports = new Knoin();

	}());

/***/ },
/* 6 */
/*!***********************************!*\
  !*** ./dev/Common/Translator.jsx ***!
  \***********************************/
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

	var _common = __webpack_require__(/*! common */ 12);

	var _ko = __webpack_require__(/*! ko */ 2);

	var _ko2 = _interopRequireDefault(_ko);

	var _Enums = __webpack_require__(/*! Common/Enums */ 4);

	var _Utils = __webpack_require__(/*! Common/Utils */ 1);

	var _Utils2 = _interopRequireDefault(_Utils);

	var _Globals = __webpack_require__(/*! Common/Globals */ 8);

	var _Globals2 = _interopRequireDefault(_Globals);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

	var Translator = (function () {
		function Translator() {
			_classCallCheck(this, Translator);

			this.data = {};
			this.notificationI18N = {};

			this.data = _common.window['rainloopI18N'] || {};
			this.trigger = _ko2.default.observable(false);
			this.i18n = _common._.bind(this.i18n, this);
			this.init();
		}

		/**
	  * @param {string} key
	  * @param {Object=} valueList
	  * @param {string=} defaulValue
	  * @return {string}
	  */

		_createClass(Translator, [{
			key: 'i18n',
			value: function i18n(key, valueList, defaulValue) {

				var valueName = '',
				    result = _common._.isUndefined(this.data[key]) ? _common._.isUndefined(defaulValue) ? key : defaulValue : this.data[key];

				if (!_common._.isUndefined(valueList) && !_common._.isNull(valueList)) {
					for (valueName in valueList) {
						if (_common._.has(valueList, valueName)) {
							result = result.replace('%' + valueName + '%', valueList[valueName]);
						}
					}
				}

				return result;
			}

			/**
	   * @param {Object} element
	   */

		}, {
			key: 'i18nToNode',
			value: function i18nToNode(element) {

				var $el = (0, _common.$)(element),
				    key = $el.data('i18n');

				if (key) {
					if ('[' === key.substr(0, 1)) {
						switch (key.substr(0, 6)) {
							case '[html]':
								$el.html(this.i18n(key.substr(6)));
								break;
							case '[place':
								$el.attr('placeholder', this.i18n(key.substr(13)));
								break;
							case '[title':
								$el.attr('title', this.i18n(key.substr(7)));
								break;
						}
					} else {
						$el.text(this.i18n(key));
					}
				}
			}

			/**
	   * @param {Object} elements
	   * @param {boolean=} animate = false
	   */

		}, {
			key: 'i18nToNodes',
			value: function i18nToNodes(elements) {
				var _this = this;

				var animate = arguments.length <= 1 || arguments[1] === undefined ? false : arguments[1];

				_common._.defer(function () {

					(0, _common.$)('[data-i18n]', elements).each(function (index, item) {
						_this.i18nToNode(item);
					});

					if (animate && _Globals2.default.bAnimationSupported) {
						(0, _common.$)('.i18n-animation[data-i18n]', elements).letterfx({
							'fx': 'fall fade', 'backwards': false, 'timing': 50, 'fx_duration': '50ms', 'letter_end': 'restore', 'element_end': 'restore'
						});
					}
				});
			}
		}, {
			key: 'reloadData',
			value: function reloadData() {
				if (_common.window['rainloopI18N']) {
					this.data = _common.window['rainloopI18N'] || {};

					this.i18nToNodes(_common.window.document, true);

					__webpack_require__(/*! Common/Momentor */ 26).reload();
					this.trigger(!this.trigger());
				}

				_common.window['rainloopI18N'] = null;
			}
		}, {
			key: 'initNotificationLanguage',
			value: function initNotificationLanguage() {
				var _this2 = this;

				var map = [[_Enums.Notification.InvalidToken, 'NOTIFICATIONS/INVALID_TOKEN'], [_Enums.Notification.InvalidToken, 'NOTIFICATIONS/INVALID_TOKEN'], [_Enums.Notification.AuthError, 'NOTIFICATIONS/AUTH_ERROR'], [_Enums.Notification.AccessError, 'NOTIFICATIONS/ACCESS_ERROR'], [_Enums.Notification.ConnectionError, 'NOTIFICATIONS/CONNECTION_ERROR'], [_Enums.Notification.CaptchaError, 'NOTIFICATIONS/CAPTCHA_ERROR'], [_Enums.Notification.SocialFacebookLoginAccessDisable, 'NOTIFICATIONS/SOCIAL_FACEBOOK_LOGIN_ACCESS_DISABLE'], [_Enums.Notification.SocialTwitterLoginAccessDisable, 'NOTIFICATIONS/SOCIAL_TWITTER_LOGIN_ACCESS_DISABLE'], [_Enums.Notification.SocialGoogleLoginAccessDisable, 'NOTIFICATIONS/SOCIAL_GOOGLE_LOGIN_ACCESS_DISABLE'], [_Enums.Notification.DomainNotAllowed, 'NOTIFICATIONS/DOMAIN_NOT_ALLOWED'], [_Enums.Notification.AccountNotAllowed, 'NOTIFICATIONS/ACCOUNT_NOT_ALLOWED'], [_Enums.Notification.AccountTwoFactorAuthRequired, 'NOTIFICATIONS/ACCOUNT_TWO_FACTOR_AUTH_REQUIRED'], [_Enums.Notification.AccountTwoFactorAuthError, 'NOTIFICATIONS/ACCOUNT_TWO_FACTOR_AUTH_ERROR'], [_Enums.Notification.CouldNotSaveNewPassword, 'NOTIFICATIONS/COULD_NOT_SAVE_NEW_PASSWORD'], [_Enums.Notification.CurrentPasswordIncorrect, 'NOTIFICATIONS/CURRENT_PASSWORD_INCORRECT'], [_Enums.Notification.NewPasswordShort, 'NOTIFICATIONS/NEW_PASSWORD_SHORT'], [_Enums.Notification.NewPasswordWeak, 'NOTIFICATIONS/NEW_PASSWORD_WEAK'], [_Enums.Notification.NewPasswordForbidden, 'NOTIFICATIONS/NEW_PASSWORD_FORBIDDENT'], [_Enums.Notification.ContactsSyncError, 'NOTIFICATIONS/CONTACTS_SYNC_ERROR'], [_Enums.Notification.CantGetMessageList, 'NOTIFICATIONS/CANT_GET_MESSAGE_LIST'], [_Enums.Notification.CantGetMessage, 'NOTIFICATIONS/CANT_GET_MESSAGE'], [_Enums.Notification.CantDeleteMessage, 'NOTIFICATIONS/CANT_DELETE_MESSAGE'], [_Enums.Notification.CantMoveMessage, 'NOTIFICATIONS/CANT_MOVE_MESSAGE'], [_Enums.Notification.CantCopyMessage, 'NOTIFICATIONS/CANT_MOVE_MESSAGE'], [_Enums.Notification.CantSaveMessage, 'NOTIFICATIONS/CANT_SAVE_MESSAGE'], [_Enums.Notification.CantSendMessage, 'NOTIFICATIONS/CANT_SEND_MESSAGE'], [_Enums.Notification.InvalidRecipients, 'NOTIFICATIONS/INVALID_RECIPIENTS'], [_Enums.Notification.CantSaveFilters, 'NOTIFICATIONS/CANT_SAVE_FILTERS'], [_Enums.Notification.CantGetFilters, 'NOTIFICATIONS/CANT_GET_FILTERS'], [_Enums.Notification.FiltersAreNotCorrect, 'NOTIFICATIONS/FILTERS_ARE_NOT_CORRECT'], [_Enums.Notification.CantCreateFolder, 'NOTIFICATIONS/CANT_CREATE_FOLDER'], [_Enums.Notification.CantRenameFolder, 'NOTIFICATIONS/CANT_RENAME_FOLDER'], [_Enums.Notification.CantDeleteFolder, 'NOTIFICATIONS/CANT_DELETE_FOLDER'], [_Enums.Notification.CantDeleteNonEmptyFolder, 'NOTIFICATIONS/CANT_DELETE_NON_EMPTY_FOLDER'], [_Enums.Notification.CantSubscribeFolder, 'NOTIFICATIONS/CANT_SUBSCRIBE_FOLDER'], [_Enums.Notification.CantUnsubscribeFolder, 'NOTIFICATIONS/CANT_UNSUBSCRIBE_FOLDER'], [_Enums.Notification.CantSaveSettings, 'NOTIFICATIONS/CANT_SAVE_SETTINGS'], [_Enums.Notification.CantSavePluginSettings, 'NOTIFICATIONS/CANT_SAVE_PLUGIN_SETTINGS'], [_Enums.Notification.DomainAlreadyExists, 'NOTIFICATIONS/DOMAIN_ALREADY_EXISTS'], [_Enums.Notification.CantInstallPackage, 'NOTIFICATIONS/CANT_INSTALL_PACKAGE'], [_Enums.Notification.CantDeletePackage, 'NOTIFICATIONS/CANT_DELETE_PACKAGE'], [_Enums.Notification.InvalidPluginPackage, 'NOTIFICATIONS/INVALID_PLUGIN_PACKAGE'], [_Enums.Notification.UnsupportedPluginPackage, 'NOTIFICATIONS/UNSUPPORTED_PLUGIN_PACKAGE'], [_Enums.Notification.LicensingServerIsUnavailable, 'NOTIFICATIONS/LICENSING_SERVER_IS_UNAVAILABLE'], [_Enums.Notification.LicensingExpired, 'NOTIFICATIONS/LICENSING_EXPIRED'], [_Enums.Notification.LicensingBanned, 'NOTIFICATIONS/LICENSING_BANNED'], [_Enums.Notification.DemoSendMessageError, 'NOTIFICATIONS/DEMO_SEND_MESSAGE_ERROR'], [_Enums.Notification.DemoAccountError, 'NOTIFICATIONS/DEMO_ACCOUNT_ERROR'], [_Enums.Notification.AccountAlreadyExists, 'NOTIFICATIONS/ACCOUNT_ALREADY_EXISTS'], [_Enums.Notification.AccountDoesNotExist, 'NOTIFICATIONS/ACCOUNT_DOES_NOT_EXIST'], [_Enums.Notification.MailServerError, 'NOTIFICATIONS/MAIL_SERVER_ERROR'], [_Enums.Notification.InvalidInputArgument, 'NOTIFICATIONS/INVALID_INPUT_ARGUMENT'], [_Enums.Notification.UnknownNotification, 'NOTIFICATIONS/UNKNOWN_ERROR'], [_Enums.Notification.UnknownError, 'NOTIFICATIONS/UNKNOWN_ERROR']];

				this.notificationI18N = this.notificationI18N || {};

				map.forEach(function (item) {
					_this2.notificationI18N[item[0]] = _this2.i18n(item[1]);
				});
			}

			/**
	   * @param {Function} callback
	   * @param {Object} scope
	   * @param {Function=} langCallback
	   */

		}, {
			key: 'initOnStartOrLangChange',
			value: function initOnStartOrLangChange(callback, scope) {
				var langCallback = arguments.length <= 2 || arguments[2] === undefined ? null : arguments[2];

				if (callback) {
					callback.call(scope);
				}

				if (langCallback) {
					this.trigger.subscribe(function () {
						if (callback) {
							callback.call(scope);
						}

						langCallback.call(scope);
					});
				} else if (callback) {
					this.trigger.subscribe(callback, scope);
				}
			}

			/**
	   * @param {number} code
	   * @param {*=} message = ''
	   * @param {*=} defCode = null
	   * @return {string}
	   */

		}, {
			key: 'getNotification',
			value: function getNotification(code) {
				var message = arguments.length <= 1 || arguments[1] === undefined ? '' : arguments[1];
				var defCode = arguments.length <= 2 || arguments[2] === undefined ? null : arguments[2];

				code = _common.window.parseInt(code, 10) || 0;
				if (_Enums.Notification.ClientViewError === code && message) {
					return message;
				}

				defCode = defCode ? _common.window.parseInt(defCode, 10) || 0 : 0;
				return _common._.isUndefined(this.notificationI18N[code]) ? defCode && _common._.isUndefined(this.notificationI18N[defCode]) ? this.notificationI18N[defCode] : '' : this.notificationI18N[code];
			}

			/**
	   * @param {object} response
	   * @param {number} defCode = Notification.UnknownNotification
	   * @return {string}
	   */

		}, {
			key: 'getNotificationFromResponse',
			value: function getNotificationFromResponse(response) {
				var defCode = arguments.length <= 1 || arguments[1] === undefined ? _Enums.Notification.UnknownNotification : arguments[1];

				return response && response.ErrorCode ? this.getNotification(_Utils2.default.pInt(response.ErrorCode), response.ErrorMessage || '') : this.getNotification(defCode);
			}

			/**
	   * @param {*} code
	   * @return {string}
	   */

		}, {
			key: 'getUploadErrorDescByCode',
			value: function getUploadErrorDescByCode(code) {
				var result = '';
				switch (_common.window.parseInt(code, 10) || 0) {
					case _Enums.UploadErrorCode.FileIsTooBig:
						result = this.i18n('UPLOAD/ERROR_FILE_IS_TOO_BIG');
						break;
					case _Enums.UploadErrorCode.FilePartiallyUploaded:
						result = this.i18n('UPLOAD/ERROR_FILE_PARTIALLY_UPLOADED');
						break;
					case _Enums.UploadErrorCode.FileNoUploaded:
						result = this.i18n('UPLOAD/ERROR_NO_FILE_UPLOADED');
						break;
					case _Enums.UploadErrorCode.MissingTempFolder:
						result = this.i18n('UPLOAD/ERROR_MISSING_TEMP_FOLDER');
						break;
					case _Enums.UploadErrorCode.FileOnSaveingError:
						result = this.i18n('UPLOAD/ERROR_ON_SAVING_FILE');
						break;
					case _Enums.UploadErrorCode.FileType:
						result = this.i18n('UPLOAD/ERROR_FILE_TYPE');
						break;
					default:
						result = this.i18n('UPLOAD/ERROR_UNKNOWN');
						break;
				}

				return result;
			}

			/**
	   * @param {boolean} admin
	   * @param {string} language
	   * @param {Function=} done
	   * @param {Function=} fail
	   */

		}, {
			key: 'reload',
			value: function reload(admin, language, done, fail) {

				var self = this,
				    start = _Utils2.default.microtime();

				_Globals2.default.$html.addClass('rl-changing-language');

				_common.$.ajax({
					'url': __webpack_require__(/*! Common/Links */ 11).langLink(language, admin),
					'dataType': 'script',
					'cache': true
				}).fail(fail || _Utils2.default.emptyFunction).done(function () {
					_common._.delay(function () {

						self.reloadData();

						(done || _Utils2.default.emptyFunction)();

						var isRtl = -1 < _Utils2.default.inArray(language, ['ar', 'ar_sa', 'he', 'he_he', 'ur', 'ur_ir']);

						_Globals2.default.$html.removeClass('rl-changing-language').removeClass('rl-rtl rl-ltr').addClass(isRtl ? 'rl-rtl' : 'rl-ltr')
						//						.attr('dir', isRtl ? 'rtl' : 'ltr')
						;
					}, 500 < _Utils2.default.microtime() - start ? 1 : 500);
				});
			}
		}, {
			key: 'init',
			value: function init() {
				_Globals2.default.$html.addClass('rl-' + (_Globals2.default.$html.attr('dir') || 'ltr'));
			}
		}]);

		return Translator;
	})();

	module.exports = new Translator();

/***/ },
/* 7 */,
/* 8 */
/*!*******************************!*\
  !*** ./dev/Common/Globals.js ***!
  \*******************************/
/***/ function(module, exports, __webpack_require__) {

	
	/* global RL_COMMUNITY */

	(function () {

		'use strict';

		var
			Globals = {},

			window = __webpack_require__(/*! window */ 13),
			_ = __webpack_require__(/*! _ */ 3),
			$ = __webpack_require__(/*! $ */ 14),
			ko = __webpack_require__(/*! ko */ 2),
			key = __webpack_require__(/*! key */ 18),

			Enums = __webpack_require__(/*! Common/Enums */ 4)
		;

		Globals.$win = $(window);
		Globals.$doc = $(window.document);
		Globals.$html = $('html');
		Globals.$body = $('body');
		Globals.$div = $('<div></div>');

		Globals.$win.__sizes = [0, 0];

		/**
		 * @type {?}
		 */
		Globals.startMicrotime = (new window.Date()).getTime();

		/**
		 * @type {boolean}
		 */
		Globals.community = (false);

		/**
		 * @type {?}
		 */
		Globals.dropdownVisibility = ko.observable(false).extend({'rateLimit': 0});

		/**
		 * @type {boolean}
		 */
		Globals.useKeyboardShortcuts = ko.observable(true);

		/**
		 * @type {number}
		 */
		Globals.iAjaxErrorCount = 0;

		/**
		 * @type {number}
		 */
		Globals.iTokenErrorCount = 0;

		/**
		 * @type {number}
		 */
		Globals.iMessageBodyCacheCount = 0;

		/**
		 * @type {boolean}
		 */
		Globals.bUnload = false;

		/**
		 * @type {string}
		 */
		Globals.sUserAgent = 'navigator' in window && 'userAgent' in window.navigator &&
			window.navigator.userAgent.toLowerCase() || '';

		/**
		 * @type {boolean}
		 */
		Globals.bIE = Globals.sUserAgent.indexOf('msie') > -1;

		/**
		 * @type {boolean}
		 */
		Globals.bChrome = Globals.sUserAgent.indexOf('chrome') > -1;

		/**
		 * @type {boolean}
		 */
		Globals.bSafari = !Globals.bChrome && Globals.sUserAgent.indexOf('safari') > -1;

		/**
		 * @type {boolean}
		 */
		Globals.bMobileDevice =
			/android/i.test(Globals.sUserAgent) ||
			/iphone/i.test(Globals.sUserAgent) ||
			/ipod/i.test(Globals.sUserAgent) ||
			/ipad/i.test(Globals.sUserAgent) ||
			/blackberry/i.test(Globals.sUserAgent)
		;

		/**
		 * @type {boolean}
		 */
		Globals.bDisableNanoScroll = Globals.bMobileDevice;

		/**
		 * @type {boolean}
		 */
		Globals.bAllowPdfPreview = !Globals.bMobileDevice;

		/**
		 * @type {boolean}
		 */
		Globals.bAnimationSupported = !Globals.bMobileDevice &&
			Globals.$html.hasClass('csstransitions') &&
			Globals.$html.hasClass('cssanimations');

		/**
		 * @type {boolean}
		 */
		Globals.bXMLHttpRequestSupported = !!window.XMLHttpRequest;

		/**
		 * @type {*}
		 */
		Globals.__APP__ = null;

		/**
		 * @type {Object}
		 */
		Globals.oHtmlEditorDefaultConfig = {
			'title': false,
			'stylesSet': false,
			'customConfig': '',
			'contentsCss': '',
			'toolbarGroups': [
				{name: 'spec'},
				{name: 'styles'},
				{name: 'basicstyles', groups: ['basicstyles', 'cleanup', 'bidi']},
				{name: 'colors'},
				{name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align']},
				{name: 'links'},
				{name: 'insert'},
				{name: 'document', groups: ['mode', 'document', 'doctools']},
				{name: 'others'}
			],

			'removePlugins': 'liststyle',
			'removeButtons': 'Format,Undo,Redo,Cut,Copy,Paste,Anchor,Strike,Subscript,Superscript,Image,SelectAll,Source',
			'removeDialogTabs': 'link:advanced;link:target;image:advanced;images:advanced',

			'extraPlugins': 'plain,signature',

			'allowedContent': true,
			'extraAllowedContent': true,

			'fillEmptyBlocks': false,
			'ignoreEmptyParagraph': true,
			'disableNativeSpellChecker': false,

			'font_defaultLabel': 'Arial',
			'fontSize_defaultLabel': '13',
			'fontSize_sizes': '10/10px;12/12px;13/13px;14/14px;16/16px;18/18px;20/20px;24/24px;28/28px;36/36px;48/48px'
		};

		/**
		 * @type {Object}
		 */
		Globals.oHtmlEditorLangsMap = {
			'bg_bg': 'bg',
			'de_de': 'de',
			'es_es': 'es',
			'fr_fr': 'fr',
			'hu_hu': 'hu',
			'is_is': 'is',
			'it_it': 'it',
			'ja_jp': 'ja',
			'ko_kr': 'ko',
			'lt_lt': 'lt',
			'lv_lv': 'lv',
			'nl_nl': 'nl',
			'bg_no': 'no',
			'pl_pl': 'pl',
			'pt_pt': 'pt',
			'pt_br': 'pt-br',
			'ro_ro': 'ro',
			'ru_ru': 'ru',
			'sk_sk': 'sk',
			'sv_se': 'sv',
			'tr_tr': 'tr',
			'uk_ua': 'ru',
			'zh_tw': 'zh',
			'zh_cn': 'zh-cn'
		};

		if (Globals.bAllowPdfPreview && window.navigator && window.navigator.mimeTypes)
		{
			Globals.bAllowPdfPreview = !!_.find(window.navigator.mimeTypes, function (oType) {
				return oType && 'application/pdf' === oType.type;
			});
		}

		Globals.aBootstrapDropdowns = [];

		Globals.aViewModels = {
			'settings': [],
			'settings-removed': [],
			'settings-disabled': []
		};

		Globals.leftPanelDisabled = ko.observable(false);
		Globals.leftPanelType = ko.observable('');

		// popups
		Globals.popupVisibilityNames = ko.observableArray([]);

		Globals.popupVisibility = ko.computed(function () {
			return 0 < Globals.popupVisibilityNames().length;
		}, this);

		Globals.popupVisibility.subscribe(function (bValue) {
			Globals.$html.toggleClass('rl-modal', bValue);
		});

		// keys
		Globals.keyScopeReal = ko.observable(Enums.KeyState.All);
		Globals.keyScopeFake = ko.observable(Enums.KeyState.All);

		Globals.keyScope = ko.computed({
			'owner': this,
			'read': function () {
				return Globals.keyScopeFake();
			},
			'write': function (sValue) {

				if (Enums.KeyState.Menu !== sValue)
				{
					if (Enums.KeyState.Compose === sValue)
					{
						// disableKeyFilter
						key.filter = function () {
							return Globals.useKeyboardShortcuts();
						};
					}
					else
					{
						// restoreKeyFilter
						key.filter = function (event) {

							if (Globals.useKeyboardShortcuts())
							{
								var
									oElement = event.target || event.srcElement,
									sTagName = oElement ? oElement.tagName : ''
								;

								sTagName = sTagName.toUpperCase();
								return !(sTagName === 'INPUT' || sTagName === 'SELECT' || sTagName === 'TEXTAREA' ||
									(oElement && sTagName === 'DIV' && ('editorHtmlArea' === oElement.className || 'true' === '' + oElement.contentEditable))
								);
							}

							return false;
						};
					}

					Globals.keyScopeFake(sValue);
					if (Globals.dropdownVisibility())
					{
						sValue = Enums.KeyState.Menu;
					}
				}

				Globals.keyScopeReal(sValue);
			}
		});

		Globals.keyScopeReal.subscribe(function (sValue) {
	//		window.console.log('keyScope=' + sValue); // DEBUG
			key.setScope(sValue);
		});

		Globals.dropdownVisibility.subscribe(function (bValue) {
			if (bValue)
			{
				Globals.keyScope(Enums.KeyState.Menu);
			}
			else if (Enums.KeyState.Menu === key.getScope())
			{
				Globals.keyScope(Globals.keyScopeFake());
			}
		});

		module.exports = Globals;

	}());

/***/ },
/* 9 */
/*!**********************************!*\
  !*** ./dev/Storage/Settings.jsx ***!
  \**********************************/
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

	var _common = __webpack_require__(/*! common */ 12);

	var _Utils = __webpack_require__(/*! Common/Utils */ 1);

	var _Utils2 = _interopRequireDefault(_Utils);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

	var SettingsStorage = (function () {
		function SettingsStorage() {
			_classCallCheck(this, SettingsStorage);

			this.settings = _common.window['rainloopAppData'] || {};
			this.settings = _Utils2.default.isNormal(this.settings) ? this.settings : {};
		}

		/**
	  * @param {string} name
	  * @return {*}
	  */

		_createClass(SettingsStorage, [{
			key: 'settingsGet',
			value: function settingsGet(name) {
				return _Utils2.default.isUnd(this.settings[name]) ? null : this.settings[name];
			}

			/**
	   * @param {string} name
	   * @param {*} value
	   */

		}, {
			key: 'settingsSet',
			value: function settingsSet(name, value) {
				this.settings[name] = value;
			}

			/**
	   * @param {string} name
	   * @return {boolean}
	   */

		}, {
			key: 'capa',
			value: function capa(name) {
				var values = this.settingsGet('Capa');
				return _Utils2.default.isArray(values) && _Utils2.default.isNormal(name) && -1 < _Utils2.default.inArray(name, values);
			}
		}]);

		return SettingsStorage;
	})();

	module.exports = new SettingsStorage();

/***/ },
/* 10 */
/*!***********************************!*\
  !*** ./dev/Knoin/AbstractView.js ***!
  \***********************************/
/***/ function(module, exports, __webpack_require__) {

	
	(function () {

		'use strict';

		var
			ko = __webpack_require__(/*! ko */ 2),

			Enums = __webpack_require__(/*! Common/Enums */ 4),
			Utils = __webpack_require__(/*! Common/Utils */ 1),
			Globals = __webpack_require__(/*! Common/Globals */ 8)
		;

		/**
		 * @constructor
		 * @param {string=} sPosition = ''
		 * @param {string=} sTemplate = ''
		 */
		function AbstractView(sPosition, sTemplate)
		{
			this.bDisabeCloseOnEsc = false;
			this.sPosition = Utils.pString(sPosition);
			this.sTemplate = Utils.pString(sTemplate);

			this.sDefaultKeyScope = Enums.KeyState.None;
			this.sCurrentKeyScope = this.sDefaultKeyScope;

			this.viewModelVisibility = ko.observable(false);
			this.modalVisibility = ko.observable(false).extend({'rateLimit': 0});

			this.viewModelName = '';
			this.viewModelNames = [];
			this.viewModelDom = null;
		}

		/**
		 * @type {boolean}
		 */
		AbstractView.prototype.bDisabeCloseOnEsc = false;

		/**
		 * @type {string}
		 */
		AbstractView.prototype.sPosition = '';

		/**
		 * @type {string}
		 */
		AbstractView.prototype.sTemplate = '';

		/**
		 * @type {string}
		 */
		AbstractView.prototype.sDefaultKeyScope = Enums.KeyState.None;

		/**
		 * @type {string}
		 */
		AbstractView.prototype.sCurrentKeyScope = Enums.KeyState.None;

		/**
		 * @type {string}
		 */
		AbstractView.prototype.viewModelName = '';

		/**
		 * @type {Array}
		 */
		AbstractView.prototype.viewModelNames = [];

		/**
		 * @type {?}
		 */
		AbstractView.prototype.viewModelDom = null;

		/**
		 * @return {string}
		 */
		AbstractView.prototype.viewModelTemplate = function ()
		{
			return this.sTemplate;
		};

		/**
		 * @return {string}
		 */
		AbstractView.prototype.viewModelPosition = function ()
		{
			return this.sPosition;
		};

		AbstractView.prototype.cancelCommand = function () {};
		AbstractView.prototype.closeCommand = function () {};

		AbstractView.prototype.storeAndSetKeyScope = function ()
		{
			this.sCurrentKeyScope = Globals.keyScope();
			Globals.keyScope(this.sDefaultKeyScope);
		};

		AbstractView.prototype.restoreKeyScope = function ()
		{
			Globals.keyScope(this.sCurrentKeyScope);
		};

		AbstractView.prototype.registerPopupKeyDown = function ()
		{
			var self = this;

			Globals.$win.on('keydown', function (oEvent) {
				if (oEvent && self.modalVisibility && self.modalVisibility())
				{
					if (!this.bDisabeCloseOnEsc && Enums.EventKeyCode.Esc === oEvent.keyCode)
					{
						Utils.delegateRun(self, 'cancelCommand');
						return false;
					}
					else if (Enums.EventKeyCode.Backspace === oEvent.keyCode && !Utils.inFocus())
					{
						return false;
					}
				}

				return true;
			});
		};

		module.exports = AbstractView;

	}());

/***/ },
/* 11 */
/*!******************************!*\
  !*** ./dev/Common/Links.jsx ***!
  \******************************/
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

	var _common = __webpack_require__(/*! common */ 12);

	var _Utils = __webpack_require__(/*! Common/Utils */ 1);

	var _Utils2 = _interopRequireDefault(_Utils);

	var _Settings = __webpack_require__(/*! Storage/Settings */ 9);

	var _Settings2 = _interopRequireDefault(_Settings);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

	var Links = (function () {
		function Links() {
			_classCallCheck(this, Links);

			this.sBase = '#/';
			this.sServer = './?';

			this.sVersion = _Settings2.default.settingsGet('Version');
			this.sAuthSuffix = _Settings2.default.settingsGet('AuthAccountHash') || '0';
			this.sWebPrefix = _Settings2.default.settingsGet('WebPath') || '';
			this.sVersionPrefix = _Settings2.default.settingsGet('WebVersionPath') || 'source/v/' + this.sVersion + '/';
			this.sStaticPrefix = this.sVersionPrefix + 'static/';
		}

		_createClass(Links, [{
			key: 'populateAuthSuffix',
			value: function populateAuthSuffix() {
				this.sAuthSuffix = _Settings2.default.settingsGet('AuthAccountHash') || '0';
			}

			/**
	   * @return {string}
	   */

		}, {
			key: 'subQueryPrefix',
			value: function subQueryPrefix() {
				return '&q[]=';
			}

			/**
	   * @param {string=} startupUrl
	   * @return {string}
	   */

		}, {
			key: 'root',
			value: function root() {
				var startupUrl = arguments.length <= 0 || arguments[0] === undefined ? '' : arguments[0];

				return this.sBase + _Utils2.default.pString(startupUrl);
			}

			/**
	   * @return {string}
	   */

		}, {
			key: 'rootAdmin',
			value: function rootAdmin() {
				return this.sServer + '/Admin/';
			}

			/**
	   * @return {string}
	   */

		}, {
			key: 'rootUser',
			value: function rootUser() {
				return './';
			}

			/**
	   * @param {string} type
	   * @param {string} download
	   * @param {string=} customSpecSuffix
	   * @return {string}
	   */

		}, {
			key: 'attachmentRaw',
			value: function attachmentRaw(type, download, customSpecSuffix) {
				customSpecSuffix = _Utils2.default.isUnd(customSpecSuffix) ? this.sAuthSuffix : customSpecSuffix;
				return this.sServer + '/Raw/' + this.subQueryPrefix() + '/' + customSpecSuffix + '/' + type + '/' + this.subQueryPrefix() + '/' + download;
			}

			/**
	   * @param {string} download
	   * @param {string=} customSpecSuffix
	   * @return {string}
	   */

		}, {
			key: 'attachmentDownload',
			value: function attachmentDownload(download, customSpecSuffix) {
				return this.attachmentRaw('Download', download, customSpecSuffix);
			}

			/**
	   * @param {string} download
	   * @param {string=} customSpecSuffix
	   * @return {string}
	   */

		}, {
			key: 'attachmentPreview',
			value: function attachmentPreview(download, customSpecSuffix) {
				return this.attachmentRaw('View', download, customSpecSuffix);
			}

			/**
	   * @param {string} download
	   * @param {string=} customSpecSuffix
	   * @return {string}
	   */

		}, {
			key: 'attachmentThumbnailPreview',
			value: function attachmentThumbnailPreview(download, customSpecSuffix) {
				return this.attachmentRaw('ViewThumbnail', download, customSpecSuffix);
			}

			/**
	   * @param {string} download
	   * @param {string=} customSpecSuffix
	   * @return {string}
	   */

		}, {
			key: 'attachmentPreviewAsPlain',
			value: function attachmentPreviewAsPlain(download, customSpecSuffix) {
				return this.attachmentRaw('ViewAsPlain', download, customSpecSuffix);
			}

			/**
	   * @param {string} download
	   * @param {string=} customSpecSuffix
	   * @return {string}
	   */

		}, {
			key: 'attachmentFramed',
			value: function attachmentFramed(download, customSpecSuffix) {
				return this.attachmentRaw('FramedView', download, customSpecSuffix);
			}

			/**
	   * @return {string}
	   */

		}, {
			key: 'upload',
			value: function upload() {
				return this.sServer + '/Upload/' + this.subQueryPrefix() + '/' + this.sAuthSuffix + '/';
			}

			/**
	   * @return {string}
	   */

		}, {
			key: 'uploadContacts',
			value: function uploadContacts() {
				return this.sServer + '/UploadContacts/' + this.subQueryPrefix() + '/' + this.sAuthSuffix + '/';
			}

			/**
	   * @return {string}
	   */

		}, {
			key: 'uploadBackground',
			value: function uploadBackground() {
				return this.sServer + '/UploadBackground/' + this.subQueryPrefix() + '/' + this.sAuthSuffix + '/';
			}

			/**
	   * @return {string}
	   */

		}, {
			key: 'append',
			value: function append() {
				return this.sServer + '/Append/' + this.subQueryPrefix() + '/' + this.sAuthSuffix + '/';
			}

			/**
	   * @param {string} email
	   * @return {string}
	   */

		}, {
			key: 'change',
			value: function change(email) {
				return this.sServer + '/Change/' + this.subQueryPrefix() + '/' + this.sAuthSuffix + '/' + _Utils2.default.encodeURIComponent(email) + '/';
			}

			/**
	   * @param {string} add
	   * @return {string}
	   */

		}, {
			key: 'ajax',
			value: function ajax(add) {
				return this.sServer + '/Ajax/' + this.subQueryPrefix() + '/' + this.sAuthSuffix + '/' + add;
			}

			/**
	   * @param {string} requestHash
	   * @return {string}
	   */

		}, {
			key: 'messageViewLink',
			value: function messageViewLink(requestHash) {
				return this.sServer + '/Raw/' + this.subQueryPrefix() + '/' + this.sAuthSuffix + '/ViewAsPlain/' + this.subQueryPrefix() + '/' + requestHash;
			}

			/**
	   * @param {string} requestHash
	   * @return {string}
	   */

		}, {
			key: 'messageDownloadLink',
			value: function messageDownloadLink(requestHash) {
				return this.sServer + '/Raw/' + this.subQueryPrefix() + '/' + this.sAuthSuffix + '/Download/' + this.subQueryPrefix() + '/' + requestHash;
			}

			/**
	   * @param {string} email
	   * @return {string}
	   */

		}, {
			key: 'avatarLink',
			value: function avatarLink(email) {
				return this.sServer + '/Raw/0/Avatar/' + _Utils2.default.encodeURIComponent(email) + '/';
			}

			/**
	   * @param {string} hash
	   * @return {string}
	   */

		}, {
			key: 'publicLink',
			value: function publicLink(hash) {
				return this.sServer + '/Raw/0/Public/' + hash + '/';
			}

			/**
	   * @param {string} hash
	   * @return {string}
	   */

		}, {
			key: 'userBackground',
			value: function userBackground(hash) {
				return this.sServer + '/Raw/' + this.subQueryPrefix() + '/' + this.sAuthSuffix + '/UserBackground/' + this.subQueryPrefix() + '/' + hash;
			}

			/**
	   * @param {string} inboxFolderName = 'INBOX'
	   * @return {string}
	   */

		}, {
			key: 'inbox',
			value: function inbox() {
				var inboxFolderName = arguments.length <= 0 || arguments[0] === undefined ? 'INBOX' : arguments[0];

				return this.sBase + 'mailbox/' + inboxFolderName;
			}

			/**
	   * @param {string=} screenName
	   * @return {string}
	   */

		}, {
			key: 'settings',
			value: function settings() {
				var screenName = arguments.length <= 0 || arguments[0] === undefined ? '' : arguments[0];

				return this.sBase + 'settings' + (screenName ? '/' + screenName : '');
			}

			/**
	   * @return {string}
	   */

		}, {
			key: 'about',
			value: function about() {
				return this.sBase + 'about';
			}

			/**
	   * @param {string} screenName
	   * @return {string}
	   */

		}, {
			key: 'admin',
			value: function admin(screenName) {
				var result = this.sBase;
				switch (screenName) {
					case 'AdminDomains':
						result += 'domains';
						break;
					case 'AdminSecurity':
						result += 'security';
						break;
					case 'AdminLicensing':
						result += 'licensing';
						break;
				}

				return result;
			}

			/**
	   * @param {string} folder
	   * @param {number=} page = 1
	   * @param {string=} search = ''
	   * @param {string=} threadUid = ''
	   * @return {string}
	   */

		}, {
			key: 'mailBox',
			value: function mailBox(folder) {
				var page = arguments.length <= 1 || arguments[1] === undefined ? 1 : arguments[1];
				var search = arguments.length <= 2 || arguments[2] === undefined ? '' : arguments[2];
				var threadUid = arguments.length <= 3 || arguments[3] === undefined ? '' : arguments[3];

				page = _Utils2.default.isNormal(page) ? _Utils2.default.pInt(page) : 1;
				search = _Utils2.default.pString(search);

				var result = this.sBase + 'mailbox/';

				if ('' !== folder) {
					var resultThreadUid = _Utils2.default.pInt(threadUid);
					result += _common.window.encodeURI(folder) + (0 < resultThreadUid ? '~' + resultThreadUid : '');
				}

				if (1 < page) {
					result = result.replace(/[\/]+$/, '');
					result += '/p' + page;
				}

				if ('' !== search) {
					result = result.replace(/[\/]+$/, '');
					result += '/' + _common.window.encodeURI(search);
				}

				return result;
			}

			/**
	   * @return {string}
	   */

		}, {
			key: 'phpInfo',
			value: function phpInfo() {
				return this.sServer + 'Info';
			}

			/**
	   * @param {string} lang
	   * @param {boolean} admin
	   * @return {string}
	   */

		}, {
			key: 'langLink',
			value: function langLink(lang, admin) {
				return this.sServer + '/Lang/0/' + (admin ? 'Admin' : 'App') + '/' + _common.window.encodeURI(lang) + '/' + this.sVersion + '/';
			}

			/**
	   * @return {string}
	   */

		}, {
			key: 'exportContactsVcf',
			value: function exportContactsVcf() {
				return this.sServer + '/Raw/' + this.subQueryPrefix() + '/' + this.sAuthSuffix + '/ContactsVcf/';
			}

			/**
	   * @return {string}
	   */

		}, {
			key: 'exportContactsCsv',
			value: function exportContactsCsv() {
				return this.sServer + '/Raw/' + this.subQueryPrefix() + '/' + this.sAuthSuffix + '/ContactsCsv/';
			}

			/**
	   * @return {string}
	   */

		}, {
			key: 'emptyContactPic',
			value: function emptyContactPic() {
				return this.sStaticPrefix + 'css/images/empty-contact.png';
			}

			/**
	   * @param {string} fileName
	   * @return {string}
	   */

		}, {
			key: 'sound',
			value: function sound(fileName) {
				return this.sStaticPrefix + 'sounds/' + fileName;
			}

			/**
	   * @param {string} theme
	   * @return {string}
	   */

		}, {
			key: 'themePreviewLink',
			value: function themePreviewLink(theme) {
				var prefix = this.sVersionPrefix;
				if ('@custom' === theme.substr(-7)) {
					theme = _Utils2.default.trim(theme.substring(0, theme.length - 7));
					prefix = this.sWebPrefix;
				}

				return prefix + 'themes/' + _common.window.encodeURI(theme) + '/images/preview.png';
			}

			/**
	   * @return {string}
	   */

		}, {
			key: 'notificationMailIcon',
			value: function notificationMailIcon() {
				return this.sStaticPrefix + 'css/images/icom-message-notification.png';
			}

			/**
	   * @return {string}
	   */

		}, {
			key: 'openPgpJs',
			value: function openPgpJs() {
				return this.sStaticPrefix + 'js/min/openpgp.min.js';
			}

			/**
	   * @return {string}
	   */

		}, {
			key: 'openPgpWorkerJs',
			value: function openPgpWorkerJs() {
				return this.sStaticPrefix + 'js/min/openpgp.worker.min.js';
			}

			/**
	   * @return {string}
	   */

		}, {
			key: 'openPgpWorkerPath',
			value: function openPgpWorkerPath() {
				return this.sStaticPrefix + 'js/min/';
			}

			/**
	   * @param {boolean} xauth = false
	   * @return {string}
	   */

		}, {
			key: 'socialGoogle',
			value: function socialGoogle() {
				var xauth = arguments.length <= 0 || arguments[0] === undefined ? false : arguments[0];

				return this.sServer + 'SocialGoogle' + ('' !== this.sAuthSuffix ? '/' + this.subQueryPrefix() + '/' + this.sAuthSuffix + '/' : '') + (xauth ? '&xauth=1' : '');
			}

			/**
	   * @return {string}
	   */

		}, {
			key: 'socialTwitter',
			value: function socialTwitter() {
				return this.sServer + 'SocialTwitter' + ('' !== this.sAuthSuffix ? '/' + this.subQueryPrefix() + '/' + this.sAuthSuffix + '/' : '');
			}

			/**
	   * @return {string}
	   */

		}, {
			key: 'socialFacebook',
			value: function socialFacebook() {
				return this.sServer + 'SocialFacebook' + ('' !== this.sAuthSuffix ? '/' + this.subQueryPrefix() + '/' + this.sAuthSuffix + '/' : '');
			}
		}]);

		return Links;
	})();

	module.exports = new Links();

/***/ },
/* 12 */
/*!************************!*\
  !*** ./dev/common.jsx ***!
  \************************/
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	Object.defineProperty(exports, "__esModule", {
	  value: true
	});
	exports.key = exports.moment = exports.Q = exports._ = exports.JSON = exports.$ = exports.window = undefined;

	var _window = __webpack_require__(/*! window */ 13);

	var _window2 = _interopRequireDefault(_window);

	var _$ = __webpack_require__(/*! $ */ 14);

	var _$2 = _interopRequireDefault(_$);

	var _JSON = __webpack_require__(/*! JSON */ 40);

	var _JSON2 = _interopRequireDefault(_JSON);

	var _2 = __webpack_require__(/*! _ */ 3);

	var _3 = _interopRequireDefault(_2);

	var _Q = __webpack_require__(/*! Q */ 48);

	var _Q2 = _interopRequireDefault(_Q);

	var _moment = __webpack_require__(/*! moment */ 54);

	var _moment2 = _interopRequireDefault(_moment);

	var _key = __webpack_require__(/*! key */ 18);

	var _key2 = _interopRequireDefault(_key);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	exports.window = _window2.default;
	exports.$ = _$2.default;
	exports.JSON = _JSON2.default;
	exports._ = _3.default;
	exports.Q = _Q2.default;
	exports.moment = _moment2.default;
	exports.key = _key2.default;

/***/ },
/* 13 */
/*!*************************!*\
  !*** external "window" ***!
  \*************************/
/***/ function(module, exports, __webpack_require__) {

	module.exports = window;

/***/ },
/* 14 */
/*!********************************!*\
  !*** external "window.jQuery" ***!
  \********************************/
/***/ function(module, exports, __webpack_require__) {

	module.exports = window.jQuery;

/***/ },
/* 15 */,
/* 16 */
/*!************************************!*\
  !*** ./dev/Component/Abstract.jsx ***!
  \************************************/
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

	Object.defineProperty(exports, "__esModule", {
		value: true
	});
	exports.componentExportHelper = exports.AbstractComponent = undefined;

	var _common = __webpack_require__(/*! common */ 12);

	var _ko = __webpack_require__(/*! ko */ 2);

	var _ko2 = _interopRequireDefault(_ko);

	var _Utils = __webpack_require__(/*! Common/Utils */ 1);

	var _Utils2 = _interopRequireDefault(_Utils);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

	var AbstractComponent = (function () {
		function AbstractComponent() {
			_classCallCheck(this, AbstractComponent);

			this.disposable = [];
		}

		_createClass(AbstractComponent, [{
			key: 'dispose',
			value: function dispose() {
				this.disposable.forEach(function (funcToDispose) {
					if (funcToDispose && funcToDispose.dispose) {
						funcToDispose.dispose();
					}
				});
			}
		}]);

		return AbstractComponent;
	})();

	/**
	 * @param {*} ClassObject
	 * @param {string} templateID = ''
	 * @return {Object}
	 */

	var componentExportHelper = function componentExportHelper(ClassObject) {
		var templateID = arguments.length <= 1 || arguments[1] === undefined ? '' : arguments[1];

		return {
			template: templateID ? { element: templateID } : '<b></b>',
			viewModel: {
				createViewModel: function createViewModel(params, componentInfo) {

					params = params || {};
					params.element = null;

					if (componentInfo && componentInfo.element) {
						params.component = componentInfo;
						params.element = (0, _common.$)(componentInfo.element);

						__webpack_require__(/*! Common/Translator */ 6).i18nToNodes(params.element);

						if (!_Utils2.default.isUnd(params.inline) && _ko2.default.unwrap(params.inline)) {
							params.element.css('display', 'inline-block');
						}
					}

					return new ClassObject(params);
				}
			}
		};
	};

	exports.AbstractComponent = AbstractComponent;
	exports.componentExportHelper = componentExportHelper;

/***/ },
/* 17 */
/*!*******************************!*\
  !*** ./dev/Common/Consts.jsx ***!
  \*******************************/
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	Object.defineProperty(exports, "__esModule", {
	  value: true
	});
	var MESSAGES_PER_PAGE = exports.MESSAGES_PER_PAGE = 20;

	var MESSAGES_PER_PAGE_VALUES = exports.MESSAGES_PER_PAGE_VALUES = [10, 20, 30, 50, 100 /*, 150, 200, 300*/];

	var CONTACTS_PER_PAGE = exports.CONTACTS_PER_PAGE = 50;

	var DEFAULT_AJAX_TIMEOUT = exports.DEFAULT_AJAX_TIMEOUT = 30000;

	var SEARCH_AJAX_TIMEOUT = exports.SEARCH_AJAX_TIMEOUT = 300000;

	var SEND_MESSAGE_AJAX_TIMEOUT = exports.SEND_MESSAGE_AJAX_TIMEOUT = 300000;

	var SAVE_MESSAGE_AJAX_TIMEOUT = exports.SAVE_MESSAGE_AJAX_TIMEOUT = 200000;

	var CONTACTS_SYNC_AJAX_TIMEOUT = exports.CONTACTS_SYNC_AJAX_TIMEOUT = 200000;

	var UNUSED_OPTION_VALUE = exports.UNUSED_OPTION_VALUE = '__UNUSE__';

	var CLIENT_SIDE_STORAGE_INDEX_NAME = exports.CLIENT_SIDE_STORAGE_INDEX_NAME = 'rlcsc';

	var IMAP_DEFAULT_PORT = exports.IMAP_DEFAULT_PORT = 143;

	var IMAP_DEFAULT_SECURE_PORT = exports.IMAP_DEFAULT_SECURE_PORT = 993;

	var SMTP_DEFAULT_PORT = exports.SMTP_DEFAULT_PORT = 25;

	var SMTP_DEFAULT_SECURE_PORT = exports.SMTP_DEFAULT_SECURE_PORT = 465;

	var SIEVE_DEFAULT_PORT = exports.SIEVE_DEFAULT_PORT = 4190;

	var MESSAGE_BODY_CACHE_LIMIT = exports.MESSAGE_BODY_CACHE_LIMIT = 15;

	var AJAX_ERROR_LIMIT = exports.AJAX_ERROR_LIMIT = 7;

	var TOKEN_ERROR_LIMIT = exports.TOKEN_ERROR_LIMIT = 10;

	var RAINLOOP_TRIAL_KEY = exports.RAINLOOP_TRIAL_KEY = 'RAINLOOP-TRIAL-KEY';

	var DATA_IMAGE_USER_DOT_PIC = exports.DATA_IMAGE_USER_DOT_PIC = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAC4AAAAuCAYAAABXuSs3AAAHHklEQVRoQ7VZW08bVxCeXRuwIbTGXIwNtBBaqjwgVUiR8lDlbza9qe1DpVZ9aNQ/0KpPeaJK07SpcuEeCEmUAObm21bfrL9lONjexSYrWfbunj37zXdmvpkz9oIgCKTD0Wg0xPd94TDP83Q0zvWa50vzklSrdanVanqf4/D84GBGr+F+Op3S8fqoJxLOdnZgTvsO/nYhenHA+UC7CWF1uXwkb9++ldPTUwVerVbVqFQqpR8YPjQ0JCMjI5LNDijoRgP3PQVu5+5Eor2XGLg7IV4GkIdHJ/LmzRs5ODiIwNbrdR0O0GCcq4Xz4eFhmZyclP7+tDQaIik/BG5XKQn4SwG3zJTLZXn9+rUclI8UHD5YVoDDN8bSzXhONwL48fFxGR4eilzFZT1uFRIB5yT8BqCdnR3Z3d0VP9Un6XRawYJpggVrZBv38ME4XKtUKnLt2jUplUoy1PR/l3U7T6sVSAQcgMAkj8PDQ9ne3pajoyMRL7zeKsYZWHgWYDGmv78/mmdwcFA+mJlSgziHDWrERrsjEXDXegTi1tZW+DLxI2bxIrqFNYTXyDyCFweMAHCwb8e4RnTNuOsqe3t7sra21pTD0Kct666E8XlcZyzw9/RUUXK5nK5oUinUQI6TQ3cynO/v78vq6qrKXCNwlTiJJpyNGc3nZHp6uqV2dwrQWOCtZBDAV1ZWwsQk7f0wiQn5kffbAu/0/KWBYzIC1+XukfGx0RGZmppKlC2tIV0Bh4aDcZW7HhkfH8urLLZL7T2pihvlkMNnz56FiadHxicL41IsFpN41bkxsYxbRdFo9jwB8KdPn14J8KnSpBQKhQs63nPmbCVRcBUAR2Lq1VVmpksyMTFxAXjcEsQybiegESionjx5osCZOeNe1O4+EhCAX7bQSgQcxRHTMgAgcz5+/Dis/hL4uHU3/B4YGNASGHIKxuEql0k+l05AeIAF1vPnz5VxFFmdDlaJrMtZITJeSsXCOTlMunKxjLtMYOKNjQ158eJFuAuKkUOb5sEwgff19SkJUBVkThZUbnXZrtCKBQ6gbnWIkjZpyne3ejAWoGnA7Icz6irvBLgbOMicCM6TkxPx/LAkbXfgWcsazuE2kFRsKD5Z+CiqDumKncpZvieWcS6dDVD8xiYCNflpJdwcdwJOf9airLmVQ7DPzMxIYWLsXGXoVqLt5k0M3K3JUVPDZdbWNzsCp48TPFdvdnZWUz32nDha7bJ63kgAJPzSdRks9/Kf9xMJAQ1gq2NpaUmy2Yz4zar4nQC3xb99AQwCcGzLAAwuhG8YiWvcOKts+r4GOe5nMhm5efOm9lUA3E3vSZJRrKvE0fnPv//Jy5cvo5cTHIPQbSjhOoqq69evS19f6lxDKK4+sVhigZPtKJqbrQeqxd5+WR4+fKgqgT0k2XX3nhiPgETWXFhYkFzuPZ2yVq1GTSOXpE47/VjgNnD4m4GG7/LhsTx69EiwD4Vr2MwIIxgbAH18fKx1yfz8vEogNvGtWnCuhLZa9UTAreVWFsHy/b/+Vrbdl7E5REMQD2jDoUbByty+/ZnU64GkU2HzyJLhktU1cLv8nARgkYS2d3ajAgwG8qU2oLmDZ92CMaOjo7K4uCiZgbDWaRWgnZhPxLhrMUCvr69riwKZk1LHF7XqrWAO9hJxH6ozNzcnCx/PqztZg9mf6SQMscCtm2C5ke4BGMlHWTUp36036AJajDVrFMzBrhhWslQsSrFYiOqVpMriNYIgqFRq2j3FAb/zffT6zuxFXxsNzs3NTXn16lW4gYiW96w1FyedF+83xG/2FNGCRpU4NjamMsn+OZ9xE5RXqdaDdPpib6RWCzuwKF9RxqI2AVNQBwQYJoK0wdBejnqtEikP3pfP51XjUTESl12FqJEKxsEorARYDD44ONTeID7YpsEnrRvQfWAI2e8WfDaTUSIwJ0iBCmFOtOUAHvVMPp/TPwvYFVYFIuP8l+DBgwdaa2Miqwa0GgYwfeMltovbDfh6c1vIgMYcliSsKv4IWFr6VDHxvldvBAH+1sA+cnl5WYOPmmr9ir+1l9I0Cgz0yjhXjfJJ0JROnmezWbl165ayr/5fqwcBNr7IfhjMqKcvESSM4eRcCasQ3bDNObmKPLdGUGpZsN24cUNLBm9zazu4d++e6qpNBFaTuUS26U5dpuR1CxyA7J9ddrMRqlz4pwLLYawymPd++/2PADt2ugcGwq9gCCdhQ96C6xWwa6j1ceuq+I0EhW0i8MAIVJfeL3d/DVD8EKi12P6/2S2jV/EccVB54O/ejz/9HGCpoBBMta5rXMXLu53D1XAwjhXwvvv+h4BAXVe4bOu3O3ChxF08LiZFG3fel199G9CH3fLyqv24NcB44MRhpdK788U3CpyKwsCw590xmfSpzsBt0Fqc3ud3vtZigxWcVZCklVpSiN0w3q5E/h9TGMIUuA3+EQAAAABJRU5ErkJggg==';

	var DATA_IMAGE_TRANSP_PIC = exports.DATA_IMAGE_TRANSP_PIC = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAC0lEQVQIW2NkAAIAAAoAAggA9GkAAAAASUVORK5CYII=';

/***/ },
/* 18 */
/*!*****************************!*\
  !*** external "window.key" ***!
  \*****************************/
/***/ function(module, exports, __webpack_require__) {

	module.exports = window.key;

/***/ },
/* 19 */
/*!**********************************!*\
  !*** ./dev/Remote/Admin/Ajax.js ***!
  \**********************************/
/***/ function(module, exports, __webpack_require__) {

	
	(function () {

		'use strict';

		var
			_ = __webpack_require__(/*! _ */ 3),

			AbstractAjaxRemote = __webpack_require__(/*! Remote/AbstractAjax */ 56)
		;

		/**
		 * @constructor
		 * @extends AbstractAjaxRemote
		 */
		function RemoteAdminStorage()
		{
			AbstractAjaxRemote.call(this);

			this.oRequests = {};
		}

		_.extend(RemoteAdminStorage.prototype, AbstractAjaxRemote.prototype);

		/**
		 * @param {?Function} fCallback
		 * @param {string} sLogin
		 * @param {string} sPassword
		 */
		RemoteAdminStorage.prototype.adminLogin = function (fCallback, sLogin, sPassword)
		{
			this.defaultRequest(fCallback, 'AdminLogin', {
				'Login': sLogin,
				'Password': sPassword
			});
		};

		/**
		 * @param {?Function} fCallback
		 */
		RemoteAdminStorage.prototype.adminLogout = function (fCallback)
		{
			this.defaultRequest(fCallback, 'AdminLogout');
		};

		/**
		 * @param {?Function} fCallback
		 * @param {?} oData
		 */
		RemoteAdminStorage.prototype.saveAdminConfig = function (fCallback, oData)
		{
			this.defaultRequest(fCallback, 'AdminSettingsUpdate', oData);
		};

		/**
		 * @param {?Function} fCallback
		 */
		RemoteAdminStorage.prototype.domainList = function (fCallback)
		{
			this.defaultRequest(fCallback, 'AdminDomainList');
		};

		/**
		 * @param {?Function} fCallback
		 */
		RemoteAdminStorage.prototype.pluginList = function (fCallback)
		{
			this.defaultRequest(fCallback, 'AdminPluginList');
		};

		/**
		 * @param {?Function} fCallback
		 */
		RemoteAdminStorage.prototype.packagesList = function (fCallback)
		{
			this.defaultRequest(fCallback, 'AdminPackagesList');
		};

		/**
		 * @param {?Function} fCallback
		 */
		RemoteAdminStorage.prototype.coreData = function (fCallback)
		{
			this.defaultRequest(fCallback, 'AdminCoreData');
		};

		/**
		 * @param {?Function} fCallback
		 */
		RemoteAdminStorage.prototype.updateCoreData = function (fCallback)
		{
			this.defaultRequest(fCallback, 'AdminUpdateCoreData', {}, 90000);
		};

		/**
		 * @param {?Function} fCallback
		 * @param {Object} oPackage
		 */
		RemoteAdminStorage.prototype.packageInstall = function (fCallback, oPackage)
		{
			this.defaultRequest(fCallback, 'AdminPackageInstall', {
				'Id': oPackage.id,
				'Type': oPackage.type,
				'File': oPackage.file
			}, 60000);
		};

		/**
		 * @param {?Function} fCallback
		 * @param {Object} oPackage
		 */
		RemoteAdminStorage.prototype.packageDelete = function (fCallback, oPackage)
		{
			this.defaultRequest(fCallback, 'AdminPackageDelete', {
				'Id': oPackage.id
			});
		};

		/**
		 * @param {?Function} fCallback
		 * @param {string} sName
		 */
		RemoteAdminStorage.prototype.domain = function (fCallback, sName)
		{
			this.defaultRequest(fCallback, 'AdminDomainLoad', {
				'Name': sName
			});
		};

		/**
		 * @param {?Function} fCallback
		 * @param {string} sName
		 */
		RemoteAdminStorage.prototype.plugin = function (fCallback, sName)
		{
			this.defaultRequest(fCallback, 'AdminPluginLoad', {
				'Name': sName
			});
		};

		/**
		 * @param {?Function} fCallback
		 * @param {string} sName
		 */
		RemoteAdminStorage.prototype.domainDelete = function (fCallback, sName)
		{
			this.defaultRequest(fCallback, 'AdminDomainDelete', {
				'Name': sName
			});
		};

		/**
		 * @param {?Function} fCallback
		 * @param {string} sName
		 * @param {boolean} bDisabled
		 */
		RemoteAdminStorage.prototype.domainDisable = function (fCallback, sName, bDisabled)
		{
			return this.defaultRequest(fCallback, 'AdminDomainDisable', {
				'Name': sName,
				'Disabled': !!bDisabled ? '1' : '0'
			});
		};

		/**
		 * @param {?Function} fCallback
		 * @param {Object} oConfig
		 */
		RemoteAdminStorage.prototype.pluginSettingsUpdate = function (fCallback, oConfig)
		{
			return this.defaultRequest(fCallback, 'AdminPluginSettingsUpdate', oConfig);
		};

		/**
		 * @param {?Function} fCallback
		 * @param {boolean} bForce
		 */
		RemoteAdminStorage.prototype.licensing = function (fCallback, bForce)
		{
			return this.defaultRequest(fCallback, 'AdminLicensing', {
				'Force' : bForce ? '1' : '0'
			});
		};

		/**
		 * @param {?Function} fCallback
		 * @param {string} sDomain
		 * @param {string} sKey
		 */
		RemoteAdminStorage.prototype.licensingActivate = function (fCallback, sDomain, sKey)
		{
			return this.defaultRequest(fCallback, 'AdminLicensingActivate', {
				'Domain' : sDomain,
				'Key' : sKey
			});
		};

		/**
		 * @param {?Function} fCallback
		 * @param {string} sName
		 * @param {boolean} bDisabled
		 */
		RemoteAdminStorage.prototype.pluginDisable = function (fCallback, sName, bDisabled)
		{
			return this.defaultRequest(fCallback, 'AdminPluginDisable', {
				'Name': sName,
				'Disabled': !!bDisabled ? '1' : '0'
			});
		};

		RemoteAdminStorage.prototype.createOrUpdateDomain = function (fCallback,
			bCreate, sName,
			sIncHost, iIncPort, sIncSecure, bIncShortLogin,
			bUseSieve, sSieveAllowRaw, sSieveHost, iSievePort, sSieveSecure,
			sOutHost, iOutPort, sOutSecure, bOutShortLogin, bOutAuth, bOutPhpMail,
			sWhiteList)
		{
			this.defaultRequest(fCallback, 'AdminDomainSave', {
				'Create': bCreate ? '1' : '0',
				'Name': sName,

				'IncHost': sIncHost,
				'IncPort': iIncPort,
				'IncSecure': sIncSecure,
				'IncShortLogin': bIncShortLogin ? '1' : '0',

				'UseSieve': bUseSieve ? '1' : '0',
				'SieveAllowRaw': sSieveAllowRaw ? '1' : '0',
				'SieveHost': sSieveHost,
				'SievePort': iSievePort,
				'SieveSecure': sSieveSecure,

				'OutHost': sOutHost,
				'OutPort': iOutPort,
				'OutSecure': sOutSecure,
				'OutShortLogin': bOutShortLogin ? '1' : '0',
				'OutAuth': bOutAuth ? '1' : '0',
				'OutUsePhpMail': bOutPhpMail ? '1' : '0',

				'WhiteList': sWhiteList
			});
		};

		RemoteAdminStorage.prototype.testConnectionForDomain = function (fCallback, sName,
			sIncHost, iIncPort, sIncSecure,
			bUseSieve, sSieveHost, iSievePort, sSieveSecure,
			sOutHost, iOutPort, sOutSecure, bOutAuth, bOutPhpMail)
		{
			this.defaultRequest(fCallback, 'AdminDomainTest', {
				'Name': sName,
				'IncHost': sIncHost,
				'IncPort': iIncPort,
				'IncSecure': sIncSecure,
				'UseSieve': bUseSieve ? '1' : '0',
				'SieveHost': sSieveHost,
				'SievePort': iSievePort,
				'SieveSecure': sSieveSecure,
				'OutHost': sOutHost,
				'OutPort': iOutPort,
				'OutSecure': sOutSecure,
				'OutAuth': bOutAuth ? '1' : '0',
				'OutUsePhpMail': bOutPhpMail ? '1' : '0'
			});
		};

		/**
		 * @param {?Function} fCallback
		 * @param {?} oData
		 */
		RemoteAdminStorage.prototype.testContacts = function (fCallback, oData)
		{
			this.defaultRequest(fCallback, 'AdminContactsTest', oData);
		};

		/**
		 * @param {?Function} fCallback
		 * @param {?} oData
		 */
		RemoteAdminStorage.prototype.saveNewAdminPassword = function (fCallback, oData)
		{
			this.defaultRequest(fCallback, 'AdminPasswordUpdate', oData);
		};

		/**
		 * @param {?Function} fCallback
		 */
		RemoteAdminStorage.prototype.adminPing = function (fCallback)
		{
			this.defaultRequest(fCallback, 'AdminPing');
		};

		module.exports = new RemoteAdminStorage();

	}());

/***/ },
/* 20 */
/*!***************************!*\
  !*** ./dev/App/Admin.jsx ***!
  \***************************/
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

	var _get = function get(object, property, receiver) { if (object === null) object = Function.prototype; var desc = Object.getOwnPropertyDescriptor(object, property); if (desc === undefined) { var parent = Object.getPrototypeOf(object); if (parent === null) { return undefined; } else { return get(parent, property, receiver); } } else if ("value" in desc) { return desc.value; } else { var getter = desc.get; if (getter === undefined) { return undefined; } return getter.call(receiver); } };

	Object.defineProperty(exports, "__esModule", {
		value: true
	});

	var _common = __webpack_require__(/*! common */ 12);

	var _ko = __webpack_require__(/*! ko */ 2);

	var _ko2 = _interopRequireDefault(_ko);

	var _progressJs = __webpack_require__(/*! progressJs */ 84);

	var _progressJs2 = _interopRequireDefault(_progressJs);

	var _Enums = __webpack_require__(/*! Common/Enums */ 4);

	var Enums = _interopRequireWildcard(_Enums);

	var _Utils = __webpack_require__(/*! Common/Utils */ 1);

	var _Utils2 = _interopRequireDefault(_Utils);

	var _Links = __webpack_require__(/*! Common/Links */ 11);

	var _Links2 = _interopRequireDefault(_Links);

	var _Translator = __webpack_require__(/*! Common/Translator */ 6);

	var _Translator2 = _interopRequireDefault(_Translator);

	var _Settings = __webpack_require__(/*! Storage/Settings */ 9);

	var _Settings2 = _interopRequireDefault(_Settings);

	var _App = __webpack_require__(/*! Stores/Admin/App */ 35);

	var _App2 = _interopRequireDefault(_App);

	var _Domain = __webpack_require__(/*! Stores/Admin/Domain */ 58);

	var _Domain2 = _interopRequireDefault(_Domain);

	var _Plugin = __webpack_require__(/*! Stores/Admin/Plugin */ 61);

	var _Plugin2 = _interopRequireDefault(_Plugin);

	var _License = __webpack_require__(/*! Stores/Admin/License */ 59);

	var _License2 = _interopRequireDefault(_License);

	var _Package = __webpack_require__(/*! Stores/Admin/Package */ 60);

	var _Package2 = _interopRequireDefault(_Package);

	var _Core = __webpack_require__(/*! Stores/Admin/Core */ 90);

	var _Core2 = _interopRequireDefault(_Core);

	var _Ajax = __webpack_require__(/*! Remote/Admin/Ajax */ 19);

	var _Ajax2 = _interopRequireDefault(_Ajax);

	var _Knoin = __webpack_require__(/*! Knoin/Knoin */ 5);

	var _Knoin2 = _interopRequireDefault(_Knoin);

	var _Abstract = __webpack_require__(/*! App/Abstract */ 66);

	function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } else { var newObj = {}; if (obj != null) { for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) newObj[key] = obj[key]; } } newObj.default = obj; return newObj; } }

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

	function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

	function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

	var AdminApp = (function (_AbstractApp) {
		_inherits(AdminApp, _AbstractApp);

		function AdminApp() {
			_classCallCheck(this, AdminApp);

			return _possibleConstructorReturn(this, Object.getPrototypeOf(AdminApp).call(this, _Ajax2.default));
		}

		_createClass(AdminApp, [{
			key: 'remote',
			value: function remote() {
				return _Ajax2.default;
			}
		}, {
			key: 'reloadDomainList',
			value: function reloadDomainList() {
				_Domain2.default.domains.loading(true);
				_Ajax2.default.domainList(function (result, data) {
					_Domain2.default.domains.loading(false);
					if (Enums.StorageResultType.Success === result && data && data.Result) {
						_Domain2.default.domains(_common._.map(data.Result, function (enabled, name) {
							return {
								'name': name,
								'disabled': _ko2.default.observable(!enabled),
								'deleteAccess': _ko2.default.observable(false)
							};
						}));
					}
				});
			}
		}, {
			key: 'reloadPluginList',
			value: function reloadPluginList() {
				_Plugin2.default.plugins.loading(true);
				_Ajax2.default.pluginList(function (result, data) {
					_Plugin2.default.plugins.loading(false);
					if (Enums.StorageResultType.Success === result && data && data.Result) {
						_Plugin2.default.plugins(_common._.map(data.Result, function (item) {
							return {
								'name': item['Name'],
								'disabled': _ko2.default.observable(!item['Enabled']),
								'configured': _ko2.default.observable(!!item['Configured'])
							};
						}));
					}
				});
			}
		}, {
			key: 'reloadPackagesList',
			value: function reloadPackagesList() {
				_Package2.default.packages.loading(true);
				_Package2.default.packagesReal(true);
				_Ajax2.default.packagesList(function (result, data) {
					_Package2.default.packages.loading(false);
					if (Enums.StorageResultType.Success === result && data && data.Result) {
						(function () {
							_Package2.default.packagesReal(!!data.Result.Real);
							_Package2.default.packagesMainUpdatable(!!data.Result.MainUpdatable);

							var list = [],
							    loading = {};

							_common._.each(_Package2.default.packages(), function (item) {
								if (item && item['loading']()) {
									loading[item['file']] = item;
								}
							});

							if (_Utils2.default.isArray(data.Result.List)) {
								list = _common._.compact(_common._.map(data.Result.List, function (item) {
									if (item) {
										item['loading'] = _ko2.default.observable(!_Utils2.default.isUnd(loading[item['file']]));
										return 'core' === item['type'] && !item['canBeInstalled'] ? null : item;
									}
									return null;
								}));
							}

							_Package2.default.packages(list);
						})();
					} else {
						_Package2.default.packagesReal(false);
					}
				});
			}
		}, {
			key: 'updateCoreData',
			value: function updateCoreData() {
				_Core2.default.coreUpdating(true);
				_Ajax2.default.updateCoreData(function (result, data) {
					_Core2.default.coreUpdating(false);
					_Core2.default.coreVersion('');
					_Core2.default.coreRemoteVersion('');
					_Core2.default.coreRemoteRelease('');
					_Core2.default.coreVersionCompare(-2);
					if (Enums.StorageResultType.Success === result && data && data.Result) {
						_Core2.default.coreReal(true);
						_common.window.location.reload();
					} else {
						_Core2.default.coreReal(false);
					}
				});
			}
		}, {
			key: 'reloadCoreData',
			value: function reloadCoreData() {
				_Core2.default.coreChecking(true);
				_Core2.default.coreReal(true);
				_Ajax2.default.coreData(function (result, data) {
					_Core2.default.coreChecking(false);
					if (Enums.StorageResultType.Success === result && data && data.Result) {
						_Core2.default.coreReal(!!data.Result.Real);
						_Core2.default.coreChannel(data.Result.Channel || 'stable');
						_Core2.default.coreType(data.Result.Type || 'stable');
						_Core2.default.coreUpdatable(!!data.Result.Updatable);
						_Core2.default.coreAccess(!!data.Result.Access);
						_Core2.default.coreWarning(!!data.Result.Warning);
						_Core2.default.coreVersion(data.Result.Version || '');
						_Core2.default.coreRemoteVersion(data.Result.RemoteVersion || '');
						_Core2.default.coreRemoteRelease(data.Result.RemoteRelease || '');
						_Core2.default.coreVersionCompare(_Utils2.default.pInt(data.Result.VersionCompare));
					} else {
						_Core2.default.coreReal(false);
						_Core2.default.coreChannel('stable');
						_Core2.default.coreType('stable');
						_Core2.default.coreWarning(false);
						_Core2.default.coreVersion('');
						_Core2.default.coreRemoteVersion('');
						_Core2.default.coreRemoteRelease('');
						_Core2.default.coreVersionCompare(-2);
					}
				});
			}

			/**
	   * @param {boolean=} force = false
	   */

		}, {
			key: 'reloadLicensing',
			value: function reloadLicensing() {
				var force = arguments.length <= 0 || arguments[0] === undefined ? false : arguments[0];

				_License2.default.licensingProcess(true);
				_License2.default.licenseError('');
				_Ajax2.default.licensing(function (result, data) {
					_License2.default.licensingProcess(false);
					if (Enums.StorageResultType.Success === result && data && data.Result && _Utils2.default.isNormal(data.Result['Expired'])) {
						_License2.default.licenseValid(true);
						_License2.default.licenseExpired(_Utils2.default.pInt(data.Result['Expired']));
						_License2.default.licenseError('');
						_License2.default.licensing(true);
						_App2.default.prem(true);
					} else {
						if (data && data.ErrorCode && -1 < _Utils2.default.inArray(_Utils2.default.pInt(data.ErrorCode), [Enums.Notification.LicensingServerIsUnavailable, Enums.Notification.LicensingExpired])) {
							_License2.default.licenseError(_Translator2.default.getNotification(_Utils2.default.pInt(data.ErrorCode)));
							_License2.default.licensing(true);
						} else {
							if (Enums.StorageResultType.Abort === result) {
								_License2.default.licenseError(_Translator2.default.getNotification(Enums.Notification.LicensingServerIsUnavailable));
								_License2.default.licensing(true);
							} else {
								_License2.default.licensing(false);
							}
						}
					}
				}, force);
			}
		}, {
			key: 'bootend',
			value: function bootend() {
				var callback = arguments.length <= 0 || arguments[0] === undefined ? null : arguments[0];

				if (_progressJs2.default) {
					_progressJs2.default.end();
				}

				if (callback) {
					callback();
				}
			}
		}, {
			key: 'bootstart',
			value: function bootstart() {

				_get(Object.getPrototypeOf(AdminApp.prototype), 'bootstart', this).call(this);

				__webpack_require__(/*! Stores/Admin/App */ 35).populate();
				__webpack_require__(/*! Stores/Admin/Capa */ 50).populate();

				_Knoin2.default.hideLoading();

				if (!_Settings2.default.settingsGet('AllowAdminPanel')) {
					_Knoin2.default.routeOff();
					_Knoin2.default.setHash(_Links2.default.root(), true);
					_Knoin2.default.routeOff();

					_common._.defer(function () {
						_common.window.location.href = '/';
					});
				} else {
					if (!!_Settings2.default.settingsGet('Auth')) {
						_Knoin2.default.startScreens([__webpack_require__(/*! Screen/Admin/Settings */ 116)]);
					} else {
						_Knoin2.default.startScreens([__webpack_require__(/*! Screen/Admin/Login */ 115)]);
					}
				}

				this.bootend();
			}
		}]);

		return AdminApp;
	})(_Abstract.AbstractApp);

	var App = new AdminApp();
	exports.default = App;

/***/ },
/* 21 */,
/* 22 */,
/* 23 */
/*!********************************!*\
  !*** ./dev/Common/Plugins.jsx ***!
  \********************************/
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

	var _common = __webpack_require__(/*! common */ 12);

	var _Utils = __webpack_require__(/*! Common/Utils */ 1);

	var _Utils2 = _interopRequireDefault(_Utils);

	var _Globals = __webpack_require__(/*! Common/Globals */ 8);

	var _Globals2 = _interopRequireDefault(_Globals);

	var _Settings = __webpack_require__(/*! Storage/Settings */ 9);

	var _Settings2 = _interopRequireDefault(_Settings);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

	var Plugins = (function () {
		function Plugins() {
			_classCallCheck(this, Plugins);

			this.oSimpleHooks = {};
			this.aUserViewModelsHooks = [];
			this.aAdminViewModelsHooks = [];
		}

		/**
	  * @param {string} name
	  * @param {Function} callback
	  */

		_createClass(Plugins, [{
			key: 'addHook',
			value: function addHook(name, callback) {
				if (_Utils2.default.isFunc(callback)) {
					if (!_Utils2.default.isArray(this.oSimpleHooks[name])) {
						this.oSimpleHooks[name] = [];
					}

					this.oSimpleHooks[name].push(callback);
				}
			}

			/**
	   * @param {string} name
	   * @param {Array=} args
	   */

		}, {
			key: 'runHook',
			value: function runHook(name) {
				var args = arguments.length <= 1 || arguments[1] === undefined ? [] : arguments[1];

				if (_Utils2.default.isArray(this.oSimpleHooks[name])) {
					_common._.each(this.oSimpleHooks[name], function (callback) {
						callback.apply(null, args);
					});
				}
			}

			/**
	   * @param {string} name
	   * @return {?}
	   */

		}, {
			key: 'mainSettingsGet',
			value: function mainSettingsGet(name) {
				return _Settings2.default.settingsGet(name);
			}

			/**
	   * @param {Function} callback
	   * @param {string} action
	   * @param {Object=} parameters
	   * @param {?number=} timeout
	   */

		}, {
			key: 'remoteRequest',
			value: function remoteRequest(callback, action, parameters, timeout) {
				if (_Globals2.default.__APP__) {
					_Globals2.default.__APP__.remote().defaultRequest(callback, 'Plugin' + action, parameters, timeout);
				}
			}

			/**
	   * @param {Function} SettingsViewModelClass
	   * @param {string} labelName
	   * @param {string} template
	   * @param {string} route
	   */

		}, {
			key: 'addSettingsViewModel',
			value: function addSettingsViewModel(SettingsViewModelClass, template, labelName, route) {
				this.aUserViewModelsHooks.push([SettingsViewModelClass, template, labelName, route]);
			}

			/**
	   * @param {Function} SettingsViewModelClass
	   * @param {string} labelName
	   * @param {string} template
	   * @param {string} route
	   */

		}, {
			key: 'addSettingsViewModelForAdmin',
			value: function addSettingsViewModelForAdmin(SettingsViewModelClass, template, labelName, route) {
				this.aAdminViewModelsHooks.push([SettingsViewModelClass, template, labelName, route]);
			}

			/**
	   * @param {boolean} admin
	   */

		}, {
			key: 'runSettingsViewModelHooks',
			value: function runSettingsViewModelHooks(admin) {
				var Knoin = __webpack_require__(/*! Knoin/Knoin */ 5);
				_common._.each(admin ? this.aAdminViewModelsHooks : this.aUserViewModelsHooks, function (view) {
					Knoin.addSettingsViewModel(view[0], view[1], view[2], view[3]);
				});
			}

			/**
	   * @param {string} pluginSection
	   * @param {string} name
	   * @return {?}
	   */

		}, {
			key: 'settingsGet',
			value: function settingsGet(pluginSection, name) {
				var plugins = _Settings2.default.settingsGet('Plugins');
				plugins = plugins && !_Utils2.default.isUnd(plugins[pluginSection]) ? plugins[pluginSection] : null;
				return plugins ? _Utils2.default.isUnd(plugins[name]) ? null : plugins[name] : null;
			}
		}]);

		return Plugins;
	})();

	module.exports = new Plugins();

/***/ },
/* 24 */,
/* 25 */
/*!*******************************!*\
  !*** ./dev/Common/Events.jsx ***!
  \*******************************/
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

	var _common = __webpack_require__(/*! common */ 12);

	var _Utils = __webpack_require__(/*! Common/Utils */ 1);

	var _Utils2 = _interopRequireDefault(_Utils);

	var _Plugins = __webpack_require__(/*! Common/Plugins */ 23);

	var _Plugins2 = _interopRequireDefault(_Plugins);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

	var Events = (function () {
		function Events() {
			_classCallCheck(this, Events);

			this.subs = {};
		}

		/**
	  * @param {string|Object} name
	  * @param {Function} func
	  * @param {Object=} context
	  * @return {Events}
	  */

		_createClass(Events, [{
			key: 'sub',
			value: function sub(name, func, context) {
				var _this = this;

				if (_Utils2.default.isObject(name)) {
					context = func || null;
					func = null;

					_common._.each(name, function (subFunc, subName) {
						_this.sub(subName, subFunc, context);
					}, this);
				} else {
					if (_Utils2.default.isUnd(this.subs[name])) {
						this.subs[name] = [];
					}

					this.subs[name].push([func, context]);
				}

				return this;
			}

			/**
	   * @param {string} name
	   * @param {Array=} args
	   * @return {Events}
	   */

		}, {
			key: 'pub',
			value: function pub(name, args) {

				_Plugins2.default.runHook('rl-pub', [name, args]);

				if (!_Utils2.default.isUnd(this.subs[name])) {
					_common._.each(this.subs[name], function (items) {
						if (items[0]) {
							items[0].apply(items[1] || null, args || []);
						}
					});
				}

				return this;
			}
		}]);

		return Events;
	})();

	module.exports = new Events();

/***/ },
/* 26 */
/*!*********************************!*\
  !*** ./dev/Common/Momentor.jsx ***!
  \*********************************/
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

	var _common = __webpack_require__(/*! common */ 12);

	var _Translator = __webpack_require__(/*! Common/Translator */ 6);

	var _Translator2 = _interopRequireDefault(_Translator);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

	var Momentor = (function () {
		function Momentor() {
			var _this = this;

			_classCallCheck(this, Momentor);

			this._moment = null;
			this._momentNow = 0;

			this.updateMomentNow = _common._.debounce(function () {
				_this._moment = (0, _common.moment)();
			}, 500, true);

			this.updateMomentNowUnix = _common._.debounce(function () {
				_this._momentNow = (0, _common.moment)().unix();
			}, 500, true);

			this.format = _common._.bind(this.format, this);
		}

		_createClass(Momentor, [{
			key: 'momentNow',
			value: function momentNow() {
				this.updateMomentNow();
				return this._moment || (0, _common.moment)();
			}
		}, {
			key: 'momentNowUnix',
			value: function momentNowUnix() {
				this.updateMomentNowUnix();
				return this._momentNow || 0;
			}

			/**
	   * @param {number} date
	   * @return {string}
	   */

		}, {
			key: 'searchSubtractFormatDateHelper',
			value: function searchSubtractFormatDateHelper(date) {
				return this.momentNow().clone().subtract('days', date).format('YYYY.MM.DD');
			}

			/**
	   * @param {Object} m
	   * @return {string}
	   */

		}, {
			key: 'formatCustomShortDate',
			value: function formatCustomShortDate(m) {

				var now = this.momentNow();
				if (m && now) {
					switch (true) {
						case 4 >= now.diff(m, 'hours'):
							return m.fromNow();
						case now.format('L') === m.format('L'):
							return _Translator2.default.i18n('MESSAGE_LIST/TODAY_AT', {
								'TIME': m.format('LT')
							});
						case now.clone().subtract('days', 1).format('L') === m.format('L'):
							return _Translator2.default.i18n('MESSAGE_LIST/YESTERDAY_AT', {
								'TIME': m.format('LT')
							});
						case now.year() === m.year():
							return m.format('D MMM.');
					}
				}

				return m ? m.format('LL') : '';
			}

			/**
	   * @param {number} timeStampInUTC
	   * @param {string} format
	   * @return {string}
	   */

		}, {
			key: 'format',
			value: function format(timeStampInUTC, _format) {

				var m = null,
				    result = '';

				var now = this.momentNowUnix();

				timeStampInUTC = 0 < timeStampInUTC ? timeStampInUTC : 0 === timeStampInUTC ? now : 0;
				timeStampInUTC = now < timeStampInUTC ? now : timeStampInUTC;

				m = 0 < timeStampInUTC ? _common.moment.unix(timeStampInUTC) : null;

				if (m && 1970 === m.year()) {
					m = null;
				}

				if (m) {
					switch (_format) {
						case 'FROMNOW':
							result = m.fromNow();
							break;
						case 'SHORT':
							result = this.formatCustomShortDate(m);
							break;
						case 'FULL':
							result = m.format('LLL');
							break;
						default:
							result = m.format(_format);
							break;
					}
				}

				return result;
			}

			/**
	   * @param {Object} element
	   */

		}, {
			key: 'momentToNode',
			value: function momentToNode(element) {

				var key = '',
				    time = 0,
				    $el = (0, _common.$)(element);

				time = $el.data('moment-time');
				if (time) {
					key = $el.data('moment-format');
					if (key) {
						$el.text(this.format(time, key));
					}

					key = $el.data('moment-format-title');
					if (key) {
						$el.attr('title', this.format(time, key));
					}
				}
			}

			/**
	   * @param {Object} elements
	   */

		}, {
			key: 'momentToNodes',
			value: function momentToNodes(elements) {
				var _this2 = this;

				_common._.defer(function () {
					(0, _common.$)('.moment', elements).each(function (index, item) {
						_this2.momentToNode(item);
					});
				});
			}
		}, {
			key: 'reload',
			value: function reload() {
				this.momentToNodes(_common.window.document);
			}
		}]);

		return Momentor;
	})();

	module.exports = new Momentor();

/***/ },
/* 27 */,
/* 28 */,
/* 29 */
/*!****************************!*\
  !*** ./dev/Model/Email.js ***!
  \****************************/
/***/ function(module, exports, __webpack_require__) {

	
	(function () {

		'use strict';

		var
			Utils = __webpack_require__(/*! Common/Utils */ 1)
		;

		/**
		 * @param {string=} sEmail
		 * @param {string=} sName
		 * @param {string=} sDkimStatus
		 * @param {string=} sDkimValue
		 *
		 * @constructor
		 */
		function EmailModel(sEmail, sName, sDkimStatus, sDkimValue)
		{
			this.email = sEmail || '';
			this.name = sName || '';
			this.dkimStatus = sDkimStatus || 'none';
			this.dkimValue = sDkimValue || '';

			this.clearDuplicateName();
		}

		/**
		 * @static
		 * @param {AjaxJsonEmail} oJsonEmail
		 * @return {?EmailModel}
		 */
		EmailModel.newInstanceFromJson = function (oJsonEmail)
		{
			var oEmailModel = new EmailModel();
			return oEmailModel.initByJson(oJsonEmail) ? oEmailModel : null;
		};

		/**
		 * @static
		 * @param {string} sLine
		 * @param {string=} sDelimiter = ';'
		 * @return {Array}
		 */
		EmailModel.splitHelper = function (sLine, sDelimiter)
		{
			sDelimiter = sDelimiter || ';';

			sLine = sLine.replace(/[\r\n]+/g, '; ').replace(/[\s]+/g, ' ');

			var
				iIndex = 0,
				iLen = sLine.length,
				bAt = false,
				sChar = '',
				sResult = ''
			;

			for (; iIndex < iLen; iIndex++)
			{
				sChar = sLine.charAt(iIndex);
				switch (sChar)
				{
					case '@':
						bAt = true;
						break;
					case ' ':
						if (bAt)
						{
							bAt = false;
							sResult += sDelimiter;
						}
						break;
				}

				sResult += sChar;
			}

			return sResult.split(sDelimiter);
		};

		/**
		 * @type {string}
		 */
		EmailModel.prototype.name = '';

		/**
		 * @type {string}
		 */
		EmailModel.prototype.email = '';

		/**
		 * @type {string}
		 */
		EmailModel.prototype.dkimStatus = 'none';

		/**
		 * @type {string}
		 */
		EmailModel.prototype.dkimValue = '';

		EmailModel.prototype.clear = function ()
		{
			this.email = '';
			this.name = '';

			this.dkimStatus = 'none';
			this.dkimValue = '';
		};

		/**
		 * @return {boolean}
		 */
		EmailModel.prototype.validate = function ()
		{
			return '' !== this.name || '' !== this.email;
		};

		/**
		 * @param {boolean} bWithoutName = false
		 * @return {string}
		 */
		EmailModel.prototype.hash = function (bWithoutName)
		{
			return '#' + (bWithoutName ? '' : this.name) + '#' + this.email + '#';
		};

		EmailModel.prototype.clearDuplicateName = function ()
		{
			if (this.name === this.email)
			{
				this.name = '';
			}
		};

		/**
		 * @param {string} sQuery
		 * @return {boolean}
		 */
		EmailModel.prototype.search = function (sQuery)
		{
			return -1 < (this.name + ' ' + this.email).toLowerCase().indexOf(sQuery.toLowerCase());
		};

		/**
		 * @param {string} sString
		 */
		EmailModel.prototype.parse = function (sString)
		{
			this.clear();

			sString = Utils.trim(sString);

			var
				mRegex = /(?:"([^"]+)")? ?[<]?(.*?@[^>,]+)>?,? ?/g,
				mMatch = mRegex.exec(sString)
			;

			if (mMatch)
			{
				this.name = mMatch[1] || '';
				this.email = mMatch[2] || '';

				this.clearDuplicateName();
			}
			else if ((/^[^@]+@[^@]+$/).test(sString))
			{
				this.name = '';
				this.email = sString;
			}
		};

		/**
		 * @param {AjaxJsonEmail} oJsonEmail
		 * @return {boolean}
		 */
		EmailModel.prototype.initByJson = function (oJsonEmail)
		{
			var bResult = false;
			if (oJsonEmail && 'Object/Email' === oJsonEmail['@Object'])
			{
				this.name = Utils.trim(oJsonEmail.Name);
				this.email = Utils.trim(oJsonEmail.Email);
				this.dkimStatus = Utils.trim(oJsonEmail.DkimStatus || '');
				this.dkimValue = Utils.trim(oJsonEmail.DkimValue || '');

				bResult = '' !== this.email;
				this.clearDuplicateName();
			}

			return bResult;
		};

		/**
		 * @param {boolean} bFriendlyView
		 * @param {boolean=} bWrapWithLink = false
		 * @param {boolean=} bEncodeHtml = false
		 * @return {string}
		 */
		EmailModel.prototype.toLine = function (bFriendlyView, bWrapWithLink, bEncodeHtml)
		{
			var sResult = '';
			if ('' !== this.email)
			{
				bWrapWithLink = Utils.isUnd(bWrapWithLink) ? false : !!bWrapWithLink;
				bEncodeHtml = Utils.isUnd(bEncodeHtml) ? false : !!bEncodeHtml;

				if (bFriendlyView && '' !== this.name)
				{
					sResult = bWrapWithLink ? '<a href="mailto:' + Utils.encodeHtml('"' + this.name + '" <' + this.email + '>') +
						'" target="_blank" tabindex="-1">' + Utils.encodeHtml(this.name) + '</a>' :
							(bEncodeHtml ? Utils.encodeHtml(this.name) : this.name);
				}
				else
				{
					sResult = this.email;
					if ('' !== this.name)
					{
						if (bWrapWithLink)
						{
							sResult = Utils.encodeHtml('"' + this.name + '" <') +
								'<a href="mailto:' + Utils.encodeHtml('"' + this.name + '" <' + this.email + '>') + '" target="_blank" tabindex="-1">' + Utils.encodeHtml(sResult) + '</a>' + Utils.encodeHtml('>');
						}
						else
						{
							sResult = '"' + this.name + '" <' + sResult + '>';
							if (bEncodeHtml)
							{
								sResult = Utils.encodeHtml(sResult);
							}
						}
					}
					else if (bWrapWithLink)
					{
						sResult = '<a href="mailto:' + Utils.encodeHtml(this.email) + '" target="_blank" tabindex="-1">' + Utils.encodeHtml(this.email) + '</a>';
					}
				}
			}

			return sResult;
		};

		/**
		 * @param {string} $sEmailAddress
		 * @return {boolean}
		 */
		EmailModel.prototype.mailsoParse = function ($sEmailAddress)
		{
			$sEmailAddress = Utils.trim($sEmailAddress);
			if ('' === $sEmailAddress)
			{
				return false;
			}

			var
				substr = function (str, start, len) {
					str += '';
					var	end = str.length;

					if (start < 0) {
						start += end;
					}

					end = typeof len === 'undefined' ? end : (len < 0 ? len + end : len + start);

					return start >= str.length || start < 0 || start > end ? false : str.slice(start, end);
				},

				substr_replace = function (str, replace, start, length) {
					if (start < 0) {
						start = start + str.length;
					}
					length = length !== undefined ? length : str.length;
					if (length < 0) {
						length = length + str.length - start;
					}
					return str.slice(0, start) + replace.substr(0, length) + replace.slice(length) + str.slice(start + length);
				},

				$sName = '',
				$sEmail = '',
				$sComment = '',

				$bInName = false,
				$bInAddress = false,
				$bInComment = false,

				$aRegs = null,

				$iStartIndex = 0,
				$iEndIndex = 0,
				$iCurrentIndex = 0
			;

			while ($iCurrentIndex < $sEmailAddress.length)
			{
				switch ($sEmailAddress.substr($iCurrentIndex, 1))
				{
					case '"':
						if ((!$bInName) && (!$bInAddress) && (!$bInComment))
						{
							$bInName = true;
							$iStartIndex = $iCurrentIndex;
						}
						else if ((!$bInAddress) && (!$bInComment))
						{
							$iEndIndex = $iCurrentIndex;
							$sName = substr($sEmailAddress, $iStartIndex + 1, $iEndIndex - $iStartIndex - 1);
							$sEmailAddress = substr_replace($sEmailAddress, '', $iStartIndex, $iEndIndex - $iStartIndex + 1);
							$iEndIndex = 0;
							$iCurrentIndex = 0;
							$iStartIndex = 0;
							$bInName = false;
						}
						break;
					case '<':
						if ((!$bInName) && (!$bInAddress) && (!$bInComment))
						{
							if ($iCurrentIndex > 0 && $sName.length === 0)
							{
								$sName = substr($sEmailAddress, 0, $iCurrentIndex);
							}

							$bInAddress = true;
							$iStartIndex = $iCurrentIndex;
						}
						break;
					case '>':
						if ($bInAddress)
						{
							$iEndIndex = $iCurrentIndex;
							$sEmail = substr($sEmailAddress, $iStartIndex + 1, $iEndIndex - $iStartIndex - 1);
							$sEmailAddress = substr_replace($sEmailAddress, '', $iStartIndex, $iEndIndex - $iStartIndex + 1);
							$iEndIndex = 0;
							$iCurrentIndex = 0;
							$iStartIndex = 0;
							$bInAddress = false;
						}
						break;
					case '(':
						if ((!$bInName) && (!$bInAddress) && (!$bInComment))
						{
							$bInComment = true;
							$iStartIndex = $iCurrentIndex;
						}
						break;
					case ')':
						if ($bInComment)
						{
							$iEndIndex = $iCurrentIndex;
							$sComment = substr($sEmailAddress, $iStartIndex + 1, $iEndIndex - $iStartIndex - 1);
							$sEmailAddress = substr_replace($sEmailAddress, '', $iStartIndex, $iEndIndex - $iStartIndex + 1);
							$iEndIndex = 0;
							$iCurrentIndex = 0;
							$iStartIndex = 0;
							$bInComment = false;
						}
						break;
					case '\\':
						$iCurrentIndex++;
						break;
				}

				$iCurrentIndex++;
			}

			if ($sEmail.length === 0)
			{
				$aRegs = $sEmailAddress.match(/[^@\s]+@\S+/i);
				if ($aRegs && $aRegs[0])
				{
					$sEmail = $aRegs[0];
				}
				else
				{
					$sName = $sEmailAddress;
				}
			}

			if ($sEmail.length > 0 && $sName.length === 0 && $sComment.length === 0)
			{
				$sName = $sEmailAddress.replace($sEmail, '');
			}

			$sEmail = Utils.trim($sEmail).replace(/^[<]+/, '').replace(/[>]+$/, '');
			$sName = Utils.trim($sName).replace(/^["']+/, '').replace(/["']+$/, '');
			$sComment = Utils.trim($sComment).replace(/^[(]+/, '').replace(/[)]+$/, '');

			// Remove backslash
			$sName = $sName.replace(/\\\\(.)/g, '$1');
			$sComment = $sComment.replace(/\\\\(.)/g, '$1');

			this.name = $sName;
			this.email = $sEmail;

			this.clearDuplicateName();
			return true;
		};

		module.exports = EmailModel;

	}());

/***/ },
/* 30 */,
/* 31 */,
/* 32 */,
/* 33 */,
/* 34 */
/*!******************************!*\
  !*** ./dev/Stores/Social.js ***!
  \******************************/
/***/ function(module, exports, __webpack_require__) {

	
	(function () {

		'use strict';

		var
			ko = __webpack_require__(/*! ko */ 2)
		;

		/**
		 * @constructor
		 */
		function SocialStore()
		{
			this.google = {};
			this.twitter = {};
			this.facebook = {};
			this.dropbox = {};

			// Google
			this.google.enabled = ko.observable(false);

			this.google.clientID = ko.observable('');
			this.google.clientSecret = ko.observable('');
			this.google.apiKey = ko.observable('');

			this.google.loading = ko.observable(false);
			this.google.userName = ko.observable('');

			this.google.loggined = ko.computed(function () {
				return '' !== this.google.userName();
			}, this);

			this.google.capa = {};
			this.google.capa.auth = ko.observable(false);
			this.google.capa.authFast = ko.observable(false);
			this.google.capa.drive = ko.observable(false);
			this.google.capa.preview = ko.observable(false);

			this.google.require = {};
			this.google.require.clientSettings = ko.computed(function () {
				return this.google.enabled() && (this.google.capa.auth() || this.google.capa.drive());
			}, this);

			this.google.require.apiKeySettings = ko.computed(function () {
				return this.google.enabled() && this.google.capa.drive();
			}, this);

			// Facebook
			this.facebook.enabled = ko.observable(false);
			this.facebook.appID = ko.observable('');
			this.facebook.appSecret = ko.observable('');
			this.facebook.loading = ko.observable(false);
			this.facebook.userName = ko.observable('');
			this.facebook.supported = ko.observable(false);

			this.facebook.loggined = ko.computed(function () {
				return '' !== this.facebook.userName();
			}, this);

			// Twitter
			this.twitter.enabled = ko.observable(false);
			this.twitter.consumerKey = ko.observable('');
			this.twitter.consumerSecret = ko.observable('');
			this.twitter.loading = ko.observable(false);
			this.twitter.userName = ko.observable('');

			this.twitter.loggined = ko.computed(function () {
				return '' !== this.twitter.userName();
			}, this);

			// Dropbox
			this.dropbox.enabled = ko.observable(false);
			this.dropbox.apiKey = ko.observable('');
		}

		SocialStore.prototype.google = {};
		SocialStore.prototype.twitter = {};
		SocialStore.prototype.facebook = {};
		SocialStore.prototype.dropbox = {};

		SocialStore.prototype.populate = function ()
		{
			var Settings = __webpack_require__(/*! Storage/Settings */ 9);

			this.google.enabled(!!Settings.settingsGet('AllowGoogleSocial'));
			this.google.clientID(Settings.settingsGet('GoogleClientID'));
			this.google.clientSecret(Settings.settingsGet('GoogleClientSecret'));
			this.google.apiKey(Settings.settingsGet('GoogleApiKey'));

			this.google.capa.auth(!!Settings.settingsGet('AllowGoogleSocialAuth'));
			this.google.capa.authFast(!!Settings.settingsGet('AllowGoogleSocialAuthFast'));
			this.google.capa.drive(!!Settings.settingsGet('AllowGoogleSocialDrive'));
			this.google.capa.preview(!!Settings.settingsGet('AllowGoogleSocialPreview'));

			this.facebook.enabled(!!Settings.settingsGet('AllowFacebookSocial'));
			this.facebook.appID(Settings.settingsGet('FacebookAppID'));
			this.facebook.appSecret(Settings.settingsGet('FacebookAppSecret'));
			this.facebook.supported(!!Settings.settingsGet('SupportedFacebookSocial'));

			this.twitter.enabled = ko.observable(!!Settings.settingsGet('AllowTwitterSocial'));
			this.twitter.consumerKey = ko.observable(Settings.settingsGet('TwitterConsumerKey'));
			this.twitter.consumerSecret = ko.observable(Settings.settingsGet('TwitterConsumerSecret'));

			this.dropbox.enabled(!!Settings.settingsGet('AllowDropboxSocial'));
			this.dropbox.apiKey(Settings.settingsGet('DropboxApiKey'));
		};

		module.exports = new SocialStore();

	}());


/***/ },
/* 35 */
/*!**********************************!*\
  !*** ./dev/Stores/Admin/App.jsx ***!
  \**********************************/
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

	var _get = function get(object, property, receiver) { if (object === null) object = Function.prototype; var desc = Object.getOwnPropertyDescriptor(object, property); if (desc === undefined) { var parent = Object.getPrototypeOf(object); if (parent === null) { return undefined; } else { return get(parent, property, receiver); } } else if ("value" in desc) { return desc.value; } else { var getter = desc.get; if (getter === undefined) { return undefined; } return getter.call(receiver); } };

	var _ko = __webpack_require__(/*! ko */ 2);

	var _ko2 = _interopRequireDefault(_ko);

	var _Settings = __webpack_require__(/*! Storage/Settings */ 9);

	var _Settings2 = _interopRequireDefault(_Settings);

	var _AbstractApp = __webpack_require__(/*! Stores/AbstractApp */ 78);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

	function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

	function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

	var AppAdminStore = (function (_AbstractAppStore) {
			_inherits(AppAdminStore, _AbstractAppStore);

			function AppAdminStore() {
					_classCallCheck(this, AppAdminStore);

					var _this = _possibleConstructorReturn(this, Object.getPrototypeOf(AppAdminStore).call(this));

					_this.determineUserLanguage = _ko2.default.observable(false);
					_this.determineUserDomain = _ko2.default.observable(false);

					_this.weakPassword = _ko2.default.observable(false);
					_this.useLocalProxyForExternalImages = _ko2.default.observable(false);
					return _this;
			}

			_createClass(AppAdminStore, [{
					key: 'populate',
					value: function populate() {

							_get(Object.getPrototypeOf(AppAdminStore.prototype), 'populate', this).call(this);

							this.determineUserLanguage(!!_Settings2.default.settingsGet('DetermineUserLanguage'));
							this.determineUserDomain(!!_Settings2.default.settingsGet('DetermineUserDomain'));

							this.weakPassword(!!_Settings2.default.settingsGet('WeakPassword'));
							this.useLocalProxyForExternalImages(!!_Settings2.default.settingsGet('UseLocalProxyForExternalImages'));
					}
			}]);

			return AppAdminStore;
	})(_AbstractApp.AbstractAppStore);

	module.exports = new AppAdminStore();

/***/ },
/* 36 */
/*!*****************************************!*\
  !*** ./dev/Component/AbstractInput.jsx ***!
  \*****************************************/
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

	Object.defineProperty(exports, "__esModule", {
		value: true
	});
	exports.default = exports.AbstractInput = undefined;

	var _ko = __webpack_require__(/*! ko */ 2);

	var _ko2 = _interopRequireDefault(_ko);

	var _Utils = __webpack_require__(/*! Common/Utils */ 1);

	var _Utils2 = _interopRequireDefault(_Utils);

	var _Enums = __webpack_require__(/*! Common/Enums */ 4);

	var _Abstract = __webpack_require__(/*! Component/Abstract */ 16);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

	function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

	function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

	var AbstractInput = (function (_AbstractComponent) {
		_inherits(AbstractInput, _AbstractComponent);

		/**
	  * @param {Object} params
	  */

		function AbstractInput(params) {
			_classCallCheck(this, AbstractInput);

			var _this = _possibleConstructorReturn(this, Object.getPrototypeOf(AbstractInput).call(this));

			_this.value = params.value || '';
			_this.size = params.size || 0;
			_this.label = params.label || '';
			_this.preLabel = params.preLabel || '';
			_this.enable = _Utils2.default.isUnd(params.enable) ? true : params.enable;
			_this.trigger = params.trigger && params.trigger.subscribe ? params.trigger : null;
			_this.placeholder = params.placeholder || '';

			_this.labeled = !_Utils2.default.isUnd(params.label);
			_this.preLabeled = !_Utils2.default.isUnd(params.preLabel);
			_this.triggered = !_Utils2.default.isUnd(params.trigger) && !!_this.trigger;

			_this.classForTrigger = _ko2.default.observable('');

			_this.className = _ko2.default.computed(function () {

				var size = _ko2.default.unwrap(_this.size),
				    suffixValue = _this.trigger ? ' ' + _Utils2.default.trim('settings-saved-trigger-input ' + _this.classForTrigger()) : '';

				return (0 < size ? 'span' + size : '') + suffixValue;
			}, _this);

			if (!_Utils2.default.isUnd(params.width) && params.element) {
				params.element.find('input,select,textarea').css('width', params.width);
			}

			_this.disposable.push(_this.className);

			if (_this.trigger) {
				_this.setTriggerState(_this.trigger());

				_this.disposable.push(_this.trigger.subscribe(_this.setTriggerState, _this));
			}
			return _this;
		}

		_createClass(AbstractInput, [{
			key: 'setTriggerState',
			value: function setTriggerState(value) {
				switch (_Utils2.default.pInt(value)) {
					case _Enums.SaveSettingsStep.TrueResult:
						this.classForTrigger('success');
						break;
					case _Enums.SaveSettingsStep.FalseResult:
						this.classForTrigger('error');
						break;
					default:
						this.classForTrigger('');
						break;
				}
			}
		}]);

		return AbstractInput;
	})(_Abstract.AbstractComponent);

	exports.AbstractInput = AbstractInput;
	exports.default = AbstractInput;

/***/ },
/* 37 */
/*!************************************!*\
  !*** ./dev/Component/Checkbox.jsx ***!
  \************************************/
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	var _Abstract = __webpack_require__(/*! Component/Abstract */ 16);

	var _AbstracCheckbox2 = __webpack_require__(/*! Component/AbstracCheckbox */ 46);

	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

	function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

	function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

	var CheckboxComponent = (function (_AbstracCheckbox) {
	  _inherits(CheckboxComponent, _AbstracCheckbox);

	  function CheckboxComponent() {
	    _classCallCheck(this, CheckboxComponent);

	    return _possibleConstructorReturn(this, Object.getPrototypeOf(CheckboxComponent).apply(this, arguments));
	  }

	  return CheckboxComponent;
	})(_AbstracCheckbox2.AbstracCheckbox);

	module.exports = (0, _Abstract.componentExportHelper)(CheckboxComponent, 'CheckboxComponent');

/***/ },
/* 38 */
/*!*************************************!*\
  !*** ./dev/Knoin/AbstractScreen.js ***!
  \*************************************/
/***/ function(module, exports, __webpack_require__) {

	
	(function () {

		'use strict';

		var
			_ = __webpack_require__(/*! _ */ 3),
			crossroads = __webpack_require__(/*! crossroads */ 49),

			Utils = __webpack_require__(/*! Common/Utils */ 1)
		;

		/**
		 * @param {string} sScreenName
		 * @param {?=} aViewModels = []
		 * @constructor
		 */
		function AbstractScreen(sScreenName, aViewModels)
		{
			this.sScreenName = sScreenName;
			this.aViewModels = Utils.isArray(aViewModels) ? aViewModels : [];
		}

		/**
		 * @type {Array}
		 */
		AbstractScreen.prototype.oCross = null;

		/**
		 * @type {string}
		 */
		AbstractScreen.prototype.sScreenName = '';

		/**
		 * @type {Array}
		 */
		AbstractScreen.prototype.aViewModels = [];

		/**
		 * @return {Array}
		 */
		AbstractScreen.prototype.viewModels = function ()
		{
			return this.aViewModels;
		};

		/**
		 * @return {string}
		 */
		AbstractScreen.prototype.screenName = function ()
		{
			return this.sScreenName;
		};

		AbstractScreen.prototype.routes = function ()
		{
			return null;
		};

		/**
		 * @return {?Object}
		 */
		AbstractScreen.prototype.__cross = function ()
		{
			return this.oCross;
		};

		AbstractScreen.prototype.__start = function ()
		{
			var
				aRoutes = this.routes(),
				oRoute = null,
				fMatcher = null
			;

			if (Utils.isNonEmptyArray(aRoutes))
			{
				fMatcher = _.bind(this.onRoute || Utils.emptyFunction, this);
				oRoute = crossroads.create();

				_.each(aRoutes, function (aItem) {
					oRoute.addRoute(aItem[0], fMatcher).rules = aItem[1];
				});

				this.oCross = oRoute;
			}
		};

		module.exports = AbstractScreen;

	}());

/***/ },
/* 39 */
/*!********************************!*\
  !*** ./dev/Stores/Language.js ***!
  \********************************/
/***/ function(module, exports, __webpack_require__) {

	
	(function () {

		'use strict';

		var
			ko = __webpack_require__(/*! ko */ 2),

			Utils = __webpack_require__(/*! Common/Utils */ 1),

			Settings = __webpack_require__(/*! Storage/Settings */ 9)
		;

		/**
		 * @constructor
		 */
		function LanguageStore()
		{
			this.languages = ko.observableArray([]);
			this.languagesAdmin = ko.observableArray([]);

			this.language = ko.observable('')
				.extend({'limitedList': this.languages});

			this.languageAdmin = ko.observable('')
				.extend({'limitedList': this.languagesAdmin});

			this.userLanguage = ko.observable('');
			this.userLanguageAdmin = ko.observable('');
		}

		LanguageStore.prototype.populate = function ()
		{
			var
				aLanguages = Settings.settingsGet('Languages'),
				aLanguagesAdmin = Settings.settingsGet('LanguagesAdmin')
			;

			this.languages(Utils.isArray(aLanguages) ? aLanguages : []);
			this.languagesAdmin(Utils.isArray(aLanguagesAdmin) ? aLanguagesAdmin : []);

			this.language(Settings.settingsGet('Language'));
			this.languageAdmin(Settings.settingsGet('LanguageAdmin'));

			this.userLanguage(Settings.settingsGet('UserLanguage'));
			this.userLanguageAdmin(Settings.settingsGet('UserLanguageAdmin'));
		};

		module.exports = new LanguageStore();

	}());


/***/ },
/* 40 */
/*!******************************!*\
  !*** external "window.JSON" ***!
  \******************************/
/***/ function(module, exports, __webpack_require__) {

	module.exports = window.JSON;

/***/ },
/* 41 */,
/* 42 */
/*!*****************************!*\
  !*** ./dev/Stores/Theme.js ***!
  \*****************************/
/***/ function(module, exports, __webpack_require__) {

	
	(function () {

		'use strict';

		var
			ko = __webpack_require__(/*! ko */ 2),

			Utils = __webpack_require__(/*! Common/Utils */ 1),

			Settings = __webpack_require__(/*! Storage/Settings */ 9)
		;

		/**
		 * @constructor
		 */
		function ThemeStore()
		{
			this.themes = ko.observableArray([]);
			this.themeBackgroundName = ko.observable('');
			this.themeBackgroundHash = ko.observable('');

			this.theme = ko.observable('')
				.extend({'limitedList': this.themes});
		}

		ThemeStore.prototype.populate = function ()
		{
			var aThemes = Settings.settingsGet('Themes');

			this.themes(Utils.isArray(aThemes) ? aThemes : []);
			this.theme(Settings.settingsGet('Theme'));
			this.themeBackgroundName(Settings.settingsGet('UserBackgroundName'));
			this.themeBackgroundHash(Settings.settingsGet('UserBackgroundHash'));
		};

		module.exports = new ThemeStore();

	}());


/***/ },
/* 43 */
/*!*******************************!*\
  !*** ./dev/View/Popup/Ask.js ***!
  \*******************************/
/***/ function(module, exports, __webpack_require__) {

	
	(function () {

		'use strict';

		var
			_ = __webpack_require__(/*! _ */ 3),
			ko = __webpack_require__(/*! ko */ 2),
			key = __webpack_require__(/*! key */ 18),

			Enums = __webpack_require__(/*! Common/Enums */ 4),
			Utils = __webpack_require__(/*! Common/Utils */ 1),
			Translator = __webpack_require__(/*! Common/Translator */ 6),

			kn = __webpack_require__(/*! Knoin/Knoin */ 5),
			AbstractView = __webpack_require__(/*! Knoin/AbstractView */ 10)
		;

		/**
		 * @constructor
		 * @extends AbstractView
		 */
		function AskPopupView()
		{
			AbstractView.call(this, 'Popups', 'PopupsAsk');

			this.askDesc = ko.observable('');
			this.yesButton = ko.observable('');
			this.noButton = ko.observable('');

			this.yesFocus = ko.observable(false);
			this.noFocus = ko.observable(false);

			this.fYesAction = null;
			this.fNoAction = null;

			this.bFocusYesOnShow = true;
			this.bDisabeCloseOnEsc = true;
			this.sDefaultKeyScope = Enums.KeyState.PopupAsk;

			kn.constructorEnd(this);
		}

		kn.extendAsViewModel(['View/Popup/Ask', 'PopupsAskViewModel'], AskPopupView);
		_.extend(AskPopupView.prototype, AbstractView.prototype);

		AskPopupView.prototype.clearPopup = function ()
		{
			this.askDesc('');
			this.yesButton(Translator.i18n('POPUPS_ASK/BUTTON_YES'));
			this.noButton(Translator.i18n('POPUPS_ASK/BUTTON_NO'));

			this.yesFocus(false);
			this.noFocus(false);

			this.fYesAction = null;
			this.fNoAction = null;
		};

		AskPopupView.prototype.yesClick = function ()
		{
			this.cancelCommand();

			if (Utils.isFunc(this.fYesAction))
			{
				this.fYesAction.call(null);
			}
		};

		AskPopupView.prototype.noClick = function ()
		{
			this.cancelCommand();

			if (Utils.isFunc(this.fNoAction))
			{
				this.fNoAction.call(null);
			}
		};

		/**
		 * @param {string} sAskDesc
		 * @param {Function=} fYesFunc
		 * @param {Function=} fNoFunc
		 * @param {string=} sYesButton
		 * @param {string=} sNoButton
		 * @param {boolean=} bFocusYesOnShow
		 */
		AskPopupView.prototype.onShow = function (sAskDesc, fYesFunc, fNoFunc, sYesButton, sNoButton, bFocusYesOnShow)
		{
			this.clearPopup();

			this.fYesAction = fYesFunc || null;
			this.fNoAction = fNoFunc || null;

			this.askDesc(sAskDesc || '');
			if (sYesButton)
			{
				this.yesButton(sYesButton);
			}

			if (sYesButton)
			{
				this.yesButton(sNoButton);
			}

			this.bFocusYesOnShow = Utils.isUnd(bFocusYesOnShow) ? true : !!bFocusYesOnShow;
		};

		AskPopupView.prototype.onShowWithDelay = function ()
		{
			if (this.bFocusYesOnShow)
			{
				this.yesFocus(true);
			}
		};

		AskPopupView.prototype.onBuild = function ()
		{
			key('tab, shift+tab, right, left', Enums.KeyState.PopupAsk, _.bind(function () {
				if (this.yesFocus())
				{
					this.noFocus(true);
				}
				else
				{
					this.yesFocus(true);
				}
				return false;
			}, this));

			key('esc', Enums.KeyState.PopupAsk, _.bind(function () {
				this.noClick();
				return false;
			}, this));
		};

		module.exports = AskPopupView;

	}());

/***/ },
/* 44 */
/*!*************************************!*\
  !*** ./dev/View/Popup/Languages.js ***!
  \*************************************/
/***/ function(module, exports, __webpack_require__) {

	
	(function () {

		'use strict';

		var
			_ = __webpack_require__(/*! _ */ 3),
			ko = __webpack_require__(/*! ko */ 2),

			Utils = __webpack_require__(/*! Common/Utils */ 1),

			kn = __webpack_require__(/*! Knoin/Knoin */ 5),
			AbstractView = __webpack_require__(/*! Knoin/AbstractView */ 10)
		;

		/**
		 * @constructor
		 * @extends AbstractView
		 */
		function LanguagesPopupView()
		{
			AbstractView.call(this, 'Popups', 'PopupsLanguages');

			var self = this;

			this.fLang = null;
			this.userLanguage = ko.observable('');

			this.langs = ko.observableArray([]);

			this.languages = ko.computed(function () {
				var sUserLanguage = self.userLanguage();
				return _.map(self.langs(), function (sLanguage) {
					return {
						'key': sLanguage,
						'user': sLanguage === sUserLanguage,
						'selected': ko.observable(false),
						'fullName': Utils.convertLangName(sLanguage)
					};
				});
			});

			this.langs.subscribe(function () {
				this.setLanguageSelection();
			}, this);

			kn.constructorEnd(this);
		}

		kn.extendAsViewModel(['View/Popup/Languages', 'PopupsLanguagesViewModel'], LanguagesPopupView);
		_.extend(LanguagesPopupView.prototype, AbstractView.prototype);

		LanguagesPopupView.prototype.languageTooltipName = function (sLanguage)
		{
			var sResult = Utils.convertLangName(sLanguage, true);
			return Utils.convertLangName(sLanguage, false) === sResult ? '' : sResult;
		};

		LanguagesPopupView.prototype.setLanguageSelection = function ()
		{
			var sCurrent = this.fLang ? ko.unwrap(this.fLang) : '';
			_.each(this.languages(), function (oItem) {
				oItem['selected'](oItem['key'] === sCurrent);
			});
		};

		LanguagesPopupView.prototype.onBeforeShow = function ()
		{
			this.fLang = null;
			this.userLanguage('');

			this.langs([]);
		};

		LanguagesPopupView.prototype.onShow = function (fLanguage, aLangs, sUserLanguage)
		{
			this.fLang = fLanguage;
			this.userLanguage(sUserLanguage || '');

			this.langs(aLangs);
		};

		LanguagesPopupView.prototype.changeLanguage = function (sLang)
		{
			if (this.fLang)
			{
				this.fLang(sLang);
			}

			this.cancelCommand();
		};

		module.exports = LanguagesPopupView;

	}());

/***/ },
/* 45 */
/*!***********************************!*\
  !*** ./dev/Common/HtmlEditor.jsx ***!
  \***********************************/
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

	Object.defineProperty(exports, "__esModule", {
		value: true
	});
	exports.default = exports.HtmlEditor = undefined;

	var _common = __webpack_require__(/*! common */ 12);

	var _Globals = __webpack_require__(/*! Common/Globals */ 8);

	var _Globals2 = _interopRequireDefault(_Globals);

	var _Settings = __webpack_require__(/*! Storage/Settings */ 9);

	var _Settings2 = _interopRequireDefault(_Settings);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

	var HtmlEditor = (function () {

		/**
	  * @param {Object} element
	  * @param {Function=} onBlur
	  * @param {Function=} onReady
	  * @param {Function=} onModeChange
	  */

		function HtmlEditor(element) {
			var onBlur = arguments.length <= 1 || arguments[1] === undefined ? null : arguments[1];
			var onReady = arguments.length <= 2 || arguments[2] === undefined ? null : arguments[2];
			var onModeChange = arguments.length <= 3 || arguments[3] === undefined ? null : arguments[3];

			_classCallCheck(this, HtmlEditor);

			this.editor = null;
			this.$element = null;
			this.blurTimer = 0;
			this.onBlur = null;
			this.onReady = null;
			this.onModeChange = null;
			this.__inited = null;

			this.onBlur = onBlur;
			this.onReady = onReady;
			this.onModeChange = onModeChange;

			this.$element = $(element);

			this.resize = _common._.throttle(_common._.bind(this.resize, this), 100);

			this.__inited = false;

			this.init();
		}

		_createClass(HtmlEditor, [{
			key: 'blurTrigger',
			value: function blurTrigger() {
				var _this = this;

				if (this.onBlur) {
					_common.window.clearTimeout(this.blurTimer);
					this.blurTimer = _common.window.setTimeout(function () {
						_this.onBlur();
					}, 200);
				}
			}
		}, {
			key: 'focusTrigger',
			value: function focusTrigger() {
				if (this.onBlur) {
					_common.window.clearTimeout(this.blurTimer);
				}
			}

			/**
	   * @return {boolean}
	   */

		}, {
			key: 'isHtml',
			value: function isHtml() {
				return this.editor ? 'wysiwyg' === this.editor.mode : false;
			}

			/**
	   * @param {string} signature
	   * @param {bool} html
	   * @param {bool} insertBefore
	   */

		}, {
			key: 'setSignature',
			value: function setSignature(signature, html, insertBefore) {
				if (this.editor) {
					this.editor.execCommand('insertSignature', {
						'isHtml': html,
						'insertBefore': insertBefore,
						'signature': signature
					});
				}
			}

			/**
	   * @return {boolean}
	   */

		}, {
			key: 'checkDirty',
			value: function checkDirty() {
				return this.editor ? this.editor.checkDirty() : false;
			}
		}, {
			key: 'resetDirty',
			value: function resetDirty() {
				if (this.editor) {
					this.editor.resetDirty();
				}
			}

			/**
	   * @param {string} text
	   * @return {string}
	   */

		}, {
			key: 'clearSignatureSigns',
			value: function clearSignatureSigns(text) {
				return text.replace(/(\u200C|\u0002)/g, '');
			}

			/**
	   * @param {boolean=} wrapIsHtml = false
	   * @param {boolean=} clearSignatureSigns = false
	   * @return {string}
	   */

		}, {
			key: 'getData',
			value: function getData() {
				var wrapIsHtml = arguments.length <= 0 || arguments[0] === undefined ? false : arguments[0];
				var clearSignatureSigns = arguments.length <= 1 || arguments[1] === undefined ? false : arguments[1];

				var result = '';
				if (this.editor) {
					try {
						if ('plain' === this.editor.mode && this.editor.plugins.plain && this.editor.__plain) {
							result = this.editor.__plain.getRawData();
						} else {
							result = wrapIsHtml ? '<div data-html-editor-font-wrapper="true" style="font-family: arial, sans-serif; font-size: 13px;">' + this.editor.getData() + '</div>' : this.editor.getData();
						}
					} catch (e) {}

					if (clearSignatureSigns) {
						result = this.clearSignatureSigns(result);
					}
				}

				return result;
			}

			/**
	   * @param {boolean=} wrapIsHtml = false
	   * @param {boolean=} clearSignatureSigns = false
	   * @return {string}
	   */

		}, {
			key: 'getDataWithHtmlMark',
			value: function getDataWithHtmlMark() {
				var wrapIsHtml = arguments.length <= 0 || arguments[0] === undefined ? false : arguments[0];
				var clearSignatureSigns = arguments.length <= 1 || arguments[1] === undefined ? false : arguments[1];

				return (this.isHtml() ? ':HTML:' : '') + this.getData(wrapIsHtml, clearSignatureSigns);
			}
		}, {
			key: 'modeToggle',
			value: function modeToggle(plain, resize) {
				if (this.editor) {
					try {
						if (plain) {
							if ('plain' === this.editor.mode) {
								this.editor.setMode('wysiwyg');
							}
						} else {
							if ('wysiwyg' === this.editor.mode) {
								this.editor.setMode('plain');
							}
						}
					} catch (e) {}

					if (resize) {
						this.resize();
					}
				}
			}
		}, {
			key: 'setHtmlOrPlain',
			value: function setHtmlOrPlain(text, focus) {
				if (':HTML:' === text.substr(0, 6)) {
					this.setHtml(text.substr(6), focus);
				} else {
					this.setPlain(text, focus);
				}
			}
		}, {
			key: 'setHtml',
			value: function setHtml(html, focus) {
				if (this.editor && this.__inited) {
					this.modeToggle(true);

					html = html.replace(/<p[^>]*><\/p>/ig, '');

					try {
						this.editor.setData(html);
					} catch (e) {}

					if (focus) {
						this.focus();
					}
				}
			}
		}, {
			key: 'replaceHtml',
			value: function replaceHtml(find, _replaceHtml) {
				if (this.editor && this.__inited && 'wysiwyg' === this.editor.mode) {
					try {
						this.editor.setData(this.editor.getData().replace(find, _replaceHtml));
					} catch (e) {}
				}
			}
		}, {
			key: 'setPlain',
			value: function setPlain(plain, focus) {
				if (this.editor && this.__inited) {
					this.modeToggle(false);
					if ('plain' === this.editor.mode && this.editor.plugins.plain && this.editor.__plain) {
						return this.editor.__plain.setRawData(plain);
					} else {
						try {
							this.editor.setData(plain);
						} catch (e) {}
					}

					if (focus) {
						this.focus();
					}
				}
			}
		}, {
			key: 'init',
			value: function init() {
				var _this2 = this;

				if (this.$element && this.$element[0] && !this.editor) {
					var initFunc = function initFunc() {

						var config = _Globals2.default.oHtmlEditorDefaultConfig,
						    language = _Settings2.default.settingsGet('Language'),
						    allowSource = !!_Settings2.default.settingsGet('AllowHtmlEditorSourceButton'),
						    biti = !!_Settings2.default.settingsGet('AllowHtmlEditorBitiButtons');

						if ((allowSource || !biti) && !config.toolbarGroups.__cfgInited) {
							config.toolbarGroups.__cfgInited = true;

							if (allowSource) {
								config.removeButtons = config.removeButtons.replace(',Source', '');
							}

							if (!biti) {
								config.removePlugins += (config.removePlugins ? ',' : '') + 'bidi';
							}
						}

						config.enterMode = _common.window.CKEDITOR.ENTER_BR;
						config.shiftEnterMode = _common.window.CKEDITOR.ENTER_P;

						config.language = _Globals2.default.oHtmlEditorLangsMap[language] || 'en';
						if (_common.window.CKEDITOR.env) {
							_common.window.CKEDITOR.env.isCompatible = true;
						}

						_this2.editor = _common.window.CKEDITOR.appendTo(_this2.$element[0], config);

						_this2.editor.on('key', function (event) {
							if (event && event.data && 9 /* Tab */ === event.data.keyCode) {
								return false;
							}
						});

						_this2.editor.on('blur', function () {
							_this2.blurTrigger();
						});

						_this2.editor.on('mode', function () {
							_this2.blurTrigger();
							if (_this2.onModeChange) {
								_this2.onModeChange('plain' !== _this2.editor.mode);
							}
						});

						_this2.editor.on('focus', function () {
							_this2.focusTrigger();
						});

						if (_common.window.FileReader) {
							_this2.editor.on('drop', function (event) {
								if (0 < event.data.dataTransfer.getFilesCount()) {
									var file = event.data.dataTransfer.getFile(0);
									if (file && _common.window.FileReader && event.data.dataTransfer.id && file.type && file.type.match(/^image/i)) {
										var id = event.data.dataTransfer.id,
										    imageId = '[img=' + id + ']',
										    reader = new _common.window.FileReader();

										reader.onloadend = function () {
											if (reader.result) {
												_this2.replaceHtml(imageId, '<img src="' + reader.result + '" />');
											}
										};

										reader.readAsDataURL(file);

										event.data.dataTransfer.setData('text/html', imageId);
									}
								}
							});
						}

						_this2.editor.on('instanceReady', function () {

							if (_this2.editor.removeMenuItem) {
								_this2.editor.removeMenuItem('cut');
								_this2.editor.removeMenuItem('copy');
								_this2.editor.removeMenuItem('paste');
							}

							_this2.__resizable = true;
							_this2.__inited = true;

							_this2.resize();

							if (_this2.onReady) {
								_this2.onReady();
							}
						});
					};

					if (_common.window.CKEDITOR) {
						initFunc();
					} else {
						_common.window.__initEditor = initFunc;
					}
				}
			}
		}, {
			key: 'focus',
			value: function focus() {
				if (this.editor) {
					try {
						this.editor.focus();
					} catch (e) {}
				}
			}
		}, {
			key: 'hasFocus',
			value: function hasFocus() {
				if (this.editor) {
					try {
						return !!this.editor.focusManager.hasFocus;
					} catch (e) {}
				}

				return false;
			}
		}, {
			key: 'blur',
			value: function blur() {
				if (this.editor) {
					try {
						this.editor.focusManager.blur(true);
					} catch (e) {}
				}
			}
		}, {
			key: 'resize',
			value: function resize() {
				if (this.editor && this.__resizable) {
					try {
						this.editor.resize(this.$element.width(), this.$element.innerHeight());
					} catch (e) {}
				}
			}
		}, {
			key: 'setReadOnly',
			value: function setReadOnly(value) {
				if (this.editor) {
					try {
						this.editor.setReadOnly(!!value);
					} catch (e) {}
				}
			}
		}, {
			key: 'clear',
			value: function clear(focus) {
				this.setHtml('', focus);
			}
		}]);

		return HtmlEditor;
	})();

	exports.HtmlEditor = HtmlEditor;
	exports.default = HtmlEditor;

	module.exports = HtmlEditor;

/***/ },
/* 46 */
/*!*******************************************!*\
  !*** ./dev/Component/AbstracCheckbox.jsx ***!
  \*******************************************/
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

	Object.defineProperty(exports, "__esModule", {
		value: true
	});
	exports.default = exports.AbstracCheckbox = undefined;

	var _ko = __webpack_require__(/*! ko */ 2);

	var _ko2 = _interopRequireDefault(_ko);

	var _Utils = __webpack_require__(/*! Common/Utils */ 1);

	var _Utils2 = _interopRequireDefault(_Utils);

	var _Abstract = __webpack_require__(/*! Component/Abstract */ 16);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

	function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

	function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

	var AbstracCheckbox = (function (_AbstractComponent) {
		_inherits(AbstracCheckbox, _AbstractComponent);

		/**
	  * @param {Object} params = {}
	  */

		function AbstracCheckbox() {
			var params = arguments.length <= 0 || arguments[0] === undefined ? {} : arguments[0];

			_classCallCheck(this, AbstracCheckbox);

			var _this = _possibleConstructorReturn(this, Object.getPrototypeOf(AbstracCheckbox).call(this));

			_this.value = params.value;
			if (_Utils2.default.isUnd(_this.value) || !_this.value.subscribe) {
				_this.value = _ko2.default.observable(_Utils2.default.isUnd(_this.value) ? false : !!_this.value);
			}

			_this.enable = params.enable;
			if (_Utils2.default.isUnd(_this.enable) || !_this.enable.subscribe) {
				_this.enable = _ko2.default.observable(_Utils2.default.isUnd(_this.enable) ? true : !!_this.enable);
			}

			_this.disable = params.disable;
			if (_Utils2.default.isUnd(_this.disable) || !_this.disable.subscribe) {
				_this.disable = _ko2.default.observable(_Utils2.default.isUnd(_this.disable) ? false : !!_this.disable);
			}

			_this.label = params.label || '';
			_this.inline = _Utils2.default.isUnd(params.inline) ? false : params.inline;

			_this.readOnly = _Utils2.default.isUnd(params.readOnly) ? false : !!params.readOnly;
			_this.inverted = _Utils2.default.isUnd(params.inverted) ? false : !!params.inverted;

			_this.labeled = !_Utils2.default.isUnd(params.label);
			_this.labelAnimated = !!params.labelAnimated;
			return _this;
		}

		_createClass(AbstracCheckbox, [{
			key: 'click',
			value: function click() {
				if (!this.readOnly && this.enable() && !this.disable()) {
					this.value(!this.value());
				}
			}
		}]);

		return AbstracCheckbox;
	})(_Abstract.AbstractComponent);

	exports.AbstracCheckbox = AbstracCheckbox;
	exports.default = AbstracCheckbox;

/***/ },
/* 47 */,
/* 48 */
/*!***************************!*\
  !*** external "window.Q" ***!
  \***************************/
/***/ function(module, exports, __webpack_require__) {

	module.exports = window.Q;

/***/ },
/* 49 */
/*!************************************!*\
  !*** external "window.crossroads" ***!
  \************************************/
/***/ function(module, exports, __webpack_require__) {

	module.exports = window.crossroads;

/***/ },
/* 50 */
/*!**********************************!*\
  !*** ./dev/Stores/Admin/Capa.js ***!
  \**********************************/
/***/ function(module, exports, __webpack_require__) {

	
	(function () {

		'use strict';

		var
			ko = __webpack_require__(/*! ko */ 2),

			Enums = __webpack_require__(/*! Common/Enums */ 4),

			Settings = __webpack_require__(/*! Storage/Settings */ 9)
		;

		/**
		 * @constructor
		 */
		function CapaAdminStore()
		{
			this.additionalAccounts = ko.observable(false);
			this.identities = ko.observable(false);
			this.gravatar = ko.observable(false);
			this.attachmentThumbnails = ko.observable(false);
			this.sieve = ko.observable(false);
			this.filters = ko.observable(false);
			this.themes = ko.observable(true);
			this.userBackground = ko.observable(false);
			this.openPGP = ko.observable(false);
			this.twoFactorAuth = ko.observable(false);
			this.twoFactorAuthForce = ko.observable(false);
			this.templates = ko.observable(false);
		}

		CapaAdminStore.prototype.populate = function()
		{
			this.additionalAccounts(Settings.capa(Enums.Capa.AdditionalAccounts));
			this.identities(Settings.capa(Enums.Capa.Identities));
			this.gravatar(Settings.capa(Enums.Capa.Gravatar));
			this.attachmentThumbnails(Settings.capa(Enums.Capa.AttachmentThumbnails));
			this.sieve(Settings.capa(Enums.Capa.Sieve));
			this.filters(Settings.capa(Enums.Capa.Filters));
			this.themes(Settings.capa(Enums.Capa.Themes));
			this.userBackground(Settings.capa(Enums.Capa.UserBackground));
			this.openPGP(Settings.capa(Enums.Capa.OpenPGP));
			this.twoFactorAuth(Settings.capa(Enums.Capa.TwoFactor));
			this.twoFactorAuthForce(Settings.capa(Enums.Capa.TwoFactorForce));
			this.templates(Settings.capa(Enums.Capa.Templates));
		};

		module.exports = new CapaAdminStore();

	}());


/***/ },
/* 51 */,
/* 52 */,
/* 53 */,
/* 54 */
/*!********************************!*\
  !*** external "window.moment" ***!
  \********************************/
/***/ function(module, exports, __webpack_require__) {

	module.exports = window.moment;

/***/ },
/* 55 */
/*!*********************************!*\
  !*** ./dev/External/Opentip.js ***!
  \*********************************/
/***/ function(module, exports, __webpack_require__) {

	
	(function () {

		'use strict';

		var
			window = __webpack_require__(/*! window */ 13),
			Opentip = window.Opentip
		;

		Opentip.styles.rainloop = {

			'extends': 'standard',

			'fixed': true,
			'target': true,

			'delay': 0.2,
			'hideDelay': 0,

			'hideEffect': 'fade',
			'hideEffectDuration': 0.2,

			'showEffect': 'fade',
			'showEffectDuration': 0.2,

			'showOn': 'mouseover click',
			'removeElementsOnHide': true,

			'background': '#fff',
			'shadow': false,

			'borderColor': '#999',
			'borderRadius': 2,
			'borderWidth': 1
		};

		Opentip.styles.rainloopTip = {
			'extends': 'rainloop',
			'delay': 0.4,
			'group': 'rainloopTips'
		};

		Opentip.styles.rainloopErrorTip = {
			'extends': 'rainloop',
			'className': 'rainloopErrorTip'
		};

		module.exports = Opentip;

	}());


/***/ },
/* 56 */
/*!************************************!*\
  !*** ./dev/Remote/AbstractAjax.js ***!
  \************************************/
/***/ function(module, exports, __webpack_require__) {

	
	(function () {

		'use strict';

		var
			window = __webpack_require__(/*! window */ 13),
			_ = __webpack_require__(/*! _ */ 3),
			$ = __webpack_require__(/*! $ */ 14),

			Consts = __webpack_require__(/*! Common/Consts */ 17),
			Enums = __webpack_require__(/*! Common/Enums */ 4),
			Globals = __webpack_require__(/*! Common/Globals */ 8),
			Utils = __webpack_require__(/*! Common/Utils */ 1),
			Plugins = __webpack_require__(/*! Common/Plugins */ 23),
			Links = __webpack_require__(/*! Common/Links */ 11),

			Settings = __webpack_require__(/*! Storage/Settings */ 9)
		;

		/**
		* @constructor
		*/
		function AbstractAjaxRemote()
		{
			this.oRequests = {};
		}

		AbstractAjaxRemote.prototype.oRequests = {};

		/**
		 * @param {?Function} fCallback
		 * @param {string} sRequestAction
		 * @param {string} sType
		 * @param {?AjaxJsonDefaultResponse} oData
		 * @param {boolean} bCached
		 * @param {*=} oRequestParameters
		 */
		AbstractAjaxRemote.prototype.defaultResponse = function (fCallback, sRequestAction, sType, oData, bCached, oRequestParameters)
		{
			var
				fCall = function () {
					if (Enums.StorageResultType.Success !== sType && Globals.bUnload)
					{
						sType = Enums.StorageResultType.Unload;
					}

					if (Enums.StorageResultType.Success === sType && oData && !oData.Result)
					{
						if (oData && -1 < Utils.inArray(oData.ErrorCode, [
							Enums.Notification.AuthError, Enums.Notification.AccessError,
							Enums.Notification.ConnectionError, Enums.Notification.DomainNotAllowed, Enums.Notification.AccountNotAllowed,
							Enums.Notification.MailServerError,	Enums.Notification.UnknownNotification, Enums.Notification.UnknownError
						]))
						{
							Globals.iAjaxErrorCount++;
						}

						if (oData && Enums.Notification.InvalidToken === oData.ErrorCode)
						{
							Globals.iTokenErrorCount++;
						}

						if (Consts.TOKEN_ERROR_LIMIT < Globals.iTokenErrorCount)
						{
							if (Globals.__APP__ && Globals.__APP__.loginAndLogoutReload)
							{
								 Globals.__APP__.loginAndLogoutReload(false, true);
							}
						}

						if (oData.ClearAuth || oData.Logout || Consts.AJAX_ERROR_LIMIT < Globals.iAjaxErrorCount)
						{
							if (Globals.__APP__ && Globals.__APP__.clearClientSideToken)
							{
								Globals.__APP__.clearClientSideToken();

								if (!oData.ClearAuth &&  Globals.__APP__.loginAndLogoutReload)
								{
									 Globals.__APP__.loginAndLogoutReload(false, true);
								}
							}
						}
					}
					else if (Enums.StorageResultType.Success === sType && oData && oData.Result)
					{
						Globals.iAjaxErrorCount = 0;
						Globals.iTokenErrorCount = 0;
					}

					if (fCallback)
					{
						Plugins.runHook('ajax-default-response', [sRequestAction, Enums.StorageResultType.Success === sType ? oData : null, sType, bCached, oRequestParameters]);

						fCallback(
							sType,
							Enums.StorageResultType.Success === sType ? oData : null,
							bCached,
							sRequestAction,
							oRequestParameters
						);
					}
				}
			;

			switch (sType)
			{
				case 'success':
					sType = Enums.StorageResultType.Success;
					break;
				case 'abort':
					sType = Enums.StorageResultType.Abort;
					break;
				default:
					sType = Enums.StorageResultType.Error;
					break;
			}

			if (Enums.StorageResultType.Error === sType)
			{
				_.delay(fCall, 300);
			}
			else
			{
				fCall();
			}
		};

		/**
		 * @param {?Function} fResultCallback
		 * @param {Object} oParameters
		 * @param {?number=} iTimeOut = 20000
		 * @param {string=} sGetAdd = ''
		 * @param {Array=} aAbortActions = []
		 * @return {jQuery.jqXHR}
		 */
		AbstractAjaxRemote.prototype.ajaxRequest = function (fResultCallback, oParameters, iTimeOut, sGetAdd, aAbortActions)
		{
			var
				self = this,
				bPost = '' === sGetAdd,
				oHeaders = {},
				iStart = (new window.Date()).getTime(),
				oDefAjax = null,
				sAction = ''
			;

			oParameters = oParameters || {};
			iTimeOut = Utils.isNormal(iTimeOut) ? iTimeOut : 20000;
			sGetAdd = Utils.isUnd(sGetAdd) ? '' : Utils.pString(sGetAdd);
			aAbortActions = Utils.isArray(aAbortActions) ? aAbortActions : [];

			sAction = oParameters.Action || '';

			if (sAction && 0 < aAbortActions.length)
			{
				_.each(aAbortActions, function (sActionToAbort) {
					if (self.oRequests[sActionToAbort])
					{
						self.oRequests[sActionToAbort].__aborted = true;
						if (self.oRequests[sActionToAbort].abort)
						{
							self.oRequests[sActionToAbort].abort();
						}
						self.oRequests[sActionToAbort] = null;
					}
				});
			}

			if (bPost)
			{
				oParameters['XToken'] = Settings.settingsGet('Token');
			}

			oDefAjax = $.ajax({
				'type': bPost ? 'POST' : 'GET',
				'url': Links.ajax(sGetAdd),
				'async': true,
				'dataType': 'json',
				'data': bPost ? oParameters : {},
				'headers': oHeaders,
				'timeout': iTimeOut,
				'global': true
			});

			oDefAjax.always(function (oData, sType) {

				var bCached = false;
				if (oData && oData['Time'])
				{
					bCached = Utils.pInt(oData['Time']) > (new window.Date()).getTime() - iStart;
				}

				if (sAction && self.oRequests[sAction])
				{
					if (self.oRequests[sAction].__aborted)
					{
						sType = 'abort';
					}

					self.oRequests[sAction] = null;
				}

				self.defaultResponse(fResultCallback, sAction, sType, oData, bCached, oParameters);
			});

			if (sAction && 0 < aAbortActions.length && -1 < Utils.inArray(sAction, aAbortActions))
			{
				if (this.oRequests[sAction])
				{
					this.oRequests[sAction].__aborted = true;
					if (this.oRequests[sAction].abort)
					{
						this.oRequests[sAction].abort();
					}
					this.oRequests[sAction] = null;
				}

				this.oRequests[sAction] = oDefAjax;
			}

			return oDefAjax;
		};

		/**
		 * @param {?Function} fCallback
		 * @param {string} sAction
		 * @param {Object=} oParameters
		 * @param {?number=} iTimeout
		 * @param {string=} sGetAdd = ''
		 * @param {Array=} aAbortActions = []
		 */
		AbstractAjaxRemote.prototype.defaultRequest = function (fCallback, sAction, oParameters, iTimeout, sGetAdd, aAbortActions)
		{
			oParameters = oParameters || {};
			oParameters.Action = sAction;

			sGetAdd = Utils.pString(sGetAdd);

			Plugins.runHook('ajax-default-request', [sAction, oParameters, sGetAdd]);

			return this.ajaxRequest(fCallback, oParameters,
				Utils.isUnd(iTimeout) ? Consts.DEFAULT_AJAX_TIMEOUT : Utils.pInt(iTimeout), sGetAdd, aAbortActions);
		};

		/**
		 * @param {?Function} fCallback
		 */
		AbstractAjaxRemote.prototype.noop = function (fCallback)
		{
			this.defaultRequest(fCallback, 'Noop');
		};

		/**
		 * @param {?Function} fCallback
		 * @param {string} sMessage
		 * @param {string} sFileName
		 * @param {number} iLineNo
		 * @param {string} sLocation
		 * @param {string} sHtmlCapa
		 * @param {number} iTime
		 */
		AbstractAjaxRemote.prototype.jsError = function (fCallback, sMessage, sFileName, iLineNo, sLocation, sHtmlCapa, iTime)
		{
			this.defaultRequest(fCallback, 'JsError', {
				'Message': sMessage,
				'FileName': sFileName,
				'LineNo': iLineNo,
				'Location': sLocation,
				'HtmlCapa': sHtmlCapa,
				'TimeOnPage': iTime
			});
		};

		/**
		 * @param {?Function} fCallback
		 * @param {string} sType
		 * @param {Array=} mData = null
		 * @param {boolean=} bIsError = false
		 */
		AbstractAjaxRemote.prototype.jsInfo = function (fCallback, sType, mData, bIsError)
		{
			this.defaultRequest(fCallback, 'JsInfo', {
				'Type': sType,
				'Data': mData,
				'IsError': (Utils.isUnd(bIsError) ? false : !!bIsError) ? '1' : '0'
			});
		};

		/**
		 * @param {?Function} fCallback
		 */
		AbstractAjaxRemote.prototype.getPublicKey = function (fCallback)
		{
			this.defaultRequest(fCallback, 'GetPublicKey');
		};

		/**
		 * @param {?Function} fCallback
		 * @param {string} sVersion
		 */
		AbstractAjaxRemote.prototype.jsVersion = function (fCallback, sVersion)
		{
			this.defaultRequest(fCallback, 'Version', {
				'Version': sVersion
			});
		};

		module.exports = AbstractAjaxRemote;

	}());

/***/ },
/* 57 */
/*!****************************************!*\
  !*** ./dev/Screen/AbstractSettings.js ***!
  \****************************************/
/***/ function(module, exports, __webpack_require__) {

	
	(function () {

		'use strict';

		var
			_ = __webpack_require__(/*! _ */ 3),
			$ = __webpack_require__(/*! $ */ 14),
			ko = __webpack_require__(/*! ko */ 2),

			Globals = __webpack_require__(/*! Common/Globals */ 8),
			Utils = __webpack_require__(/*! Common/Utils */ 1),
			Links = __webpack_require__(/*! Common/Links */ 11),

			kn = __webpack_require__(/*! Knoin/Knoin */ 5),
			AbstractScreen = __webpack_require__(/*! Knoin/AbstractScreen */ 38)
		;

		/**
		 * @constructor
		 * @param {Array} aViewModels
		 * @extends AbstractScreen
		 */
		function AbstractSettingsScreen(aViewModels)
		{
			AbstractScreen.call(this, 'settings', aViewModels);

			this.menu = ko.observableArray([]);

			this.oCurrentSubScreen = null;
			this.oViewModelPlace = null;

			this.setupSettings();
		}

		_.extend(AbstractSettingsScreen.prototype, AbstractScreen.prototype);

		/**
		 * @param {Function=} fCallback
		 */
		AbstractSettingsScreen.prototype.setupSettings = function (fCallback)
		{
			if (fCallback)
			{
				fCallback();
			}
		};

		AbstractSettingsScreen.prototype.onRoute = function (sSubName)
		{
			var
				self = this,
				oSettingsScreen = null,
				RoutedSettingsViewModel = null,
				oViewModelPlace = null,
				oViewModelDom = null
			;

			RoutedSettingsViewModel = _.find(Globals.aViewModels['settings'], function (SettingsViewModel) {
				return SettingsViewModel && SettingsViewModel.__rlSettingsData &&
					sSubName === SettingsViewModel.__rlSettingsData.Route;
			});

			if (RoutedSettingsViewModel)
			{
				if (_.find(Globals.aViewModels['settings-removed'], function (DisabledSettingsViewModel) {
					return DisabledSettingsViewModel && DisabledSettingsViewModel === RoutedSettingsViewModel;
				}))
				{
					RoutedSettingsViewModel = null;
				}

				if (RoutedSettingsViewModel && _.find(Globals.aViewModels['settings-disabled'], function (DisabledSettingsViewModel) {
					return DisabledSettingsViewModel && DisabledSettingsViewModel === RoutedSettingsViewModel;
				}))
				{
					RoutedSettingsViewModel = null;
				}
			}

			if (RoutedSettingsViewModel)
			{
				if (RoutedSettingsViewModel.__builded && RoutedSettingsViewModel.__vm)
				{
					oSettingsScreen = RoutedSettingsViewModel.__vm;
				}
				else
				{
					oViewModelPlace = this.oViewModelPlace;
					if (oViewModelPlace && 1 === oViewModelPlace.length)
					{
						oSettingsScreen = new RoutedSettingsViewModel();

						oViewModelDom = $('<div></div>').addClass('rl-settings-view-model').hide();
						oViewModelDom.appendTo(oViewModelPlace);

						oSettingsScreen.viewModelDom = oViewModelDom;

						oSettingsScreen.__rlSettingsData = RoutedSettingsViewModel.__rlSettingsData;

						RoutedSettingsViewModel.__dom = oViewModelDom;
						RoutedSettingsViewModel.__builded = true;
						RoutedSettingsViewModel.__vm = oSettingsScreen;

						ko.applyBindingAccessorsToNode(oViewModelDom[0], {
							'translatorInit': true,
							'template': function () { return {'name': RoutedSettingsViewModel.__rlSettingsData.Template}; }
						}, oSettingsScreen);

						Utils.delegateRun(oSettingsScreen, 'onBuild', [oViewModelDom]);
					}
					else
					{
						Utils.log('Cannot find sub settings view model position: SettingsSubScreen');
					}
				}

				if (oSettingsScreen)
				{
					_.defer(function () {
						// hide
						if (self.oCurrentSubScreen)
						{
							Utils.delegateRun(self.oCurrentSubScreen, 'onHide');
							self.oCurrentSubScreen.viewModelDom.hide();
						}
						// --

						self.oCurrentSubScreen = oSettingsScreen;

						// show
						if (self.oCurrentSubScreen)
						{
							Utils.delegateRun(self.oCurrentSubScreen, 'onBeforeShow');
							self.oCurrentSubScreen.viewModelDom.show();
							Utils.delegateRun(self.oCurrentSubScreen, 'onShow');
							Utils.delegateRun(self.oCurrentSubScreen, 'onShowWithDelay', [], 200);

							_.each(self.menu(), function (oItem) {
								oItem.selected(oSettingsScreen && oSettingsScreen.__rlSettingsData && oItem.route === oSettingsScreen.__rlSettingsData.Route);
							});

							$('#rl-content .b-settings .b-content .content').scrollTop(0);
						}
						// --

						Utils.windowResize();
					});
				}
			}
			else
			{
				kn.setHash(Links.settings(), false, true);
			}
		};

		AbstractSettingsScreen.prototype.onHide = function ()
		{
			if (this.oCurrentSubScreen && this.oCurrentSubScreen.viewModelDom)
			{
				Utils.delegateRun(this.oCurrentSubScreen, 'onHide');
				this.oCurrentSubScreen.viewModelDom.hide();
			}
		};

		AbstractSettingsScreen.prototype.onBuild = function ()
		{
			_.each(Globals.aViewModels['settings'], function (SettingsViewModel) {
				if (SettingsViewModel && SettingsViewModel.__rlSettingsData &&
					!_.find(Globals.aViewModels['settings-removed'], function (RemoveSettingsViewModel) {
						return RemoveSettingsViewModel && RemoveSettingsViewModel === SettingsViewModel;
					}))
				{
					this.menu.push({
						'route': SettingsViewModel.__rlSettingsData.Route,
						'label': SettingsViewModel.__rlSettingsData.Label,
						'selected': ko.observable(false),
						'disabled': !!_.find(Globals.aViewModels['settings-disabled'], function (DisabledSettingsViewModel) {
							return DisabledSettingsViewModel && DisabledSettingsViewModel === SettingsViewModel;
						})
					});
				}
			}, this);

			this.oViewModelPlace = $('#rl-content #rl-settings-subscreen');
		};

		AbstractSettingsScreen.prototype.routes = function ()
		{
			var
				DefaultViewModel = _.find(Globals.aViewModels['settings'], function (SettingsViewModel) {
					return SettingsViewModel && SettingsViewModel.__rlSettingsData && SettingsViewModel.__rlSettingsData['IsDefault'];
				}),
				sDefaultRoute = DefaultViewModel ? DefaultViewModel.__rlSettingsData['Route'] : 'general',
				oRules = {
					'subname': /^(.*)$/,
					'normalize_': function (oRequest, oVals) {
						oVals.subname = Utils.isUnd(oVals.subname) ? sDefaultRoute : Utils.pString(oVals.subname);
						return [oVals.subname];
					}
				}
			;

			return [
				['{subname}/', oRules],
				['{subname}', oRules],
				['', oRules]
			];
		};

		module.exports = AbstractSettingsScreen;

	}());

/***/ },
/* 58 */
/*!************************************!*\
  !*** ./dev/Stores/Admin/Domain.js ***!
  \************************************/
/***/ function(module, exports, __webpack_require__) {

	
	(function () {

		'use strict';

		var
			ko = __webpack_require__(/*! ko */ 2)
		;

		/**
		 * @constructor
		 */
		function DomainAdminStore()
		{
			this.domains = ko.observableArray([]);
			this.domains.loading = ko.observable(false).extend({'throttle': 100});
		}

		module.exports = new DomainAdminStore();

	}());


/***/ },
/* 59 */
/*!*************************************!*\
  !*** ./dev/Stores/Admin/License.js ***!
  \*************************************/
/***/ function(module, exports, __webpack_require__) {

	
	(function () {

		'use strict';

		var
			ko = __webpack_require__(/*! ko */ 2)
		;

		/**
		 * @constructor
		 */
		function LicenseAdminStore()
		{
			this.licensing = ko.observable(false);
			this.licensingProcess = ko.observable(false);
			this.licenseValid = ko.observable(false);
			this.licenseExpired = ko.observable(0);
			this.licenseError = ko.observable('');

			this.licenseTrigger = ko.observable(false);
		}

		module.exports = new LicenseAdminStore();

	}());


/***/ },
/* 60 */
/*!*************************************!*\
  !*** ./dev/Stores/Admin/Package.js ***!
  \*************************************/
/***/ function(module, exports, __webpack_require__) {

	
	(function () {

		'use strict';

		var
			ko = __webpack_require__(/*! ko */ 2)
		;

		/**
		 * @constructor
		 */
		function PackageAdminStore()
		{
			this.packages = ko.observableArray([]);
			this.packages.loading = ko.observable(false).extend({'throttle': 100});

			this.packagesReal = ko.observable(true);
			this.packagesMainUpdatable = ko.observable(true);
		}

		module.exports = new PackageAdminStore();

	}());


/***/ },
/* 61 */
/*!************************************!*\
  !*** ./dev/Stores/Admin/Plugin.js ***!
  \************************************/
/***/ function(module, exports, __webpack_require__) {

	
	(function () {

		'use strict';

		var
			ko = __webpack_require__(/*! ko */ 2)
		;

		/**
		 * @constructor
		 */
		function PluginAdminStore()
		{
			this.plugins = ko.observableArray([]);
			this.plugins.loading = ko.observable(false).extend({'throttle': 100});
			this.plugins.error = ko.observable('');
		}

		module.exports = new PluginAdminStore();

	}());


/***/ },
/* 62 */,
/* 63 */,
/* 64 */,
/* 65 */,
/* 66 */
/*!******************************!*\
  !*** ./dev/App/Abstract.jsx ***!
  \******************************/
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

	Object.defineProperty(exports, "__esModule", {
		value: true
	});
	exports.default = exports.AbstractApp = undefined;

	var _common = __webpack_require__(/*! common */ 12);

	var _Globals = __webpack_require__(/*! Common/Globals */ 8);

	var _Globals2 = _interopRequireDefault(_Globals);

	var _Enums = __webpack_require__(/*! Common/Enums */ 4);

	var Enums = _interopRequireWildcard(_Enums);

	var _Utils = __webpack_require__(/*! Common/Utils */ 1);

	var _Utils2 = _interopRequireDefault(_Utils);

	var _Links = __webpack_require__(/*! Common/Links */ 11);

	var _Links2 = _interopRequireDefault(_Links);

	var _Events = __webpack_require__(/*! Common/Events */ 25);

	var _Events2 = _interopRequireDefault(_Events);

	var _Translator = __webpack_require__(/*! Common/Translator */ 6);

	var _Translator2 = _interopRequireDefault(_Translator);

	var _Settings = __webpack_require__(/*! Storage/Settings */ 9);

	var _Settings2 = _interopRequireDefault(_Settings);

	var _AbstractBoot2 = __webpack_require__(/*! Knoin/AbstractBoot */ 77);

	function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } else { var newObj = {}; if (obj != null) { for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) newObj[key] = obj[key]; } } newObj.default = obj; return newObj; } }

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

	function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

	function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

	var AbstractApp = (function (_AbstractBoot) {
		_inherits(AbstractApp, _AbstractBoot);

		/**
	  * @param {RemoteStorage|AdminRemoteStorage} Remote
	  */

		function AbstractApp(Remote) {
			_classCallCheck(this, AbstractApp);

			var _this = _possibleConstructorReturn(this, Object.getPrototypeOf(AbstractApp).call(this));

			_this.googlePreviewSupportedCache = null;
			_this.isLocalAutocomplete = true;
			_this.iframe = null;

			_this.iframe = (0, _common.$)('<iframe style="display:none" src="javascript:;" />').appendTo('body');

			_Globals2.default.$win.on('error', function (oEvent) {
				if (oEvent && oEvent.originalEvent && oEvent.originalEvent.message && -1 === _Utils2.default.inArray(oEvent.originalEvent.message, ['Script error.', 'Uncaught Error: Error calling method on NPObject.'])) {
					Remote.jsError(_Utils2.default.emptyFunction, oEvent.originalEvent.message, oEvent.originalEvent.filename, oEvent.originalEvent.lineno, _common.window.location && _common.window.location.toString ? _common.window.location.toString() : '', _Globals2.default.$html.attr('class'), _Utils2.default.microtime() - _Globals2.default.startMicrotime);
				}
			});

			_Globals2.default.$win.on('resize', function () {
				_Events2.default.pub('window.resize');
			});

			_Events2.default.sub('window.resize', _common._.throttle(function () {

				var iH = _Globals2.default.$win.height(),
				    iW = _Globals2.default.$win.height();

				if (_Globals2.default.$win.__sizes[0] !== iH || _Globals2.default.$win.__sizes[1] !== iW) {
					_Globals2.default.$win.__sizes[0] = iH;
					_Globals2.default.$win.__sizes[1] = iW;

					_Events2.default.pub('window.resize.real');
				}
			}, 50));

			// DEBUG
			//		Events.sub({
			//			'window.resize': function () {
			//				window.console.log('window.resize');
			//			},
			//			'window.resize.real': function () {
			//				window.console.log('window.resize.real');
			//			}
			//		});

			_Globals2.default.$doc.on('keydown', function (oEvent) {
				if (oEvent && oEvent.ctrlKey) {
					_Globals2.default.$html.addClass('rl-ctrl-key-pressed');
				}
			}).on('keyup', function (oEvent) {
				if (oEvent && !oEvent.ctrlKey) {
					_Globals2.default.$html.removeClass('rl-ctrl-key-pressed');
				}
			});

			_Globals2.default.$doc.on('mousemove keypress click', _common._.debounce(function () {
				_Events2.default.pub('rl.auto-logout-refresh');
			}, 5000));

			(0, _common.key)('esc, enter', Enums.KeyState.All, _common._.bind(function () {
				_Utils2.default.detectDropdownVisibility();
			}, _this));
			return _this;
		}

		_createClass(AbstractApp, [{
			key: 'remote',
			value: function remote() {
				return null;
			}
		}, {
			key: 'data',
			value: function data() {
				return null;
			}

			/**
	   * @param {string} link
	   * @return {boolean}
	   */

		}, {
			key: 'download',
			value: function download(link) {

				if (_Globals2.default.sUserAgent && (_Globals2.default.sUserAgent.indexOf('chrome') > -1 || _Globals2.default.sUserAgent.indexOf('chrome') > -1)) {
					var oLink = _common.window.document.createElement('a');
					oLink['href'] = link;

					if (_common.window.document['createEvent']) {
						var oE = _common.window.document['createEvent']('MouseEvents');
						if (oE && oE['initEvent'] && oLink['dispatchEvent']) {
							oE['initEvent']('click', true, true);
							oLink['dispatchEvent'](oE);
							return true;
						}
					}
				}

				if (_Globals2.default.bMobileDevice) {
					_common.window.open(link, '_self');
					_common.window.focus();
				} else {
					this.iframe.attr('src', link);
					//		window.document.location.href = link;
				}

				return true;
			}

			/**
	   * @return {boolean}
	   */

		}, {
			key: 'googlePreviewSupported',
			value: function googlePreviewSupported() {
				if (null === this.googlePreviewSupportedCache) {
					this.googlePreviewSupportedCache = !!_Settings2.default.settingsGet('AllowGoogleSocial') && !!_Settings2.default.settingsGet('AllowGoogleSocialPreview');
				}

				return this.googlePreviewSupportedCache;
			}

			/**
	   * @param {string} title
	   */

		}, {
			key: 'setWindowTitle',
			value: function setWindowTitle(title) {
				title = _Utils2.default.isNormal(title) && 0 < title.length ? '' + title : '';
				if (_Settings2.default.settingsGet('Title')) {
					title += (title ? ' - ' : '') + _Settings2.default.settingsGet('Title');
				}

				_common.window.document.title = title + ' ...';
				_common.window.document.title = title;
			}
		}, {
			key: 'redirectToAdminPanel',
			value: function redirectToAdminPanel() {
				_common._.delay(function () {
					return _common.window.location.href = _Links2.default.rootAdmin();
				}, 100);
			}
		}, {
			key: 'clearClientSideToken',
			value: function clearClientSideToken() {
				if (_common.window.__rlah_clear) {
					_common.window.__rlah_clear();
				}
			}

			/**
	   * @param {string} key
	   */

		}, {
			key: 'setClientSideToken',
			value: function setClientSideToken(key) {
				if (_common.window.__rlah_set) {
					_common.window.__rlah_set(key);

					__webpack_require__(/*! Storage/Settings */ 9).settingsSet('AuthAccountHash', key);
					__webpack_require__(/*! Common/Links */ 11).populateAuthSuffix();
				}
			}

			/**
	   * @param {boolean=} admin = false
	   * @param {boolean=} logout = false
	   * @param {boolean=} close = false
	   */

		}, {
			key: 'loginAndLogoutReload',
			value: function loginAndLogoutReload() {
				var admin = arguments.length <= 0 || arguments[0] === undefined ? false : arguments[0];
				var logout = arguments.length <= 1 || arguments[1] === undefined ? false : arguments[1];
				var close = arguments.length <= 2 || arguments[2] === undefined ? false : arguments[2];

				var kn = __webpack_require__(/*! Knoin/Knoin */ 5),
				    inIframe = !!_Settings2.default.settingsGet('InIframe');

				var customLogoutLink = _Utils2.default.pString(_Settings2.default.settingsGet('CustomLogoutLink'));

				if (logout) {
					this.clearClientSideToken();
				}

				if (logout && close && _common.window.close) {
					_common.window.close();
				}

				customLogoutLink = customLogoutLink || (admin ? _Links2.default.rootAdmin() : _Links2.default.rootUser());

				if (logout && _common.window.location.href !== customLogoutLink) {
					_common._.delay(function () {
						if (inIframe && _common.window.parent) {
							_common.window.parent.location.href = customLogoutLink;
						} else {
							_common.window.location.href = customLogoutLink;
						}
					}, 100);
				} else {
					kn.routeOff();
					kn.setHash(_Links2.default.root(), true);
					kn.routeOff();

					_common._.delay(function () {
						if (inIframe && _common.window.parent) {
							_common.window.parent.location.reload();
						} else {
							_common.window.location.reload();
						}
					}, 100);
				}
			}
		}, {
			key: 'historyBack',
			value: function historyBack() {
				_common.window.history.back();
			}
		}, {
			key: 'bootstart',
			value: function bootstart() {

				// Utils.log('Ps' + 'ss, hac' + 'kers! The' + 're\'s not' + 'hing inte' + 'resting :' + ')');

				_Events2.default.pub('rl.bootstart');

				var ssm = __webpack_require__(/*! ssm */ 85),
				    ko = __webpack_require__(/*! ko */ 2);

				ko.components.register('SaveTrigger', __webpack_require__(/*! Component/SaveTrigger */ 73));
				ko.components.register('Input', __webpack_require__(/*! Component/Input */ 70));
				ko.components.register('Select', __webpack_require__(/*! Component/Select */ 75));
				ko.components.register('Radio', __webpack_require__(/*! Component/Radio */ 72));
				ko.components.register('TextArea', __webpack_require__(/*! Component/TextArea */ 76));

				ko.components.register('x-script', __webpack_require__(/*! Component/Script */ 74));
				//		ko.components.register('svg-icon', require('Component/SvgIcon'));

				if ( /**false && /**/_Settings2.default.settingsGet('MaterialDesign') && _Globals2.default.bAnimationSupported) {
					ko.components.register('Checkbox', __webpack_require__(/*! Component/MaterialDesign/Checkbox */ 71));
					ko.components.register('CheckboxSimple', __webpack_require__(/*! Component/Checkbox */ 37));
				} else {
					//			ko.components.register('Checkbox', require('Component/Classic/Checkbox'));
					//			ko.components.register('CheckboxSimple', require('Component/Classic/Checkbox'));
					ko.components.register('Checkbox', __webpack_require__(/*! Component/Checkbox */ 37));
					ko.components.register('CheckboxSimple', __webpack_require__(/*! Component/Checkbox */ 37));
				}

				_Translator2.default.initOnStartOrLangChange(_Translator2.default.initNotificationLanguage, _Translator2.default);

				_common._.delay(_Utils2.default.windowResizeCallback, 1000);

				ssm.addState({
					'id': 'mobile',
					'maxWidth': 767,
					'onEnter': function onEnter() {
						_Globals2.default.$html.addClass('ssm-state-mobile');
						_Events2.default.pub('ssm.mobile-enter');
					},
					'onLeave': function onLeave() {
						_Globals2.default.$html.removeClass('ssm-state-mobile');
						_Events2.default.pub('ssm.mobile-leave');
					}
				});

				ssm.addState({
					'id': 'tablet',
					'minWidth': 768,
					'maxWidth': 999,
					'onEnter': function onEnter() {
						_Globals2.default.$html.addClass('ssm-state-tablet');
					},
					'onLeave': function onLeave() {
						_Globals2.default.$html.removeClass('ssm-state-tablet');
					}
				});

				ssm.addState({
					'id': 'desktop',
					'minWidth': 1000,
					'maxWidth': 1400,
					'onEnter': function onEnter() {
						_Globals2.default.$html.addClass('ssm-state-desktop');
					},
					'onLeave': function onLeave() {
						_Globals2.default.$html.removeClass('ssm-state-desktop');
					}
				});

				ssm.addState({
					'id': 'desktop-large',
					'minWidth': 1400,
					'onEnter': function onEnter() {
						_Globals2.default.$html.addClass('ssm-state-desktop-large');
					},
					'onLeave': function onLeave() {
						_Globals2.default.$html.removeClass('ssm-state-desktop-large');
					}
				});

				_Events2.default.sub('ssm.mobile-enter', function () {
					_Globals2.default.leftPanelDisabled(true);
				});

				_Events2.default.sub('ssm.mobile-leave', function () {
					_Globals2.default.leftPanelDisabled(false);
				});

				_Globals2.default.leftPanelDisabled.subscribe(function (bValue) {
					_Globals2.default.$html.toggleClass('rl-left-panel-disabled', bValue);
				});

				_Globals2.default.leftPanelType.subscribe(function (sValue) {
					_Globals2.default.$html.toggleClass('rl-left-panel-none', 'none' === sValue);
					_Globals2.default.$html.toggleClass('rl-left-panel-short', 'short' === sValue);
				});

				ssm.ready();

				__webpack_require__(/*! Stores/Language */ 39).populate();
				__webpack_require__(/*! Stores/Theme */ 42).populate();
				__webpack_require__(/*! Stores/Social */ 34).populate();
			}
		}]);

		return AbstractApp;
	})(_AbstractBoot2.AbstractBoot);

	exports.AbstractApp = AbstractApp;
	exports.default = AbstractApp;

/***/ },
/* 67 */,
/* 68 */
/*!*****************************!*\
  !*** ./dev/Common/Mime.jsx ***!
  \*****************************/
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	module.exports = {

		'eml': 'message/rfc822',
		'mime': 'message/rfc822',
		'txt': 'text/plain',
		'text': 'text/plain',
		'def': 'text/plain',
		'list': 'text/plain',
		'in': 'text/plain',
		'ini': 'text/plain',
		'log': 'text/plain',
		'sql': 'text/plain',
		'cfg': 'text/plain',
		'conf': 'text/plain',
		'asc': 'text/plain',
		'rtx': 'text/richtext',
		'vcard': 'text/vcard',
		'vcf': 'text/vcard',
		'htm': 'text/html',
		'html': 'text/html',
		'csv': 'text/csv',
		'ics': 'text/calendar',
		'ifb': 'text/calendar',
		'xml': 'text/xml',
		'json': 'application/json',
		'swf': 'application/x-shockwave-flash',
		'hlp': 'application/winhlp',
		'wgt': 'application/widget',
		'chm': 'application/vnd.ms-htmlhelp',
		'p10': 'application/pkcs10',
		'p7c': 'application/pkcs7-mime',
		'p7m': 'application/pkcs7-mime',
		'p7s': 'application/pkcs7-signature',
		'torrent': 'application/x-bittorrent',

		// scripts
		'js': 'application/javascript',
		'pl': 'text/perl',
		'css': 'text/css',
		'asp': 'text/asp',
		'php': 'application/x-httpd-php',
		'php3': 'application/x-httpd-php',
		'php4': 'application/x-httpd-php',
		'php5': 'application/x-httpd-php',
		'phtml': 'application/x-httpd-php',

		// images
		'png': 'image/png',
		'jpg': 'image/jpeg',
		'jpeg': 'image/jpeg',
		'jpe': 'image/jpeg',
		'jfif': 'image/jpeg',
		'gif': 'image/gif',
		'bmp': 'image/bmp',
		'cgm': 'image/cgm',
		'ief': 'image/ief',
		'ico': 'image/x-icon',
		'tif': 'image/tiff',
		'tiff': 'image/tiff',
		'svg': 'image/svg+xml',
		'svgz': 'image/svg+xml',
		'djv': 'image/vnd.djvu',
		'djvu': 'image/vnd.djvu',
		'webp': 'image/webp',

		// archives
		'zip': 'application/zip',
		'7z': 'application/x-7z-compressed',
		'rar': 'application/x-rar-compressed',
		'exe': 'application/x-msdownload',
		'dll': 'application/x-msdownload',
		'scr': 'application/x-msdownload',
		'com': 'application/x-msdownload',
		'bat': 'application/x-msdownload',
		'msi': 'application/x-msdownload',
		'cab': 'application/vnd.ms-cab-compressed',
		'gz': 'application/x-gzip',
		'tgz': 'application/x-gzip',
		'bz': 'application/x-bzip',
		'bz2': 'application/x-bzip2',
		'deb': 'application/x-debian-package',

		// fonts
		'psf': 'application/x-font-linux-psf',
		'otf': 'application/x-font-otf',
		'pcf': 'application/x-font-pcf',
		'snf': 'application/x-font-snf',
		'ttf': 'application/x-font-ttf',
		'ttc': 'application/x-font-ttf',

		// audio
		'mp3': 'audio/mpeg',
		'amr': 'audio/amr',
		'aac': 'audio/x-aac',
		'aif': 'audio/x-aiff',
		'aifc': 'audio/x-aiff',
		'aiff': 'audio/x-aiff',
		'wav': 'audio/x-wav',
		'wma': 'audio/x-ms-wma',
		'wax': 'audio/x-ms-wax',
		'midi': 'audio/midi',
		'mp4a': 'audio/mp4',
		'ogg': 'audio/ogg',
		'weba': 'audio/webm',
		'ra': 'audio/x-pn-realaudio',
		'ram': 'audio/x-pn-realaudio',
		'rmp': 'audio/x-pn-realaudio-plugin',
		'm3u': 'audio/x-mpegurl',

		// video
		'flv': 'video/x-flv',
		'qt': 'video/quicktime',
		'mov': 'video/quicktime',
		'wmv': 'video/windows-media',
		'avi': 'video/x-msvideo',
		'mpg': 'video/mpeg',
		'mpeg': 'video/mpeg',
		'mpe': 'video/mpeg',
		'm1v': 'video/mpeg',
		'm2v': 'video/mpeg',
		'3gp': 'video/3gpp',
		'3g2': 'video/3gpp2',
		'h261': 'video/h261',
		'h263': 'video/h263',
		'h264': 'video/h264',
		'jpgv': 'video/jpgv',
		'mp4': 'video/mp4',
		'mp4v': 'video/mp4',
		'mpg4': 'video/mp4',
		'ogv': 'video/ogg',
		'webm': 'video/webm',
		'm4v': 'video/x-m4v',
		'asf': 'video/x-ms-asf',
		'asx': 'video/x-ms-asf',
		'wm': 'video/x-ms-wm',
		'wmx': 'video/x-ms-wmx',
		'wvx': 'video/x-ms-wvx',
		'movie': 'video/x-sgi-movie',

		// adobe
		'pdf': 'application/pdf',
		'psd': 'image/vnd.adobe.photoshop',
		'ai': 'application/postscript',
		'eps': 'application/postscript',
		'ps': 'application/postscript',

		// ms office
		'doc': 'application/msword',
		'dot': 'application/msword',
		'rtf': 'application/rtf',
		'xls': 'application/vnd.ms-excel',
		'ppt': 'application/vnd.ms-powerpoint',
		'docx': 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'xlsx': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		'dotx': 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
		'pptx': 'application/vnd.openxmlformats-officedocument.presentationml.presentation',

		// open office
		'odt': 'application/vnd.oasis.opendocument.text',
		'ods': 'application/vnd.oasis.opendocument.spreadsheet'

	};

/***/ },
/* 69 */
/*!****************************************!*\
  !*** ./dev/Component/AbstracRadio.jsx ***!
  \****************************************/
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

	Object.defineProperty(exports, "__esModule", {
		value: true
	});
	exports.default = exports.AbstracRadio = undefined;

	var _common = __webpack_require__(/*! common */ 12);

	var _ko = __webpack_require__(/*! ko */ 2);

	var _ko2 = _interopRequireDefault(_ko);

	var _Utils = __webpack_require__(/*! Common/Utils */ 1);

	var _Utils2 = _interopRequireDefault(_Utils);

	var _Abstract = __webpack_require__(/*! Component/Abstract */ 16);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

	function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

	function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

	var AbstracRadio = (function (_AbstractComponent) {
		_inherits(AbstracRadio, _AbstractComponent);

		/**
	  * @param {Object} params
	  */

		function AbstracRadio(params) {
			_classCallCheck(this, AbstracRadio);

			var _this = _possibleConstructorReturn(this, Object.getPrototypeOf(AbstracRadio).call(this));

			_this.values = _ko2.default.observableArray([]);

			_this.value = params.value;
			if (_Utils2.default.isUnd(_this.value) || !_this.value.subscribe) {
				_this.value = _ko2.default.observable('');
			}

			_this.inline = _Utils2.default.isUnd(params.inline) ? false : params.inline;
			_this.readOnly = _Utils2.default.isUnd(params.readOnly) ? false : !!params.readOnly;

			if (params.values) {
				_this.values(_common._.map(params.values, function (label, value) {
					return { 'label': label, 'value': value };
				}));
			}

			_this.click = _common._.bind(_this.click, _this);
			return _this;
		}

		_createClass(AbstracRadio, [{
			key: 'click',
			value: function click(value) {
				if (!this.readOnly && value) {
					this.value(value.value);
				}
			}
		}]);

		return AbstracRadio;
	})(_Abstract.AbstractComponent);

	exports.AbstracRadio = AbstracRadio;
	exports.default = AbstracRadio;

/***/ },
/* 70 */
/*!*********************************!*\
  !*** ./dev/Component/Input.jsx ***!
  \*********************************/
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	var _Abstract = __webpack_require__(/*! Component/Abstract */ 16);

	var _AbstractInput2 = __webpack_require__(/*! Component/AbstractInput */ 36);

	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

	function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

	function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

	var InputComponent = (function (_AbstractInput) {
	  _inherits(InputComponent, _AbstractInput);

	  function InputComponent() {
	    _classCallCheck(this, InputComponent);

	    return _possibleConstructorReturn(this, Object.getPrototypeOf(InputComponent).apply(this, arguments));
	  }

	  return InputComponent;
	})(_AbstractInput2.AbstractInput);

	module.exports = (0, _Abstract.componentExportHelper)(InputComponent, 'InputComponent');

/***/ },
/* 71 */
/*!***************************************************!*\
  !*** ./dev/Component/MaterialDesign/Checkbox.jsx ***!
  \***************************************************/
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

	var _common = __webpack_require__(/*! common */ 12);

	var _ko = __webpack_require__(/*! ko */ 2);

	var _ko2 = _interopRequireDefault(_ko);

	var _Abstract = __webpack_require__(/*! Component/Abstract */ 16);

	var _AbstracCheckbox2 = __webpack_require__(/*! Component/AbstracCheckbox */ 46);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

	function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

	function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

	var CheckboxMaterialDesignComponent = (function (_AbstracCheckbox) {
		_inherits(CheckboxMaterialDesignComponent, _AbstracCheckbox);

		/**
	  * @param {Object} params
	  */

		function CheckboxMaterialDesignComponent(params) {
			_classCallCheck(this, CheckboxMaterialDesignComponent);

			var _this = _possibleConstructorReturn(this, Object.getPrototypeOf(CheckboxMaterialDesignComponent).call(this, params));

			_this.animationBox = _ko2.default.observable(false).extend({ 'falseTimeout': 200 });
			_this.animationCheckmark = _ko2.default.observable(false).extend({ 'falseTimeout': 200 });

			_this.animationBoxSetTrue = _common._.bind(_this.animationBoxSetTrue, _this);
			_this.animationCheckmarkSetTrue = _common._.bind(_this.animationCheckmarkSetTrue, _this);

			_this.disposable.push(_this.value.subscribe(function (value) {
				_this.triggerAnimation(value);
			}, _this));
			return _this;
		}

		_createClass(CheckboxMaterialDesignComponent, [{
			key: 'animationBoxSetTrue',
			value: function animationBoxSetTrue() {
				this.animationBox(true);
			}
		}, {
			key: 'animationCheckmarkSetTrue',
			value: function animationCheckmarkSetTrue() {
				this.animationCheckmark(true);
			}
		}, {
			key: 'triggerAnimation',
			value: function triggerAnimation(box) {
				if (box) {
					this.animationBoxSetTrue();
					_common._.delay(this.animationCheckmarkSetTrue, 200);
				} else {
					this.animationCheckmarkSetTrue();
					_common._.delay(this.animationBoxSetTrue, 200);
				}
			}
		}]);

		return CheckboxMaterialDesignComponent;
	})(_AbstracCheckbox2.AbstracCheckbox);

	module.exports = (0, _Abstract.componentExportHelper)(CheckboxMaterialDesignComponent, 'CheckboxMaterialDesignComponent');

/***/ },
/* 72 */
/*!*********************************!*\
  !*** ./dev/Component/Radio.jsx ***!
  \*********************************/
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	var _Abstract = __webpack_require__(/*! Component/Abstract */ 16);

	var _AbstracRadio2 = __webpack_require__(/*! Component/AbstracRadio */ 69);

	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

	function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

	function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

	var RadioComponent = (function (_AbstracRadio) {
	  _inherits(RadioComponent, _AbstracRadio);

	  function RadioComponent() {
	    _classCallCheck(this, RadioComponent);

	    return _possibleConstructorReturn(this, Object.getPrototypeOf(RadioComponent).apply(this, arguments));
	  }

	  return RadioComponent;
	})(_AbstracRadio2.AbstracRadio);

	module.exports = (0, _Abstract.componentExportHelper)(RadioComponent, 'RadioComponent');

/***/ },
/* 73 */
/*!***************************************!*\
  !*** ./dev/Component/SaveTrigger.jsx ***!
  \***************************************/
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

	var _Utils = __webpack_require__(/*! Common/Utils */ 1);

	var _Utils2 = _interopRequireDefault(_Utils);

	var _Enums = __webpack_require__(/*! Common/Enums */ 4);

	var _Abstract = __webpack_require__(/*! Component/Abstract */ 16);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

	function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

	function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

	var SaveTriggerComponent = (function (_AbstractComponent) {
		_inherits(SaveTriggerComponent, _AbstractComponent);

		/**
	  * @param {Object} params
	  */

		function SaveTriggerComponent(params) {
			_classCallCheck(this, SaveTriggerComponent);

			var _this = _possibleConstructorReturn(this, Object.getPrototypeOf(SaveTriggerComponent).call(this));

			_this.element = params.element || null;
			_this.value = params.value && params.value.subscribe ? params.value : null;

			if (_this.element) {
				if (_this.value) {
					_this.element.css('display', 'inline-block');

					if (params.verticalAlign) {
						_this.element.css('vertical-align', params.verticalAlign);
					}

					_this.setState(_this.value());

					_this.disposable.push(_this.value.subscribe(_this.setState, _this));
				} else {
					_this.element.hide();
				}
			}
			return _this;
		}

		_createClass(SaveTriggerComponent, [{
			key: 'setState',
			value: function setState(value) {

				switch (_Utils2.default.pInt(value)) {
					case _Enums.SaveSettingsStep.TrueResult:
						this.element.find('.animated,.error').hide().removeClass('visible').end().find('.success').show().addClass('visible');
						break;
					case _Enums.SaveSettingsStep.FalseResult:
						this.element.find('.animated,.success').hide().removeClass('visible').end().find('.error').show().addClass('visible');
						break;
					case _Enums.SaveSettingsStep.Animate:
						this.element.find('.error,.success').hide().removeClass('visible').end().find('.animated').show().addClass('visible');
						break;
					default:
					case _Enums.SaveSettingsStep.Idle:
						this.element.find('.animated').hide().end().find('.error,.success').removeClass('visible');
						break;
				}
			}
		}]);

		return SaveTriggerComponent;
	})(_Abstract.AbstractComponent);

	module.exports = (0, _Abstract.componentExportHelper)(SaveTriggerComponent, 'SaveTriggerComponent');

/***/ },
/* 74 */
/*!**********************************!*\
  !*** ./dev/Component/Script.jsx ***!
  \**********************************/
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	var _common = __webpack_require__(/*! common */ 12);

	var _Abstract = __webpack_require__(/*! Component/Abstract */ 16);

	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

	function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

	function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

	var ScriptComponent = (function (_AbstractComponent) {
		_inherits(ScriptComponent, _AbstractComponent);

		/**
	  * @param {Object} params
	  */

		function ScriptComponent(params) {
			_classCallCheck(this, ScriptComponent);

			var _this = _possibleConstructorReturn(this, Object.getPrototypeOf(ScriptComponent).call(this));

			if (params.component && params.component.templateNodes && params.element && params.element[0] && params.element[0].outerHTML) {
				var script = params.element[0].outerHTML;
				script = !script ? '' : script.replace(/<x-script/i, '<script').replace(/<b><\/b><\/x-script>/i, '</script>');

				if (script) {
					params.element.text('');
					params.element.replaceWith((0, _common.$)(script).text(params.component.templateNodes[0] && params.component.templateNodes[0].nodeValue ? params.component.templateNodes[0].nodeValue : ''));
				} else {
					params.element.remove();
				}
			}
			return _this;
		}

		return ScriptComponent;
	})(_Abstract.AbstractComponent);

	module.exports = (0, _Abstract.componentExportHelper)(ScriptComponent, 'ScriptComponent');

/***/ },
/* 75 */
/*!**********************************!*\
  !*** ./dev/Component/Select.jsx ***!
  \**********************************/
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	var _Utils = __webpack_require__(/*! Common/Utils */ 1);

	var _Utils2 = _interopRequireDefault(_Utils);

	var _Translator = __webpack_require__(/*! Common/Translator */ 6);

	var _Translator2 = _interopRequireDefault(_Translator);

	var _Abstract = __webpack_require__(/*! Component/Abstract */ 16);

	var _AbstractInput2 = __webpack_require__(/*! Component/AbstractInput */ 36);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

	function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

	function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

	var SelectComponent = (function (_AbstractInput) {
			_inherits(SelectComponent, _AbstractInput);

			/**
	   * @param {Object} params
	   */

			function SelectComponent(params) {
					_classCallCheck(this, SelectComponent);

					var _this = _possibleConstructorReturn(this, Object.getPrototypeOf(SelectComponent).call(this, params));

					_this.options = params.options || '';

					_this.optionsText = params.optionsText || null;
					_this.optionsValue = params.optionsValue || null;
					_this.optionsCaption = params.optionsCaption || null;

					if (_this.optionsCaption) {
							_this.optionsCaption = _Translator2.default.i18n(_this.optionsCaption);
					}

					_this.defautOptionsAfterRender = _Utils2.default.defautOptionsAfterRender;
					return _this;
			}

			return SelectComponent;
	})(_AbstractInput2.AbstractInput);

	module.exports = (0, _Abstract.componentExportHelper)(SelectComponent, 'SelectComponent');

/***/ },
/* 76 */
/*!************************************!*\
  !*** ./dev/Component/TextArea.jsx ***!
  \************************************/
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	var _Utils = __webpack_require__(/*! Common/Utils */ 1);

	var _Utils2 = _interopRequireDefault(_Utils);

	var _Abstract = __webpack_require__(/*! Component/Abstract */ 16);

	var _AbstractInput2 = __webpack_require__(/*! Component/AbstractInput */ 36);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

	function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

	function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

	var TextAreaComponent = (function (_AbstractInput) {
		_inherits(TextAreaComponent, _AbstractInput);

		/**
	  * @param {Object} params
	  */

		function TextAreaComponent(params) {
			_classCallCheck(this, TextAreaComponent);

			var _this = _possibleConstructorReturn(this, Object.getPrototypeOf(TextAreaComponent).call(this, params));

			_this.rows = params.rows || 5;
			_this.spellcheck = _Utils2.default.isUnd(params.spellcheck) ? false : !!params.spellcheck;
			return _this;
		}

		return TextAreaComponent;
	})(_AbstractInput2.AbstractInput);

	module.exports = (0, _Abstract.componentExportHelper)(TextAreaComponent, 'TextAreaComponent');

/***/ },
/* 77 */
/*!************************************!*\
  !*** ./dev/Knoin/AbstractBoot.jsx ***!
  \************************************/
/***/ function(module, exports, __webpack_require__) {

	"use strict";

	var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

	Object.defineProperty(exports, "__esModule", {
		value: true
	});

	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

	var AbstractBoot = (function () {
		function AbstractBoot() {
			_classCallCheck(this, AbstractBoot);
		}

		_createClass(AbstractBoot, [{
			key: "bootstart",
			value: function bootstart(Remote) {}
		}]);

		return AbstractBoot;
	})();

	exports.AbstractBoot = AbstractBoot;
	exports.default = AbstractBoot;

/***/ },
/* 78 */
/*!************************************!*\
  !*** ./dev/Stores/AbstractApp.jsx ***!
  \************************************/
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

	Object.defineProperty(exports, "__esModule", {
			value: true
	});
	exports.default = exports.AbstractAppStore = undefined;

	var _ko = __webpack_require__(/*! ko */ 2);

	var _ko2 = _interopRequireDefault(_ko);

	var _Globals = __webpack_require__(/*! Common/Globals */ 8);

	var _Globals2 = _interopRequireDefault(_Globals);

	var _Settings = __webpack_require__(/*! Storage/Settings */ 9);

	var _Settings2 = _interopRequireDefault(_Settings);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

	var AbstractAppStore = (function () {
			function AbstractAppStore() {
					_classCallCheck(this, AbstractAppStore);

					this.allowLanguagesOnSettings = _ko2.default.observable(true);
					this.allowLanguagesOnLogin = _ko2.default.observable(true);

					this.interfaceAnimation = _ko2.default.observable(true);

					this.interfaceAnimation.subscribe(function (bValue) {
							var bAnim = _Globals2.default.bMobileDevice || !bValue;
							_Globals2.default.$html.toggleClass('rl-anim', !bAnim).toggleClass('no-rl-anim', bAnim);
					});

					this.interfaceAnimation.valueHasMutated();

					this.prem = _ko2.default.observable(false);
					this.community = _ko2.default.observable(true);
			}

			_createClass(AbstractAppStore, [{
					key: 'populate',
					value: function populate() {
							this.allowLanguagesOnLogin(!!_Settings2.default.settingsGet('AllowLanguagesOnLogin'));
							this.allowLanguagesOnSettings(!!_Settings2.default.settingsGet('AllowLanguagesOnSettings'));

							this.interfaceAnimation(!!_Settings2.default.settingsGet('InterfaceAnimation'));

							this.prem(!!_Settings2.default.settingsGet('PremType'));
							this.community(!!_Settings2.default.settingsGet('Community'));
					}
			}]);

			return AbstractAppStore;
	})();

	exports.AbstractAppStore = AbstractAppStore;
	exports.default = AbstractAppStore;

/***/ },
/* 79 */
/*!***************************!*\
  !*** ./dev/bootstrap.jsx ***!
  \***************************/
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	Object.defineProperty(exports, "__esModule", {
		value: true
	});

	exports.default = function (App) {

		var window = __webpack_require__(/*! window */ 13),
		    _ = __webpack_require__(/*! _ */ 3),
		    $ = __webpack_require__(/*! $ */ 14),
		    Globals = __webpack_require__(/*! Common/Globals */ 8),
		    Plugins = __webpack_require__(/*! Common/Plugins */ 23),
		    Utils = __webpack_require__(/*! Common/Utils */ 1),
		    Enums = __webpack_require__(/*! Common/Enums */ 4),
		    Translator = __webpack_require__(/*! Common/Translator */ 6),
		    EmailModel = __webpack_require__(/*! Model/Email */ 29);

		Globals.__APP__ = App;

		Globals.$win.keydown(Utils.kill_CtrlA_CtrlS).unload(function () {
			Globals.bUnload = true;
		});

		Globals.$html.addClass(Globals.bMobileDevice ? 'mobile' : 'no-mobile').on('click.dropdown.data-api', function () {
			Utils.detectDropdownVisibility();
		});

		// export
		window['rl'] = window['rl'] || {};
		window['rl']['i18n'] = _.bind(Translator.i18n, Translator);

		window['rl']['addHook'] = _.bind(Plugins.addHook, Plugins);
		window['rl']['settingsGet'] = _.bind(Plugins.mainSettingsGet, Plugins);
		window['rl']['createCommand'] = Utils.createCommand;

		window['rl']['addSettingsViewModel'] = _.bind(Plugins.addSettingsViewModel, Plugins);

		window['rl']['pluginRemoteRequest'] = _.bind(Plugins.remoteRequest, Plugins);
		window['rl']['pluginSettingsGet'] = _.bind(Plugins.settingsGet, Plugins);

		window['rl']['EmailModel'] = EmailModel;
		window['rl']['Enums'] = Enums;

		window['__APP_BOOT'] = function (fCall) {

			$(_.delay(function () {

				if (!$('#rl-check').is(':visible')) {
					Globals.$html.addClass('no-css');
				}

				$('#rl-check').remove();

				if (window['rainloopTEMPLATES'] && window['rainloopTEMPLATES'][0]) {
					$('#rl-templates').html(window['rainloopTEMPLATES'][0]);

					_.delay(function () {

						App.bootstart();

						Globals.$html.removeClass('no-js rl-booted-trigger').addClass('rl-booted');
					}, 10);
				} else {
					fCall(false);
				}

				window['__APP_BOOT'] = null;
			}, 10));
		};
	};

/***/ },
/* 80 */
/*!************************************!*\
  !*** external "window.Autolinker" ***!
  \************************************/
/***/ function(module, exports, __webpack_require__) {

	module.exports = window.Autolinker;

/***/ },
/* 81 */
/*!***********************************!*\
  !*** external "window.JSEncrypt" ***!
  \***********************************/
/***/ function(module, exports, __webpack_require__) {

	module.exports = window.JSEncrypt;

/***/ },
/* 82 */,
/* 83 */
/*!********************************!*\
  !*** external "window.hasher" ***!
  \********************************/
/***/ function(module, exports, __webpack_require__) {

	module.exports = window.hasher;

/***/ },
/* 84 */
/*!********************************************!*\
  !*** external "window.rainloopProgressJs" ***!
  \********************************************/
/***/ function(module, exports, __webpack_require__) {

	module.exports = window.rainloopProgressJs;

/***/ },
/* 85 */
/*!*****************************!*\
  !*** external "window.ssm" ***!
  \*****************************/
/***/ function(module, exports, __webpack_require__) {

	module.exports = window.ssm;

/***/ },
/* 86 */,
/* 87 */,
/* 88 */,
/* 89 */,
/* 90 */
/*!**********************************!*\
  !*** ./dev/Stores/Admin/Core.js ***!
  \**********************************/
/***/ function(module, exports, __webpack_require__) {

	
	(function () {

		'use strict';

		var
			ko = __webpack_require__(/*! ko */ 2)
		;

		/**
		 * @constructor
		 */
		function CoreAdminStore()
		{
			this.coreReal = ko.observable(true);
			this.coreChannel = ko.observable('stable');
			this.coreType = ko.observable('stable');
			this.coreUpdatable = ko.observable(true);
			this.coreAccess = ko.observable(true);
			this.coreWarning = ko.observable(false);
			this.coreChecking = ko.observable(false).extend({'throttle': 100});
			this.coreUpdating = ko.observable(false).extend({'throttle': 100});
			this.coreVersion = ko.observable('');
			this.coreRemoteVersion = ko.observable('');
			this.coreRemoteRelease = ko.observable('');
			this.coreVersionCompare = ko.observable(-2);
		}

		module.exports = new CoreAdminStore();

	}());


/***/ },
/* 91 */,
/* 92 */,
/* 93 */,
/* 94 */
/*!************************************!*\
  !*** ./dev/View/Popup/Activate.js ***!
  \************************************/
/***/ function(module, exports, __webpack_require__) {

	
	(function () {

		'use strict';

		var
			_ = __webpack_require__(/*! _ */ 3),
			ko = __webpack_require__(/*! ko */ 2),

			Enums = __webpack_require__(/*! Common/Enums */ 4),
			Utils = __webpack_require__(/*! Common/Utils */ 1),
			Consts = __webpack_require__(/*! Common/Consts */ 17),
			Translator = __webpack_require__(/*! Common/Translator */ 6),

			Settings = __webpack_require__(/*! Storage/Settings */ 9),
			Remote = __webpack_require__(/*! Remote/Admin/Ajax */ 19),

			LicenseStore = __webpack_require__(/*! Stores/Admin/License */ 59),

			kn = __webpack_require__(/*! Knoin/Knoin */ 5),
			AbstractView = __webpack_require__(/*! Knoin/AbstractView */ 10)
		;

		/**
		 * @constructor
		 * @extends AbstractView
		 */
		function ActivatePopupView()
		{
			AbstractView.call(this, 'Popups', 'PopupsActivate');

			var self = this;

			this.domain = ko.observable('');
			this.key = ko.observable('');
			this.key.focus = ko.observable(false);
			this.activationSuccessed = ko.observable(false);

			this.licenseTrigger = LicenseStore.licenseTrigger;

			this.activateProcess = ko.observable(false);
			this.activateText = ko.observable('');
			this.activateText.isError = ko.observable(false);

			this.htmlDescription = ko.computed(function () {
				return Translator.i18n('POPUPS_ACTIVATE/HTML_DESC', {'DOMAIN': this.domain()});
			}, this);

			this.key.subscribe(function () {
				this.activateText('');
				this.activateText.isError(false);
			}, this);

			this.activationSuccessed.subscribe(function (bValue) {
				if (bValue)
				{
					this.licenseTrigger(!this.licenseTrigger());
				}
			}, this);

			this.activateCommand = Utils.createCommand(this, function () {

				this.activateProcess(true);
				if (this.validateSubscriptionKey())
				{
					Remote.licensingActivate(function (sResult, oData) {

						self.activateProcess(false);
						if (Enums.StorageResultType.Success === sResult && oData.Result)
						{
							if (true === oData.Result)
							{
								self.activationSuccessed(true);
								self.activateText(Translator.i18n('POPUPS_ACTIVATE/SUBS_KEY_ACTIVATED'));
								self.activateText.isError(false);
							}
							else
							{
								self.activateText(oData.Result);
								self.activateText.isError(true);
								self.key.focus(true);
							}
						}
						else if (oData.ErrorCode)
						{
							self.activateText(Translator.getNotification(oData.ErrorCode));
							self.activateText.isError(true);
							self.key.focus(true);
						}
						else
						{
							self.activateText(Translator.getNotification(Enums.Notification.UnknownError));
							self.activateText.isError(true);
							self.key.focus(true);
						}

					}, this.domain(), this.key());
				}
				else
				{
					this.activateProcess(false);
					this.activateText(Translator.i18n('POPUPS_ACTIVATE/ERROR_INVALID_SUBS_KEY'));
					this.activateText.isError(true);
					this.key.focus(true);
				}

			}, function () {
				return !this.activateProcess() && '' !== this.domain() && '' !== this.key() && !this.activationSuccessed();
			});

			kn.constructorEnd(this);
		}

		kn.extendAsViewModel(['View/Popup/Activate', 'PopupsActivateViewModel'], ActivatePopupView);
		_.extend(ActivatePopupView.prototype, AbstractView.prototype);

		ActivatePopupView.prototype.onShow = function (bTrial)
		{
			this.domain(Settings.settingsGet('AdminDomain'));
			if (!this.activateProcess())
			{
				bTrial = Utils.isUnd(bTrial) ? false : !!bTrial;

				this.key(bTrial ? Consts.RAINLOOP_TRIAL_KEY : '');
				this.activateText('');
				this.activateText.isError(false);
				this.activationSuccessed(false);
			}
		};

		ActivatePopupView.prototype.onShowWithDelay = function ()
		{
			if (!this.activateProcess())
			{
				this.key.focus(true);
			}
		};

		/**
		 * @return {boolean}
		 */
		ActivatePopupView.prototype.validateSubscriptionKey = function ()
		{
			var sValue = this.key();
			return '' === sValue || Consts.RAINLOOP_TRIAL_KEY === sValue ||
				!!/^RL[\d]+-[A-Z0-9\-]+Z$/.test(Utils.trim(sValue));
		};

		module.exports = ActivatePopupView;

	}());

/***/ },
/* 95 */
/*!**********************************!*\
  !*** ./dev/View/Popup/Domain.js ***!
  \**********************************/
/***/ function(module, exports, __webpack_require__) {

	
	(function () {

		'use strict';

		var
			_ = __webpack_require__(/*! _ */ 3),
			ko = __webpack_require__(/*! ko */ 2),

			Enums = __webpack_require__(/*! Common/Enums */ 4),
			Consts = __webpack_require__(/*! Common/Consts */ 17),
			Globals = __webpack_require__(/*! Common/Globals */ 8),
			Utils = __webpack_require__(/*! Common/Utils */ 1),

			Translator = __webpack_require__(/*! Common/Translator */ 6),

			CapaAdminStore = __webpack_require__(/*! Stores/Admin/Capa */ 50),

			Remote = __webpack_require__(/*! Remote/Admin/Ajax */ 19),

			kn = __webpack_require__(/*! Knoin/Knoin */ 5),
			AbstractView = __webpack_require__(/*! Knoin/AbstractView */ 10)
		;

		/**
		 * @constructor
		 * @extends AbstractView
		 */
		function DomainPopupView()
		{
			AbstractView.call(this, 'Popups', 'PopupsDomain');

			this.edit = ko.observable(false);
			this.saving = ko.observable(false);
			this.savingError = ko.observable('');
			this.page = ko.observable('main');
			this.sieveSettings = ko.observable(false);

			this.testing = ko.observable(false);
			this.testingDone = ko.observable(false);
			this.testingImapError = ko.observable(false);
			this.testingSieveError = ko.observable(false);
			this.testingSmtpError = ko.observable(false);
			this.testingImapErrorDesc = ko.observable('');
			this.testingSieveErrorDesc = ko.observable('');
			this.testingSmtpErrorDesc = ko.observable('');

			this.testingImapError.subscribe(function (bValue) {
				if (!bValue)
				{
					this.testingImapErrorDesc('');
				}
			}, this);

			this.testingSieveError.subscribe(function (bValue) {
				if (!bValue)
				{
					this.testingSieveErrorDesc('');
				}
			}, this);

			this.testingSmtpError.subscribe(function (bValue) {
				if (!bValue)
				{
					this.testingSmtpErrorDesc('');
				}
			}, this);

			this.imapServerFocus = ko.observable(false);
			this.sieveServerFocus = ko.observable(false);
			this.smtpServerFocus = ko.observable(false);

			this.name = ko.observable('');
			this.name.focused = ko.observable(false);

			this.imapServer = ko.observable('');
			this.imapPort = ko.observable('' + Consts.IMAP_DEFAULT_PORT);
			this.imapSecure = ko.observable(Enums.ServerSecure.None);
			this.imapShortLogin = ko.observable(false);
			this.useSieve = ko.observable(false);
			this.sieveAllowRaw = ko.observable(false);
			this.sieveServer = ko.observable('');
			this.sievePort = ko.observable('' + Consts.SIEVE_DEFAULT_PORT);
			this.sieveSecure = ko.observable(Enums.ServerSecure.None);
			this.smtpServer = ko.observable('');
			this.smtpPort = ko.observable('' + Consts.SMTP_DEFAULT_PORT);
			this.smtpSecure = ko.observable(Enums.ServerSecure.None);
			this.smtpShortLogin = ko.observable(false);
			this.smtpAuth = ko.observable(true);
			this.smtpPhpMail = ko.observable(false);
			this.whiteList = ko.observable('');

			this.enableSmartPorts = ko.observable(false);

			this.allowSieve = ko.computed(function () {
				return CapaAdminStore.filters() && CapaAdminStore.sieve();
			}, this);

			this.headerText = ko.computed(function () {
				var sName = this.name();
				return this.edit() ? Translator.i18n('POPUPS_DOMAIN/TITLE_EDIT_DOMAIN', {'NAME': sName}) :
					('' === sName ? Translator.i18n('POPUPS_DOMAIN/TITLE_ADD_DOMAIN') :
						Translator.i18n('POPUPS_DOMAIN/TITLE_ADD_DOMAIN_WITH_NAME', {'NAME': sName}));
			}, this);

			this.domainDesc = ko.computed(function () {
				var sName = this.name();
				return !this.edit() && sName ? Translator.i18n('POPUPS_DOMAIN/NEW_DOMAIN_DESC', {'NAME': '*@' + sName}) : '';
			}, this);

			this.domainIsComputed = ko.computed(function () {

				var
					bPhpMail = this.smtpPhpMail(),
					bAllowSieve = this.allowSieve(),
					bUseSieve = this.useSieve()
				;

				return '' !== this.name() &&
					'' !== this.imapServer() &&
					'' !== this.imapPort() &&
					(bAllowSieve && bUseSieve ? ('' !== this.sieveServer() && '' !== this.sievePort()) : true) &&
					(('' !== this.smtpServer() && '' !== this.smtpPort()) || bPhpMail);

			}, this);

			this.canBeTested = ko.computed(function () {
				return !this.testing() && this.domainIsComputed();
			}, this);

			this.canBeSaved = ko.computed(function () {
				return !this.saving() && this.domainIsComputed();
			}, this);

			this.createOrAddCommand = Utils.createCommand(this, function () {
				this.saving(true);
				Remote.createOrUpdateDomain(
					_.bind(this.onDomainCreateOrSaveResponse, this),
					!this.edit(),
					this.name(),

					this.imapServer(),
					Utils.pInt(this.imapPort()),
					this.imapSecure(),
					this.imapShortLogin(),

					this.useSieve(),
					this.sieveAllowRaw(),
					this.sieveServer(),
					Utils.pInt(this.sievePort()),
					this.sieveSecure(),

					this.smtpServer(),
					Utils.pInt(this.smtpPort()),
					this.smtpSecure(),
					this.smtpShortLogin(),
					this.smtpAuth(),
					this.smtpPhpMail(),

					this.whiteList()
				);
			}, this.canBeSaved);

			this.testConnectionCommand = Utils.createCommand(this, function () {

				this.page('main');

				this.testingDone(false);
				this.testingImapError(false);
				this.testingSieveError(false);
				this.testingSmtpError(false);
				this.testing(true);

				Remote.testConnectionForDomain(
					_.bind(this.onTestConnectionResponse, this),
					this.name(),

					this.imapServer(),
					Utils.pInt(this.imapPort()),
					this.imapSecure(),

					this.useSieve(),
					this.sieveServer(),
					Utils.pInt(this.sievePort()),
					this.sieveSecure(),

					this.smtpServer(),
					Utils.pInt(this.smtpPort()),
					this.smtpSecure(),
					this.smtpAuth(),
					this.smtpPhpMail()
				);
			}, this.canBeTested);

			this.whiteListCommand = Utils.createCommand(this, function () {
				this.page('white-list');
			});

			this.backCommand = Utils.createCommand(this, function () {
				this.page('main');
			});

			this.sieveCommand = Utils.createCommand(this, function () {
				this.sieveSettings(!this.sieveSettings());
				this.clearTesting();
			});

			this.page.subscribe(function () {
				this.sieveSettings(false);
			}, this);

			// smart form improvements
			this.imapServerFocus.subscribe(function (bValue) {
				if (bValue && '' !== this.name() && '' === this.imapServer())
				{
					this.imapServer(this.name().replace(/[.]?[*][.]?/g, ''));
				}
			}, this);

			this.sieveServerFocus.subscribe(function (bValue) {
				if (bValue && '' !== this.imapServer() && '' === this.sieveServer())
				{
					this.sieveServer(this.imapServer());
				}
			}, this);

			this.smtpServerFocus.subscribe(function (bValue) {
				if (bValue && '' !== this.imapServer() && '' === this.smtpServer())
				{
					this.smtpServer(this.imapServer().replace(/imap/ig, 'smtp'));
				}
			}, this);

			this.imapSecure.subscribe(function (sValue) {
				if (this.enableSmartPorts())
				{
					var iPort = Utils.pInt(this.imapPort());
					sValue = Utils.pString(sValue);
					switch (sValue)
					{
						case '0':
							if (993 === iPort)
							{
								this.imapPort('143');
							}
							break;
						case '1':
							if (143 === iPort)
							{
								this.imapPort('993');
							}
							break;
					}
				}
			}, this);

			this.smtpSecure.subscribe(function (sValue) {
				if (this.enableSmartPorts())
				{
					var iPort = Utils.pInt(this.smtpPort());
					sValue = Utils.pString(sValue);
					switch (sValue)
					{
						case '0':
							if (465 === iPort || 587 === iPort)
							{
								this.smtpPort('25');
							}
							break;
						case '1':
							if (25 === iPort || 587 === iPort)
							{
								this.smtpPort('465');
							}
							break;
						case '2':
							if (25 === iPort || 465 === iPort)
							{
								this.smtpPort('587');
							}
							break;
					}
				}
			}, this);

			kn.constructorEnd(this);
		}

		kn.extendAsViewModel(['View/Popup/Domain', 'PopupsDomainViewModel'], DomainPopupView);
		_.extend(DomainPopupView.prototype, AbstractView.prototype);

		DomainPopupView.prototype.onTestConnectionResponse = function (sResult, oData)
		{
			this.testing(false);
			if (Enums.StorageResultType.Success === sResult && oData.Result)
			{
				var
					bImap = false,
					bSieve = false
				;

				this.testingDone(true);
				this.testingImapError(true !== oData.Result.Imap);
				this.testingSieveError(true !== oData.Result.Sieve);
				this.testingSmtpError(true !== oData.Result.Smtp);

				if (this.testingImapError() && oData.Result.Imap)
				{
					bImap = true;
					this.testingImapErrorDesc('');
					this.testingImapErrorDesc(oData.Result.Imap);
				}

				if (this.testingSieveError() && oData.Result.Sieve)
				{
					bSieve = true;
					this.testingSieveErrorDesc('');
					this.testingSieveErrorDesc(oData.Result.Sieve);
				}

				if (this.testingSmtpError() && oData.Result.Smtp)
				{
					this.testingSmtpErrorDesc('');
					this.testingSmtpErrorDesc(oData.Result.Smtp);
				}

				if (this.sieveSettings())
				{
					if (!bSieve && bImap)
					{
						this.sieveSettings(false);
					}
				}
				else
				{
					if (bSieve && !bImap)
					{
						this.sieveSettings(true);
					}
				}
			}
			else
			{
				this.testingImapError(true);
				this.testingSieveError(true);
				this.testingSmtpError(true);
				this.sieveSettings(false);
			}
		};

		DomainPopupView.prototype.onDomainCreateOrSaveResponse = function (sResult, oData)
		{
			this.saving(false);
			if (Enums.StorageResultType.Success === sResult && oData)
			{
				if (oData.Result)
				{
					__webpack_require__(/*! App/Admin */ 20).default.reloadDomainList();
					this.closeCommand();
				}
				else if (Enums.Notification.DomainAlreadyExists === oData.ErrorCode)
				{
					this.savingError(Translator.i18n('ERRORS/DOMAIN_ALREADY_EXISTS'));
				}
			}
			else
			{
				this.savingError(Translator.i18n('ERRORS/UNKNOWN_ERROR'));
			}
		};

		DomainPopupView.prototype.clearTesting = function ()
		{
			this.testing(false);
			this.testingDone(false);
			this.testingImapError(false);
			this.testingSieveError(false);
			this.testingSmtpError(false);
		};

		DomainPopupView.prototype.onHide = function ()
		{
			this.page('main');
			this.sieveSettings(false);
		};


		DomainPopupView.prototype.onShow = function (oDomain)
		{
			this.saving(false);

			this.page('main');
			this.sieveSettings(false);

			this.clearTesting();

			this.clearForm();
			if (oDomain)
			{
				this.enableSmartPorts(false);

				this.edit(true);

				this.name(Utils.trim(oDomain.Name));
				this.imapServer(Utils.trim(oDomain.IncHost));
				this.imapPort('' + Utils.pInt(oDomain.IncPort));
				this.imapSecure(Utils.trim(oDomain.IncSecure));
				this.imapShortLogin(!!oDomain.IncShortLogin);
				this.useSieve(!!oDomain.UseSieve);
				this.sieveAllowRaw(!!oDomain.SieveAllowRaw);
				this.sieveServer(Utils.trim(oDomain.SieveHost));
				this.sievePort('' + Utils.pInt(oDomain.SievePort));
				this.sieveSecure(Utils.trim(oDomain.SieveSecure));
				this.smtpServer(Utils.trim(oDomain.OutHost));
				this.smtpPort('' + Utils.pInt(oDomain.OutPort));
				this.smtpSecure(Utils.trim(oDomain.OutSecure));
				this.smtpShortLogin(!!oDomain.OutShortLogin);
				this.smtpAuth(!!oDomain.OutAuth);
				this.smtpPhpMail(!!oDomain.OutUsePhpMail);
				this.whiteList(Utils.trim(oDomain.WhiteList));

				this.enableSmartPorts(true);
			}
		};

		DomainPopupView.prototype.onShowWithDelay = function ()
		{
			if ('' === this.name() && !Globals.bMobile)
			{
				this.name.focused(true);
			}
		};

		DomainPopupView.prototype.clearForm = function ()
		{
			this.edit(false);

			this.page('main');
			this.sieveSettings(false);

			this.enableSmartPorts(false);

			this.savingError('');

			this.name('');
			this.name.focused(false);

			this.imapServer('');
			this.imapPort('' + Consts.IMAP_DEFAULT_PORT);
			this.imapSecure(Enums.ServerSecure.None);
			this.imapShortLogin(false);

			this.useSieve(false);
			this.sieveAllowRaw(false);
			this.sieveServer('');
			this.sievePort('' + Consts.SIEVE_DEFAULT_PORT);
			this.sieveSecure(Enums.ServerSecure.None);

			this.smtpServer('');
			this.smtpPort('' + Consts.SMTP_DEFAULT_PORT);
			this.smtpSecure(Enums.ServerSecure.None);
			this.smtpShortLogin(false);
			this.smtpAuth(true);
			this.smtpPhpMail(false);

			this.whiteList('');
			this.enableSmartPorts(true);
		};

		module.exports = DomainPopupView;

	}());

/***/ },
/* 96 */,
/* 97 */,
/* 98 */,
/* 99 */,
/* 100 */,
/* 101 */,
/* 102 */,
/* 103 */,
/* 104 */,
/* 105 */,
/* 106 */,
/* 107 */,
/* 108 */,
/* 109 */,
/* 110 */,
/* 111 */,
/* 112 */,
/* 113 */,
/* 114 */,
/* 115 */
/*!***********************************!*\
  !*** ./dev/Screen/Admin/Login.js ***!
  \***********************************/
/***/ function(module, exports, __webpack_require__) {

	
	(function () {

		'use strict';

		var
			_ = __webpack_require__(/*! _ */ 3),

			AbstractScreen = __webpack_require__(/*! Knoin/AbstractScreen */ 38)
		;

		/**
		 * @constructor
		 * @extends AbstractScreen
		 */
		function LoginAdminScreen()
		{
			AbstractScreen.call(this, 'login', [
				__webpack_require__(/*! View/Admin/Login */ 143)
			]);
		}

		_.extend(LoginAdminScreen.prototype, AbstractScreen.prototype);

		LoginAdminScreen.prototype.onShow = function ()
		{
			__webpack_require__(/*! App/Admin */ 20).default.setWindowTitle('');
		};

		module.exports = LoginAdminScreen;

	}());

/***/ },
/* 116 */
/*!**************************************!*\
  !*** ./dev/Screen/Admin/Settings.js ***!
  \**************************************/
/***/ function(module, exports, __webpack_require__) {

	
	(function () {

		'use strict';

		var
			_ = __webpack_require__(/*! _ */ 3),

			kn = __webpack_require__(/*! Knoin/Knoin */ 5),

			Plugins = __webpack_require__(/*! Common/Plugins */ 23),

			AbstractSettings = __webpack_require__(/*! Screen/AbstractSettings */ 57)
		;

		/**
		 * @constructor
		 * @extends AbstractSettings
		 */
		function SettingsAdminScreen()
		{
			AbstractSettings.call(this, [
				__webpack_require__(/*! View/Admin/Settings/Menu */ 144),
				__webpack_require__(/*! View/Admin/Settings/Pane */ 145)
			]);
		}

		_.extend(SettingsAdminScreen.prototype, AbstractSettings.prototype);

		/**
		 * @param {Function=} fCallback
		 */
		SettingsAdminScreen.prototype.setupSettings = function (fCallback)
		{
			kn.addSettingsViewModel(__webpack_require__(/*! Settings/Admin/General */ 124),
				'AdminSettingsGeneral', 'TABS_LABELS/LABEL_GENERAL_NAME', 'general', true);

			kn.addSettingsViewModel(__webpack_require__(/*! Settings/Admin/Login */ 125),
				'AdminSettingsLogin', 'TABS_LABELS/LABEL_LOGIN_NAME', 'login');

			if (false)
			{
				kn.addSettingsViewModel(require('Settings/Admin/Branding'),
					'AdminSettingsBranding', 'TABS_LABELS/LABEL_BRANDING_NAME', 'branding');
			}
			else
			{
				kn.addSettingsViewModel(__webpack_require__(/*! Settings/Admin/Prem/Branding */ 128),
					'AdminSettingsBranding', 'TABS_LABELS/LABEL_BRANDING_NAME', 'branding');
			}

			kn.addSettingsViewModel(__webpack_require__(/*! Settings/Admin/Contacts */ 122),
				'AdminSettingsContacts', 'TABS_LABELS/LABEL_CONTACTS_NAME', 'contacts');

			kn.addSettingsViewModel(__webpack_require__(/*! Settings/Admin/Domains */ 123),
				'AdminSettingsDomains', 'TABS_LABELS/LABEL_DOMAINS_NAME', 'domains');

			kn.addSettingsViewModel(__webpack_require__(/*! Settings/Admin/Security */ 130),
				'AdminSettingsSecurity', 'TABS_LABELS/LABEL_SECURITY_NAME', 'security');

			kn.addSettingsViewModel(__webpack_require__(/*! Settings/Admin/Social */ 131),
				'AdminSettingsSocial', 'TABS_LABELS/LABEL_INTEGRATION_NAME', 'integrations');

			kn.addSettingsViewModel(__webpack_require__(/*! Settings/Admin/Plugins */ 127),
				'AdminSettingsPlugins', 'TABS_LABELS/LABEL_PLUGINS_NAME', 'plugins');

			kn.addSettingsViewModel(__webpack_require__(/*! Settings/Admin/Packages */ 126),
				'AdminSettingsPackages', 'TABS_LABELS/LABEL_PACKAGES_NAME', 'packages');

			if (true)
			{
				kn.addSettingsViewModel(__webpack_require__(/*! Settings/Admin/Prem/Licensing */ 129),
					'AdminSettingsLicensing', 'TABS_LABELS/LABEL_LICENSING_NAME', 'licensing');
			}

			kn.addSettingsViewModel(__webpack_require__(/*! Settings/Admin/About */ 120),
				'AdminSettingsAbout', 'TABS_LABELS/LABEL_ABOUT_NAME', 'about');

			Plugins.runSettingsViewModelHooks(true);

			if (fCallback)
			{
				fCallback();
			}
		};

		SettingsAdminScreen.prototype.onShow = function ()
		{
			__webpack_require__(/*! App/Admin */ 20).default.setWindowTitle('');
		};

		module.exports = SettingsAdminScreen;

	}());

/***/ },
/* 117 */,
/* 118 */,
/* 119 */,
/* 120 */
/*!*************************************!*\
  !*** ./dev/Settings/Admin/About.js ***!
  \*************************************/
/***/ function(module, exports, __webpack_require__) {

	
	(function () {

		'use strict';

		var
			ko = __webpack_require__(/*! ko */ 2),

			Translator = __webpack_require__(/*! Common/Translator */ 6),

			Settings = __webpack_require__(/*! Storage/Settings */ 9),
			CoreStore = __webpack_require__(/*! Stores/Admin/Core */ 90),
			AppStore = __webpack_require__(/*! Stores/Admin/App */ 35)
		;

		/**
		 * @constructor
		 */
		function AboutAdminSettings()
		{
			this.version = ko.observable(Settings.settingsGet('Version'));
			this.access = ko.observable(!!Settings.settingsGet('CoreAccess'));
			this.errorDesc = ko.observable('');

			this.coreReal = CoreStore.coreReal;
			this.coreChannel = CoreStore.coreChannel;
			this.coreType = CoreStore.coreType;
			this.coreUpdatable = CoreStore.coreUpdatable;
			this.coreAccess = CoreStore.coreAccess;
			this.coreChecking = CoreStore.coreChecking;
			this.coreUpdating = CoreStore.coreUpdating;
			this.coreWarning = CoreStore.coreWarning;
			this.coreVersion = CoreStore.coreVersion;
			this.coreRemoteVersion = CoreStore.coreRemoteVersion;
			this.coreRemoteRelease = CoreStore.coreRemoteRelease;
			this.coreVersionCompare = CoreStore.coreVersionCompare;

			this.community = (false) || AppStore.community();

			this.coreRemoteVersionHtmlDesc = ko.computed(function () {
				Translator.trigger();
				return Translator.i18n('TAB_ABOUT/HTML_NEW_VERSION', {'VERSION': this.coreRemoteVersion()});
			}, this);

			this.statusType = ko.computed(function () {

				var
					sType = '',
					iVersionCompare = this.coreVersionCompare(),
					bChecking = this.coreChecking(),
					bUpdating = this.coreUpdating(),
					bReal = this.coreReal()
				;

				if (bChecking)
				{
					sType = 'checking';
				}
				else if (bUpdating)
				{
					sType = 'updating';
				}
				else if (bReal && 0 === iVersionCompare)
				{
					sType = 'up-to-date';
				}
				else if (bReal && -1 === iVersionCompare)
				{
					sType = 'available';
				}
				else if (!bReal)
				{
					sType = 'error';
					this.errorDesc('Cannot access the repository at the moment.');
				}

				return sType;

			}, this);
		}

		AboutAdminSettings.prototype.onBuild = function ()
		{
			if (this.access() && !this.community)
			{
				__webpack_require__(/*! App/Admin */ 20).default.reloadCoreData();
			}
		};

		AboutAdminSettings.prototype.updateCoreData = function ()
		{
			if (!this.coreUpdating() && !this.community)
			{
				__webpack_require__(/*! App/Admin */ 20).default.updateCoreData();
			}
		};

		module.exports = AboutAdminSettings;

	}());

/***/ },
/* 121 */
/*!****************************************!*\
  !*** ./dev/Settings/Admin/Branding.js ***!
  \****************************************/
/***/ function(module, exports, __webpack_require__) {

	
	(function () {

		'use strict';

		var
			_ = __webpack_require__(/*! _ */ 3),
			ko = __webpack_require__(/*! ko */ 2),

			Utils = __webpack_require__(/*! Common/Utils */ 1),
			Translator = __webpack_require__(/*! Common/Translator */ 6)
		;

		/**
		 * @constructor
		 */
		function BrandingAdminSettings()
		{
			var
				Enums = __webpack_require__(/*! Common/Enums */ 4),
				Settings = __webpack_require__(/*! Storage/Settings */ 9),
				AppStore = __webpack_require__(/*! Stores/Admin/App */ 35)
			;

			this.capa = AppStore.prem;

			this.title = ko.observable(Settings.settingsGet('Title'));
			this.title.trigger = ko.observable(Enums.SaveSettingsStep.Idle);

			this.loadingDesc = ko.observable(Settings.settingsGet('LoadingDescription'));
			this.loadingDesc.trigger = ko.observable(Enums.SaveSettingsStep.Idle);

			this.faviconUrl = ko.observable(Settings.settingsGet('FaviconUrl'));
			this.faviconUrl.trigger = ko.observable(Enums.SaveSettingsStep.Idle);

			this.loginLogo = ko.observable(Settings.settingsGet('LoginLogo') || '');
			this.loginLogo.trigger = ko.observable(Enums.SaveSettingsStep.Idle);

			this.loginBackground = ko.observable(Settings.settingsGet('LoginBackground') || '');
			this.loginBackground.trigger = ko.observable(Enums.SaveSettingsStep.Idle);

			this.userLogo = ko.observable(Settings.settingsGet('UserLogo') || '');
			this.userLogo.trigger = ko.observable(Enums.SaveSettingsStep.Idle);

			this.userLogoMessage = ko.observable(Settings.settingsGet('UserLogoMessage') || '');
			this.userLogoMessage.trigger = ko.observable(Enums.SaveSettingsStep.Idle);

			this.userIframeMessage = ko.observable(Settings.settingsGet('UserIframeMessage') || '');
			this.userIframeMessage.trigger = ko.observable(Enums.SaveSettingsStep.Idle);

			this.userLogoTitle = ko.observable(Settings.settingsGet('UserLogoTitle') || '');
			this.userLogoTitle.trigger = ko.observable(Enums.SaveSettingsStep.Idle);

			this.loginDescription = ko.observable(Settings.settingsGet('LoginDescription'));
			this.loginDescription.trigger = ko.observable(Enums.SaveSettingsStep.Idle);

			this.loginCss = ko.observable(Settings.settingsGet('LoginCss'));
			this.loginCss.trigger = ko.observable(Enums.SaveSettingsStep.Idle);

			this.userCss = ko.observable(Settings.settingsGet('UserCss'));
			this.userCss.trigger = ko.observable(Enums.SaveSettingsStep.Idle);

			this.welcomePageUrl = ko.observable(Settings.settingsGet('WelcomePageUrl'));
			this.welcomePageUrl.trigger = ko.observable(Enums.SaveSettingsStep.Idle);

			this.welcomePageDisplay = ko.observable(Settings.settingsGet('WelcomePageDisplay'));
			this.welcomePageDisplay.trigger = ko.observable(Enums.SaveSettingsStep.Idle);

			this.welcomePageDisplay.options = ko.computed(function () {
				Translator.trigger();
				return [
					{'optValue': 'none', 'optText': Translator.i18n('TAB_BRANDING/OPTION_WELCOME_PAGE_DISPLAY_NONE')},
					{'optValue': 'once', 'optText': Translator.i18n('TAB_BRANDING/OPTION_WELCOME_PAGE_DISPLAY_ONCE')},
					{'optValue': 'always', 'optText': Translator.i18n('TAB_BRANDING/OPTION_WELCOME_PAGE_DISPLAY_ALWAYS')}
				];
			});

			this.loginPowered = ko.observable(!!Settings.settingsGet('LoginPowered'));

			this.community = (false) || AppStore.community();
		}

		BrandingAdminSettings.prototype.onBuild = function ()
		{
			var
				self = this,
				Remote = __webpack_require__(/*! Remote/Admin/Ajax */ 19)
			;

			_.delay(function () {

				var
					f1 = Utils.settingsSaveHelperSimpleFunction(self.title.trigger, self),
					f2 = Utils.settingsSaveHelperSimpleFunction(self.loadingDesc.trigger, self),
					f3 = Utils.settingsSaveHelperSimpleFunction(self.faviconUrl.trigger, self)
				;

				self.title.subscribe(function (sValue) {
					Remote.saveAdminConfig(f1, {
						'Title': Utils.trim(sValue)
					});
				});

				self.loadingDesc.subscribe(function (sValue) {
					Remote.saveAdminConfig(f2, {
						'LoadingDescription': Utils.trim(sValue)
					});
				});

				self.faviconUrl.subscribe(function (sValue) {
					Remote.saveAdminConfig(f3, {
						'FaviconUrl': Utils.trim(sValue)
					});
				});

			}, 50);
		};

		module.exports = BrandingAdminSettings;

	}());

/***/ },
/* 122 */
/*!****************************************!*\
  !*** ./dev/Settings/Admin/Contacts.js ***!
  \****************************************/
/***/ function(module, exports, __webpack_require__) {

	
	(function () {

		'use strict';

		var
			_ = __webpack_require__(/*! _ */ 3),
			ko = __webpack_require__(/*! ko */ 2),

			Enums = __webpack_require__(/*! Common/Enums */ 4),
			Utils = __webpack_require__(/*! Common/Utils */ 1),

			Translator = __webpack_require__(/*! Common/Translator */ 6),

			Settings = __webpack_require__(/*! Storage/Settings */ 9)
		;

		/**
		 * @constructor
		 */
		function ContactsAdminSettings()
		{
			var
				Remote = __webpack_require__(/*! Remote/Admin/Ajax */ 19)
			;

			this.defautOptionsAfterRender = Utils.defautOptionsAfterRender;
			this.enableContacts = ko.observable(!!Settings.settingsGet('ContactsEnable'));
			this.contactsSharing = ko.observable(!!Settings.settingsGet('ContactsSharing'));
			this.contactsSync = ko.observable(!!Settings.settingsGet('ContactsSync'));

			var
				aTypes = ['sqlite', 'mysql', 'pgsql'],
				aSupportedTypes = [],
				getTypeName = function(sName) {
					switch (sName)
					{
						case 'sqlite':
							sName = 'SQLite';
							break;
						case 'mysql':
							sName = 'MySQL';
							break;
						case 'pgsql':
							sName = 'PostgreSQL';
							break;
					}

					return sName;
				}
			;

			if (!!Settings.settingsGet('SQLiteIsSupported'))
			{
				aSupportedTypes.push('sqlite');
			}
			if (!!Settings.settingsGet('MySqlIsSupported'))
			{
				aSupportedTypes.push('mysql');
			}
			if (!!Settings.settingsGet('PostgreSqlIsSupported'))
			{
				aSupportedTypes.push('pgsql');
			}

			this.contactsSupported = 0 < aSupportedTypes.length;

			this.contactsTypes = ko.observableArray([]);
			this.contactsTypesOptions = this.contactsTypes.map(function (sValue) {
				var bDisabled = -1 === Utils.inArray(sValue, aSupportedTypes);
				return {
					'id': sValue,
					'name': getTypeName(sValue) + (bDisabled ? ' (' + Translator.i18n('HINTS/NOT_SUPPORTED') + ')' : ''),
					'disabled': bDisabled
				};
			});

			this.contactsTypes(aTypes);
			this.contactsType = ko.observable('');

			this.mainContactsType = ko.computed({
				'owner': this,
				'read': this.contactsType,
				'write': function (sValue) {
					if (sValue !== this.contactsType())
					{
						if (-1 < Utils.inArray(sValue, aSupportedTypes))
						{
							this.contactsType(sValue);
						}
						else if (0 < aSupportedTypes.length)
						{
							this.contactsType('');
						}
					}
					else
					{
						this.contactsType.valueHasMutated();
					}
				}
			}).extend({'notify': 'always'});

			this.contactsType.subscribe(function () {
				this.testContactsSuccess(false);
				this.testContactsError(false);
				this.testContactsErrorMessage('');
			}, this);

			this.pdoDsn = ko.observable(Settings.settingsGet('ContactsPdoDsn'));
			this.pdoUser = ko.observable(Settings.settingsGet('ContactsPdoUser'));
			this.pdoPassword = ko.observable(Settings.settingsGet('ContactsPdoPassword'));

			this.pdoDsnTrigger = ko.observable(Enums.SaveSettingsStep.Idle);
			this.pdoUserTrigger = ko.observable(Enums.SaveSettingsStep.Idle);
			this.pdoPasswordTrigger = ko.observable(Enums.SaveSettingsStep.Idle);
			this.contactsTypeTrigger = ko.observable(Enums.SaveSettingsStep.Idle);

			this.testing = ko.observable(false);
			this.testContactsSuccess = ko.observable(false);
			this.testContactsError = ko.observable(false);
			this.testContactsErrorMessage = ko.observable('');

			this.testContactsCommand = Utils.createCommand(this, function () {

				this.testContactsSuccess(false);
				this.testContactsError(false);
				this.testContactsErrorMessage('');
				this.testing(true);

				Remote.testContacts(this.onTestContactsResponse, {
					'ContactsPdoType': this.contactsType(),
					'ContactsPdoDsn': this.pdoDsn(),
					'ContactsPdoUser': this.pdoUser(),
					'ContactsPdoPassword': this.pdoPassword()
				});

			}, function () {
				return '' !== this.pdoDsn() && '' !== this.pdoUser();
			});

			this.contactsType(Settings.settingsGet('ContactsPdoType'));

			this.onTestContactsResponse = _.bind(this.onTestContactsResponse, this);
		}

		ContactsAdminSettings.prototype.onTestContactsResponse = function (sResult, oData)
		{
			this.testContactsSuccess(false);
			this.testContactsError(false);
			this.testContactsErrorMessage('');

			if (Enums.StorageResultType.Success === sResult && oData && oData.Result && oData.Result.Result)
			{
				this.testContactsSuccess(true);
			}
			else
			{
				this.testContactsError(true);
				if (oData && oData.Result)
				{
					this.testContactsErrorMessage(oData.Result.Message || '');
				}
				else
				{
					this.testContactsErrorMessage('');
				}
			}

			this.testing(false);
		};

		ContactsAdminSettings.prototype.onShow = function ()
		{
			this.testContactsSuccess(false);
			this.testContactsError(false);
			this.testContactsErrorMessage('');
		};

		ContactsAdminSettings.prototype.onBuild = function ()
		{
			var
				self = this,
				Remote = __webpack_require__(/*! Remote/Admin/Ajax */ 19)
			;

			_.delay(function () {

				var
					f1 = Utils.settingsSaveHelperSimpleFunction(self.pdoDsnTrigger, self),
					f3 = Utils.settingsSaveHelperSimpleFunction(self.pdoUserTrigger, self),
					f4 = Utils.settingsSaveHelperSimpleFunction(self.pdoPasswordTrigger, self),
					f5 = Utils.settingsSaveHelperSimpleFunction(self.contactsTypeTrigger, self)
				;

				self.enableContacts.subscribe(function (bValue) {
					Remote.saveAdminConfig(null, {
						'ContactsEnable': bValue ? '1' : '0'
					});
				});

				self.contactsSharing.subscribe(function (bValue) {
					Remote.saveAdminConfig(null, {
						'ContactsSharing': bValue ? '1' : '0'
					});
				});

				self.contactsSync.subscribe(function (bValue) {
					Remote.saveAdminConfig(null, {
						'ContactsSync': bValue ? '1' : '0'
					});
				});

				self.contactsType.subscribe(function (sValue) {
					Remote.saveAdminConfig(f5, {
						'ContactsPdoType': sValue
					});
				});

				self.pdoDsn.subscribe(function (sValue) {
					Remote.saveAdminConfig(f1, {
						'ContactsPdoDsn': Utils.trim(sValue)
					});
				});

				self.pdoUser.subscribe(function (sValue) {
					Remote.saveAdminConfig(f3, {
						'ContactsPdoUser': Utils.trim(sValue)
					});
				});

				self.pdoPassword.subscribe(function (sValue) {
					Remote.saveAdminConfig(f4, {
						'ContactsPdoPassword': Utils.trim(sValue)
					});
				});

				self.contactsType(Settings.settingsGet('ContactsPdoType'));

			}, 50);
		};

		module.exports = ContactsAdminSettings;

	}());

/***/ },
/* 123 */
/*!***************************************!*\
  !*** ./dev/Settings/Admin/Domains.js ***!
  \***************************************/
/***/ function(module, exports, __webpack_require__) {

	
	(function () {

		'use strict';

		var
			_ = __webpack_require__(/*! _ */ 3),
			ko = __webpack_require__(/*! ko */ 2),

			Enums = __webpack_require__(/*! Common/Enums */ 4),

			DomainStore = __webpack_require__(/*! Stores/Admin/Domain */ 58),
			Remote = __webpack_require__(/*! Remote/Admin/Ajax */ 19)
		;

		/**
		 * @constructor
		 */
		function DomainsAdminSettings()
		{
			this.domains = DomainStore.domains;

			this.visibility = ko.computed(function () {
				return this.domains.loading() ? 'visible' : 'hidden';
			}, this);

			this.domainForDeletion = ko.observable(null).deleteAccessHelper();
		}

		DomainsAdminSettings.prototype.createDomain = function ()
		{
			__webpack_require__(/*! Knoin/Knoin */ 5).showScreenPopup(__webpack_require__(/*! View/Popup/Domain */ 95));
		};

		DomainsAdminSettings.prototype.deleteDomain = function (oDomain)
		{
			this.domains.remove(oDomain);
			Remote.domainDelete(_.bind(this.onDomainListChangeRequest, this), oDomain.name);
		};

		DomainsAdminSettings.prototype.disableDomain = function (oDomain)
		{
			oDomain.disabled(!oDomain.disabled());
			Remote.domainDisable(_.bind(this.onDomainListChangeRequest, this), oDomain.name, oDomain.disabled());
		};

		DomainsAdminSettings.prototype.onBuild = function (oDom)
		{
			var self = this;
			oDom
				.on('click', '.b-admin-domains-list-table .e-item .e-action', function () {
					var oDomainItem = ko.dataFor(this);
					if (oDomainItem)
					{
						Remote.domain(_.bind(self.onDomainLoadRequest, self), oDomainItem.name);
					}
				})
			;

			__webpack_require__(/*! App/Admin */ 20).default.reloadDomainList();
		};

		DomainsAdminSettings.prototype.onDomainLoadRequest = function (sResult, oData)
		{
			if (Enums.StorageResultType.Success === sResult && oData && oData.Result)
			{
				__webpack_require__(/*! Knoin/Knoin */ 5).showScreenPopup(__webpack_require__(/*! View/Popup/Domain */ 95), [oData.Result]);
			}
		};

		DomainsAdminSettings.prototype.onDomainListChangeRequest = function ()
		{
			__webpack_require__(/*! App/Admin */ 20).default.reloadDomainList();
		};

		module.exports = DomainsAdminSettings;

	}());

/***/ },
/* 124 */
/*!***************************************!*\
  !*** ./dev/Settings/Admin/General.js ***!
  \***************************************/
/***/ function(module, exports, __webpack_require__) {

	
	(function () {

		'use strict';

		var
			_ = __webpack_require__(/*! _ */ 3),
			ko = __webpack_require__(/*! ko */ 2),

			Enums = __webpack_require__(/*! Common/Enums */ 4),
			Utils = __webpack_require__(/*! Common/Utils */ 1),
			Links = __webpack_require__(/*! Common/Links */ 11),
			Translator = __webpack_require__(/*! Common/Translator */ 6),

			ThemeStore = __webpack_require__(/*! Stores/Theme */ 42),
			LanguageStore = __webpack_require__(/*! Stores/Language */ 39),
			AppAdminStore = __webpack_require__(/*! Stores/Admin/App */ 35),
			CapaAdminStore = __webpack_require__(/*! Stores/Admin/Capa */ 50),

			Settings = __webpack_require__(/*! Storage/Settings */ 9)
		;

		/**
		 * @constructor
		 */
		function GeneralAdminSettings()
		{
			this.language = LanguageStore.language;
			this.languages = LanguageStore.languages;
			this.languageAdmin = LanguageStore.languageAdmin;
			this.languagesAdmin = LanguageStore.languagesAdmin;

			this.theme = ThemeStore.theme;
			this.themes = ThemeStore.themes;

			this.capaThemes = CapaAdminStore.themes;
			this.capaUserBackground = CapaAdminStore.userBackground;
			this.capaGravatar = CapaAdminStore.gravatar;
			this.capaAdditionalAccounts = CapaAdminStore.additionalAccounts;
			this.capaIdentities = CapaAdminStore.identities;
			this.capaAttachmentThumbnails = CapaAdminStore.attachmentThumbnails;
			this.capaTemplates = CapaAdminStore.templates;

			this.allowLanguagesOnSettings = AppAdminStore.allowLanguagesOnSettings;
			this.weakPassword = AppAdminStore.weakPassword;

			this.mainAttachmentLimit = ko.observable(Utils.pInt(Settings.settingsGet('AttachmentLimit')) / (1024 * 1024)).extend({'posInterer': 25});
			this.uploadData = Settings.settingsGet('PhpUploadSizes');
			this.uploadDataDesc = this.uploadData && (this.uploadData['upload_max_filesize'] || this.uploadData['post_max_size']) ? [
				this.uploadData['upload_max_filesize'] ? 'upload_max_filesize = ' + this.uploadData['upload_max_filesize'] + '; ' : '',
				this.uploadData['post_max_size'] ? 'post_max_size = ' + this.uploadData['post_max_size'] : ''
			].join('') : '';

			this.themesOptions = ko.computed(function () {
				return _.map(this.themes(), function (sTheme) {
					return {
						'optValue': sTheme,
						'optText': Utils.convertThemeName(sTheme)
					};
				});
			}, this);

			this.languageFullName = ko.computed(function () {
				return Utils.convertLangName(this.language());
			}, this);

			this.languageAdminFullName = ko.computed(function () {
				return Utils.convertLangName(this.languageAdmin());
			}, this);

			this.attachmentLimitTrigger = ko.observable(Enums.SaveSettingsStep.Idle);
			this.languageTrigger = ko.observable(Enums.SaveSettingsStep.Idle);
			this.languageAdminTrigger = ko.observable(Enums.SaveSettingsStep.Idle).extend({'throttle': 100});
			this.themeTrigger = ko.observable(Enums.SaveSettingsStep.Idle);
		}

		GeneralAdminSettings.prototype.onBuild = function ()
		{
			var
				self = this,
				Remote = __webpack_require__(/*! Remote/Admin/Ajax */ 19)
			;

			_.delay(function () {

				var
					f1 = Utils.settingsSaveHelperSimpleFunction(self.attachmentLimitTrigger, self),
					f2 = Utils.settingsSaveHelperSimpleFunction(self.languageTrigger, self),
					f3 = Utils.settingsSaveHelperSimpleFunction(self.themeTrigger, self),
					fReloadLanguageHelper = function (iSaveSettingsStep) {
						return function() {
							self.languageAdminTrigger(iSaveSettingsStep);
							_.delay(function () {
								self.languageAdminTrigger(Enums.SaveSettingsStep.Idle);
							}, 1000);
						};
					}
				;

				self.mainAttachmentLimit.subscribe(function (sValue) {
					Remote.saveAdminConfig(f1, {
						'AttachmentLimit': Utils.pInt(sValue)
					});
				});

				self.language.subscribe(function (sValue) {
					Remote.saveAdminConfig(f2, {
						'Language': Utils.trim(sValue)
					});
				});

				self.languageAdmin.subscribe(function (sValue) {

					self.languageAdminTrigger(Enums.SaveSettingsStep.Animate);

					Translator.reload(true, sValue,
						fReloadLanguageHelper(Enums.SaveSettingsStep.TrueResult),
						fReloadLanguageHelper(Enums.SaveSettingsStep.FalseResult));

					Remote.saveAdminConfig(null, {
						'LanguageAdmin': Utils.trim(sValue)
					});
				});

				self.theme.subscribe(function (sValue) {

					Utils.changeTheme(sValue, self.themeTrigger);

					Remote.saveAdminConfig(f3, {
						'Theme': Utils.trim(sValue)
					});
				});

				self.capaAdditionalAccounts.subscribe(function (bValue) {
					Remote.saveAdminConfig(null, {
						'CapaAdditionalAccounts': bValue ? '1' : '0'
					});
				});

				self.capaIdentities.subscribe(function (bValue) {
					Remote.saveAdminConfig(null, {
						'CapaIdentities': bValue ? '1' : '0'
					});
				});

				self.capaTemplates.subscribe(function (bValue) {
					Remote.saveAdminConfig(null, {
						'CapaTemplates': bValue ? '1' : '0'
					});
				});

				self.capaGravatar.subscribe(function (bValue) {
					Remote.saveAdminConfig(null, {
						'CapaGravatar': bValue ? '1' : '0'
					});
				});

				self.capaAttachmentThumbnails.subscribe(function (bValue) {
					Remote.saveAdminConfig(null, {
						'CapaAttachmentThumbnails': bValue ? '1' : '0'
					});
				});

				self.capaThemes.subscribe(function (bValue) {
					Remote.saveAdminConfig(null, {
						'CapaThemes': bValue ? '1' : '0'
					});
				});

				self.capaUserBackground.subscribe(function (bValue) {
					Remote.saveAdminConfig(null, {
						'CapaUserBackground': bValue ? '1' : '0'
					});
				});

				self.allowLanguagesOnSettings.subscribe(function (bValue) {
					Remote.saveAdminConfig(null, {
						'AllowLanguagesOnSettings': bValue ? '1' : '0'
					});
				});

			}, 50);
		};

		GeneralAdminSettings.prototype.selectLanguage = function ()
		{
			__webpack_require__(/*! Knoin/Knoin */ 5).showScreenPopup(__webpack_require__(/*! View/Popup/Languages */ 44), [
				this.language, this.languages(), LanguageStore.userLanguage()
			]);
		};

		GeneralAdminSettings.prototype.selectLanguageAdmin = function ()
		{
			__webpack_require__(/*! Knoin/Knoin */ 5).showScreenPopup(__webpack_require__(/*! View/Popup/Languages */ 44), [
				this.languageAdmin, this.languagesAdmin(), LanguageStore.userLanguageAdmin()
			]);
		};

		/**
		 * @return {string}
		 */
		GeneralAdminSettings.prototype.phpInfoLink = function ()
		{
			return Links.phpInfo();
		};

		module.exports = GeneralAdminSettings;

	}());

/***/ },
/* 125 */
/*!*************************************!*\
  !*** ./dev/Settings/Admin/Login.js ***!
  \*************************************/
/***/ function(module, exports, __webpack_require__) {

	
	(function () {

		'use strict';

		var
			_ = __webpack_require__(/*! _ */ 3),
			ko = __webpack_require__(/*! ko */ 2),

			Enums = __webpack_require__(/*! Common/Enums */ 4),
			Utils = __webpack_require__(/*! Common/Utils */ 1),

			AppAdminStore = __webpack_require__(/*! Stores/Admin/App */ 35),

			Settings = __webpack_require__(/*! Storage/Settings */ 9)
		;

		/**
		 * @constructor
		 */
		function LoginAdminSettings()
		{
			this.determineUserLanguage = AppAdminStore.determineUserLanguage;
			this.determineUserDomain = AppAdminStore.determineUserDomain;

			this.defaultDomain = ko.observable(Settings.settingsGet('LoginDefaultDomain'));

			this.allowLanguagesOnLogin = AppAdminStore.allowLanguagesOnLogin;
			this.defaultDomainTrigger = ko.observable(Enums.SaveSettingsStep.Idle);

			this.dummy = ko.observable(false);
		}

		LoginAdminSettings.prototype.onBuild = function ()
		{
			var
				self = this,
				Remote = __webpack_require__(/*! Remote/Admin/Ajax */ 19)
			;

			_.delay(function () {

				var f1 = Utils.settingsSaveHelperSimpleFunction(self.defaultDomainTrigger, self);

				self.determineUserLanguage.subscribe(function (bValue) {
					Remote.saveAdminConfig(null, {
						'DetermineUserLanguage': bValue ? '1' : '0'
					});
				});

				self.determineUserDomain.subscribe(function (bValue) {
					Remote.saveAdminConfig(null, {
						'DetermineUserDomain': bValue ? '1' : '0'
					});
				});

				self.allowLanguagesOnLogin.subscribe(function (bValue) {
					Remote.saveAdminConfig(null, {
						'AllowLanguagesOnLogin': bValue ? '1' : '0'
					});
				});

				self.defaultDomain.subscribe(function (sValue) {
					Remote.saveAdminConfig(f1, {
						'LoginDefaultDomain': Utils.trim(sValue)
					});
				});

			}, 50);
		};

		module.exports = LoginAdminSettings;

	}());

/***/ },
/* 126 */
/*!****************************************!*\
  !*** ./dev/Settings/Admin/Packages.js ***!
  \****************************************/
/***/ function(module, exports, __webpack_require__) {

	
	(function () {

		'use strict';

		var
			window = __webpack_require__(/*! window */ 13),
			_ = __webpack_require__(/*! _ */ 3),
			ko = __webpack_require__(/*! ko */ 2),

			Enums = __webpack_require__(/*! Common/Enums */ 4),
			Translator = __webpack_require__(/*! Common/Translator */ 6),

			PackageStore = __webpack_require__(/*! Stores/Admin/Package */ 60),
			Remote = __webpack_require__(/*! Remote/Admin/Ajax */ 19)
		;

		/**
		 * @constructor
		 */
		function PackagesAdminSettings()
		{
			this.packagesError = ko.observable('');

			this.packages = PackageStore.packages;
			this.packagesReal = PackageStore.packagesReal;
			this.packagesMainUpdatable = PackageStore.packagesMainUpdatable;

			this.packagesCurrent = this.packages.filter(function (oItem) {
				return oItem && '' !== oItem['installed'] && !oItem['compare'];
			});

			this.packagesAvailableForUpdate = this.packages.filter(function (oItem) {
				return oItem && '' !== oItem['installed'] && !!oItem['compare'];
			});

			this.packagesAvailableForInstallation = this.packages.filter(function (oItem) {
				return oItem && '' === oItem['installed'];
			});

			this.visibility = ko.computed(function () {
				return PackageStore.packages.loading() ? 'visible' : 'hidden';
			}, this);
		}

		PackagesAdminSettings.prototype.onShow = function ()
		{
			this.packagesError('');
		};

		PackagesAdminSettings.prototype.onBuild = function ()
		{
			__webpack_require__(/*! App/Admin */ 20).default.reloadPackagesList();
		};

		PackagesAdminSettings.prototype.requestHelper = function (oPackage, bInstall)
		{
			var self = this;
			return function (sResult, oData) {

				if (Enums.StorageResultType.Success !== sResult || !oData || !oData.Result)
				{
					if (oData && oData.ErrorCode)
					{
						self.packagesError(Translator.getNotification(oData.ErrorCode));
					}
					else
					{
						self.packagesError(Translator.getNotification(
							bInstall ? Enums.Notification.CantInstallPackage : Enums.Notification.CantDeletePackage));
					}
				}

				_.each(self.packages(), function (oItem) {
					if (oItem && oPackage && oItem['loading']() && oPackage['file'] === oItem['file'])
					{
						oPackage['loading'](false);
						oItem['loading'](false);
					}
				});

				if (Enums.StorageResultType.Success === sResult && oData && oData.Result && oData.Result['Reload'])
				{
					window.location.reload();
				}
				else
				{
					__webpack_require__(/*! App/Admin */ 20).default.reloadPackagesList();
				}
			};
		};

		PackagesAdminSettings.prototype.deletePackage = function (oPackage)
		{
			if (oPackage)
			{
				oPackage['loading'](true);
				Remote.packageDelete(this.requestHelper(oPackage, false), oPackage);
			}
		};

		PackagesAdminSettings.prototype.installPackage = function (oPackage)
		{
			if (oPackage)
			{
				oPackage['loading'](true);
				Remote.packageInstall(this.requestHelper(oPackage, true), oPackage);
			}
		};

		module.exports = PackagesAdminSettings;

	}());

/***/ },
/* 127 */
/*!***************************************!*\
  !*** ./dev/Settings/Admin/Plugins.js ***!
  \***************************************/
/***/ function(module, exports, __webpack_require__) {

	
	(function () {

		'use strict';

		var
			_ = __webpack_require__(/*! _ */ 3),
			ko = __webpack_require__(/*! ko */ 2),

			Enums = __webpack_require__(/*! Common/Enums */ 4),
			Utils = __webpack_require__(/*! Common/Utils */ 1),
			Translator = __webpack_require__(/*! Common/Translator */ 6),

			Settings = __webpack_require__(/*! Storage/Settings */ 9),

			AppStore = __webpack_require__(/*! Stores/Admin/App */ 35),
			PluginStore = __webpack_require__(/*! Stores/Admin/Plugin */ 61),

			Remote = __webpack_require__(/*! Remote/Admin/Ajax */ 19)
		;

		/**
		 * @constructor
		 */
		function PluginsAdminSettings()
		{
			this.enabledPlugins = ko.observable(!!Settings.settingsGet('EnabledPlugins'));

			this.plugins = PluginStore.plugins;
			this.pluginsError = PluginStore.plugins.error;

			this.community = (false) || AppStore.community();

			this.visibility = ko.computed(function () {
				return PluginStore.plugins.loading() ? 'visible' : 'hidden';
			}, this);

			this.onPluginLoadRequest = _.bind(this.onPluginLoadRequest, this);
			this.onPluginDisableRequest = _.bind(this.onPluginDisableRequest, this);
		}

		PluginsAdminSettings.prototype.disablePlugin = function (oPlugin)
		{
			oPlugin.disabled(!oPlugin.disabled());
			Remote.pluginDisable(this.onPluginDisableRequest, oPlugin.name, oPlugin.disabled());
		};

		PluginsAdminSettings.prototype.configurePlugin = function (oPlugin)
		{
			Remote.plugin(this.onPluginLoadRequest, oPlugin.name);
		};

		PluginsAdminSettings.prototype.onBuild = function (oDom)
		{
			var self = this;

			oDom
				.on('click', '.e-item .configure-plugin-action', function () {
					var oPlugin = ko.dataFor(this);
					if (oPlugin)
					{
						self.configurePlugin(oPlugin);
					}
				})
				.on('click', '.e-item .disabled-plugin', function () {
					var oPlugin = ko.dataFor(this);
					if (oPlugin)
					{
						self.disablePlugin(oPlugin);
					}
				})
			;

			this.enabledPlugins.subscribe(function (bValue) {
				Remote.saveAdminConfig(Utils.emptyFunction, {
					'EnabledPlugins': bValue ? '1' : '0'
				});
			});
		};

		PluginsAdminSettings.prototype.onShow = function ()
		{
			PluginStore.plugins.error('');
			__webpack_require__(/*! App/Admin */ 20).default.reloadPluginList();
		};

		PluginsAdminSettings.prototype.onPluginLoadRequest = function (sResult, oData)
		{
			if (Enums.StorageResultType.Success === sResult && oData && oData.Result)
			{
				__webpack_require__(/*! Knoin/Knoin */ 5).showScreenPopup(__webpack_require__(/*! View/Popup/Plugin */ 152), [oData.Result]);
			}
		};

		PluginsAdminSettings.prototype.onPluginDisableRequest = function (sResult, oData)
		{
			if (Enums.StorageResultType.Success === sResult && oData)
			{
				if (!oData.Result && oData.ErrorCode)
				{
					if (Enums.Notification.UnsupportedPluginPackage === oData.ErrorCode && oData.ErrorMessage && '' !== oData.ErrorMessage)
					{
						PluginStore.plugins.error(oData.ErrorMessage);
					}
					else
					{
						PluginStore.plugins.error(Translator.getNotification(oData.ErrorCode));
					}
				}
			}

			__webpack_require__(/*! App/Admin */ 20).default.reloadPluginList();
		};

		module.exports = PluginsAdminSettings;

	}());

/***/ },
/* 128 */
/*!*********************************************!*\
  !*** ./dev/Settings/Admin/Prem/Branding.js ***!
  \*********************************************/
/***/ function(module, exports, __webpack_require__) {

	
	(function () {

		'use strict';

		var
			_ = __webpack_require__(/*! _ */ 3),

			Utils = __webpack_require__(/*! Common/Utils */ 1),

			BrandingAdminSettings = __webpack_require__(/*! Settings/Admin/Branding */ 121)
		;

		/**
		 * @constructor
		 */
		function BrandingPremAdminSettings()
		{
			BrandingAdminSettings.call(this);
		}

		BrandingPremAdminSettings.prototype.onBuild = function (oDom)
		{
			BrandingAdminSettings.prototype.onBuild.call(this, oDom);

			if (this.capa && this.capa() && !this.community)
			{
				var
					self = this,
					Remote = __webpack_require__(/*! Remote/Admin/Ajax */ 19)
				;

				_.delay(function () {

					var
						f1 = Utils.settingsSaveHelperSimpleFunction(self.loginLogo.trigger, self),
						f2 = Utils.settingsSaveHelperSimpleFunction(self.loginDescription.trigger, self),
						f3 = Utils.settingsSaveHelperSimpleFunction(self.loginCss.trigger, self),
						f4 = Utils.settingsSaveHelperSimpleFunction(self.userLogo.trigger, self),
						f5 = Utils.settingsSaveHelperSimpleFunction(self.userLogoTitle.trigger, self),
						f6 = Utils.settingsSaveHelperSimpleFunction(self.loginBackground.trigger, self),
						f7 = Utils.settingsSaveHelperSimpleFunction(self.userCss.trigger, self),
						f8 = Utils.settingsSaveHelperSimpleFunction(self.welcomePageUrl.trigger, self),
						f9 = Utils.settingsSaveHelperSimpleFunction(self.welcomePageDisplay.trigger, self),
						f10 = Utils.settingsSaveHelperSimpleFunction(self.userLogoMessage.trigger, self),
						f11 = Utils.settingsSaveHelperSimpleFunction(self.userIframeMessage.trigger, self)
					;

					self.loginLogo.subscribe(function (sValue) {
						Remote.saveAdminConfig(f1, {
							'LoginLogo': Utils.trim(sValue)
						});
					});

					self.loginDescription.subscribe(function (sValue) {
						Remote.saveAdminConfig(f2, {
							'LoginDescription': Utils.trim(sValue)
						});
					});

					self.loginCss.subscribe(function (sValue) {
						Remote.saveAdminConfig(f3, {
							'LoginCss': Utils.trim(sValue)
						});
					});

					self.userLogo.subscribe(function (sValue) {
						Remote.saveAdminConfig(f4, {
							'UserLogo': Utils.trim(sValue)
						});
					});

					self.userLogoTitle.subscribe(function (sValue) {
						Remote.saveAdminConfig(f5, {
							'UserLogoTitle': Utils.trim(sValue)
						});
					});

					self.userLogoMessage.subscribe(function (sValue) {
						Remote.saveAdminConfig(f10, {
							'UserLogoMessage': Utils.trim(sValue)
						});
					});

					self.userIframeMessage.subscribe(function (sValue) {
						Remote.saveAdminConfig(f11, {
							'UserIframeMessage': Utils.trim(sValue)
						});
					});

					self.loginBackground.subscribe(function (sValue) {
						Remote.saveAdminConfig(f6, {
							'LoginBackground': Utils.trim(sValue)
						});
					});

					self.userCss.subscribe(function (sValue) {
						Remote.saveAdminConfig(f7, {
							'UserCss': Utils.trim(sValue)
						});
					});

					self.welcomePageUrl.subscribe(function (sValue) {
						Remote.saveAdminConfig(f8, {
							'WelcomePageUrl': Utils.trim(sValue)
						});
					});

					self.welcomePageDisplay.subscribe(function (sValue) {
						Remote.saveAdminConfig(f9, {
							'WelcomePageDisplay': Utils.trim(sValue)
						});
					});

					self.loginPowered.subscribe(function (bValue) {
						Remote.saveAdminConfig(null, {
							'LoginPowered': bValue ? '1' : '0'
						});
					});

				}, 50);
			}
		};

		module.exports = BrandingPremAdminSettings;

	}());

/***/ },
/* 129 */
/*!**********************************************!*\
  !*** ./dev/Settings/Admin/Prem/Licensing.js ***!
  \**********************************************/
/***/ function(module, exports, __webpack_require__) {

	
	(function () {

		'use strict';

		var
			ko = __webpack_require__(/*! ko */ 2),

			Settings = __webpack_require__(/*! Storage/Settings */ 9),
			LicenseStore = __webpack_require__(/*! Stores/Admin/License */ 59)
		;

		/**
		 * @constructor
		 */
		function LicensingPremAdminSettings()
		{
			this.licensing = LicenseStore.licensing;
			this.licensingProcess = LicenseStore.licensingProcess;
			this.licenseValid = LicenseStore.licenseValid;
			this.licenseExpired = LicenseStore.licenseExpired;
			this.licenseError = LicenseStore.licenseError;
			this.licenseTrigger = LicenseStore.licenseTrigger;

			this.adminDomain = ko.observable('');
			this.subscriptionEnabled = ko.observable(!!Settings.settingsGet('SubscriptionEnabled'));

			this.licenseTrigger.subscribe(function () {
				if (this.subscriptionEnabled())
				{
					__webpack_require__(/*! App/Admin */ 20).default.reloadLicensing(true);
				}
			}, this);
		}

		LicensingPremAdminSettings.prototype.onBuild = function ()
		{
			if (this.subscriptionEnabled())
			{
				__webpack_require__(/*! App/Admin */ 20).default.reloadLicensing(false);
			}
		};

		LicensingPremAdminSettings.prototype.onShow = function ()
		{
			this.adminDomain(Settings.settingsGet('AdminDomain'));
		};

		LicensingPremAdminSettings.prototype.showActivationForm = function ()
		{
			__webpack_require__(/*! Knoin/Knoin */ 5).showScreenPopup(__webpack_require__(/*! View/Popup/Activate */ 94));
		};

		LicensingPremAdminSettings.prototype.showTrialForm = function ()
		{
			__webpack_require__(/*! Knoin/Knoin */ 5).showScreenPopup(__webpack_require__(/*! View/Popup/Activate */ 94), [true]);
		};

		/**
		 * @return {boolean}
		 */
		LicensingPremAdminSettings.prototype.licenseIsUnlim = function ()
		{
			return 1898625600 === this.licenseExpired() || 1898625700 === this.licenseExpired();
		};

		/**
		 * @return {string}
		 */
		LicensingPremAdminSettings.prototype.licenseExpiredMomentValue = function ()
		{
			var
				moment = __webpack_require__(/*! moment */ 54),
				iTime = this.licenseExpired(),
				oM = moment.unix(iTime)
			;

			return this.licenseIsUnlim() ? 'Never' :
				(iTime && (oM.format('LL') + ' (' + oM.from(moment()) + ')'));
		};

		module.exports = LicensingPremAdminSettings;

	}());

/***/ },
/* 130 */
/*!****************************************!*\
  !*** ./dev/Settings/Admin/Security.js ***!
  \****************************************/
/***/ function(module, exports, __webpack_require__) {

	
	(function () {

		'use strict';

		var
			_ = __webpack_require__(/*! _ */ 3),
			ko = __webpack_require__(/*! ko */ 2),

			Enums = __webpack_require__(/*! Common/Enums */ 4),
			Utils = __webpack_require__(/*! Common/Utils */ 1),
			Links = __webpack_require__(/*! Common/Links */ 11),

			AppAdminStore = __webpack_require__(/*! Stores/Admin/App */ 35),
			CapaAdminStore = __webpack_require__(/*! Stores/Admin/Capa */ 50),

			Settings = __webpack_require__(/*! Storage/Settings */ 9),
			Remote = __webpack_require__(/*! Remote/Admin/Ajax */ 19)
		;

		/**
		 * @constructor
		 */
		function SecurityAdminSettings()
		{
			this.useLocalProxyForExternalImages = AppAdminStore.useLocalProxyForExternalImages;

			this.weakPassword = AppAdminStore.weakPassword;

			this.capaOpenPGP = CapaAdminStore.openPGP;

			this.capaTwoFactorAuth = CapaAdminStore.twoFactorAuth;
			this.capaTwoFactorAuthForce = CapaAdminStore.twoFactorAuthForce;

			this.capaTwoFactorAuth.subscribe(function (bValue) {
				if (!bValue)
				{
					this.capaTwoFactorAuthForce(false);
				}
			}, this);

			this.verifySslCertificate = ko.observable(!!Settings.settingsGet('VerifySslCertificate'));
			this.allowSelfSigned = ko.observable(!!Settings.settingsGet('AllowSelfSigned'));

			this.verifySslCertificate.subscribe(function (bValue) {
				if (!bValue)
				{
					this.allowSelfSigned(true);
				}
			}, this);

			this.adminLogin = ko.observable(Settings.settingsGet('AdminLogin'));
			this.adminLoginError = ko.observable(false);
			this.adminPassword = ko.observable('');
			this.adminPasswordNew = ko.observable('');
			this.adminPasswordNew2 = ko.observable('');
			this.adminPasswordNewError = ko.observable(false);

			this.adminPasswordUpdateError = ko.observable(false);
			this.adminPasswordUpdateSuccess = ko.observable(false);

			this.adminPassword.subscribe(function () {
				this.adminPasswordUpdateError(false);
				this.adminPasswordUpdateSuccess(false);
			}, this);

			this.adminLogin.subscribe(function () {
				this.adminLoginError(false);
			}, this);

			this.adminPasswordNew.subscribe(function () {
				this.adminPasswordUpdateError(false);
				this.adminPasswordUpdateSuccess(false);
				this.adminPasswordNewError(false);
			}, this);

			this.adminPasswordNew2.subscribe(function () {
				this.adminPasswordUpdateError(false);
				this.adminPasswordUpdateSuccess(false);
				this.adminPasswordNewError(false);
			}, this);

			this.saveNewAdminPasswordCommand = Utils.createCommand(this, function () {

				if ('' === Utils.trim(this.adminLogin()))
				{
					this.adminLoginError(true);
					return false;
				}

				if (this.adminPasswordNew() !== this.adminPasswordNew2())
				{
					this.adminPasswordNewError(true);
					return false;
				}

				this.adminPasswordUpdateError(false);
				this.adminPasswordUpdateSuccess(false);

				Remote.saveNewAdminPassword(this.onNewAdminPasswordResponse, {
					'Login': this.adminLogin(),
					'Password': this.adminPassword(),
					'NewPassword': this.adminPasswordNew()
				});

			}, function () {
				return '' !== Utils.trim(this.adminLogin()) && '' !== this.adminPassword();
			});

			this.onNewAdminPasswordResponse = _.bind(this.onNewAdminPasswordResponse, this);
		}

		SecurityAdminSettings.prototype.onNewAdminPasswordResponse = function (sResult, oData)
		{
			if (Enums.StorageResultType.Success === sResult && oData && oData.Result)
			{
				this.adminPassword('');
				this.adminPasswordNew('');
				this.adminPasswordNew2('');

				this.adminPasswordUpdateSuccess(true);

				this.weakPassword(!!oData.Result.Weak);
			}
			else
			{
				this.adminPasswordUpdateError(true);
			}
		};

		SecurityAdminSettings.prototype.onBuild = function ()
		{
			this.capaOpenPGP.subscribe(function (bValue) {
				Remote.saveAdminConfig(Utils.emptyFunction, {
					'CapaOpenPGP': bValue ? '1' : '0'
				});
			});

			this.capaTwoFactorAuth.subscribe(function (bValue) {
				Remote.saveAdminConfig(Utils.emptyFunction, {
					'CapaTwoFactorAuth': bValue ? '1' : '0'
				});
			});

			this.capaTwoFactorAuthForce.subscribe(function (bValue) {
				Remote.saveAdminConfig(Utils.emptyFunction, {
					'CapaTwoFactorAuthForce': bValue ? '1' : '0'
				});
			});

			this.useLocalProxyForExternalImages.subscribe(function (bValue) {
				Remote.saveAdminConfig(null, {
					'UseLocalProxyForExternalImages': bValue ? '1' : '0'
				});
			});

			this.verifySslCertificate.subscribe(function (bValue) {
				Remote.saveAdminConfig(null, {
					'VerifySslCertificate': bValue ? '1' : '0'
				});
			});

			this.allowSelfSigned.subscribe(function (bValue) {
				Remote.saveAdminConfig(null, {
					'AllowSelfSigned': bValue ? '1' : '0'
				});
			});
		};

		SecurityAdminSettings.prototype.onHide = function ()
		{
			this.adminPassword('');
			this.adminPasswordNew('');
			this.adminPasswordNew2('');
		};

		/**
		 * @return {string}
		 */
		SecurityAdminSettings.prototype.phpInfoLink = function ()
		{
			return Links.phpInfo();
		};

		module.exports = SecurityAdminSettings;

	}());


/***/ },
/* 131 */
/*!**************************************!*\
  !*** ./dev/Settings/Admin/Social.js ***!
  \**************************************/
/***/ function(module, exports, __webpack_require__) {

	
	(function () {

		'use strict';

		var
			_ = __webpack_require__(/*! _ */ 3),
			ko = __webpack_require__(/*! ko */ 2),

			Enums = __webpack_require__(/*! Common/Enums */ 4),
			Utils = __webpack_require__(/*! Common/Utils */ 1)
		;

		/**
		 * @constructor
		 */
		function SocialAdminSettings()
		{
			var SocialStore = __webpack_require__(/*! Stores/Social */ 34);

			this.googleEnable = SocialStore.google.enabled;
			this.googleEnableAuth = SocialStore.google.capa.auth;
			this.googleEnableAuthFast = SocialStore.google.capa.authFast;
			this.googleEnableDrive = SocialStore.google.capa.drive;
			this.googleEnablePreview = SocialStore.google.capa.preview;

			this.googleEnableRequireClientSettings = SocialStore.google.require.clientSettings;
			this.googleEnableRequireApiKey = SocialStore.google.require.apiKeySettings;

			this.googleClientID = SocialStore.google.clientID;
			this.googleClientSecret = SocialStore.google.clientSecret;
			this.googleApiKey = SocialStore.google.apiKey;

			this.googleTrigger1 = ko.observable(Enums.SaveSettingsStep.Idle);
			this.googleTrigger2 = ko.observable(Enums.SaveSettingsStep.Idle);
			this.googleTrigger3 = ko.observable(Enums.SaveSettingsStep.Idle);

			this.facebookSupported = SocialStore.facebook.supported;
			this.facebookEnable = SocialStore.facebook.enabled;
			this.facebookAppID = SocialStore.facebook.appID;
			this.facebookAppSecret = SocialStore.facebook.appSecret;

			this.facebookTrigger1 = ko.observable(Enums.SaveSettingsStep.Idle);
			this.facebookTrigger2 = ko.observable(Enums.SaveSettingsStep.Idle);

			this.twitterEnable = SocialStore.twitter.enabled;
			this.twitterConsumerKey = SocialStore.twitter.consumerKey;
			this.twitterConsumerSecret = SocialStore.twitter.consumerSecret;

			this.twitterTrigger1 = ko.observable(Enums.SaveSettingsStep.Idle);
			this.twitterTrigger2 = ko.observable(Enums.SaveSettingsStep.Idle);

			this.dropboxEnable = SocialStore.dropbox.enabled;
			this.dropboxApiKey = SocialStore.dropbox.apiKey;

			this.dropboxTrigger1 = ko.observable(Enums.SaveSettingsStep.Idle);
		}

		SocialAdminSettings.prototype.onBuild = function ()
		{
			var
				self = this,
				Remote = __webpack_require__(/*! Remote/Admin/Ajax */ 19)
			;

			_.delay(function () {

				var
					f1 = Utils.settingsSaveHelperSimpleFunction(self.facebookTrigger1, self),
					f2 = Utils.settingsSaveHelperSimpleFunction(self.facebookTrigger2, self),
					f3 = Utils.settingsSaveHelperSimpleFunction(self.twitterTrigger1, self),
					f4 = Utils.settingsSaveHelperSimpleFunction(self.twitterTrigger2, self),
					f5 = Utils.settingsSaveHelperSimpleFunction(self.googleTrigger1, self),
					f6 = Utils.settingsSaveHelperSimpleFunction(self.googleTrigger2, self),
					f7 = Utils.settingsSaveHelperSimpleFunction(self.googleTrigger3, self),
					f8 = Utils.settingsSaveHelperSimpleFunction(self.dropboxTrigger1, self)
				;

				self.facebookEnable.subscribe(function (bValue) {
					if (self.facebookSupported())
					{
						Remote.saveAdminConfig(Utils.emptyFunction, {
							'FacebookEnable': bValue ? '1' : '0'
						});
					}
				});

				self.facebookAppID.subscribe(function (sValue) {
					if (self.facebookSupported())
					{
						Remote.saveAdminConfig(f1, {
							'FacebookAppID': Utils.trim(sValue)
						});
					}
				});

				self.facebookAppSecret.subscribe(function (sValue) {
					if (self.facebookSupported())
					{
						Remote.saveAdminConfig(f2, {
							'FacebookAppSecret': Utils.trim(sValue)
						});
					}
				});

				self.twitterEnable.subscribe(function (bValue) {
					Remote.saveAdminConfig(Utils.emptyFunction, {
						'TwitterEnable': bValue ? '1' : '0'
					});
				});

				self.twitterConsumerKey.subscribe(function (sValue) {
					Remote.saveAdminConfig(f3, {
						'TwitterConsumerKey': Utils.trim(sValue)
					});
				});

				self.twitterConsumerSecret.subscribe(function (sValue) {
					Remote.saveAdminConfig(f4, {
						'TwitterConsumerSecret': Utils.trim(sValue)
					});
				});

				self.googleEnable.subscribe(function (bValue) {
					Remote.saveAdminConfig(Utils.emptyFunction, {
						'GoogleEnable': bValue ? '1' : '0'
					});
				});

				self.googleEnableAuth.subscribe(function (bValue) {
					Remote.saveAdminConfig(Utils.emptyFunction, {
						'GoogleEnableAuth': bValue ? '1' : '0'
					});
				});

				self.googleEnableDrive.subscribe(function (bValue) {
					Remote.saveAdminConfig(Utils.emptyFunction, {
						'GoogleEnableDrive': bValue ? '1' : '0'
					});
				});

				self.googleEnablePreview.subscribe(function (bValue) {
					Remote.saveAdminConfig(Utils.emptyFunction, {
						'GoogleEnablePreview': bValue ? '1' : '0'
					});
				});

				self.googleClientID.subscribe(function (sValue) {
					Remote.saveAdminConfig(f5, {
						'GoogleClientID': Utils.trim(sValue)
					});
				});

				self.googleClientSecret.subscribe(function (sValue) {
					Remote.saveAdminConfig(f6, {
						'GoogleClientSecret': Utils.trim(sValue)
					});
				});

				self.googleApiKey.subscribe(function (sValue) {
					Remote.saveAdminConfig(f7, {
						'GoogleApiKey': Utils.trim(sValue)
					});
				});

				self.dropboxEnable.subscribe(function (bValue) {
					Remote.saveAdminConfig(Utils.emptyFunction, {
						'DropboxEnable': bValue ? '1' : '0'
					});
				});

				self.dropboxApiKey.subscribe(function (sValue) {
					Remote.saveAdminConfig(f8, {
						'DropboxApiKey': Utils.trim(sValue)
					});
				});

			}, 50);
		};

		module.exports = SocialAdminSettings;

	}());

/***/ },
/* 132 */,
/* 133 */,
/* 134 */,
/* 135 */,
/* 136 */,
/* 137 */,
/* 138 */,
/* 139 */,
/* 140 */,
/* 141 */,
/* 142 */,
/* 143 */
/*!*********************************!*\
  !*** ./dev/View/Admin/Login.js ***!
  \*********************************/
/***/ function(module, exports, __webpack_require__) {

	
	(function () {

		'use strict';

		var
			_ = __webpack_require__(/*! _ */ 3),
			ko = __webpack_require__(/*! ko */ 2),

			Enums = __webpack_require__(/*! Common/Enums */ 4),
			Utils = __webpack_require__(/*! Common/Utils */ 1),
			Translator = __webpack_require__(/*! Common/Translator */ 6),

			Settings = __webpack_require__(/*! Storage/Settings */ 9),
			Remote = __webpack_require__(/*! Remote/Admin/Ajax */ 19),

			kn = __webpack_require__(/*! Knoin/Knoin */ 5),
			AbstractView = __webpack_require__(/*! Knoin/AbstractView */ 10)
		;

		/**
		 * @constructor
		 * @extends AbstractView
		 */
		function LoginAdminView()
		{
			AbstractView.call(this, 'Center', 'AdminLogin');

			this.logoPowered = !!Settings.settingsGet('LoginPowered');

			this.login = ko.observable('');
			this.password = ko.observable('');

			this.loginError = ko.observable(false);
			this.passwordError = ko.observable(false);

			this.loginErrorAnimation = ko.observable(false).extend({'falseTimeout': 500});
			this.passwordErrorAnimation = ko.observable(false).extend({'falseTimeout': 500});

			this.loginFocus = ko.observable(false);

			this.formHidden = ko.observable(false);

			this.formError = ko.computed(function () {
				return this.loginErrorAnimation() || this.passwordErrorAnimation();
			}, this);

			this.login.subscribe(function () {
				this.loginError(false);
			}, this);

			this.password.subscribe(function () {
				this.passwordError(false);
			}, this);

			this.loginError.subscribe(function (bV) {
				this.loginErrorAnimation(!!bV);
			}, this);

			this.passwordError.subscribe(function (bV) {
				this.passwordErrorAnimation(!!bV);
			}, this);

			this.submitRequest = ko.observable(false);
			this.submitError = ko.observable('');

			this.submitCommand = Utils.createCommand(this, function () {

				Utils.triggerAutocompleteInputChange();

				this.loginError(false);
				this.passwordError(false);

				this.loginError('' === Utils.trim(this.login()));
				this.passwordError('' === Utils.trim(this.password()));

				if (this.loginError() || this.passwordError())
				{
					return false;
				}

				this.submitRequest(true);

				Remote.adminLogin(_.bind(function (sResult, oData) {

					if (Enums.StorageResultType.Success === sResult && oData && 'AdminLogin' === oData.Action)
					{
						if (oData.Result)
						{
							__webpack_require__(/*! App/Admin */ 20).default.loginAndLogoutReload(true);
						}
						else if (oData.ErrorCode)
						{
							this.submitRequest(false);
							this.submitError(Translator.getNotification(oData.ErrorCode));
						}
					}
					else
					{
						this.submitRequest(false);
						this.submitError(Translator.getNotification(Enums.Notification.UnknownError));
					}

				}, this), this.login(), this.password());

				return true;

			}, function () {
				return !this.submitRequest();
			});

			kn.constructorEnd(this);
		}

		kn.extendAsViewModel(['View/Admin/Login', 'AdminLoginViewModel'], LoginAdminView);
		_.extend(LoginAdminView.prototype, AbstractView.prototype);

		LoginAdminView.prototype.onShow = function ()
		{
			kn.routeOff();

			_.delay(_.bind(function () {
				this.loginFocus(true);
			}, this), 100);

		};

		LoginAdminView.prototype.onHide = function ()
		{
			this.loginFocus(false);
		};

		LoginAdminView.prototype.onBuild = function ()
		{
			Utils.triggerAutocompleteInputChange(true);
		};

		LoginAdminView.prototype.submitForm = function ()
		{
			this.submitCommand();
		};

		module.exports = LoginAdminView;

	}());

/***/ },
/* 144 */
/*!*****************************************!*\
  !*** ./dev/View/Admin/Settings/Menu.js ***!
  \*****************************************/
/***/ function(module, exports, __webpack_require__) {

	
	(function () {

		'use strict';

		var
			_ = __webpack_require__(/*! _ */ 3),

			Globals = __webpack_require__(/*! Common/Globals */ 8),

			kn = __webpack_require__(/*! Knoin/Knoin */ 5),
			AbstractView = __webpack_require__(/*! Knoin/AbstractView */ 10)
		;

		/**
		 * @param {?} oScreen
		 *
		 * @constructor
		 * @extends AbstractView
		 */
		function MenuSettingsAdminView(oScreen)
		{
			AbstractView.call(this, 'Left', 'AdminMenu');

			this.leftPanelDisabled = Globals.leftPanelDisabled;

			this.menu = oScreen.menu;

			kn.constructorEnd(this);
		}

		kn.extendAsViewModel(['View/Admin/Settings/Menu', 'AdminSettingsMenuViewModel'], MenuSettingsAdminView);
		_.extend(MenuSettingsAdminView.prototype, AbstractView.prototype);

		MenuSettingsAdminView.prototype.link = function (sRoute)
		{
			return '#/' + sRoute;
		};

		MenuSettingsAdminView.prototype.onBuild = function (oDom)
		{
			key('up, down', _.throttle(function (event, handler) {

				var
					sH = '',
					iIndex = -1,
					bUp = handler && 'up' === handler.shortcut,
					$items = $('.b-admin-menu .e-item', oDom)
				;

				if (event && $items.length)
				{
					iIndex = $items.index($items.filter('.selected'));
					if (bUp && iIndex > 0)
					{
						iIndex--;
					}
					else if (!bUp && iIndex < $items.length - 1)
					{
						iIndex++;
					}

					sH = $items.eq(iIndex).attr('href');
					if (sH)
					{
						kn.setHash(sH, false, true);
					}
				}

			}, 200));
		};

		module.exports = MenuSettingsAdminView;

	}());


/***/ },
/* 145 */
/*!*****************************************!*\
  !*** ./dev/View/Admin/Settings/Pane.js ***!
  \*****************************************/
/***/ function(module, exports, __webpack_require__) {

	
	(function () {

		'use strict';

		var
			_ = __webpack_require__(/*! _ */ 3),
			ko = __webpack_require__(/*! ko */ 2),

			Settings = __webpack_require__(/*! Storage/Settings */ 9),
			Remote = __webpack_require__(/*! Remote/Admin/Ajax */ 19),

			kn = __webpack_require__(/*! Knoin/Knoin */ 5),
			AbstractView = __webpack_require__(/*! Knoin/AbstractView */ 10)
		;

		/**
		 * @constructor
		 * @extends AbstractView
		 */
		function PaneSettingsAdminView()
		{
			AbstractView.call(this, 'Right', 'AdminPane');

			this.adminDomain = ko.observable(Settings.settingsGet('AdminDomain'));
			this.version = ko.observable(Settings.settingsGet('Version'));

			this.capa = !!Settings.settingsGet('PremType');
			this.community = (false);

			this.adminManLoading = ko.computed(function () {
				return '000' !== [
					__webpack_require__(/*! Stores/Admin/Domain */ 58).domains.loading() ? '1' : '0',
					__webpack_require__(/*! Stores/Admin/Plugin */ 61).plugins.loading() ? '1' : '0',
					__webpack_require__(/*! Stores/Admin/Package */ 60).packages.loading() ? '1' : '0'
				].join('');
			}, this);

			this.adminManLoadingVisibility = ko.computed(function () {
				return this.adminManLoading() ? 'visible' : 'hidden';
			}, this).extend({'rateLimit': 300});

			kn.constructorEnd(this);
		}

		kn.extendAsViewModel(['View/Admin/Settings/Pane', 'AdminSettingsPaneViewModel'], PaneSettingsAdminView);
		_.extend(PaneSettingsAdminView.prototype, AbstractView.prototype);

		PaneSettingsAdminView.prototype.logoutClick = function ()
		{
			Remote.adminLogout(function () {
				__webpack_require__(/*! App/Admin */ 20).default.loginAndLogoutReload(true, true);
			});
		};

		module.exports = PaneSettingsAdminView;

	}());

/***/ },
/* 146 */,
/* 147 */,
/* 148 */,
/* 149 */,
/* 150 */,
/* 151 */,
/* 152 */
/*!**********************************!*\
  !*** ./dev/View/Popup/Plugin.js ***!
  \**********************************/
/***/ function(module, exports, __webpack_require__) {

	
	(function () {

		'use strict';

		var
			_ = __webpack_require__(/*! _ */ 3),
			ko = __webpack_require__(/*! ko */ 2),
			key = __webpack_require__(/*! key */ 18),

			Enums = __webpack_require__(/*! Common/Enums */ 4),
			Utils = __webpack_require__(/*! Common/Utils */ 1),
			Translator = __webpack_require__(/*! Common/Translator */ 6),

			Remote = __webpack_require__(/*! Remote/Admin/Ajax */ 19),

			kn = __webpack_require__(/*! Knoin/Knoin */ 5),
			AbstractView = __webpack_require__(/*! Knoin/AbstractView */ 10)
		;

		/**
		 * @constructor
		 * @extends AbstractView
		 */
		function PluginPopupView()
		{
			AbstractView.call(this, 'Popups', 'PopupsPlugin');

			var self = this;

			this.onPluginSettingsUpdateResponse = _.bind(this.onPluginSettingsUpdateResponse, this);

			this.saveError = ko.observable('');

			this.name = ko.observable('');
			this.readme = ko.observable('');

			this.configures = ko.observableArray([]);

			this.hasReadme = ko.computed(function () {
				return '' !== this.readme();
			}, this);

			this.hasConfiguration = ko.computed(function () {
				return 0 < this.configures().length;
			}, this);

			this.readmePopoverConf = {
				'placement': 'right',
				'trigger': 'hover',
	//			'trigger': 'click',
				'title': Translator.i18n('POPUPS_PLUGIN/TOOLTIP_ABOUT_TITLE'),
				'container': 'body',
				'html': true,
				'content': function () {
					return '<pre>' + self.readme() + '</pre>';
	//					.replace(/[\r]/g, '').replace(/[\n]/g, '<br />').replace(/[\t]/g, '&nbsp;&nbsp;&nbsp;');
				}
			};

			this.saveCommand = Utils.createCommand(this, function () {

				var oList = {};

				oList['Name'] = this.name();

				_.each(this.configures(), function (oItem) {

					var mValue = oItem.value();
					if (false === mValue || true === mValue)
					{
						mValue = mValue ? '1' : '0';
					}

					oList['_' + oItem['Name']] = mValue;

				}, this);

				this.saveError('');
				Remote.pluginSettingsUpdate(this.onPluginSettingsUpdateResponse, oList);

			}, this.hasConfiguration);

			this.bDisabeCloseOnEsc = true;
			this.sDefaultKeyScope = Enums.KeyState.All;

			this.tryToClosePopup = _.debounce(_.bind(this.tryToClosePopup, this), 200);

			kn.constructorEnd(this);
		}

		kn.extendAsViewModel(['View/Popup/Plugin', 'PopupsPluginViewModel'], PluginPopupView);
		_.extend(PluginPopupView.prototype, AbstractView.prototype);

		PluginPopupView.prototype.onPluginSettingsUpdateResponse = function (sResult, oData)
		{
			if (Enums.StorageResultType.Success === sResult && oData && oData.Result)
			{
				this.cancelCommand();
			}
			else
			{
				this.saveError('');
				if (oData && oData.ErrorCode)
				{
					this.saveError(Translator.getNotification(oData.ErrorCode));
				}
				else
				{
					this.saveError(Translator.getNotification(Enums.Notification.CantSavePluginSettings));
				}
			}
		};

		PluginPopupView.prototype.onShow = function (oPlugin)
		{
			this.name();
			this.readme();
			this.configures([]);

			if (oPlugin)
			{
				this.name(oPlugin['Name']);
				this.readme(oPlugin['Readme']);

				var aConfig = oPlugin['Config'];
				if (Utils.isNonEmptyArray(aConfig))
				{
					this.configures(_.map(aConfig, function (aItem) {
						return {
							'value': ko.observable(aItem[0]),
							'placeholder': ko.observable(aItem[6]),
							'Name': aItem[1],
							'Type': aItem[2],
							'Label': aItem[3],
							'Default': aItem[4],
							'Desc': aItem[5]
						};
					}));
				}
			}
		};

		PluginPopupView.prototype.tryToClosePopup = function ()
		{
			var
				self = this,
				PopupsAskViewModel = __webpack_require__(/*! View/Popup/Ask */ 43)
			;

			if (!kn.isPopupVisible(PopupsAskViewModel))
			{
				kn.showScreenPopup(PopupsAskViewModel, [Translator.i18n('POPUPS_ASK/DESC_WANT_CLOSE_THIS_WINDOW'), function () {
					if (self.modalVisibility())
					{
						Utils.delegateRun(self, 'cancelCommand');
					}
				}]);
			}
		};

		PluginPopupView.prototype.onBuild = function ()
		{
			key('esc', Enums.KeyState.All, _.bind(function () {
				if (this.modalVisibility())
				{
					this.tryToClosePopup();
				}
				return false;
			}, this));
		};

		module.exports = PluginPopupView;

	}());

/***/ }
/******/ ])
