/*
 * jQuery File Upload Plugin JS Example 6.11
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

/*jslint nomen: true, unparam: true, regexp: true */
/*global $, window, document */

$(function () {
    'use strict';
//<a rel="popover" data-delay=1500 class="project_link" data-trigger="hover" data-html="true" data-placement="right" data-content="<h6>Description:</h6><p>{% render "AceUtilitiesBundle:Default:getDescription" with {'id':file['id']} %}</p><h6>Files:</h6><p>{% render "AceUtilitiesBundle:Default:listFilenames" with {'id':file['id'], 'show_ino':1} %}</p><hr><p><a class='btn btn-danger' href='javascript:void()' onClick='warnDeleteProject(&quot;{{ path('AceUtilitiesBundle_deleteproject', {'id':file['id']}) }}&quot;);'>Delete Project</a></p>"data-original-title="{{ file['name'] }}" href="{{ path('AceGenericBundle_project', { 'id': file['id'] }) }}">{{ file['name'] }}</a>
    // Initialize the jQuery File Upload widget:
    $('#fileupload').fileupload({
			url: document.URL+'utilities/upload/',
			dataType: 'json',
			acceptFileTypes: /(\.|\/)(ino|pde|zip)$/i,
			maxNumberOfFiles: 1
	});

	 $('#fileupload').bind('fileuploadcompleted',
	function (e, data) {if(!data.result[0].error) 
						{ $("#Links").before('<li><a href="'+data.result[0].url+'">'+(data.result[0].name).slice(0,-4)+'</a></li>');}
						else{  $('#fileupload').fileupload().data('fileupload')._disableFileInputButton(); 
							   $('.btn.btn-warning').click(function (e) {  $('.template-download.fade.in').remove();  $('#fileupload').fileupload().data('fileupload')._enableFileInputButton(); });}
		} );
		
	//$('#fileupload').bind('fileuploadalways', function (e, data) {alert(data.jqXHR.responseText)});

    // Enable iframe cross-domain access via redirect option:
    $('#fileupload').fileupload(
        'option',
        'redirect',
        window.location.href.replace(
            /\/[^\/]*$/,
            '/cors/result.html?%s'
        )
    );

    if (window.location.hostname === 'blueimp.github.com') {
        // Demo settings:
        $('#fileupload').fileupload('option', {
            url: '//jquery-file-upload.appspot.com/',
            maxFileSize: 5000000,
            acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
            process: [
                {
                    action: 'load',
                    fileTypes: /^image\/(gif|jpeg|png)$/,
                    maxFileSize: 20000000 // 20MB
                },
                {
                    action: 'resize',
                    maxWidth: 1440,
                    maxHeight: 900
                },
                {
                    action: 'save'
                }
            ]
        });
        // Upload server status check for browsers with CORS support:
        if ($.support.cors) {
            $.ajax({
                url: '//jquery-file-upload.appspot.com/',
                type: 'HEAD'
            }).fail(function () {
                $('<span class="alert alert-error"/>')
                    .text('Upload server currently unavailable - ' +
                            new Date())
                    .appendTo('#fileupload');
            });
        }
    } else {
        // Load existing files:
        $.ajax({
            // Uncomment the following to send cross-domain cookies:
            //xhrFields: {withCredentials: true},
            url: $('#fileupload').fileupload('option', 'url'),
            dataType: 'json',
            context: $('#fileupload')[0]
        }).done(function (result) {
            if (result && result.length) {
                $(this).fileupload('option', 'done')
                    .call(this, null, {result: result});
            }
        });
    }

});
