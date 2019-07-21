/**
 * FluitoPHP(tm): Lightweight MVC (http://www.fluitophp.org)
 * Copyright (c) 2017, FluitoSoft (http://www.fluitosoft.com)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) 2017, FluitoSoft (http://www.fluitosoft.com)
 * @link          http://www.fluitophp.org FluitoPHP(tm): Lightweight MVC
 * @since         0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @author        Vipin Jain
 */

if (typeof jQuery.FluitoPHP === 'undefined') {
    jQuery.extend(jQuery, {FluitoPHP: {}});
}

jQuery.extend(jQuery.FluitoPHP, {
    FileManagerLib: {
        RemoveAltImg: function (image) {

            jQuery(image).parent().find('.alt-img').hide();
        },
        InitProgress: function (fileManager) {

            var value = 0;

            fileManager.loading = true;
            jQuery(fileManager.target).find('.loader').show();
            jQuery(fileManager.target).find('.loader').find('.loading-percent').html(value);
        },
        UpdateProgress: function (fileManager, value) {

            if (typeof value !== 'number') {

                value = 0;
            }

            if (value < 0) {

                value = 0;
            }

            if (value > 100) {

                value = 100;
            }
            
            clearInterval(window.FMInterval);

            window.FMInterval = setInterval(function () {
                var current_value = parseInt(jQuery(fileManager.target).find('.loader').find('.loading-percent').html());

                if (current_value < value) {
                    jQuery(fileManager.target).find('.loader').find('.loading-percent').html(current_value + 1);
                } else {
                    
                    clearInterval(window.FMInterval);
                }
            }, 10);
        },
        EndProgress: function (fileManager) {

            var value = 100;

            clearInterval(window.FMInterval);

            window.FMInterval = setInterval(function () {
                var current_value = parseInt(jQuery(fileManager.target).find('.loader').find('.loading-percent').html());

                if (current_value < value) {
                    jQuery(fileManager.target).find('.loader').find('.loading-percent').html(current_value + 1);
                } else {
                    
                    clearInterval(window.FMInterval);
                    jQuery(fileManager.target).find('.loader').fadeOut();
                }
            }, 10);
            
            fileManager.loading = false;
        }
    },
    FileManager: function (setupOptions) {

        var defaultOptions = {
            selector: '',
            multiSelect: false,
            multiUpload: true,
            selectDir: false,
            selectCallback: null,
            cancelCallback: null,
            baseURL: '',
            initialPath: '',
            listPath: '/index/fileman/list',
            uploadPath: '/index/fileman/upload',
            newDirectoryPath: '/index/fileman/newdirectory',
            cutPath: '/index/fileman/cut',
            copyPath: '/index/fileman/copy',
            renamePath: '/index/fileman/rename',
            deletePath: '/index/fileman/delete',
            pathParameter: 'path',
            uploadParameter: 'upload',
            newDirectoryParameter: 'directoryname',
            cutParameter: 'filepath',
            copyParameter: 'filepath',
            renameParameter: 'filepath',
            renameNewNameParameter: 'newname',
            deleteParameter: 'filepath'
        };

        var fileManager = {
            loading: true,
            clipboard: {
                copy: false,
                items: []
            }
        };

        fileManager.setupOptions = jQuery.extend(fileManager, defaultOptions, setupOptions);

        fileManager.target = jQuery(fileManager.setupOptions.selector).first();

        if (fileManager.target.length === 0) {

            return false;
        }

        fileManager.target.css("min-height", jQuery(window).height() + "px");

        fileManager.target.html('');

        if (typeof fileManager.setupOptions.selectCallback !== 'function') {

            fileManager.target.append(jQuery('<div class="FluitoPHP-filemanager"><header><nav class="base-nav">' +
                    '<button type="button" class="btn btn-sm btn-outline-secondary upload" title="Upload"><span class="actn-img">Upload</span></button>' +
                    '<button type="button" class="btn btn-sm btn-outline-secondary new-directory" title="New Directory"><span class="actn-img">New Directory</span></button>' +
                    '<button type="button" class="btn btn-sm btn-outline-secondary cut" title="Cut"><span class="actn-img">Cut</span></button>' +
                    '<button type="button" class="btn btn-sm btn-outline-secondary copy" title="Copy"><span class="actn-img">Copy</span></button>' +
                    '<button type="button" class="btn btn-sm btn-outline-secondary paste" title="Paste"><span class="actn-img">Paste</span></button>' +
                    '<button type="button" class="btn btn-sm btn-outline-secondary clear-clipboard" title="Clear Clipboard"><span class="actn-img">Clear Clipboard</span></button>' +
                    '<button type="button" class="btn btn-sm btn-outline-secondary rename" title="Rename"><span class="actn-img">Rename</span></button>' +
                    '<button type="button" class="btn btn-sm btn-outline-secondary delete" title="Delete"><span class="actn-img">Delete</span></button>' +
                    '<button type="button" class="btn btn-sm btn-outline-secondary clear-selection" title="Clear Selection"><span class="actn-img">Clear Selection</span></button>' +
                    '<button type="button" class="btn btn-sm btn-outline-secondary select-all" title="Select All"><span class="actn-img">Select All</span></button>' +
                    '</nav></header><div class="card mb-2"><div class="card-body"><div class="breadcrumbs mb-3"></div>' +
                    '<div class="folder-viewport"></div></div></div><footer><div class="foot-buttons">' +
                    '<div class="float-left badge badge-primary loader">Loading <span class="loading-percent">0</span>%</div>' +
                    '</div></footer></div>'));
        } else {

            fileManager.target.append(jQuery('<div class="FluitoPHP-filemanager"><header><nav class="base-nav">' +
                    '<button type="button" class="btn btn-sm btn-outline-secondary upload" title="Upload"><span class="actn-img">Upload</span></button>' +
                    '<button type="button" class="btn btn-sm btn-outline-secondary new-directory" title="New Directory"><span class="actn-img">New Directory</span></button>' +
                    '<button type="button" class="btn btn-sm btn-outline-secondary cut" title="Cut"><span class="actn-img">Cut</span></button>' +
                    '<button type="button" class="btn btn-sm btn-outline-secondary copy" title="Copy"><span class="actn-img">Copy</span></button>' +
                    '<button type="button" class="btn btn-sm btn-outline-secondary paste" title="Paste"><span class="actn-img">Paste</span></button>' +
                    '<button type="button" class="btn btn-sm btn-outline-secondary clear-clipboard" title="Clear Clipboard"><span class="actn-img">Clear Clipboard</span></button>' +
                    '<button type="button" class="btn btn-sm btn-outline-secondary rename" title="Rename"><span class="actn-img">Rename</span></button>' +
                    '<button type="button" class="btn btn-sm btn-outline-secondary delete" title="Delete"><span class="actn-img">Delete</span></button>' +
                    '<button type="button" class="btn btn-sm btn-outline-secondary clear-selection" title="Clear Selection"><span class="actn-img">Clear Selection</span></button>' +
                    '<button type="button" class="btn btn-sm btn-outline-secondary select-all" title="Select All"><span class="actn-img">Select All</span></button>' +
                    '</nav></header><div class="card mb-2"><div class="card-body"><div class="breadcrumbs mb-3"></div>' +
                    '<div class="folder-viewport"></div></div></div><footer><div class="foot-buttons">' +
                    '<div class="float-left badge badge-primary loader">Loading <span class="loading-percent">0</span>%</div>' +
                    '<button type="button" class="btn btn-sm btn-outline-primary ok-button" title="OK">OK</button>' +
                    '<button type="button" class="btn btn-sm btn-outline-secondary cancel-button" title="Cancel">Cancel</button></div></footer></div>'));
        }

        fileManager.selectorCallback = function (event) {

            if (jQuery(this).parents('.folder-item').hasClass('selected')) {

                jQuery(this).parents('.folder-item').removeClass('selected');
            } else {

                jQuery(this).parents('.folder-item').addClass('selected');
            }

            event.stopPropagation();
        };

        fileManager.clickCallback = function () {

            if (jQuery(this).hasClass('directory') &&
                    fileManager.target.find('.folder-item.selected').length === 0) {

                if (fileManager.loading) {

                    return;
                }

                jQuery.FluitoPHP.FileManagerLib.InitProgress(fileManager);
                jQuery.FluitoPHP.FileManagerLib.UpdateProgress(fileManager, 10);

                var currIndex = jQuery(this).attr('FluitoPHPlistnum');
                var newPath = fileManager.currentList.list[currIndex].path;

                fileManager.currentPath = newPath;

                fileManager.dataObject = {};

                fileManager.dataObject[fileManager.setupOptions.pathParameter] = fileManager.currentPath;

                jQuery.ajax({
                    url: fileManager.setupOptions.baseURL + fileManager.setupOptions.listPath,
                    data: fileManager.dataObject,
                    method: 'POST',
                    success: fileManager.listAjaxCallback
                });
            } else {

                if (jQuery(this).hasClass('selected')) {

                    jQuery(this).removeClass('selected');
                } else {

                    jQuery(this).addClass('selected');
                }
            }
        };

        fileManager.bcClickCallback = function () {

            if (fileManager.loading) {

                return;
            }

            jQuery.FluitoPHP.FileManagerLib.InitProgress(fileManager);
            jQuery.FluitoPHP.FileManagerLib.UpdateProgress(fileManager, 10);

            var currIndex = jQuery(this).attr('FluitoPHPbclistnum');
            var newPath = fileManager.currentList.breadcrumbs[currIndex].path;

            fileManager.currentPath = newPath;

            fileManager.dataObject = {};

            fileManager.dataObject[fileManager.setupOptions.pathParameter] = fileManager.currentPath;

            jQuery.ajax({
                url: fileManager.setupOptions.baseURL + fileManager.setupOptions.listPath,
                data: fileManager.dataObject,
                method: 'POST',
                success: fileManager.listAjaxCallback
            });
        };

        fileManager.uploadCallback = function () {

            if (fileManager.loading) {

                return;
            }

            fileManager.target.find('.uploader').remove();

            var uid = 'uploader' + (!Date.now ? new Date().getTime() : Date.now());

            fileManager.target.append(jQuery('<div class="uploader ' + uid + '" style="display: none;"><form method="POST" action="' +
                    fileManager.setupOptions.baseURL + fileManager.setupOptions.uploadPath +
                    '" target="' + uid + 'iframe" enctype="multipart/form-data">' +
                    '<input type="hidden" name=' + fileManager.setupOptions.pathParameter + ' value="' + fileManager.currentPath + '"/>' +
                    '<input type="file" id="' + uid + 'input" name="' + fileManager.setupOptions.uploadParameter +
                    (fileManager.setupOptions.multiUpload === true ? '[]' : '') + '"' +
                    (fileManager.setupOptions.multiUpload === true ? ' multiple' : '') + '/></form>' +
                    '<iframe name="' + uid + 'iframe" id="' + uid + 'iframe"></iframe></div>'));

            fileManager.target.find('#' + uid + 'input').on('change.FluitoPHPFileManager', function () {

                jQuery.FluitoPHP.FileManagerLib.InitProgress(fileManager);
                jQuery.FluitoPHP.FileManagerLib.UpdateProgress(fileManager, 10);

                jQuery(this).parents('form').submit();
            });

            fileManager.target.find('#' + uid + 'iframe').on('load.FluitoPHPFileManager', function () {

                response = jQuery.parseJSON(jQuery(this).contents().find('body').html());
                
                response = response.response;

                for (var x in response) {

                    if (typeof response[x] !== 'object') {

                        alert('Error: Unable to upload all files.');
                        break;
                    }
                }

                fileManager.dataObject = {};

                fileManager.dataObject[fileManager.setupOptions.pathParameter] = fileManager.currentPath;

                jQuery.ajax({
                    url: fileManager.setupOptions.baseURL + fileManager.setupOptions.listPath,
                    data: fileManager.dataObject,
                    method: 'POST',
                    success: fileManager.listAjaxCallback
                });
            });

            fileManager.target.find('#' + uid + 'input').click();
        };

        fileManager.newdirectoryCallback = function () {

            if (fileManager.loading) {

                return;
            }

            var newDirectoryName = prompt('Please enter new directory name.');

            if (!newDirectoryName) {

                return;
            }

            jQuery.FluitoPHP.FileManagerLib.InitProgress(fileManager);
            jQuery.FluitoPHP.FileManagerLib.UpdateProgress(fileManager, 10);

            fileManager.dataObject = {};

            fileManager.dataObject[fileManager.setupOptions.pathParameter] = fileManager.currentPath;
            fileManager.dataObject[fileManager.setupOptions.newDirectoryParameter] = newDirectoryName;

            jQuery.ajax({
                url: fileManager.setupOptions.baseURL + fileManager.setupOptions.newDirectoryPath,
                data: fileManager.dataObject,
                method: 'POST',
                success: function (response) {

                    response = jQuery.parseJSON(response);

                    if (!response) {

                        alert('Error: Unable to create the directory.');
                    }

                    fileManager.dataObject = {};

                    fileManager.dataObject[fileManager.setupOptions.pathParameter] = fileManager.currentPath;

                    jQuery.ajax({
                        url: fileManager.setupOptions.baseURL + fileManager.setupOptions.listPath,
                        data: fileManager.dataObject,
                        method: 'POST',
                        success: fileManager.listAjaxCallback
                    });
                }
            });
        };

        fileManager.cutCallback = function () {

            if (fileManager.loading) {

                return;
            }

            var currentSelections = fileManager.target.find('.folder-item.selected');

            if (currentSelections.length === 0) {

                alert('Please select at least one directory or file to cut.');
                return;
            }

            var tempList = [];

            currentSelections.each(function () {

                var selIndex = jQuery(this).attr('FluitoPHPlistnum');

                tempList.push(fileManager.currentList.list[selIndex].path);
            });

            fileManager.clipboard.copy = false;
            fileManager.clipboard.items = tempList;

            fileManager.target.find('.folder-item.selected').removeClass('selected');
        };

        fileManager.copyCallback = function () {

            if (fileManager.loading) {

                return;
            }

            var currentSelections = fileManager.target.find('.folder-item.selected');

            if (currentSelections.length === 0) {

                alert('Please select at least one directory or file to copy.');
                return;
            }

            var tempList = [];

            currentSelections.each(function () {

                var selIndex = jQuery(this).attr('FluitoPHPlistnum');

                tempList.push(fileManager.currentList.list[selIndex].path);
            });

            fileManager.clipboard.copy = true;
            fileManager.clipboard.items = tempList;

            fileManager.target.find('.folder-item.selected').removeClass('selected');
        };

        fileManager.pasteCallback = function () {

            if (fileManager.loading) {

                return;
            }

            if (fileManager.clipboard.items.length === 0) {

                alert('Nothing to paste.');
                return;
            }

            jQuery.FluitoPHP.FileManagerLib.InitProgress(fileManager);
            jQuery.FluitoPHP.FileManagerLib.UpdateProgress(fileManager, 10);

            fileManager.dataObject = {};

            fileManager.dataObject[fileManager.setupOptions.cutParameter] = fileManager.clipboard.items;

            var URL = fileManager.setupOptions.baseURL + fileManager.setupOptions.cutPath;

            if (fileManager.clipboard.copy) {

                fileManager.dataObject = {};

                fileManager.dataObject[fileManager.setupOptions.copyParameter] = fileManager.clipboard.items;

                URL = fileManager.setupOptions.baseURL + fileManager.setupOptions.copyPath;
            }

            fileManager.dataObject[fileManager.setupOptions.pathParameter] = fileManager.currentPath;

            jQuery.ajax({
                url: URL,
                data: fileManager.dataObject,
                method: 'POST',
                success: function (response) {

                    if (!fileManager.clipboard.copy) {

                        fileManager.clipboard.items = [];
                    }

                    response = jQuery.parseJSON(response);

                    if (response.indexOf(false) !== -1) {

                        alert('Error: Unable to paste some directory/file(s).');
                    }

                    fileManager.dataObject = {};

                    fileManager.dataObject[fileManager.setupOptions.pathParameter] = fileManager.currentPath;

                    jQuery.ajax({
                        url: fileManager.setupOptions.baseURL + fileManager.setupOptions.listPath,
                        data: fileManager.dataObject,
                        method: 'POST',
                        success: fileManager.listAjaxCallback
                    });
                }
            });
        };

        fileManager.clearClipboardCallback = function () {

            fileManager.clipboard.copy = false;
            fileManager.clipboard.items = [];
        };

        fileManager.renameCallback = function () {

            if (fileManager.loading) {

                return;
            }

            var currentSelections = fileManager.target.find('.folder-item.selected');

            if (currentSelections.length === 0) {

                alert('Please select a directory or file to rename.');
                return;
            }

            if (currentSelections.length > 1) {

                alert('Please select only one directory or file to rename.');
                return;
            }

            var selIndex = currentSelections.first().attr('FluitoPHPlistnum');

            var name = fileManager.currentList.list[selIndex].name;

            var extension = fileManager.currentList.list[selIndex].extension !== false ?
                    '.' + fileManager.currentList.list[selIndex].extension : '';

            var path = fileManager.currentList.list[selIndex].path;

            name = prompt('Please update name.', name);

            if (fileManager.currentList.list[selIndex].name == name ||
                    !name) {

                return;
            }

            jQuery.FluitoPHP.FileManagerLib.InitProgress(fileManager);
            jQuery.FluitoPHP.FileManagerLib.UpdateProgress(fileManager, 10);

            fileManager.dataObject = {};

            fileManager.dataObject[fileManager.setupOptions.renameParameter] = path;
            fileManager.dataObject[fileManager.setupOptions.renameNewNameParameter] = name + extension;

            jQuery.ajax({
                url: fileManager.setupOptions.baseURL + fileManager.setupOptions.renamePath,
                data: fileManager.dataObject,
                method: 'POST',
                success: function (response) {

                    response = jQuery.parseJSON(response);

                    if (!response) {

                        alert('Error: Unable to rename the directory/file.');
                    }

                    fileManager.dataObject = {};

                    fileManager.dataObject[fileManager.setupOptions.pathParameter] = fileManager.currentPath;

                    jQuery.ajax({
                        url: fileManager.setupOptions.baseURL + fileManager.setupOptions.listPath,
                        data: fileManager.dataObject,
                        method: 'POST',
                        success: fileManager.listAjaxCallback
                    });
                }
            });
        };

        fileManager.deleteCallback = function () {

            if (fileManager.loading) {

                return;
            }

            var currentSelections = fileManager.target.find('.folder-item.selected');

            if (currentSelections.length === 0) {

                alert('Please select at least one directory or file to delete.');
                return;
            }

            var confirmation = confirm('Are you sure you want to delete ' + currentSelections.length + ' directory/file(s).');

            if (!confirmation) {

                return;
            }

            jQuery.FluitoPHP.FileManagerLib.InitProgress(fileManager);
            jQuery.FluitoPHP.FileManagerLib.UpdateProgress(fileManager, 10);

            fileManager.dataObject = {};

            fileManager.dataObject[fileManager.setupOptions.deleteParameter] = [];

            currentSelections.each(function () {

                var selIndex = jQuery(this).attr('FluitoPHPlistnum');

                fileManager.dataObject[fileManager.setupOptions.deleteParameter].push(fileManager.currentList.list[selIndex].path);
            });

            jQuery.ajax({
                url: fileManager.setupOptions.baseURL + fileManager.setupOptions.deletePath,
                data: fileManager.dataObject,
                method: 'POST',
                success: function (response) {

                    response = jQuery.parseJSON(response);

                    if (response.indexOf(false) !== -1) {

                        alert('Error: Unable to delete some directory/file(s).');
                    }

                    fileManager.dataObject = {};

                    fileManager.dataObject[fileManager.setupOptions.pathParameter] = fileManager.currentPath;

                    jQuery.ajax({
                        url: fileManager.setupOptions.baseURL + fileManager.setupOptions.listPath,
                        data: fileManager.dataObject,
                        method: 'POST',
                        success: fileManager.listAjaxCallback
                    });
                }
            });
        };

        fileManager.clearSelectionCallback = function () {

            fileManager.target.find('.folder-item.selected').removeClass('selected');
        };

        fileManager.selectAllCallback = function () {

            fileManager.target.find('.folder-item').addClass('selected');
        };

        fileManager.okButtonCallback = function () {

            if (typeof fileManager.setupOptions.selectCallback !== 'function') {

                alert('Please provide the select function.');
                return;
            }

            var currentSelections = fileManager.target.find('.folder-item.selected');

            var selections = [];

            currentSelections.each(function () {

                var selIndex = jQuery(this).attr('FluitoPHPlistnum');

                if (!fileManager.currentList.list[selIndex].isdir ||
                        fileManager.setupOptions.selectDir) {

                    selections.push(fileManager.currentList.list[selIndex]);
                }
            });

            if (selections.length === 0) {

                alert('Please select directory/file(s).');
                return;
            }

            if (selections.length > 1 &&
                    !fileManager.setupOptions.multiSelect) {

                alert('Please select only one file.');
                return;
            }

            fileManager.setupOptions.selectCallback(selections, fileManager, this);
        };

        fileManager.cancelButtonCallback = function () {

            if (typeof fileManager.setupOptions.cancelCallback !== 'function') {

                alert('Please provide the cancel function.');
                return;
            }

            fileManager.setupOptions.cancelCallback(this, fileManager);
        };

        fileManager.listAjaxCallback = function (response) {

            response = jQuery.parseJSON(response);

            fileManager.currentList = response;

            var breadcrumbs = [];

            fileManager.target.find('.FluitoPHP-filemanager .breadcrumbs').html("");

            for (var x in fileManager.currentList.breadcrumbs) {

                var bcName = fileManager.currentList.breadcrumbs[x].name;

                if (x === '0') {

                    bcName = "Home";
                }

                if (parseInt(x) + 1 < fileManager.currentList.breadcrumbs.length) {

                    breadcrumbs[x] = '<li class="breadcrumb-item bc-item"><span class="bc-link btn-link" title="' + bcName + '" FluitoPHPbclistnum="' + x + '">' + bcName + '</span></li>';
                } else {

                    breadcrumbs[x] = '<li class="breadcrumb-item bc-item active"><span class="bc-link-refresh" title="' + bcName + ' (click to refresh)" FluitoPHPbclistnum="' + x + '">' +
                            bcName + '</span></li>';
                }
            }

            fileManager.target.find('.FluitoPHP-filemanager .breadcrumbs').append(jQuery('<ul class="breadcrumb">' + breadcrumbs.join('') + '</ul>'));

            var folderList = [];
            var fileList = [];

            fileManager.target.find('.FluitoPHP-filemanager .folder-viewport').html("");

            for (var x in fileManager.currentList.list) {

                var itemName = fileManager.currentList.list[x].basename;

                var isImage = fileManager.currentList.list[x].mime.substr(0, 5).toLowerCase() === 'image';

                var mimeClass = ' ' + fileManager.currentList.list[x].mime.replace(/[^\w]/g, '-') + ' ' + fileManager.currentList.list[x].extension;

                var temp = '<div class="card folder-item pull-left p-1 float-left' + mimeClass + ' m-1" title="' + itemName + '" FluitoPHPlistnum="' + x + '">' +
                        '<div class="item-thumb"><div class="alt-img"></div>' +
                        (isImage ? '<img class="card-img-top" src="' + fileManager.currentList.list[x].thumburl +
                                '" onerror="this.style.display=\'none\';" onload="jQuery.FluitoPHP.FileManagerLib.RemoveAltImg(this);"/>' : '') +
                        '<div class="selector"></div></div><figcaption class="figure-caption item-name text-center mt-1 pl-2 pr-2"><div class="overflow-hidden">' +
                        itemName + '</div></figcaption></div>';

                if (fileManager.currentList.list[x].isdir) {

                    folderList[x] = temp;
                } else {

                    fileList[x] = temp;
                }
            }

            fileManager.target.find('.FluitoPHP-filemanager .folder-viewport').append(jQuery(folderList.join('') + fileList.join('') + '<div class="clearfix"></div>'));

            fileManager.target.find('.folder-viewport .folder-item .item-thumb .selector').on('click.FluitoPHPFileManager', fileManager.selectorCallback);

            fileManager.target.find('.folder-viewport .folder-item').on('click.FluitoPHPFileManager', fileManager.clickCallback);

            fileManager.target.find('.bc-item').find('.bc-link, .bc-link-refresh').on('click.FluitoPHPFileManager', fileManager.bcClickCallback);

            jQuery.FluitoPHP.FileManagerLib.EndProgress(fileManager);
        };

        fileManager.target.find('.upload').on('click.FluitoPHPFileManager', fileManager.uploadCallback);
        fileManager.target.find('.new-directory').on('click.FluitoPHPFileManager', fileManager.newdirectoryCallback);
        fileManager.target.find('.cut').on('click.FluitoPHPFileManager', fileManager.cutCallback);
        fileManager.target.find('.copy').on('click.FluitoPHPFileManager', fileManager.copyCallback);
        fileManager.target.find('.paste').on('click.FluitoPHPFileManager', fileManager.pasteCallback);
        fileManager.target.find('.clear-clipboard').on('click.FluitoPHPFileManager', fileManager.clearClipboardCallback);
        fileManager.target.find('.rename').on('click.FluitoPHPFileManager', fileManager.renameCallback);
        fileManager.target.find('.delete').on('click.FluitoPHPFileManager', fileManager.deleteCallback);
        fileManager.target.find('.clear-selection').on('click.FluitoPHPFileManager', fileManager.clearSelectionCallback);
        fileManager.target.find('.select-all').on('click.FluitoPHPFileManager', fileManager.selectAllCallback);
        fileManager.target.find('.ok-button').on('click.FluitoPHPFileManager', fileManager.okButtonCallback);
        fileManager.target.find('.cancel-button').on('click.FluitoPHPFileManager', fileManager.cancelButtonCallback);

        jQuery.FluitoPHP.FileManagerLib.InitProgress(fileManager);
        jQuery.FluitoPHP.FileManagerLib.UpdateProgress(fileManager, 10);

        fileManager.currentPath = fileManager.setupOptions.initialPath;

        fileManager.dataObject = {};

        fileManager.dataObject[fileManager.setupOptions.pathParameter] = fileManager.currentPath;

        jQuery.ajax({
            url: fileManager.setupOptions.baseURL + fileManager.setupOptions.listPath,
            data: fileManager.dataObject,
            method: 'POST',
            success: fileManager.listAjaxCallback
        });
    }
});